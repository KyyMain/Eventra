<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Event Saya</h1>
        <p class="text-gray-600">Daftar event yang telah Anda daftarkan</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="?status=all" class="<?= (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-200">
                    Semua Event
                </a>
                <a href="?status=upcoming" class="<?= (isset($_GET['status']) && $_GET['status'] === 'upcoming') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-200">
                    Mendatang
                </a>
                <a href="?status=attended" class="<?= (isset($_GET['status']) && $_GET['status'] === 'attended') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-200">
                    Sudah Dihadiri
                </a>
                <a href="?status=cancelled" class="<?= (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-200">
                    Dibatalkan
                </a>
            </nav>
        </div>
    </div>

    <!-- Events List -->
    <?php if (empty($registrations)): ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Event</h3>
            <p class="text-gray-600 mb-6">Anda belum mendaftar ke event apapun. Mulai jelajahi event yang tersedia!</p>
            <a href="/user/events" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-search mr-2"></i>Cari Event
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($registrations as $registration): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="md:flex">
                        <!-- Event Image -->
                        <div class="md:w-1/3">
                            <?php if (isset($registration['event_image']) && $registration['event_image']): ?>
                                <img src="/uploads/events/<?= $registration['event_image'] ?>" alt="<?= esc($registration['event_title']) ?>" class="w-full h-48 md:h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 md:h-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                    <i class="fas fa-calendar text-white text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Event Content -->
                        <div class="md:w-2/3 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $registration['event_type'] === 'seminar' ? 'blue' : ($registration['event_type'] === 'workshop' ? 'green' : ($registration['event_type'] === 'conference' ? 'purple' : 'yellow')) ?>-100 text-<?= $registration['event_type'] === 'seminar' ? 'blue' : ($registration['event_type'] === 'workshop' ? 'green' : ($registration['event_type'] === 'conference' ? 'purple' : 'yellow')) ?>-800">
                                        <i class="fas fa-<?= $registration['event_type'] === 'seminar' ? 'microphone' : ($registration['event_type'] === 'workshop' ? 'tools' : ($registration['event_type'] === 'conference' ? 'users' : 'graduation-cap')) ?> mr-1"></i>
                                        <?= ucfirst($registration['event_type']) ?>
                                    </span>
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $registration['status'] === 'attended' ? 'green' : ($registration['status'] === 'registered' ? 'blue' : 'red') ?>-100 text-<?= $registration['status'] === 'attended' ? 'green' : ($registration['status'] === 'registered' ? 'blue' : 'red') ?>-800">
                                        <i class="fas fa-<?= $registration['status'] === 'attended' ? 'check-circle' : ($registration['status'] === 'registered' ? 'clock' : 'times-circle') ?> mr-1"></i>
                                        <?= $registration['status'] === 'attended' ? 'Hadir' : ($registration['status'] === 'registered' ? 'Terdaftar' : 'Dibatalkan') ?>
                                    </span>
                                </div>
                                
                                <?php if ($registration['event_price'] > 0): ?>
                                    <span class="text-green-600 font-semibold">Rp <?= number_format($registration['event_price'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-blue-600 font-semibold">Gratis</span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($registration['event_title']) ?></h3>
                            <p class="text-gray-600 mb-4"><?= esc(substr($registration['event_description'], 0, 150)) ?>...</p>
                            
                            <!-- Event Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie w-4 mr-2"></i>
                                    <span><?= esc($registration['event_speaker']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                    <span><?= date('d M Y', strtotime($registration['event_start_date'])) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-4 mr-2"></i>
                                    <span><?= date('H:i', strtotime($registration['event_start_date'])) ?> - <?= date('H:i', strtotime($registration['event_end_date'])) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span><?= esc($registration['event_location']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus w-4 mr-2"></i>
                                    <span>Daftar: <?= date('d M Y', strtotime($registration['registration_date'])) ?></span>
                                </div>
                                <?php if ($registration['payment_status']): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-credit-card w-4 mr-2"></i>
                                        <span class="<?= $registration['payment_status'] === 'paid' ? 'text-green-600' : ($registration['payment_status'] === 'pending' ? 'text-yellow-600' : 'text-red-600') ?>">
                                            <?= ucfirst($registration['payment_status']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-3">
                                    <a href="/user/events/<?= $registration['event_id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Detail
                                    </a>
                                    
                                    <?php if ($registration['status'] === 'attended' && $registration['certificate_issued']): ?>
                                        <a href="/user/certificates/download/<?= $registration['id'] ?>" class="text-green-600 hover:text-green-800 font-medium">
                                            <i class="fas fa-download mr-1"></i>Download Sertifikat
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($registration['status'] === 'registered'): ?>
                                    <?php if (strtotime($registration['event_start_date']) > time()): ?>
                                        <?= form_open('user/registrations/cancel/' . $registration['id'], ['class' => 'inline', 'onsubmit' => "return confirm('Apakah Anda yakin ingin membatalkan registrasi?')"]) ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                <i class="fas fa-times mr-1"></i>Batalkan
                                            </button>
                                        <?= form_close() ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 font-medium cursor-not-allowed" title="Event sudah dimulai, tidak dapat dibatalkan">
                                            <i class="fas fa-times mr-1"></i>Tidak dapat dibatalkan
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Summary Statistics -->
    <?php if (!empty($registrations)): ?>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Registrasi</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count($registrations) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Sudah Dihadiri</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($registrations, function($r) { return $r['status'] === 'attended'; })) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Mendatang</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($registrations, function($r) { return $r['status'] === 'registered' && strtotime($r['event_start_date']) > time(); })) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Sertifikat</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($registrations, function($r) { return $r['certificate_issued']; })) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>