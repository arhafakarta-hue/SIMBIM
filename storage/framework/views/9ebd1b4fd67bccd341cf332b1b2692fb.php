<?php $__env->startSection('content'); ?>
<div class="container grid">
    <section class="card hero-card">
        <div class="hero-content page-header">
            <div>
                <div class="page-kicker"><?php echo e(strtoupper($user->role)); ?></div>
                <h1>Halo, <?php echo e($user->name); ?></h1>
                <?php if($user->role === 'admin'): ?>
                    <p class="muted">Pusat kendali SIMBIM untuk mengatur data pengguna, identitas registrasi, jadwal, progress studi, dan monitoring akademik.</p>
                <?php elseif($user->role === 'dosen'): ?>
                    <p class="muted">Pantau mahasiswa bimbingan, balas obrolan, proses jadwal, review progress, dan beri rekomendasi mata kuliah.</p>
                <?php else: ?>
                    <p class="muted">Ajukan jadwal, pantau progress studi, baca rekomendasi mata kuliah, dan lanjutkan obrolan bersama dosen wali.</p>
                <?php endif; ?>
            </div>
            <div class="page-actions">
                <?php if($user->role === 'admin'): ?>
                    <a class="btn" href="<?php echo e(route('identitas-registrasi.index')); ?>">Tambah Identitas</a>
                    <a class="btn btn-secondary" href="<?php echo e(route('data-mahasiswa.index')); ?>">Kelola Mahasiswa</a>
                <?php elseif($user->role === 'dosen'): ?>
                    <a class="btn" href="<?php echo e(route('chat.index')); ?>">Buka Obrolan</a>
                    <a class="btn btn-secondary" href="<?php echo e(route('data-mahasiswa.index')); ?>">Mahasiswa Bimbingan</a>
                <?php else: ?>
                    <a class="btn" href="<?php echo e(route('jadwal.index')); ?>">Ajukan Jadwal</a>
                    <a class="btn btn-secondary" href="<?php echo e(route('chat.index')); ?>">Buka Obrolan</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if($user->role === 'admin'): ?>
        <section class="grid grid-4">
            <div class="card stat-card"><div class="stat-number"><?php echo e($totalUsers); ?></div><div class="stat-label">Total User</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($totalIdentitas); ?></div><div class="stat-label">Identitas Registrasi</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($jadwalMenunggu); ?></div><div class="stat-label">Jadwal Menunggu</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($progressPending); ?></div><div class="stat-label">Progress Perlu Review</div></div>
        </section>

        <section class="grid grid-3">
            <div class="card">
                <h2>Admin Center</h2>
                <p class="muted">Aksi cepat yang paling sering dipakai admin.</p>
                <div class="feature-grid">
                    <a class="feature-card" href="<?php echo e(route('identitas-registrasi.index')); ?>"><span class="feature-icon">✦</span><span><b>Identitas</b><br><small class="muted">Tambah NIM/NIDN</small></span></a>
                    <a class="feature-card" href="<?php echo e(route('data-dosen.index')); ?>"><span class="feature-icon">◇</span><span><b>Data Dosen</b><br><small class="muted">Kelola dosen wali</small></span></a>
                    <a class="feature-card" href="<?php echo e(route('data-mahasiswa.index')); ?>"><span class="feature-icon">◉</span><span><b>Data Mahasiswa</b><br><small class="muted">Pasangkan dosen wali</small></span></a>
                </div>
            </div>

            <div class="card">
                <h2>Kondisi Sistem</h2>
                <div class="table-like">
                    <div class="list-row"><span>Identitas belum dipakai</span><b><?php echo e($identitasBelumDipakai); ?></b></div>
                    <div class="list-row"><span>Ruang bimbingan aktif</span><b><?php echo e($totalConversations); ?></b></div>
                    <div class="list-row"><span>Total rekomendasi</span><b><?php echo e($totalRekomendasi); ?></b></div>
                    <div class="list-row"><span>Jadwal selesai</span><b><?php echo e($jadwalSelesai); ?></b></div>
                </div>
            </div>

            <div class="card">
                <h2>Monitoring Jadwal</h2>
                <?php $__empty_1 = true; $__currentLoopData = $recentJadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="info-card">
                        <b><?php echo e($jadwal->topik); ?></b>
                        <div class="muted"><?php echo e($jadwal->mahasiswa->name ?? '-'); ?> • <?php echo e(strtoupper($jadwal->status)); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty">Belum ada jadwal.</div>
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <section class="grid grid-4">
            <div class="card stat-card"><div class="stat-number"><?php echo e($totalConversations); ?></div><div class="stat-label">Ruang Bimbingan</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($jadwalMenunggu); ?></div><div class="stat-label">Jadwal Menunggu</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($progressPending); ?></div><div class="stat-label">Progress Pending</div></div>
            <div class="card stat-card"><div class="stat-number"><?php echo e($totalRekomendasi); ?></div><div class="stat-label">Rekomendasi</div></div>
        </section>

        <section class="grid grid-2">
            <div class="card">
                <div class="page-header">
                    <div><h2>Jadwal Terbaru</h2><p class="muted">Pantau agenda bimbingan akademik.</p></div>
                    <a class="btn btn-secondary" href="<?php echo e(route('jadwal.index')); ?>">Lihat Jadwal</a>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $recentJadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="info-card">
                        <span class="badge <?php echo e($jadwal->status === 'selesai' ? 'green' : ($jadwal->status === 'menunggu' ? 'warning' : '')); ?>"><?php echo e(strtoupper($jadwal->status)); ?></span>
                        <h3 style="margin-top:10px;"><?php echo e($jadwal->topik); ?></h3>
                        <p class="muted" style="margin-bottom:0;"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y')); ?> • <?php echo e(substr($jadwal->jam, 0, 5)); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty">Belum ada jadwal bimbingan.</div>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="page-header">
                    <div><h2>Pesan Terbaru</h2><p class="muted">Obrolan terakhir dari ruang bimbingan.</p></div>
                    <a class="btn btn-secondary" href="<?php echo e(route('chat.index')); ?>">Obrolan</a>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $latestMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="row-link" style="margin-bottom:10px">
                        <div class="row-left">
                            <div class="avatar"><?php echo e(strtoupper(substr($message->sender->name, 0, 1))); ?></div>
                            <div>
                                <b><?php echo e($message->sender->name); ?></b>
                                <div class="muted truncate"><?php echo e($message->message); ?></div>
                            </div>
                        </div>
                        <small class="muted"><?php echo e($message->created_at->format('H:i')); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty">Belum ada pesan.</div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Dashboard SIMBIM'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>