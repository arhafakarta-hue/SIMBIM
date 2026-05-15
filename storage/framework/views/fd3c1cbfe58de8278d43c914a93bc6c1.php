<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Obrolan Bimbingan</h1>
        <p class="muted">Pilih ruang bimbingan. Dosen dan mahasiswa bisa saling mengirim pesan dari perangkat berbeda.</p>
    </div>

    <div class="card">
        <div class="table-like">
            <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $other = auth()->id() === $conversation->dosen_id ? $conversation->mahasiswa : $conversation->dosen;
                    $last = $conversation->messages->first();
                ?>
                <a class="row-link" href="<?php echo e(route('chat.show', $conversation)); ?>">
                    <div class="row-left">
                        <div class="avatar"><?php echo e(strtoupper(substr($other->name, 0, 1))); ?></div>
                        <div style="min-width:0">
                            <b><?php echo e($conversation->judul); ?></b>
                            <div class="muted">Dengan <?php echo e($other->name); ?> • <?php echo e(ucfirst($other->role)); ?></div>
                            <div class="muted truncate" style="margin-top:4px"><?php echo e($last?->message ?? 'Belum ada pesan'); ?></div>
                        </div>
                    </div>
                    <span class="badge green"><?php echo e($conversation->status); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty">Belum ada ruang bimbingan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Obrolan Bimbingan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/chat/index.blade.php ENDPATH**/ ?>