

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Riwayat Bimbingan</h1>
        <p class="muted">Halaman ini menampilkan riwayat bimbingan akademik yang sudah diproses oleh dosen wali.</p>
    </div>

    <div class="card">
        <h2>Daftar Riwayat Bimbingan</h2>

        <?php $__empty_1 = true; $__currentLoopData = $riwayats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $riwayat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($riwayat->topik); ?></h3>

                <p>
                    <b>Mahasiswa:</b> <?php echo e($riwayat->mahasiswa->name ?? '-'); ?> <br>
                    <b>Dosen Wali:</b> <?php echo e($riwayat->dosen->name ?? '-'); ?> <br>
                    <b>Tanggal:</b> <?php echo e(\Carbon\Carbon::parse($riwayat->tanggal)->format('d M Y')); ?> <br>
                    <b>Jam:</b> <?php echo e(substr($riwayat->jam, 0, 5)); ?> <br>
                    <b>Status:</b> <?php echo e(strtoupper($riwayat->status)); ?>

                </p>

                <?php if($riwayat->keterangan): ?>
                    <p>
                        <b>Keterangan Mahasiswa:</b><br>
                        <?php echo e($riwayat->keterangan); ?>

                    </p>
                <?php endif; ?>

                <?php if($riwayat->catatan_dosen): ?>
                    <p>
                        <b>Catatan Dosen:</b><br>
                        <?php echo e($riwayat->catatan_dosen); ?>

                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada riwayat bimbingan.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Riwayat Bimbingan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/riwayat-bimbingan/index.blade.php ENDPATH**/ ?>