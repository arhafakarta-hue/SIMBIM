<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-shell">
        <section class="auth-hero">
            <div class="auth-hero-content">
                <span class="auth-tag">SIMBIM • Academic Guidance</span>
                <div>
                    <h1>Bimbingan akademik yang lebih rapi dan modern.</h1>
                    <p>Kelola obrolan, jadwal, progress studi, dan rekomendasi mata kuliah dalam satu sistem.</p>
                </div>
                <div class="hero-decoration"></div>
            </div>
        </section>

        <section class="auth-card">
            <div class="logo-text">
                <div class="logo">SB</div>
                <div>
                    <div class="logo-title">SIMBIM</div>
                    <div class="logo-subtitle">Sistem Informasi Bimbingan Akademik</div>
                </div>
            </div>

            <h1>Masuk</h1>
            <p class="muted">Gunakan email dan password yang sudah terdaftar.</p>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.post')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" placeholder="nama@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" style="width:100%">Masuk ke Dashboard</button>
            </form>

            <p class="muted" style="margin-top: 18px; text-align:center;">
                Belum punya akun? <a href="<?php echo e(route('register')); ?>" style="color:var(--primary); font-weight:900;">Daftar akun</a>
            </p>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Login SIMBIM'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>