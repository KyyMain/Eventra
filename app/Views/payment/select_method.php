<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pilih Metode Pembayaran</h1>
            <p class="text-gray-600">Pilih metode pembayaran yang Anda inginkan untuk menyelesaikan pendaftaran</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Methods -->
            <div class="lg:col-span-2">
                <form action="/payment/create" method="POST" id="paymentForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="registration_id" value="<?= $registration['id'] ?>">
                    
                    <!-- E-Wallet Methods -->
                    <?php if (isset($paymentMethods['ewallet'])): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-mobile-alt mr-2 text-blue-600"></i>
                                    E-Wallet
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Pembayaran instan dengan dompet digital</p>
                            </div>
                            <div class="p-6 space-y-4">
                                <?php foreach ($paymentMethods['ewallet'] as $method): ?>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                                        <input type="radio" name="payment_method_id" value="<?= $method['id'] ?>" class="sr-only payment-method-radio">
                                        <div class="flex items-center justify-between w-full">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                    <i class="fas fa-wallet text-xl text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900"><?= esc($method['name']) ?></h4>
                                                    <p class="text-sm text-gray-600">Biaya admin: Rp <?= number_format($method['admin_fee'], 0, ',', '.') ?></p>
                                                </div>
                                            </div>
                                            <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-blue-600 rounded-full hidden"></div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- QRIS -->
                    <?php if (isset($paymentMethods['qris'])): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-qrcode mr-2 text-green-600"></i>
                                    QRIS
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Scan QR Code dengan aplikasi pembayaran apapun</p>
                            </div>
                            <div class="p-6 space-y-4">
                                <?php foreach ($paymentMethods['qris'] as $method): ?>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 cursor-pointer transition-all">
                                        <input type="radio" name="payment_method_id" value="<?= $method['id'] ?>" class="sr-only payment-method-radio">
                                        <div class="flex items-center justify-between w-full">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                    <i class="fas fa-qrcode text-xl text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900"><?= esc($method['name']) ?></h4>
                                                    <p class="text-sm text-gray-600">
                                                        Biaya admin: <?= $method['admin_fee_type'] === 'percentage' ? $method['admin_fee'] . '%' : 'Rp ' . number_format($method['admin_fee'], 0, ',', '.') ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-green-600 rounded-full hidden"></div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Bank Transfer -->
                    <?php if (isset($paymentMethods['bank_transfer'])): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-university mr-2 text-purple-600"></i>
                                    Transfer Bank
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Transfer melalui Virtual Account</p>
                            </div>
                            <div class="p-6 space-y-4">
                                <?php foreach ($paymentMethods['bank_transfer'] as $method): ?>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 cursor-pointer transition-all">
                                        <input type="radio" name="payment_method_id" value="<?= $method['id'] ?>" class="sr-only payment-method-radio">
                                        <div class="flex items-center justify-between w-full">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                    <i class="fas fa-university text-xl text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900"><?= esc($method['name']) ?></h4>
                                                    <p class="text-sm text-gray-600">Biaya admin: Rp <?= number_format($method['admin_fee'], 0, ',', '.') ?></p>
                                                </div>
                                            </div>
                                            <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-purple-600 rounded-full hidden"></div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-medium transition-colors cursor-not-allowed">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Ringkasan Pesanan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2"><?= esc($registration['event_title']) ?></h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                    <span><?= date('d M Y, H:i', strtotime($registration['start_date'])) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span><?= esc($registration['location']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user w-4 mr-2"></i>
                                    <span><?= esc($registration['user_name']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Harga Event</span>
                                <span class="font-medium">Rp <?= number_format($registration['event_price'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-2" id="adminFeeRow" style="display: none;">
                                <span class="text-gray-600">Biaya Admin</span>
                                <span class="font-medium" id="adminFeeAmount">Rp 0</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-semibold text-gray-900" id="totalAmount">Rp <?= number_format($registration['event_price'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const submitBtn = document.getElementById('submitBtn');
    const adminFeeRow = document.getElementById('adminFeeRow');
    const adminFeeAmount = document.getElementById('adminFeeAmount');
    const totalAmount = document.getElementById('totalAmount');
    const baseAmount = <?= $registration['event_price'] ?>;

    // Payment method data
    const paymentMethods = <?= json_encode(array_merge(...array_values($paymentMethods))) ?>;

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update visual indicators
            document.querySelectorAll('.radio-indicator').forEach(indicator => {
                indicator.classList.remove('border-blue-500', 'border-green-500', 'border-purple-500');
                indicator.classList.add('border-gray-300');
                indicator.querySelector('div').classList.add('hidden');
            });

            document.querySelectorAll('label').forEach(label => {
                label.classList.remove('border-blue-300', 'bg-blue-50', 'border-green-300', 'bg-green-50', 'border-purple-300', 'bg-purple-50');
                label.classList.add('border-gray-200');
            });

            if (this.checked) {
                const label = this.closest('label');
                const indicator = label.querySelector('.radio-indicator');
                const dot = indicator.querySelector('div');
                
                // Get payment method data
                const methodId = this.value;
                const method = paymentMethods.find(m => m.id == methodId);
                
                // Update styling based on type
                if (method.type === 'ewallet') {
                    label.classList.add('border-blue-300', 'bg-blue-50');
                    indicator.classList.add('border-blue-500');
                    dot.classList.remove('hidden');
                } else if (method.type === 'qris') {
                    label.classList.add('border-green-300', 'bg-green-50');
                    indicator.classList.add('border-green-500');
                    dot.classList.remove('hidden');
                } else if (method.type === 'bank_transfer') {
                    label.classList.add('border-purple-300', 'bg-purple-50');
                    indicator.classList.add('border-purple-500');
                    dot.classList.remove('hidden');
                }

                // Calculate and display admin fee
                let adminFee = 0;
                if (method.admin_fee_type === 'percentage') {
                    adminFee = Math.round((baseAmount * parseFloat(method.admin_fee)) / 100);
                } else {
                    adminFee = parseFloat(method.admin_fee) || 0;
                }

                console.log('Base Amount:', baseAmount);
                console.log('Admin Fee Type:', method.admin_fee_type);
                console.log('Admin Fee Value:', method.admin_fee);
                console.log('Calculated Admin Fee:', adminFee);

                if (adminFee > 0) {
                    adminFeeRow.style.display = 'flex';
                    adminFeeAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(adminFee);
                } else {
                    adminFeeRow.style.display = 'none';
                }

                const total = baseAmount + adminFee;
                console.log('Total Amount:', total);
                totalAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

                // Enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>