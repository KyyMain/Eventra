<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Error Message -->
<?php if (isset($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4">
    <strong>Error:</strong> <?= esc($error) ?>
</div>
<?php endif; ?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
            <p class="text-gray-600">Selamat datang kembali, <?= session()->get('full_name') ?>!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $eventStats['total_events'] ?? 0 ?></p>
                        <p class="text-xs text-blue-600 flex items-center mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            Semua event
                        </p>
                    </div>
                </div>
            </div>

            <!-- Published Events -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Published Events</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $eventStats['published_events'] ?? 0 ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-eye mr-1"></i>
                            Event aktif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $userStats['total_users'] ?? 0 ?></p>
                        <p class="text-xs text-purple-600 flex items-center mt-1">
                            <i class="fas fa-user-plus mr-1"></i>
                            Pengguna terdaftar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Registrations -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Registrations</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $registrationStats['total_registrations'] ?? 0 ?></p>
                        <p class="text-xs text-orange-600 flex items-center mt-1">
                            <i class="fas fa-clipboard-check mr-1"></i>
                            Pendaftaran event
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="/admin/events/create" class="flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-plus-circle text-xl mr-3"></i>
                    <div class="text-left">
                        <h3 class="font-semibold">Buat Event Baru</h3>
                        <p class="text-blue-100 text-sm">Tambahkan event seminar atau workshop</p>
                    </div>
                </a>
                
                <a href="/admin/users" class="flex items-center justify-center px-6 py-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-users-cog text-xl mr-3"></i>
                    <div class="text-left">
                        <h3 class="font-semibold">Kelola Users</h3>
                        <p class="text-green-100 text-sm">Manajemen pengguna sistem</p>
                    </div>
                </a>
                
                <a href="/admin/reports" class="flex items-center justify-center px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-chart-line text-xl mr-3"></i>
                    <div class="text-left">
                        <h3 class="font-semibold">Lihat Laporan</h3>
                        <p class="text-purple-100 text-sm">Analisis dan statistik event</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Events and Registrations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Events -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Event Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if (empty($recentEvents)): ?>
                        <div class="p-12 text-center">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada event yang dibuat</p>
                            <a href="/admin/events/create" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Buat Event Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentEvents as $event): ?>
                            <div class="p-6 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 mb-1"><?= esc($event['title']) ?></h4>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : 'purple') ?>-100 text-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : 'purple') ?>-800">
                                                <?= ucfirst($event['type']) ?>
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                <?= date('d M Y', strtotime($event['start_date'])) ?>
                                            </span>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $event['status'] === 'published' ? 'green' : ($event['status'] === 'draft' ? 'yellow' : 'red') ?>-100 text-<?= $event['status'] === 'published' ? 'green' : ($event['status'] === 'draft' ? 'yellow' : 'red') ?>-800">
                                            <?= ucfirst($event['status']) ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="/admin/events/edit/<?= $event['id'] ?>" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <a href="/admin/events" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                        Lihat semua events <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Registrations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Pendaftaran Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if (empty($recentRegistrations)): ?>
                        <div class="p-12 text-center">
                            <i class="fas fa-user-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada pendaftaran</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentRegistrations as $registration): ?>
                            <div class="p-6 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 mb-1"><?= esc($registration['full_name']) ?></h4>
                                        <p class="text-sm text-gray-600 mb-2"><?= esc($registration['title']) ?></p>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $registration['status'] === 'registered' ? 'blue' : ($registration['status'] === 'attended' ? 'green' : 'red') ?>-100 text-<?= $registration['status'] === 'registered' ? 'blue' : ($registration['status'] === 'attended' ? 'green' : 'red') ?>-800">
                                                <?= ucfirst($registration['status']) ?>
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-clock mr-1"></i>
                                                <?= date('d M Y H:i', strtotime($registration['created_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <a href="/admin/users" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                        Lihat semua registrasi <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>