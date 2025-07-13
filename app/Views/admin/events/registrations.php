<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Peserta Event</h1>
                <p class="text-gray-600"><?= esc($event['title']) ?></p>
            </div>
            <a href="/admin/events" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Events
            </a>
        </div>
    </div>

    <!-- Event Info -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Event</h3>
                <p class="text-sm text-gray-600">Tanggal: <?= date('d M Y', strtotime($event['start_date'])) ?></p>
                <p class="text-sm text-gray-600">Lokasi: <?= esc($event['location']) ?></p>
                <p class="text-sm text-gray-600">Speaker: <?= esc($event['speaker']) ?></p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Kapasitas</h3>
                <p class="text-sm text-gray-600">Maksimal: <?= $event['max_participants'] ?> peserta</p>
                <p class="text-sm text-gray-600">Terdaftar: <?= count($registrations) ?> peserta</p>
                <p class="text-sm text-gray-600">Sisa: <?= $event['max_participants'] - count($registrations) ?> peserta</p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Status</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    <?= $event['status'] === 'published' ? 'bg-green-100 text-green-800' : 
                        ($event['status'] === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                        ($event['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) ?>">
                    <?= ucfirst($event['status']) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Certificate Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Cara Membuat Sertifikat</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Untuk membuat sertifikat peserta:</p>
                    <ol class="list-decimal list-inside mt-1 space-y-1">
                        <li>Ubah status peserta menjadi <strong>"Attended"</strong> pada kolom Status</li>
                        <li>Sertifikat akan otomatis diterbitkan dengan kode unik</li>
                        <li>Peserta dapat mengunduh sertifikat melalui dashboard mereka</li>
                        <li>Admin dapat melihat dan mengunduh sertifikat melalui tombol aksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Peserta</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Total <?= count($registrations) ?> peserta terdaftar</p>
        </div>
        
        <?php if (empty($registrations)): ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Belum ada peserta yang mendaftar untuk event ini.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sertifikat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($registrations as $registration): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= esc($registration['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= esc($registration['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= esc($registration['email']) ?></div>
                                    <div class="text-sm text-gray-500"><?= esc($registration['phone'] ?? '-') ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y H:i', strtotime($registration['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="/admin/registrations/update-status/<?= $registration['id'] ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 border-0
                                            <?= $registration['status'] === 'attended' ? 'bg-blue-100 text-blue-800' : 
                                                ($registration['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                ($registration['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) ?>">
                                            <option value="pending" <?= $registration['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $registration['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="attended" <?= $registration['status'] === 'attended' ? 'selected' : '' ?>>Attended</option>
                                            <option value="cancelled" <?= $registration['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="/admin/registrations/update-status/<?= $registration['id'] ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <select name="payment_status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 border-0
                                            <?= $registration['payment_status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                                ($registration['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <option value="pending" <?= $registration['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="paid" <?= $registration['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="failed" <?= $registration['payment_status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($registration['status'] === 'attended' && $registration['certificate_issued']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Diterbitkan
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?= esc($registration['certificate_code']) ?>
                                        </div>
                                    <?php elseif ($registration['status'] === 'attended'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Menunggu
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-minus mr-1"></i>
                                            Belum Tersedia
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($registration['status'] === 'attended'): ?>
                                        <a href="/user/certificates/view/<?= $registration['id'] ?>" 
                                           class="text-green-600 hover:text-green-900 mr-3" 
                                           title="Lihat Sertifikat" target="_blank">
                                            <i class="fas fa-certificate"></i>
                                        </a>
                                        <a href="/user/certificates/download/<?= $registration['id'] ?>" 
                                           class="text-blue-600 hover:text-blue-900 mr-3" 
                                           title="Download Sertifikat" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400 mr-3" title="Sertifikat belum tersedia">
                                            <i class="fas fa-certificate"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>