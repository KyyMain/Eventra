<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error Icon -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times text-3xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Gagal</h1>
            <p class="text-gray-600">Maaf, terjadi kesalahan dalam memproses pembayaran Anda</p>
        </div>

        <!-- Error Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Error</h3>
            </div>
            <div class="p-6">
                <?php if (isset($payment)): ?>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pembayaran</label>
                            <span class="font-mono text-sm text-gray-900"><?= $payment['payment_code'] ?></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <?= ucfirst($payment['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                        <span class="text-sm text-gray-900"><?= esc($payment['event_title']) ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                    <p class="text-red-800"><?= esc($error_message) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Common Issues -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-yellow-900 mb-3">Kemungkinan Penyebab</h3>
            <ul class="space-y-2 text-yellow-800">
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 mt-0.5 text-yellow-600"></i>
                    <span>Saldo tidak mencukupi atau limit transaksi terlampaui</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 mt-0.5 text-yellow-600"></i>
                    <span>Koneksi internet terputus saat proses pembayaran</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 mt-0.5 text-yellow-600"></i>
                    <span>Waktu pembayaran telah habis (expired)</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 mt-0.5 text-yellow-600"></i>
                    <span>Gangguan sistem pada penyedia layanan pembayaran</span>
                </li>
            </ul>
        </div>

        <!-- What to do next -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Apa yang harus dilakukan?</h3>
            <ul class="space-y-2 text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-redo w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Coba lakukan pembayaran ulang dengan metode yang sama</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-credit-card w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Gunakan metode pembayaran yang berbeda</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-phone w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Hubungi customer service penyedia pembayaran</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-envelope w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Hubungi support kami jika masalah berlanjut</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <?php if (isset($payment)): ?>
            <a href="/payment/select-method/<?= $payment['registration_id'] ?>" class="flex-1 bg-blue-600 text-white text-center py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-redo mr-2"></i>
                Coba Lagi
            </a>
            <?php endif; ?>
            <a href="/user/events" class="flex-1 bg-gray-600 text-white text-center py-3 px-6 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Event
            </a>
        </div>

        <!-- Support -->
        <div class="text-center mt-8 text-sm text-gray-600">
            <p>Butuh bantuan? <a href="mailto:support@eventra.com" class="text-blue-600 hover:text-blue-800">Hubungi Support</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>