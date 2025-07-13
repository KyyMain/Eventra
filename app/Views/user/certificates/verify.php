<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Sertifikat</h1>
        <p class="text-gray-600">Masukkan kode sertifikat untuk memverifikasi keaslian dan validitas sertifikat</p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Verification Form -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="text-center mb-8">
            <div class="bg-purple-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-certificate text-3xl text-purple-600"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Verifikasi Sertifikat</h2>
            <p class="text-gray-600">Pastikan keaslian sertifikat dengan memasukkan kode verifikasi</p>
        </div>

        <form action="/user/certificates/verify" method="post" class="max-w-md mx-auto">
            <?= csrf_field() ?>
            
            <div class="mb-6">
                <label for="certificate_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Sertifikat
                </label>
                <div class="relative">
                    <input type="text" 
                           id="certificate_code" 
                           name="certificate_code" 
                           value="<?= old('certificate_code') ?>"
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 text-center text-lg font-mono" 
                           placeholder="CERT-2024-001" 
                           required>
                    <i class="fas fa-barcode absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <p class="text-sm text-gray-500 mt-2">Format: CERT-YYYY-XXX (contoh: CERT-2024-001)</p>
            </div>

            <button type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <i class="fas fa-search mr-2"></i>
                Verifikasi Sertifikat
            </button>
        </form>
    </div>

    <!-- Certificate Result (if verification successful) -->
    <?php if (isset($certificate) && $certificate): ?>
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg shadow-xl p-8 text-white mb-8">
            <div class="text-center mb-8">
                <i class="fas fa-certificate text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold mb-2">Sertifikat Valid</h2>
                <p class="text-lg opacity-90">Sertifikat ini telah terverifikasi dan sah</p>
            </div>
            
            <div class="bg-white bg-opacity-20 rounded-lg p-6 backdrop-blur-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Informasi Sertifikat</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-barcode w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Kode Sertifikat</p>
                                    <p class="opacity-90"><?= esc($certificate['certificate_code']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-user w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Nama Penerima</p>
                                    <p class="opacity-90"><?= esc($certificate['user_name']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-calendar w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Tanggal Terbit</p>
                                    <p class="opacity-90"><?= date('d F Y', strtotime($certificate['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Informasi Event</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-calendar-alt w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Nama Event</p>
                                    <p class="opacity-90"><?= esc($certificate['event_title']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-tag w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Jenis Event</p>
                                    <p class="opacity-90"><?= ucfirst($certificate['event_type']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock w-5 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Tanggal Event</p>
                                    <p class="opacity-90"><?= date('d F Y', strtotime($certificate['event_start_date'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-white border-opacity-30">
                <div class="flex items-center justify-center">
                    <i class="fas fa-shield-alt text-2xl mr-3"></i>
                    <div class="text-center">
                        <p class="font-semibold">Sertifikat Terverifikasi</p>
                        <p class="text-sm opacity-90">Diterbitkan oleh Eventra - Platform Event Management</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- How to Use -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Cara Menggunakan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Masukkan Kode</h3>
                <p class="text-gray-600">Masukkan kode sertifikat yang tertera pada sertifikat Anda</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cog text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Verifikasi</h3>
                <p class="text-gray-600">Sistem akan memverifikasi keaslian sertifikat secara otomatis</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Hasil</h3>
                <p class="text-gray-600">Lihat informasi lengkap sertifikat dan status verifikasinya</p>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 rounded-lg p-6 mt-8">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Penting</h3>
                <ul class="text-blue-800 space-y-1">
                    <li>• Kode sertifikat bersifat unik dan hanya dapat digunakan untuk satu sertifikat</li>
                    <li>• Sertifikat yang valid akan menampilkan informasi lengkap event dan penerima</li>
                    <li>• Jika terjadi masalah verifikasi, silakan hubungi tim support kami</li>
                    <li>• Verifikasi dapat dilakukan kapan saja tanpa batas waktu</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>