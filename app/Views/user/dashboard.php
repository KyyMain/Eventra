<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, <?= esc(session('user_name')) ?>!</h1>
                    <p class="text-blue-100 text-lg">Temukan dan ikuti event menarik untuk mengembangkan skill Anda</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-calendar-check text-6xl text-blue-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Registrasi</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_registrations'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Event Dihadiri</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $stats['attended_events'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Event Mendatang</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $stats['upcoming_events'] ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?= $stats['certificates_earned'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/user/events" class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Cari Event</h3>
                        <p class="text-gray-600">Temukan event yang sesuai dengan minat Anda</p>
                    </div>
                </div>
            </a>

            <a href="/user/my-events" class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Event Saya</h3>
                        <p class="text-gray-600">Lihat event yang sudah Anda daftarkan</p>
                    </div>
                </div>
            </a>

            <a href="/user/certificates" class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sertifikat</h3>
                        <p class="text-gray-600">Download sertifikat yang telah Anda peroleh</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Event Mendatang</h2>
            <a href="/user/events" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <?php if (empty($upcoming_events)): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Event Mendatang</h3>
                <p class="text-gray-600 mb-4">Jelajahi event yang tersedia dan daftarkan diri Anda</p>
                <a href="/user/events" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                    <i class="fas fa-search mr-2"></i>Cari Event
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($upcoming_events as $event): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <?php if ($event['image']): ?>
                            <img src="/uploads/events/<?= $event['image'] ?>" alt="<?= esc($event['title']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-calendar text-white text-4xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-100 text-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-800">
                                    <i class="fas fa-<?= $event['type'] === 'seminar' ? 'microphone' : ($event['type'] === 'workshop' ? 'tools' : ($event['type'] === 'conference' ? 'users' : 'graduation-cap')) ?> mr-1"></i>
                                    <?= ucfirst($event['type']) ?>
                                </span>
                                <?php if ($event['price'] > 0): ?>
                                    <span class="text-green-600 font-semibold">Rp <?= number_format($event['price'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-blue-600 font-semibold">Gratis</span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= esc($event['title']) ?></h3>
                            <p class="text-gray-600 text-sm mb-3"><?= esc(substr($event['description'], 0, 100)) ?>...</p>
                            
                            <div class="space-y-2 text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie w-4 mr-2"></i>
                                    <span><?= esc($event['speaker']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                    <span><?= date('d M Y, H:i', strtotime($event['start_date'])) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span><?= esc($event['location']) ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users w-4 mr-2"></i>
                                    <span><?= $event['current_participants'] ?>/<?= $event['max_participants'] ?> peserta</span>
                                </div>
                            </div>
                            
                            <a href="/user/events/<?= $event['id'] ?>" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-center block">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- My Registrations -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Registrasi Saya</h2>
            <a href="/user/my-events" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <?php if (empty($user_registrations)): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Registrasi</h3>
                <p class="text-gray-600 mb-4">Mulai dengan mendaftarkan diri ke event yang menarik</p>
                <a href="/user/events" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Daftar Event
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach (array_slice($user_registrations, 0, 5) as $registration): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php if ($registration['event_image']): ?>
                                                    <img class="h-10 w-10 rounded-lg object-cover" src="/uploads/events/<?= $registration['event_image'] ?>" alt="">
                                                <?php else: ?>
                                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-calendar text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= esc($registration['event_title']) ?></div>
                                                <div class="text-sm text-gray-500"><?= ucfirst($registration['event_type']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($registration['event_start_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $registration['status'] === 'attended' ? 'green' : ($registration['status'] === 'registered' ? 'blue' : 'red') ?>-100 text-<?= $registration['status'] === 'attended' ? 'green' : ($registration['status'] === 'registered' ? 'blue' : 'red') ?>-800">
                                            <i class="fas fa-<?= $registration['status'] === 'attended' ? 'check-circle' : ($registration['status'] === 'registered' ? 'clock' : 'times-circle') ?> mr-1"></i>
                                            <?= ucfirst($registration['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="/user/events/<?= $registration['event_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Lihat Detail
                                        </a>
                                        <?php if ($registration['status'] === 'attended' && $registration['certificate_issued']): ?>
                                            <a href="/user/certificates/download/<?= $registration['id'] ?>" class="text-green-600 hover:text-green-900">
                                                Download Sertifikat
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>