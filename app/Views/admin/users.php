<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen User</h1>
                    <p class="text-gray-600">Kelola pengguna dan status akun mereka</p>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total User</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_users'] ?></p>
                        <p class="text-xs text-blue-600 flex items-center mt-1">
                            <i class="fas fa-user-plus mr-1"></i>
                            Terdaftar
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">User Aktif</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['active_users'] ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-check-circle mr-1"></i>
                            Status aktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-user-times text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">User Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['inactive_users'] ?></p>
                        <p class="text-xs text-red-600 flex items-center mt-1">
                            <i class="fas fa-times-circle mr-1"></i>
                            Status nonaktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">User Baru (30 hari)</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['new_users'] ?></p>
                        <p class="text-xs text-purple-600 flex items-center mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            Bulan ini
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter & Pencarian</h3>
            <form method="GET" action="/admin/users" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" 
                           placeholder="Cari berdasarkan nama, username, atau email..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Semua Status</option>
                        <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Semua Role</option>
                        <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="/admin/users" class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </a>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Daftar User</h2>
            </div>
            
            <?php if (empty($users)): ?>
                <div class="p-16 text-center">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada User</h3>
                    <p class="text-gray-500">Belum ada user yang terdaftar atau sesuai dengan filter yang dipilih.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center border border-gray-200">
                                                    <span class="text-white font-medium text-sm">
                                                        <?= strtoupper(substr($user['full_name'], 0, 2)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 mb-1"><?= esc($user['full_name']) ?></div>
                                                <div class="text-xs text-gray-500 flex items-center">
                                                    <i class="fas fa-at mr-1"></i>
                                                    <?= esc($user['username']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                            <?= esc($user['email']) ?>
                                        </div>
                                        <?php if (!empty($user['phone'])): ?>
                                            <div class="text-sm text-gray-500 flex items-center">
                                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                                <?= esc($user['phone']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <i class="fas <?= $user['role'] === 'admin' ? 'fa-crown' : 'fa-user' ?> mr-1"></i>
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <i class="fas <?= $user['is_active'] ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                                            <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="font-medium text-gray-900"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
                                        <div class="text-xs flex items-center mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?= date('H:i', strtotime($user['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                                            <?= $user['total_registrations'] ?? 0 ?> Event
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-certificate mr-2 text-yellow-500"></i>
                                            <?= $user['certificates_count'] ?? 0 ?> Sertifikat
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <form method="POST" action="/admin/users/toggle-status/<?= $user['id'] ?>" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition duration-200 <?= $user['is_active'] ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah status user ini?')">
                                                <i class="fas <?= $user['is_active'] ? 'fa-user-times' : 'fa-user-check' ?> mr-1"></i>
                                                <?= $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700">
                                                    <i class="fas fa-crown mr-1"></i>Admin
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- User Activity Summary -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Registrations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Registrasi Terbaru</h3>
                <?php if (empty($recent_registrations)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-user-plus text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada registrasi terbaru</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recent_registrations as $reg): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-150">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-400 flex items-center justify-center border border-gray-200">
                                        <span class="text-white font-medium text-xs">
                                            <?= strtoupper(substr($reg['user_name'], 0, 2)) ?>
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900"><?= esc($reg['user_name']) ?></p>
                                        <p class="text-xs text-gray-500 flex items-center mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <?= esc($reg['event_title']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1"><?= date('d M', strtotime($reg['created_at'])) ?></p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        <?= ucfirst($reg['status']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- User Growth Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Pertumbuhan User (30 Hari Terakhir)</h3>
                <div class="space-y-4">
                    <?php 
                    $userGrowthData = $user_growth ?? [];
                    $maxRegistrations = !empty($userGrowthData) ? max(array_column($userGrowthData, 'count')) : 0;
                    foreach ($userGrowthData as $growth): 
                        $percentage = $maxRegistrations > 0 ? ($growth['count'] / $maxRegistrations) * 100 : 0;
                    ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 w-16"><?= date('d M', strtotime($growth['date'])) ?></span>
                            <div class="flex items-center flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-8 text-right"><?= $growth['count'] ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($userGrowthData)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada data pertumbuhan user</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>