<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Certificate Container -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Certificate Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Sertifikat Kehadiran</h1>
                        <p class="text-blue-100">Certificate of Attendance</p>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-certificate text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Certificate Body -->
            <div class="px-8 py-12 text-center">
                <!-- Certificate Logo/Seal -->
                <div class="mb-8">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-award text-white text-3xl"></i>
                    </div>
                </div>

                <!-- Certificate Title -->
                <h2 class="text-3xl font-bold text-gray-900 mb-2">SERTIFIKAT KEHADIRAN</h2>
                <p class="text-lg text-gray-600 mb-8">Certificate of Attendance</p>

                <!-- Certificate Content -->
                <div class="space-y-6">
                    <p class="text-lg text-gray-700">Dengan ini menyatakan bahwa</p>
                    
                    <div class="border-b-2 border-gray-300 pb-2 mb-6">
                        <h3 class="text-2xl font-bold text-gray-900"><?= esc($registration['full_name']) ?></h3>
                    </div>

                    <p class="text-lg text-gray-700">telah mengikuti dan menyelesaikan</p>

                    <div class="bg-gray-50 rounded-lg p-6 my-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-2"><?= esc($registration['event_title']) ?></h4>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2"></i>
                            <?= date('d F Y', strtotime($registration['event_start_date'])) ?>
                            <?php if ($registration['event_end_date'] && $registration['event_end_date'] !== $registration['event_start_date']): ?>
                                - <?= date('d F Y', strtotime($registration['event_end_date'])) ?>
                            <?php endif; ?>
                        </p>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-user-tie mr-2"></i>
                            Pembicara: <?= esc($registration['event_speaker']) ?>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-tag mr-2"></i>
                            <?= ucfirst($registration['event_type']) ?>
                        </p>
                    </div>

                    <p class="text-lg text-gray-700">yang diselenggarakan oleh <strong>Eventra</strong></p>
                </div>

                <!-- Certificate Footer -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Tanggal Terbit</p>
                            <p class="font-semibold"><?= date('d F Y') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Kode Sertifikat</p>
                            <p class="font-semibold font-mono"><?= esc($registration['certificate_code']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Verification Note -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Sertifikat ini dapat diverifikasi di <strong>eventra.com/verify-certificate</strong> 
                        dengan menggunakan kode sertifikat di atas.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                <i class="fas fa-print mr-2"></i>Cetak Sertifikat
            </button>
            <a href="/user/certificates" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-gray-50 {
        background: white !important;
    }
}
</style>

<?= $this->endSection() ?>