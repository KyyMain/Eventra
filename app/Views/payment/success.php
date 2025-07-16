<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600">Terima kasih, pembayaran Anda telah berhasil diproses</p>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Pembayaran</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pembayaran</label>
                        <span class="font-mono text-sm text-gray-900"><?= $payment['payment_code'] ?></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <span class="text-sm text-gray-900"><?= esc($payment['payment_method_name']) ?></span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Dibayar</label>
                        <span class="text-lg font-bold text-green-600">Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                        <span class="text-sm text-gray-900"><?= date('d M Y, H:i', strtotime($payment['paid_at'] ?? $payment['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Event</h3>
            </div>
            <div class="p-6">
                <h4 class="font-medium text-gray-900 mb-4"><?= esc($payment['event_title']) ?></h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-user w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-gray-600">Peserta:</span>
                            <span class="font-medium ml-1"><?= esc($payment['user_name']) ?></span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium ml-1"><?= esc($payment['user_email']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Langkah Selanjutnya</h3>
            <ul class="space-y-2 text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Anda akan menerima email konfirmasi dalam beberapa menit</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Simpan email konfirmasi sebagai bukti pendaftaran</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Datang tepat waktu sesuai jadwal event</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-5 mr-3 mt-0.5 text-blue-600"></i>
                    <span>Sertifikat akan diterbitkan setelah menghadiri event</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="/user/my-events" class="flex-1 bg-blue-600 text-white text-center py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-calendar-alt mr-2"></i>
                Lihat Event Saya
            </a>
            <a href="/user/events" class="flex-1 bg-gray-600 text-white text-center py-3 px-6 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i>
                Cari Event Lain
            </a>
        </div>

        <!-- Support -->
        <div class="text-center mt-8 text-sm text-gray-600">
            <p>Butuh bantuan? <a href="mailto:support@eventra.com" class="text-blue-600 hover:text-blue-800">Hubungi Support</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>