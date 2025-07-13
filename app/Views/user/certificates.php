<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sertifikat Saya</h1>
        <p class="text-gray-600">Koleksi sertifikat dari event yang telah Anda hadiri</p>
    </div>

    <!-- Certificate Verification Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Verifikasi Sertifikat</h2>
        <p class="text-gray-600 mb-4">Masukkan kode sertifikat untuk memverifikasi keaslian sertifikat</p>
        
        <form action="/verify-certificate" method="post" class="flex space-x-4">
            <?= csrf_field() ?>
            <div class="flex-1">
                <input type="text" name="certificate_code" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                       placeholder="Masukkan kode sertifikat (contoh: CERT-2024-001)" required>
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-search mr-2"></i>Verifikasi
            </button>
        </form>
    </div>

    <!-- Certificates Grid -->
    <?php if (empty($certificates)): ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-certificate text-6xl text-gray-400 mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Sertifikat</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki sertifikat. Hadiri event dan dapatkan sertifikat Anda!</p>
            <a href="/user/events" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-search mr-2"></i>Cari Event
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($certificates as $cert): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <!-- Certificate Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-certificate text-3xl"></i>
                            <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                <?= ucfirst($cert['event_type']) ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Sertifikat Kehadiran</h3>
                        <p class="text-blue-100 text-sm">Kode: <?= esc($cert['certificate_code']) ?></p>
                    </div>
                    
                    <!-- Certificate Content -->
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2"><?= esc($cert['event_title']) ?></h4>
                        <p class="text-gray-600 text-sm mb-4"><?= esc(substr($cert['event_description'], 0, 100)) ?>...</p>
                        
                        <!-- Event Details -->
                        <div class="space-y-2 text-sm text-gray-500 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie w-4 mr-3"></i>
                                <span><?= esc($cert['event_speaker']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-4 mr-3"></i>
                                <span><?= date('d M Y', strtotime($cert['event_start_date'])) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-4 mr-3"></i>
                                <span><?= esc($cert['event_location']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock w-4 mr-3"></i>
                                <span>Diterbitkan: <?= date('d M Y', strtotime($cert['created_at'])) ?></span>
                            </div>
                        </div>
                        
                        <!-- Certificate Status -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Terverifikasi
                            </span>
                            <span class="text-xs text-gray-500">
                                ID: #<?= $cert['id'] ?>
                            </span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="/user/certificates/download/<?= $cert['id'] ?>" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium text-center transition duration-200">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                            <button onclick="shareCertificate('<?= $cert['certificate_code'] ?>')" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-share mr-2"></i>Share
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Certificate Statistics -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Sertifikat</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count($certificates) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Seminar</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($certificates, function($c) { return $c['event_type'] === 'seminar'; })) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Workshop</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($certificates, function($c) { return $c['event_type'] === 'workshop'; })) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Conference</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($certificates, function($c) { return $c['event_type'] === 'conference'; })) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function shareCertificate(certificateCode) {
    const url = window.location.origin + '/verify-certificate';
    const text = `Lihat sertifikat saya dengan kode: ${certificateCode}\nVerifikasi di: ${url}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Sertifikat Eventra',
            text: text,
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(text).then(() => {
            alert('Link sertifikat telah disalin ke clipboard!');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Link sertifikat telah disalin ke clipboard!');
        });
    }
}
</script>
<?= $this->endSection() ?>