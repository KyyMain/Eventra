<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Sertifikat Saya</h1>
            <p class="mt-2 text-gray-600">Kelola dan unduh sertifikat kehadiran event Anda</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Certificates Grid -->
        <?php if (!empty($certificates)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($certificates as $cert): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <!-- Certificate Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-white">
                                    <i class="fas fa-certificate text-2xl"></i>
                                </div>
                                <span class="bg-white bg-opacity-20 text-white text-xs px-2 py-1 rounded-full">
                                    <?= ucfirst($cert['type']) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Certificate Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                <?= esc($cert['title']) ?>
                            </h3>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                    <span>
                                        <?= date('d M Y', strtotime($cert['start_date'])) ?>
                                        <?php if ($cert['end_date'] && $cert['end_date'] !== $cert['start_date']): ?>
                                            - <?= date('d M Y', strtotime($cert['end_date'])) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie w-4 mr-2"></i>
                                    <span><?= esc($cert['speaker']) ?></span>
                                </div>
                                
                                <div class="flex items-center">
                                    <i class="fas fa-code w-4 mr-2"></i>
                                    <span class="font-mono text-xs"><?= esc($cert['certificate_code']) ?></span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                <?php if ($cert['status'] === 'attended'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Hadir
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        <?= ucfirst($cert['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <?php if ($cert['status'] === 'attended'): ?>
                                    <a href="/user/certificates/download/<?= $cert['id'] ?>" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">
                                        <i class="fas fa-download mr-1"></i>
                                        Unduh
                                    </a>
                                    <a href="/user/certificates/view/<?= $cert['id'] ?>" 
                                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat
                                    </a>
                                <?php else: ?>
                                    <button disabled 
                                            class="flex-1 bg-gray-300 text-gray-500 text-center py-2 px-4 rounded-md text-sm font-medium cursor-not-allowed">
                                        <i class="fas fa-lock mr-1"></i>
                                        Belum Tersedia
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <div class="mt-8">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-certificate text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Sertifikat</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki sertifikat. Daftar dan hadiri event untuk mendapatkan sertifikat.</p>
                <a href="/events" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Jelajahi Event
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?= $this->endSection() ?>