

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Identitas Registrasi</h1>
        <p class="muted">
            Halaman ini digunakan admin untuk menambahkan NIM mahasiswa, NIDN/NIP dosen, atau kode admin yang boleh digunakan saat registrasi akun.
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
        <h2>Tambah Identitas Baru</h2>

        <form action="<?php echo e(route('identitas-registrasi.store')); ?>" method="POST" class="grid">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label>Nomor Identitas</label>
                <input type="text" name="nomor_identitas" placeholder="Contoh: 22010003 / 1987654323 / ADMIN-02" required>
            </div>

            <div class="form-group">
                <label>Nama Pemilik Identitas</label>
                <input type="text" name="nama" placeholder="Masukkan nama mahasiswa/dosen/admin" required>
            </div>

            <div class="form-group">
                <label>Jenis Akun</label>
                <select name="role" required>
                    <option value="">Pilih jenis akun</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen Wali</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label>Program Studi</label>
                    <input type="text" name="prodi" placeholder="Contoh: Sistem Informasi">
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: SI-4A">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" placeholder="Contoh: 4">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <button type="submit">Simpan Identitas</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Identitas Registrasi</h2>

        <?php $__empty_1 = true; $__currentLoopData = $identitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($item->nama); ?></h3>

                <p>
                    <b>Nomor Identitas:</b> <?php echo e($item->nomor_identitas); ?> <br>
                    <b>Jenis Akun:</b> <?php echo e(strtoupper($item->role)); ?> <br>
                    <b>Program Studi:</b> <?php echo e($item->prodi ?? '-'); ?> <br>
                    <b>Kelas:</b> <?php echo e($item->kelas ?? '-'); ?> <br>
                    <b>Semester:</b> <?php echo e($item->semester ?? '-'); ?> <br>
                    <b>Status:</b> <?php echo e(strtoupper($item->status)); ?> <br>
                    <b>Sudah Digunakan:</b> <?php echo e($item->sudah_digunakan ? 'YA' : 'BELUM'); ?>

                </p>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <?php if($item->sudah_digunakan): ?>
                        <form action="<?php echo e(route('identitas-registrasi.reset', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit">Reset Penggunaan</button>
                        </form>
                    <?php endif; ?>

                    <form action="<?php echo e(route('identitas-registrasi.destroy', $item)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus identitas ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada identitas registrasi.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Identitas Registrasi'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/identitas-registrasi/index.blade.php ENDPATH**/ ?>