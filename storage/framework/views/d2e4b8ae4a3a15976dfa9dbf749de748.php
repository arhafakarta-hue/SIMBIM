

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Data Dosen Wali</h1>
        <p class="muted">Halaman ini digunakan untuk mengelola dan melihat data dosen wali.</p>

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

    <?php if($user->role === 'admin'): ?>
        <div class="card">
            <h2>Tambah / Update Data Dosen Wali</h2>

            <form action="<?php echo e(route('data-dosen.store')); ?>" method="POST" class="grid">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Akun Dosen</label>
                    <select name="user_id" required>
                        <option value="">Pilih akun dosen</option>
                        <?php $__currentLoopData = $akunDosen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($akun->id); ?>"><?php echo e($akun->name); ?> - <?php echo e($akun->email); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>NIDN</label>
                        <input type="text" name="nidn" placeholder="Contoh: 1234567890">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Nama lengkap dosen">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" name="prodi" placeholder="Contoh: Sistem Informasi">
                    </div>

                    <div class="form-group">
                        <label>Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" placeholder="Contoh: Rekayasa Perangkat Lunak">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 08123456789">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Alamat dosen"></textarea>
                </div>

                <button type="submit">Simpan Data Dosen</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Daftar Dosen Wali</h2>

        <?php $__empty_1 = true; $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($dosen->nama_lengkap ?? $dosen->user->name ?? '-'); ?></h3>

                <p>
                    <b>Akun:</b> <?php echo e($dosen->user->email ?? '-'); ?> <br>
                    <b>NIDN:</b> <?php echo e($dosen->nidn ?? '-'); ?> <br>
                    <b>Program Studi:</b> <?php echo e($dosen->prodi ?? '-'); ?> <br>
                    <b>Bidang Keahlian:</b> <?php echo e($dosen->bidang_keahlian ?? '-'); ?> <br>
                    <b>No. HP:</b> <?php echo e($dosen->no_hp ?? '-'); ?> <br>
                    <b>Alamat:</b> <?php echo e($dosen->alamat ?? '-'); ?>

                </p>

                <?php if($user->role === 'admin'): ?>
                    <form action="<?php echo e(route('data-dosen.destroy', $dosen)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus data dosen ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn-danger" type="submit">Hapus</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada data dosen wali.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Data Dosen Wali'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/data-dosen/index.blade.php ENDPATH**/ ?>