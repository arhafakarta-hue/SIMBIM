

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Jadwal Bimbingan</h1>
        <p class="muted">Halaman ini digunakan untuk mengajukan, melihat, dan mengelola jadwal bimbingan akademik.</p>

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

    <?php if($user->role === 'mahasiswa'): ?>
        <div class="card">
            <h2>Ajukan Jadwal Bimbingan</h2>

            <form action="<?php echo e(route('jadwal.store')); ?>" method="POST" class="grid">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Dosen Wali</label>
                    <select name="dosen_id" required>
                        <option value="">Pilih dosen wali</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dosen->id); ?>"><?php echo e($dosen->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" required>
                    </div>

                    <div class="form-group">
                        <label>Jam</label>
                        <input type="time" name="jam" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Topik Bimbingan</label>
                    <input type="text" name="topik" placeholder="Contoh: Konsultasi KRS semester depan" required>
                </div>

                <div class="form-group">
                    <label>Keterangan Tambahan</label>
                    <textarea name="keterangan" placeholder="Tulis keterangan jika ada..."></textarea>
                </div>

                <button type="submit">Ajukan Jadwal</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Daftar Jadwal Bimbingan</h2>

        <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($jadwal->topik); ?></h3>

                <p>
                    <b>Mahasiswa:</b> <?php echo e($jadwal->mahasiswa->name ?? '-'); ?> <br>
                    <b>Dosen:</b> <?php echo e($jadwal->dosen->name ?? '-'); ?> <br>
                    <b>Tanggal:</b> <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y')); ?> <br>
                    <b>Jam:</b> <?php echo e(substr($jadwal->jam, 0, 5)); ?> <br>
                    <b>Status:</b> <?php echo e(strtoupper($jadwal->status)); ?>

                </p>

                <?php if($jadwal->keterangan): ?>
                    <p>
                        <b>Keterangan Mahasiswa:</b><br>
                        <?php echo e($jadwal->keterangan); ?>

                    </p>
                <?php endif; ?>

                <?php if($jadwal->catatan_dosen): ?>
                    <p>
                        <b>Catatan Dosen:</b><br>
                        <?php echo e($jadwal->catatan_dosen); ?>

                    </p>
                <?php endif; ?>

                <?php if($user->role === 'dosen' && $jadwal->status === 'menunggu'): ?>
                    <form action="<?php echo e(route('jadwal.approve', $jadwal)); ?>" method="POST" style="margin-top: 12px;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="form-group">
                            <label>Catatan Persetujuan</label>
                            <textarea name="catatan_dosen" placeholder="Contoh: Jadwal disetujui, silakan hadir tepat waktu."></textarea>
                        </div>

                        <button type="submit">Setujui Jadwal</button>
                    </form>

                    <form action="<?php echo e(route('jadwal.reject', $jadwal)); ?>" method="POST" style="margin-top: 12px;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="form-group">
                            <label>Alasan Penolakan</label>
                            <textarea name="catatan_dosen" placeholder="Contoh: Mohon pilih jadwal lain."></textarea>
                        </div>

                        <button type="submit">Tolak Jadwal</button>
                    </form>
                <?php endif; ?>

                <?php if($user->role === 'dosen' && $jadwal->status === 'disetujui'): ?>
                    <form action="<?php echo e(route('jadwal.finish', $jadwal)); ?>" method="POST" style="margin-top: 12px;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit">Tandai Selesai</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada jadwal bimbingan.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Jadwal Bimbingan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/jadwal/index.blade.php ENDPATH**/ ?>