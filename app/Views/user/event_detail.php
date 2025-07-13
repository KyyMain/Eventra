<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="/user/events" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Event
        </a>
    </div>

    <!-- Event Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <?php if (isset($event['image']) && $event['image']): ?>
            <img src="/uploads/events/<?= $event['image'] ?>" alt="<?= esc($event['title']) ?>" class="w-full h-64 object-cover">
        <?php else: ?>
            <div class="w-full h-64 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                <i class="fas fa-calendar text-white text-6xl"></i>
            </div>
        <?php endif; ?>
        
        <div class="p-8">
            <div class="flex items-center justify-between mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-100 text-<?= $event['type'] === 'seminar' ? 'blue' : ($event['type'] === 'workshop' ? 'green' : ($event['type'] === 'conference' ? 'purple' : 'yellow')) ?>-800">
                    <i class="fas fa-<?= $event['type'] === 'seminar' ? 'microphone' : ($event['type'] === 'workshop' ? 'tools' : ($event['type'] === 'conference' ? 'users' : 'graduation-cap')) ?> mr-2"></i>
                    <?= ucfirst($event['type']) ?>
                </span>
                
                <?php if ($event['price'] > 0): ?>
                    <span class="text-2xl font-bold text-green-600">Rp <?= number_format($event['price'], 0, ',', '.') ?></span>
                <?php else: ?>
                    <span class="text-2xl font-bold text-blue-600">Gratis</span>
                <?php endif; ?>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= esc($event['title']) ?></h1>
            
            <!-- Event Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-user-tie w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Speaker</span>
                            <p class="font-medium"><?= esc($event['speaker']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Tanggal</span>
                            <p class="font-medium"><?= date('d M Y', strtotime($event['start_date'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-clock w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Waktu</span>
                            <p class="font-medium"><?= date('H:i', strtotime($event['start_date'])) ?> - <?= date('H:i', strtotime($event['end_date'])) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Lokasi</span>
                            <p class="font-medium"><?= esc($event['location']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-users w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Peserta</span>
                            <p class="font-medium"><?= $event['current_participants'] ?>/<?= $event['max_participants'] ?> terdaftar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-user w-5 mr-3 text-gray-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Dibuat oleh</span>
                            <p class="font-medium"><?= esc($event['creator_name']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Participants Progress -->
            <div class="mb-6">
                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                    <span>Kapasitas Peserta</span>
                    <span class="font-medium"><?= $event['current_participants'] ?>/<?= $event['max_participants'] ?></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                         style="width: <?= $event['max_participants'] > 0 ? ($event['current_participants'] / $event['max_participants']) * 100 : 0 ?>%"></div>
                </div>
                <?php if ($event['current_participants'] >= $event['max_participants']): ?>
                    <p class="text-red-600 text-sm mt-2 font-medium">Event sudah penuh</p>
                <?php elseif ($event['current_participants'] / $event['max_participants'] > 0.8): ?>
                    <p class="text-orange-600 text-sm mt-2 font-medium">Sisa tempat terbatas</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Event Description -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Event</h2>
        <div class="prose max-w-none text-gray-600">
            <?= nl2br(esc($event['description'])) ?>
        </div>
    </div>

    <!-- Registration Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Registrasi</h2>
        
        <?php if ($isRegistered): ?>
            <!-- Already Registered -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Anda sudah terdaftar!</h3>
                        <p class="text-green-600">Terima kasih telah mendaftar untuk event ini. Jangan lupa untuk hadir tepat waktu.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <a href="/user/my-events" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-medium text-center transition duration-200">
                    Lihat Event Saya
                </a>
                
                <?php if (strtotime($event['start_date']) > time() && $userRegistration): ?>
                    <?= form_open('user/registrations/cancel/' . $userRegistration['id'], ['class' => 'flex-1', 'onsubmit' => "return confirm('Apakah Anda yakin ingin membatalkan registrasi?')"]) ?>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg font-medium transition duration-200">
                            Batalkan Registrasi
                        </button>
                    <?= form_close() ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Registration Form -->
            <?php if ($event['current_participants'] >= $event['max_participants']): ?>
                <!-- Event Full -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-red-800">Event Sudah Penuh</h3>
                            <p class="text-red-600">Maaf, event ini sudah mencapai kapasitas maksimum peserta.</p>
                        </div>
                    </div>
                </div>
            <?php elseif (strtotime($event['start_date']) <= time()): ?>
                <!-- Event Already Started -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Event Sudah Dimulai</h3>
                            <p class="text-yellow-600">Registrasi untuk event ini sudah ditutup karena event sudah dimulai.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Can Register -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 text-2xl mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-blue-800">Daftar Sekarang</h3>
                                <p class="text-blue-600">Klik tombol di bawah untuk mendaftar ke event ini.</p>
                            </div>
                        </div>
                        <?php if ($event['price'] > 0): ?>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Biaya</p>
                                <p class="text-xl font-bold text-green-600">Rp <?= number_format($event['price'], 0, ',', '.') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <form action="/user/events/register/<?= $event['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-semibold text-lg transition duration-200 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        <?= $event['price'] > 0 ? 'Daftar & Bayar' : 'Daftar Gratis' ?>
                    </button>
                </form>
                
                <?php if ($event['price'] > 0): ?>
                    <p class="text-sm text-gray-500 mt-3 text-center">
                        Setelah mendaftar, Anda akan diarahkan untuk melakukan pembayaran
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>