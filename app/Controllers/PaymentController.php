<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\PaymentMethodModel;
use App\Models\EventRegistrationModel;
use App\Models\EventModel;
use App\Models\UserModel;

class PaymentController extends BaseController
{
    protected $paymentModel;
    protected $paymentMethodModel;
    protected $registrationModel;
    protected $eventModel;
    protected $userModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->registrationModel = new EventRegistrationModel();
        $this->eventModel = new EventModel();
        $this->userModel = new UserModel();
    }

    /**
     * Show payment methods for registration
     */
    public function selectMethod($registrationId)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get registration details
        $registration = $this->registrationModel
            ->select('event_registrations.*, events.title as event_title, events.price as event_price, 
                     events.start_date, events.location, users.full_name as user_name')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.id', $registrationId)
            ->where('event_registrations.user_id', session()->get('user_id'))
            ->first();

        if (!$registration) {
            return redirect()->to('/user/my-events')->with('error', 'Pendaftaran tidak ditemukan');
        }

        // Check if already paid
        if ($registration['payment_status'] === 'paid') {
            return redirect()->to('/user/my-events')->with('info', 'Pembayaran sudah berhasil');
        }

        // Get active payment methods
        $paymentMethods = $this->paymentMethodModel->getActivePaymentMethods();

        // Group by type
        $groupedMethods = [];
        foreach ($paymentMethods as $method) {
            $groupedMethods[$method['type']][] = $method;
        }

        $data = [
            'title' => 'Pilih Metode Pembayaran',
            'registration' => $registration,
            'paymentMethods' => $groupedMethods
        ];

        return view('payment/select_method', $data);
    }

    /**
     * Process payment creation
     */
    public function createPayment()
    {
        try {
            // Load validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'registration_id' => [
                    'rules' => 'required|integer|valid_user_registration',
                    'errors' => [
                        'required' => 'Registration ID wajib diisi',
                        'integer' => 'Registration ID harus berupa angka',
                        'valid_user_registration' => 'Pendaftaran tidak valid'
                    ]
                ],
                'payment_method_id' => [
                    'rules' => 'required|integer|active_payment_method',
                    'errors' => [
                        'required' => 'Metode pembayaran wajib dipilih',
                        'integer' => 'Metode pembayaran tidak valid',
                        'active_payment_method' => 'Metode pembayaran tidak aktif'
                    ]
                ]
            ]);

            $inputData = [
                'registration_id' => $this->request->getPost('registration_id'),
                'payment_method_id' => $this->request->getPost('payment_method_id'),
                'user_id' => session()->get('user_id')
            ];

            if (!$validation->run($inputData)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            // Use PaymentService for business logic
            $paymentService = new \App\Services\PaymentService();
            $result = $paymentService->createPayment($inputData);

            if ($result['success']) {
                // Trigger payment created event
                \CodeIgniter\Events\Events::trigger('payment_created', [
                    'id' => $result['payment_id'],
                    'registration_id' => $inputData['registration_id'],
                    'payment_method_id' => $inputData['payment_method_id'],
                    'total_amount' => 0, // Will be filled by service
                    'user_id' => $inputData['user_id']
                ]);

                return redirect()->to("/payment/instructions/{$result['payment_id']}")
                    ->with('success', $result['message']);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Payment creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Show payment instructions
     */
    public function instructions($paymentId)
    {
        $payment = $this->paymentModel->getPaymentWithDetails($paymentId);

        if (!$payment || $payment['user_id'] != session()->get('user_id')) {
            return redirect()->to('/user/my-events')->with('error', 'Pembayaran tidak ditemukan');
        }

        $data = [
            'title' => 'Instruksi Pembayaran',
            'payment' => $payment
        ];

        return view('payment/instructions', $data);
    }

    /**
     * Check payment status
     */
    public function checkStatus($paymentId)
    {
        $payment = $this->paymentModel->find($paymentId);

        if (!$payment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pembayaran tidak ditemukan']);
        }

        // In real implementation, you would check with payment gateway
        // For now, we'll simulate status check
        
        return $this->response->setJSON([
            'success' => true,
            'status' => $payment['status'],
            'message' => $this->getStatusMessage($payment['status'])
        ]);
    }

    /**
     * Simulate payment (for testing)
     */
    public function simulatePayment($paymentId)
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan');
        }

        // Update payment status to paid
        $this->paymentModel->updatePaymentStatus($paymentId, 'paid');

        // Update registration payment status
        $this->registrationModel->update($payment['registration_id'], [
            'payment_status' => 'paid'
        ]);

        return redirect()->to("/payment/success/{$paymentId}");
    }

    /**
     * Payment success page
     */
    public function success($paymentId)
    {
        $payment = $this->paymentModel->getPaymentWithDetails($paymentId);

        if (!$payment || $payment['user_id'] != session()->get('user_id')) {
            return redirect()->to('/user/my-events')->with('error', 'Pembayaran tidak ditemukan');
        }

        $data = [
            'title' => 'Pembayaran Berhasil',
            'payment' => $payment
        ];

        return view('payment/success', $data);
    }

    /**
     * Payment callback (webhook)
     */
    public function callback()
    {
        // This would handle webhooks from payment gateways
        // Implementation depends on the specific payment gateway used
        
        $input = $this->request->getJSON(true);
        
        // Log the callback for debugging
        log_message('info', 'Payment callback received: ' . json_encode($input));

        // Process callback based on payment gateway
        // Update payment status accordingly
        
        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Generate payment instructions based on method
     */
    private function generatePaymentInstructions($paymentData, $paymentMethod)
    {
        switch ($paymentMethod['type']) {
            case 'ewallet':
                $paymentData['payment_instructions'] = $this->generateEwalletInstructions($paymentMethod);
                break;
            case 'qris':
                $paymentData['qr_code'] = $this->generateQRCode($paymentData['payment_code']);
                $paymentData['payment_instructions'] = 'Scan QR Code dengan aplikasi pembayaran Anda';
                break;
            case 'bank_transfer':
                $paymentData['virtual_account'] = $this->generateVirtualAccount($paymentMethod['code']);
                $paymentData['payment_instructions'] = $this->generateBankInstructions($paymentMethod);
                break;
        }

        return $paymentData;
    }

    /**
     * Generate e-wallet instructions
     */
    private function generateEwalletInstructions($paymentMethod)
    {
        $instructions = [
            'dana' => 'Buka aplikasi DANA → Scan QR Code → Konfirmasi pembayaran',
            'ovo' => 'Buka aplikasi OVO → Scan QR Code → Masukkan PIN → Konfirmasi',
            'gopay' => 'Buka aplikasi Gojek → GoPay → Scan QR Code → Konfirmasi',
            'shopeepay' => 'Buka aplikasi Shopee → ShopeePay → Scan QR Code → Konfirmasi'
        ];

        return $instructions[$paymentMethod['code']] ?? 'Gunakan aplikasi e-wallet untuk melakukan pembayaran';
    }

    /**
     * Generate bank transfer instructions
     */
    private function generateBankInstructions($paymentMethod)
    {
        $instructions = [
            'bca_va' => 'Transfer ke Virtual Account BCA yang tertera',
            'mandiri_va' => 'Transfer ke Virtual Account Mandiri yang tertera',
            'bni_va' => 'Transfer ke Virtual Account BNI yang tertera',
            'bri_va' => 'Transfer ke Virtual Account BRI yang tertera'
        ];

        return $instructions[$paymentMethod['code']] ?? 'Transfer ke nomor Virtual Account yang tertera';
    }

    /**
     * Generate QR Code (placeholder)
     */
    private function generateQRCode($paymentCode)
    {
        // In real implementation, this would generate actual QR code
        return "data:image/svg+xml;base64," . base64_encode("
            <svg width='200' height='200' xmlns='http://www.w3.org/2000/svg'>
                <rect width='200' height='200' fill='white'/>
                <text x='100' y='100' text-anchor='middle' font-family='Arial' font-size='12'>
                    QR Code: {$paymentCode}
                </text>
            </svg>
        ");
    }

    /**
     * Generate Virtual Account number
     */
    private function generateVirtualAccount($bankCode)
    {
        $prefix = [
            'bca_va' => '70012',
            'mandiri_va' => '88808',
            'bni_va' => '88810',
            'bri_va' => '88812'
        ];

        return ($prefix[$bankCode] ?? '88888') . rand(1000000000, 9999999999);
    }

    /**
     * Get status message
     */
    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Menunggu pembayaran',
            'processing' => 'Sedang diproses',
            'paid' => 'Pembayaran berhasil',
            'failed' => 'Pembayaran gagal',
            'expired' => 'Pembayaran kedaluwarsa',
            'cancelled' => 'Pembayaran dibatalkan'
        ];

        return $messages[$status] ?? 'Status tidak diketahui';
    }
}