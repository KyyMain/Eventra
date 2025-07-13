<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Event Tersedia</h1>
        <p class="text-gray-600">Temukan dan daftarkan diri Anda ke event yang menarik</p>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form method="GET" action="/user/events" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Event</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="<?= esc($search ?? '') ?>" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                           placeholder="Cari berdasarkan judul, speaker, atau deskripsi...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="md:w-48">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event</label>
                <select id="type" name="type" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    <option value="">Semua Tipe</option>
                    <option value="seminar" <?= ($type ?? '') === 'seminar' ? 'selected' : '' ?>>Seminar</option>
                    <option value="workshop" <?= ($type ?? '') === 'workshop' ? 'selected' : '' ?>>Workshop</option>
                    <option value="conference" <?= ($type ?? '') === 'conference' ? 'selected' : '' ?>>Conference</option>
                    <option value="training" <?= ($type ?? '') === 'training' ? 'selected' : '' ?>>Training</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="/user/events" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Events Grid -->
    <?php if (empty($events)): ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Tidak Ada Event Ditemukan</h3>
            <?php if (!empty($search) || !empty($type)): ?>
                <p class="text-gray-600 mb-6">Tidak ada event yang sesuai dengan kriteria pencarian Anda.</p>
                <a href="/user/events" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Event
                </a>
            <?php else: ?>
                <p class="text-gray-600 mb-6">Belum ada event yang tersedia saat ini. Silakan cek kembali nanti.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($events as $event): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <!-- Event Image -->
                    <div class="relative">
                        <?php if ($event['image']): ?>
                            <img src="/uploads/events/<?= $event['image'] ?>" alt="<?= esc($event['title']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-calendar text-white text-4xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Event Type Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-100 text-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-800 backdrop-blur-sm">
                                <i class="fas fa-<?= $event['type'] === 'seminar' ? 'microphone' : ($event['type'] === 'workshop' ? 'tools' : ($event['type'] === 'conference' ? 'users' : 'graduation-cap')) ?> mr-1"></i>
                                <?= ucfirst($event['type']) ?>
                            </span>
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <?php if ($event['price'] > 0): ?>
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Rp <?= number_format($event['price'], 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Gratis
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Availability Indicator -->
                        <?php if ($event['current_participants'] >= $event['max_participants']): ?>
                            <div class="absolute bottom-4 right-4">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    Penuh
                                </span>
                            </div>
                        <?php elseif ($event['current_participants'] / $event['max_participants'] > 0.8): ?>
                            <div class="absolute bottom-4 right-4">
                                <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    Hampir Penuh
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Event Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2"><?= esc($event['title']) ?></h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?= esc(substr($event['description'], 0, 120)) ?>...</p>
                        
                        <!-- Event Details -->
                        <div class="space-y-2 text-sm text-gray-500 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie w-5 mr-3 text-gray-400"></i>
                                <span class="font-medium"><?= esc($event['speaker']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-5 mr-3 text-gray-400"></i>
                                <span><?= date('d M Y', strtotime($event['start_date'])) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock w-5 mr-3 text-gray-400"></i>
                                <span><?= date('H:i', strtotime($event['start_date'])) ?> - <?= date('H:i', strtotime($event['end_date'])) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-5 mr-3 text-gray-400"></i>
                                <span class="line-clamp-1"><?= esc($event['location']) ?></span>
                            </div>
                        </div>

                        <!-- Participants Progress -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Peserta Terdaftar</span>
                                <span class="font-medium"><?= $event['current_participants'] ?>/<?= $event['max_participants'] ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: <?= $event['max_participants'] > 0 ? ($event['current_participants'] / $event['max_participants']) * 100 : 0 ?>%"></div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex items-center justify-between">
                            <a href="/user/events/<?= $event['id'] ?>" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition duration-200 text-center mr-2">
                                Lihat Detail
                            </a>
                            
                            <?php if (isset($user_registrations[$event['id']])): ?>
                                <div class="flex items-center text-green-600 ml-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span class="text-sm font-medium">Terdaftar</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Load More or Pagination (if needed) -->
        <?php if (count($events) >= 9): ?>
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Menampilkan <?= count($events) ?> event</p>
                <!-- Add pagination here if needed -->
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<?= $this->endSection() ?>