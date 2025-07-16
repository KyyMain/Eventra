<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-credit-card text-2xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Instruksi Pembayaran</h1>
            <p class="text-gray-600">Selesaikan pembayaran Anda sesuai instruksi di bawah ini</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Instructions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pembayaran</label>
                                <div class="flex items-center">
                                    <span class="font-mono text-lg font-bold text-blue-600" id="paymentCode"><?= $payment['payment_code'] ?></span>
                                    <button onclick="copyToClipboard('paymentCode')" class="ml-2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Pembayaran</label>
                                <span class="text-lg font-bold text-green-600">Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Waktu</label>
                            <span class="text-red-600 font-medium" id="countdown"><?= date('d M Y, H:i', strtotime($payment['expired_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Specific Instructions -->
                <?php if ($payment['payment_method_type'] === 'qris'): ?>
                    <!-- QRIS Instructions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-qrcode mr-2 text-green-600"></i>
                                Pembayaran QRIS
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                                    <img src="<?= $payment['qr_code'] ?>" alt="QR Code" class="w-48 h-48 mx-auto">
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Scan QR Code dengan aplikasi pembayaran Anda</p>
                            </div>
                            
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Cara Pembayaran:</h4>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                                    <li>Buka aplikasi pembayaran (DANA, OVO, GoPay, ShopeePay, dll)</li>
                                    <li>Pilih menu "Scan QR" atau "Bayar"</li>
                                    <li>Scan QR Code di atas</li>
                                    <li>Pastikan nominal pembayaran sesuai</li>
                                    <li>Konfirmasi pembayaran</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                <?php elseif ($payment['payment_method_type'] === 'ewallet'): ?>
                    <!-- E-Wallet Instructions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-mobile-alt mr-2 text-blue-600"></i>
                                Pembayaran <?= esc($payment['payment_method_name']) ?>
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-blue-800"><?= esc($payment['payment_instructions']) ?></p>
                            </div>
                            
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Langkah-langkah:</h4>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                                    <li>Buka aplikasi <?= esc($payment['payment_method_name']) ?></li>
                                    <li>Pilih menu "Scan QR" atau "Bayar"</li>
                                    <li>Masukkan kode pembayaran: <strong><?= $payment['payment_code'] ?></strong></li>
                                    <li>Pastikan nominal Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></li>
                                    <li>Konfirmasi pembayaran</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                <?php elseif ($payment['payment_method_type'] === 'bank_transfer'): ?>
                    <!-- Bank Transfer Instructions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-university mr-2 text-purple-600"></i>
                                Transfer <?= esc($payment['payment_method_name']) ?>
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                <div class="text-center">
                                    <label class="block text-sm font-medium text-purple-700 mb-1">Nomor Virtual Account</label>
                                    <div class="flex items-center justify-center">
                                        <span class="font-mono text-xl font-bold text-purple-800" id="virtualAccount"><?= $payment['virtual_account'] ?></span>
                                        <button onclick="copyToClipboard('virtualAccount')" class="ml-2 text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Cara Transfer:</h4>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                                    <li>Login ke mobile banking atau internet banking <?= esc($payment['payment_method_name']) ?></li>
                                    <li>Pilih menu "Transfer" → "Virtual Account"</li>
                                    <li>Masukkan nomor Virtual Account: <strong><?= $payment['virtual_account'] ?></strong></li>
                                    <li>Masukkan nominal: <strong>Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></strong></li>
                                    <li>Konfirmasi dan selesaikan transfer</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Status Check -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">Status Pembayaran</h4>
                                <p class="text-sm text-gray-600">Klik tombol di samping untuk mengecek status pembayaran</p>
                            </div>
                            <button onclick="checkPaymentStatus()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Cek Status
                            </button>
                        </div>
                        <div id="statusMessage" class="mt-4 hidden"></div>
                    </div>
                </div>

                <!-- Development Only: Simulate Payment -->
                <?php if (ENVIRONMENT === 'development'): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800 font-medium">Mode Development</span>
                        </div>
                        <p class="text-yellow-700 text-sm mt-1">Untuk testing, Anda bisa simulasi pembayaran berhasil</p>
                        <a href="/payment/simulate/<?= $payment['id'] ?>" class="inline-block mt-2 bg-yellow-600 text-white px-4 py-2 rounded text-sm hover:bg-yellow-700 transition-colors">
                            Simulasi Pembayaran Berhasil
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Pesanan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2"><?= esc($payment['event_title']) ?></h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-user w-4 mr-2"></i>
                                    <span><?= esc($payment['user_name']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope w-4 mr-2"></i>
                                    <span><?= esc($payment['user_email']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Harga Event</span>
                                <span>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></span>
                            </div>
                            <?php if ($payment['admin_fee'] > 0): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Biaya Admin</span>
                                    <span>Rp <?= number_format($payment['admin_fee'], 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total</span>
                                    <span class="font-semibold text-gray-900">Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <p class="text-sm text-gray-600">Pembayaran akan kedaluwarsa dalam</p>
                                <p class="font-bold text-red-600" id="timeRemaining"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown timer
function updateCountdown() {
    const expiredAt = new Date('<?= $payment['expired_at'] ?>').getTime();
    const now = new Date().getTime();
    const distance = expiredAt - now;

    if (distance > 0) {
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('timeRemaining').innerHTML = 
            hours.toString().padStart(2, '0') + ':' + 
            minutes.toString().padStart(2, '0') + ':' + 
            seconds.toString().padStart(2, '0');
    } else {
        document.getElementById('timeRemaining').innerHTML = 'EXPIRED';
        document.getElementById('timeRemaining').className = 'font-bold text-red-600';
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();

// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const originalText = element.innerHTML;
        element.innerHTML = '<i class="fas fa-check text-green-600"></i> Copied!';
        setTimeout(() => {
            element.innerHTML = originalText;
        }, 2000);
    });
}

// Check payment status
function checkPaymentStatus() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
    button.disabled = true;

    fetch('/payment/check-status/<?= $payment['id'] ?>')
        .then(response => response.json())
        .then(data => {
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.classList.remove('hidden');
            
            if (data.status === 'paid') {
                statusDiv.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 font-medium">Pembayaran Berhasil!</span>
                        </div>
                        <p class="text-green-700 text-sm mt-1">Pembayaran Anda telah dikonfirmasi. Halaman akan dialihkan...</p>
                    </div>
                `;
                setTimeout(() => {
                    window.location.href = '/payment/success/<?= $payment['id'] ?>';
                }, 2000);
            } else {
                statusDiv.innerHTML = `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="text-blue-800 font-medium">${data.message}</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                        <span class="text-red-800 font-medium">Gagal mengecek status</span>
                    </div>
                </div>
            `;
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
}
</script>
<?= $this->endSection() ?>