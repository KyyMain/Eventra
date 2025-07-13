<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="/admin/events" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
        </div>
        <p class="text-gray-600">Edit informasi event: <?= esc($event['title']) ?></p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form action="/admin/events/update/<?= $event['id'] ?>" method="post" enctype="multipart/form-data" class="space-y-6 p-6">
            <?= csrf_field() ?>
            
            <!-- Basic Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Event *</label>
                        <input type="text" id="title" name="title" value="<?= old('title', $event['title']) ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="Masukkan judul event" required>
                        <?php if (isset($validation) && $validation->hasError('title')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('title') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                        <select id="type" name="type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <option value="">Pilih tipe event</option>
                            <option value="seminar" <?= old('type', $event['type']) === 'seminar' ? 'selected' : '' ?>>Seminar</option>
                            <option value="workshop" <?= old('type', $event['type']) === 'workshop' ? 'selected' : '' ?>>Workshop</option>
                            <option value="conference" <?= old('type', $event['type']) === 'conference' ? 'selected' : '' ?>>Conference</option>
                            <option value="training" <?= old('type', $event['type']) === 'training' ? 'selected' : '' ?>>Training</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('type')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('type') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="speaker" class="block text-sm font-medium text-gray-700 mb-2">Speaker *</label>
                        <input type="text" id="speaker" name="speaker" value="<?= old('speaker', $event['speaker']) ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="Nama speaker" required>
                        <?php if (isset($validation) && $validation->hasError('speaker')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('speaker') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                        <textarea id="description" name="description" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                  placeholder="Deskripsi detail tentang event" required><?= old('description', $event['description']) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('description')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('description') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Date & Location -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Waktu & Lokasi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Mulai *</label>
                        <input type="datetime-local" id="start_date" name="start_date" value="<?= old('start_date', date('Y-m-d\TH:i', strtotime($event['start_date']))) ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        <?php if (isset($validation) && $validation->hasError('start_date')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('start_date') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Selesai *</label>
                        <input type="datetime-local" id="end_date" name="end_date" value="<?= old('end_date', date('Y-m-d\TH:i', strtotime($event['end_date']))) ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        <?php if (isset($validation) && $validation->hasError('end_date')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('end_date') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                        <input type="text" id="location" name="location" value="<?= old('location', $event['location']) ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="Alamat lengkap lokasi event" required>
                        <?php if (isset($validation) && $validation->hasError('location')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('location') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Participants & Pricing -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Peserta & Harga</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta *</label>
                        <input type="number" id="max_participants" name="max_participants" value="<?= old('max_participants', $event['max_participants']) ?>" min="<?= $event['current_participants'] ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="Jumlah maksimal peserta" required>
                        <p class="mt-1 text-sm text-gray-500">Saat ini ada <?= $event['current_participants'] ?> peserta terdaftar</p>
                        <?php if (isset($validation) && $validation->hasError('max_participants')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('max_participants') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                        <input type="number" id="price" name="price" value="<?= old('price', $event['price']) ?>" min="0" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="0 untuk gratis">
                        <p class="mt-1 text-sm text-gray-500">Kosongkan atau isi 0 untuk event gratis</p>
                        <?php if (isset($validation) && $validation->hasError('price')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('price') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Image & Status -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar & Status</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Event</label>
                        
                        <?php if ($event['image']): ?>
                            <div class="mb-4">
                                <img src="/uploads/events/<?= $event['image'] ?>" alt="Current event image" class="h-32 w-auto rounded-lg shadow-md">
                                <p class="mt-1 text-sm text-gray-500">Gambar saat ini</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span><?= $event['image'] ? 'Ganti gambar' : 'Upload gambar' ?></span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('image')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('image') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <option value="draft" <?= old('status', $event['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= old('status', $event['status']) === 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="cancelled" <?= old('status', $event['status']) === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="completed" <?= old('status', $event['status']) === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if ($event['current_participants'] > 0): ?>
                                <span class="text-orange-600">Perhatian: Ada <?= $event['current_participants'] ?> peserta terdaftar</span>
                            <?php else: ?>
                                Draft: Event tidak akan terlihat oleh user. Published: Event dapat dilihat dan didaftar oleh user.
                            <?php endif; ?>
                        </p>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('status') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Certificate Template -->
            <div class="pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Sertifikat</h3>
                
                <div>
                    <label for="certificate_template" class="block text-sm font-medium text-gray-700 mb-2">Template Sertifikat</label>
                    <textarea id="certificate_template" name="certificate_template" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                              placeholder="Template untuk sertifikat (opsional)"><?= old('certificate_template', $event['certificate_template']) ?></textarea>
                    <p class="mt-1 text-sm text-gray-500">Template ini akan digunakan untuk generate sertifikat peserta yang hadir. Gunakan placeholder seperti {nama_peserta}, {nama_event}, {tanggal_event}</p>
                    <?php if (isset($validation) && $validation->hasError('certificate_template')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('certificate_template') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="/admin/events" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Update Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview image
            const preview = document.createElement('img');
            preview.src = e.target.result;
            preview.className = 'mt-2 h-32 w-auto rounded-lg shadow-md';
            
            // Remove existing preview
            const existingPreview = document.querySelector('.image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Add new preview
            preview.className += ' image-preview';
            e.target.parentNode.parentNode.parentNode.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>