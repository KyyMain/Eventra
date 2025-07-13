<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan & Analitik</h1>
            <p class="text-gray-600">Dashboard analitik dan laporan komprehensif untuk platform Eventra</p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Event</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_events'] ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?= $stats['events_growth'] ?>% dari bulan lalu
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Registrasi</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_registrations'] ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?= $stats['registrations_growth'] ?>% dari bulan lalu
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-user-friends text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total User</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_users'] ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?= $stats['users_growth'] ?>% dari bulan lalu
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-certificate text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">Sertifikat Diterbitkan</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_certificates'] ?></p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?= $stats['certificates_growth'] ?>% dari bulan lalu
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Registrations Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Registrasi Bulanan</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg font-medium">6 Bulan</button>
                        <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">1 Tahun</button>
                    </div>
                </div>
                <div class="space-y-4">
                    <?php 
                    $monthlyData = $monthly_registrations ?? [];
                    $maxRegistrations = !empty($monthlyData) ? max(array_column($monthlyData, 'count')) : 0;
                    if (!empty($monthlyData)):
                        foreach ($monthlyData as $month): 
                            $percentage = $maxRegistrations > 0 ? ($month['count'] / $maxRegistrations) * 100 : 0;
                    ?>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-700 w-20"><?= $month['month'] ?></span>
                            <div class="flex items-center flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                                         style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 w-12 text-right"><?= $month['count'] ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada data registrasi bulanan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Event Type Distribution -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Distribusi Jenis Event</h2>
                <div class="space-y-4">
                    <?php 
                    $eventTypeData = $event_type_distribution ?? [];
                    $totalEvents = !empty($eventTypeData) ? array_sum(array_column($eventTypeData, 'count')) : 0;
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-yellow-500', 'bg-red-500'];
                    if (!empty($eventTypeData)):
                        foreach ($eventTypeData as $index => $type): 
                            $percentage = $totalEvents > 0 ? ($type['count'] / $totalEvents) * 100 : 0;
                            $colorClass = $colors[$index % count($colors)];
                    ?>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 <?= $colorClass ?> rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700"><?= ucfirst($type['event_type'] ?? $type['type']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="<?= $colorClass ?> h-2 rounded-full transition-all duration-500" style="width: <?= $percentage ?>%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-8 text-right"><?= $type['count'] ?></span>
                                <span class="text-xs text-gray-500 w-12 text-right">(<?= number_format($percentage, 1) ?>%)</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-chart-pie text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada data distribusi jenis event</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Top Events -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Event Terpopuler</h2>
                <?php if (empty($top_events)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-trophy text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada data event</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($top_events as $index => $event): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm"><?= $index + 1 ?></span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900"><?= esc(substr($event['title'], 0, 30)) ?>...</p>
                                        <p class="text-xs text-gray-500"><?= ucfirst($event['type']) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900"><?= $event['participants'] ?></p>
                                    <p class="text-xs text-gray-500">peserta</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Aktivitas Terbaru</h2>
                <?php if (empty($recent_activities)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium"><?= esc($activity['user_name']) ?></span>
                                        mendaftar ke event
                                        <span class="font-medium"><?= esc(substr($activity['event_title'], 0, 20)) ?>...</span>
                                    </p>
                                    <p class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($activity['created_at'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Revenue Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pendapatan</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Total Pendapatan</p>
                            <p class="text-xs text-gray-500">Semua event berbayar</p>
                        </div>
                        <p class="text-lg font-bold text-green-600">Rp <?= number_format($revenue_summary['total_revenue'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Bulan Ini</p>
                            <p class="text-xs text-gray-500"><?= date('F Y') ?></p>
                        </div>
                        <p class="text-lg font-bold text-blue-600">Rp <?= number_format($revenue_summary['monthly_revenue'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Rata-rata per Event</p>
                            <p class="text-xs text-gray-500">Event berbayar</p>
                        </div>
                        <p class="text-lg font-bold text-purple-600">Rp <?= number_format($revenue_summary['avg_revenue'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Event Gratis:</span>
                            <span class="font-medium"><?= $revenue_summary['free_events'] ?? 0 ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Event Berbayar:</span>
                            <span class="font-medium"><?= $revenue_summary['paid_events'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Export Laporan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <a href="/admin/reports/export/events" 
                   class="flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Data Event
                </a>
                <a href="/admin/reports/export/users" 
                   class="flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-file-csv mr-2"></i>
                    Export Data User
                </a>
                <a href="/admin/reports/export/registrations" 
                   class="flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition duration-200 shadow-sm">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export Registrasi
                </a>
            </div>
            
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Filter Periode Export</h3>
                <form class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Jenis Event</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="seminar">Seminar</option>
                            <option value="workshop">Workshop</option>
                            <option value="conference">Conference</option>
                            <option value="training">Training</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition duration-200 shadow-sm">
                        <i class="fas fa-filter mr-1"></i>Terapkan Filter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh data setiap 5 menit
setInterval(function() {
    // Refresh halaman untuk update data real-time
    // location.reload();
}, 300000);

// Chart animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
            bar.style.transition = 'width 1s ease-in-out';
        }, 100);
    });
});

    <!-- Detailed Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Top Events -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Event Terpopuler</h2>
            <?php if (empty($top_events)): ?>
                <p class="text-gray-500 text-center py-8">Belum ada data event</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($top_events as $index => $event): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm"><?= $index + 1 ?></span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900"><?= esc(substr($event['title'], 0, 30)) ?>...</p>
                                    <p class="text-xs text-gray-500"><?= ucfirst($event['type']) ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900"><?= $event['participants'] ?></p>
                                <p class="text-xs text-gray-500">peserta</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Aktivitas Terbaru</h2>
            <?php if (empty($recent_activities)): ?>
                <p class="text-gray-500 text-center py-8">Belum ada aktivitas terbaru</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium"><?= esc($activity['user_name']) ?></span>
                                    mendaftar ke event
                                    <span class="font-medium"><?= esc(substr($activity['event_title'], 0, 20)) ?>...</span>
                                </p>
                                <p class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($activity['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Revenue Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pendapatan</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Total Pendapatan</p>
                        <p class="text-xs text-gray-500">Semua event berbayar</p>
                    </div>
                    <p class="text-lg font-bold text-green-600">Rp <?= number_format($revenue_summary['total_revenue'] ?? 0, 0, ',', '.') ?></p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Bulan Ini</p>
                        <p class="text-xs text-gray-500"><?= date('F Y') ?></p>
                    </div>
                    <p class="text-lg font-bold text-blue-600">Rp <?= number_format($revenue_summary['monthly_revenue'] ?? 0, 0, ',', '.') ?></p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Rata-rata per Event</p>
                        <p class="text-xs text-gray-500">Event berbayar</p>
                    </div>
                    <p class="text-lg font-bold text-purple-600">Rp <?= number_format($revenue_summary['avg_revenue'] ?? 0, 0, ',', '.') ?></p>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Event Gratis:</span>
                        <span class="font-medium"><?= $revenue_summary['free_events'] ?? 0 ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Event Berbayar:</span>
                        <span class="font-medium"><?= $revenue_summary['paid_events'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Export Laporan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/admin/reports/export/events" 
               class="flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-file-excel mr-2"></i>
                Export Data Event
            </a>
            <a href="/admin/reports/export/users" 
               class="flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-file-csv mr-2"></i>
                Export Data User
            </a>
            <a href="/admin/reports/export/registrations" 
               class="flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition duration-200">
                <i class="fas fa-file-pdf mr-2"></i>
                Export Registrasi
            </a>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Filter Periode Export</h3>
            <form class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
                    <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Jenis Event</label>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="seminar">Seminar</option>
                        <option value="workshop">Workshop</option>
                        <option value="conference">Conference</option>
                        <option value="training">Training</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-filter mr-1"></i>Terapkan Filter
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-refresh data setiap 5 menit
setInterval(function() {
    // Refresh halaman untuk update data real-time
    // location.reload();
}, 300000);

// Chart animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
            bar.style.transition = 'width 1s ease-in-out';
        }, 100);
    });
});
</script>

<?= $this->endSection() ?>