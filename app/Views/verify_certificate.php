<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <i class="fas fa-calendar-alt text-2xl text-white mr-3"></i>
                        <span class="text-xl font-bold text-white">Eventra</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-white hover:text-gray-200 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="/register" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <div class="animate-float mb-6">
                    <i class="fas fa-certificate text-6xl text-purple-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Verifikasi Sertifikat</h1>
                <p class="text-xl text-gray-600">Pastikan keaslian sertifikat Eventra dengan memasukkan kode verifikasi</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Verification Form -->
            <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Masukkan Kode Sertifikat</h2>
                
                <form action="/verify-certificate" method="post" class="space-y-6">
                    <?= csrf_field() ?>
                    <div>
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
                    
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg font-medium text-lg transition duration-200 transform hover:scale-105">
                        <i class="fas fa-search mr-3"></i>Verifikasi Sertifikat
                    </button>
                </form>
            </div>

            <!-- Certificate Result -->
            <?php if (isset($certificate) && $certificate): ?>
                <div class="certificate-bg rounded-lg shadow-xl p-8 text-white">
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
                                            <p class="font-medium">Penerima</p>
                                            <p class="opacity-90"><?= esc($certificate['user_name']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-calendar w-5 mr-3 mt-1"></i>
                                        <div>
                                            <p class="font-medium">Tanggal Diterbitkan</p>
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
                                        <i class="fas fa-user-tie w-5 mr-3 mt-1"></i>
                                        <div>
                                            <p class="font-medium">Pembicara</p>
                                            <p class="opacity-90"><?= esc($certificate['event_speaker']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-calendar-check w-5 mr-3 mt-1"></i>
                                        <div>
                                            <p class="font-medium">Tanggal Event</p>
                                            <p class="opacity-90"><?= date('d F Y', strtotime($certificate['event_start_date'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-map-marker-alt w-5 mr-3 mt-1"></i>
                                        <div>
                                            <p class="font-medium">Lokasi</p>
                                            <p class="opacity-90"><?= esc($certificate['event_location']) ?></p>
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
                </div>
            <?php elseif (isset($certificate) && !$certificate): ?>
                <div class="bg-red-100 border border-red-400 rounded-lg p-8 text-center">
                    <i class="fas fa-times-circle text-6xl text-red-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-red-700 mb-2">Sertifikat Tidak Valid</h2>
                    <p class="text-red-600">Kode sertifikat yang Anda masukkan tidak ditemukan atau tidak valid.</p>
                    <p class="text-red-600 mt-2">Pastikan Anda memasukkan kode yang benar.</p>
                </div>
            <?php endif; ?>

            <!-- How to Use -->
            <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
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
                            <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
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
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-2xl mr-3"></i>
                    <span class="text-xl font-bold">Eventra</span>
                </div>
                <p class="text-gray-400 mb-4">Platform Event Management Terpercaya</p>
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                <p class="text-gray-400 text-sm mt-4">© 2024 Eventra. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Format certificate code input
        document.getElementById('certificate_code').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>