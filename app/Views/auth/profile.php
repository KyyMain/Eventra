<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Profile Saya</h1>
        <p class="text-gray-600">Kelola informasi akun dan preferensi Anda</p>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Informasi Akun
            </h2>
        </div>

        <div class="p-6">
            <?= form_open('auth/profile', ['class' => 'space-y-6']) ?>
                
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?= old('username', esc($user['username'])) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           required>
                    <?php if (isset($errors['username'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i>Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?= old('email', esc($user['email'])) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           required>
                    <?php if (isset($errors['email'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-1"></i>Nama Lengkap
                    </label>
                    <input type="text" 
                           id="full_name" 
                           name="full_name" 
                           value="<?= old('full_name', esc($user['full_name'])) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           required>
                    <?php if (isset($errors['full_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['full_name'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1"></i>Nomor Telepon
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="<?= old('phone', esc($user['phone'] ?? '')) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           placeholder="Contoh: 08123456789">
                    <?php if (isset($errors['phone'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['phone'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Ubah Password
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-1"></i>Password Baru
                            </label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Minimal 8 karakter">
                            <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter, harus mengandung huruf besar, kecil, angka, dan simbol</p>
                            <?php if (isset($errors['new_password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['new_password'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-1"></i>Konfirmasi Password
                            </label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Ulangi password baru">
                            <p class="mt-1 text-sm text-gray-500">Harus sama dengan password baru</p>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['confirm_password'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Informasi Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Role:</span>
                            <span class="ml-2 font-medium text-gray-900 capitalize"><?= esc($user['role']) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="ml-2">
                                <?php if ($user['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Bergabung:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Terakhir Update:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= date('d M Y', strtotime($user['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="<?= session()->get('role') === 'admin' ? '/admin/dashboard' : '/user/dashboard' ?>" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    
                    <button type="submit" 
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>

            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>