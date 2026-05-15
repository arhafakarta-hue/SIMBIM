

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Profile Saya</h1>
        <p class="muted">
            Halaman ini menampilkan data akun dan identitas pengguna SIMBIM.
        </p>

        <?php if(session('success')): ?>
            <div class="info-card" style="color: green;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="info-card" style="color: red;">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
            <?php if($user->photo): ?>
                <img 
                    src="<?php echo e(asset('storage/' . $user->photo)); ?>" 
                    alt="Foto Profile"
                    style="width:110px; height:110px; border-radius:50%; object-fit:cover; border:4px solid #f4d7e6;"
                >
            <?php else: ?>
                <div style="width:110px; height:110px; border-radius:50%; background:#f8e8f0; display:flex; align-items:center; justify-content:center; font-size:42px; font-weight:bold; color:#c2185b; border:4px solid #f4d7e6;">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
            <?php endif; ?>

            <div>
                <h2 style="margin-bottom:6px;"><?php echo e($user->name); ?></h2>
                <p class="muted" style="margin:0;">
                    <?php echo e($user->email); ?> <br>
                    <?php echo e(strtoupper($user->role)); ?>

                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Informasi Akun</h2>

        <?php if($user->role === 'mahasiswa'): ?>
            <div class="info-card">
                <h3>Data Mahasiswa</h3>
                <p>
                    <b>NIM:</b> <?php echo e($profile->nim ?? '-'); ?> <br>
                    <b>Program Studi:</b> <?php echo e($profile->prodi ?? '-'); ?> <br>
                    <b>Kelas:</b> <?php echo e($profile->kelas ?? '-'); ?> <br>
                    <b>Semester:</b> <?php echo e($profile->semester ?? '-'); ?> <br>
                    <b>Dosen Wali:</b> <?php echo e($profile->dosen->name ?? '-'); ?> <br>
                    <b>No. HP:</b> <?php echo e($profile->no_hp ?? '-'); ?> <br>
                    <b>Alamat:</b> <?php echo e($profile->alamat ?? '-'); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if($user->role === 'dosen'): ?>
            <div class="info-card">
                <h3>Data Dosen Wali</h3>
                <p>
                    <b>NIDN/NIP:</b> <?php echo e($profile->nidn ?? '-'); ?> <br>
                    <b>Program Studi:</b> <?php echo e($profile->prodi ?? '-'); ?> <br>
                    <b>Bidang Keahlian:</b> <?php echo e($profile->bidang_keahlian ?? '-'); ?> <br>
                    <b>No. HP:</b> <?php echo e($profile->no_hp ?? '-'); ?> <br>
                    <b>Alamat:</b> <?php echo e($profile->alamat ?? '-'); ?>

                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Edit Profile</h2>

        <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="grid">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div class="form-group">
                <label>Foto Profile</label>
                <input type="file" name="photo" accept="image/png,image/jpeg,image/jpg,image/webp">
                <small class="muted">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</small>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" value="<?php echo e(old('no_hp', $profile->no_hp ?? '')); ?>" placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Masukkan alamat"><?php echo e(old('alamat', $profile->alamat ?? '')); ?></textarea>
            </div>

            <hr>

            <h3>Ubah Password</h3>
            <p class="muted">
                Kosongkan bagian password jika tidak ingin mengganti password.
            </p>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Profile Saya'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/profile/index.blade.php ENDPATH**/ ?>