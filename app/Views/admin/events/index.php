<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Event</h1>
                    <p class="text-gray-600">Kelola semua event seminar, workshop, dan konferensi</p>
                </div>
                <a href="/admin/events/create" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                    <i class="fas fa-plus mr-2"></i>Buat Event Baru
                </a>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada event yang dibuat</h3>
                                        <p class="text-gray-500 mb-6">Mulai dengan membuat event pertama Anda</p>
                                        <a href="/admin/events/create" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-plus mr-2"></i>Buat Event Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <?php if (isset($event['image']) && $event['image']): ?>
                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="/uploads/events/<?= $event['image'] ?>" alt="Event image">
                                                <?php else: ?>
                                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center border border-gray-200">
                                                        <i class="fas fa-calendar text-blue-600"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 mb-1"><?= esc($event['title']) ?></div>
                                                <div class="text-xs text-gray-500 flex items-center mb-1">
                                                    <i class="fas fa-user mr-1"></i>
                                                    by <?= esc($event['creator_name']) ?>
                                                </div>
                                                <div class="text-xs text-gray-500 flex items-center">
                                                    <i class="fas fa-microphone mr-1"></i>
                                                    Speaker: <?= esc($event['speaker']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-100 text-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-800">
                                            <i class="fas fa-<?= $event['type'] === 'seminar' ? 'microphone' : ($event['type'] === 'workshop' ? 'tools' : ($event['type'] === 'conference' ? 'users' : 'graduation-cap')) ?> mr-1"></i>
                                            <?= ucfirst($event['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium"><?= date('d M Y', strtotime($event['start_date'])) ?></div>
                                        <div class="text-gray-500 text-xs flex items-center mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?= date('H:i', strtotime($event['start_date'])) ?> - <?= date('H:i', strtotime($event['end_date'])) ?>
                                        </div>
                                        <div class="text-xs text-gray-400 flex items-center mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <?= esc($event['location']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center mb-2">
                                            <div class="text-sm font-medium"><?= $event['current_participants'] ?>/<?= $event['max_participants'] ?></div>
                                            <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?= $event['max_participants'] > 0 ? ($event['current_participants'] / $event['max_participants']) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                        <?php if ($event['price'] > 0): ?>
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-money-bill-wave mr-1"></i>
                                                Rp <?= number_format($event['price'], 0, ',', '.') ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-gift mr-1"></i>
                                                Gratis
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?= $event['status'] === 'published' ? 'green' : ($event['status'] === 'draft' ? 'yellow' : ($event['status'] === 'completed' ? 'blue' : 'red')) ?>-100 text-<?= $event['status'] === 'published' ? 'green' : ($event['status'] === 'draft' ? 'yellow' : ($event['status'] === 'completed' ? 'blue' : 'red')) ?>-800">
                                            <i class="fas fa-<?= $event['status'] === 'published' ? 'check-circle' : ($event['status'] === 'draft' ? 'edit' : ($event['status'] === 'completed' ? 'flag-checkered' : 'times-circle')) ?> mr-1"></i>
                                            <?= ucfirst($event['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-1">
                                            <a href="/admin/events/registrations/<?= $event['id'] ?>" class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200" title="Lihat Peserta">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <a href="/admin/events/edit/<?= $event['id'] ?>" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin/events/delete/<?= $event['id'] ?>" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition duration-200" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Event Statistics -->
        <?php if (!empty($events)): ?>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Events</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($events) ?></p>
                            <p class="text-xs text-blue-600 flex items-center mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                Semua event
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Published</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($events, function($e) { return $e['status'] === 'published'; })) ?></p>
                            <p class="text-xs text-green-600 flex items-center mt-1">
                                <i class="fas fa-eye mr-1"></i>
                                Event aktif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-edit text-xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Draft</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($events, function($e) { return $e['status'] === 'draft'; })) ?></p>
                            <p class="text-xs text-yellow-600 flex items-center mt-1">
                                <i class="fas fa-pencil-alt mr-1"></i>
                                Belum publish
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Peserta</p>
                            <p class="text-2xl font-bold text-gray-900"><?= array_sum(array_column($events, 'current_participants')) ?></p>
                            <p class="text-xs text-purple-600 flex items-center mt-1">
                                <i class="fas fa-user-check mr-1"></i>
                                Terdaftar
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>