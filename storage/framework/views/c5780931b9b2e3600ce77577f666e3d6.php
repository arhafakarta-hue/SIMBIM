

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Progress Studi</h1>
        <p class="muted">Halaman ini digunakan untuk mengupload KHS/progress akademik dan melihat catatan dari dosen wali.</p>

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
            <h2>Upload Progress Studi</h2>

            <form action="<?php echo e(route('progress-studi.store')); ?>" method="POST" enctype="multipart/form-data" class="grid">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" placeholder="Contoh: 4" required>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>IP Semester</label>
                        <input type="number" step="0.01" name="ip" min="0" max="4" placeholder="Contoh: 3.50">
                    </div>

                    <div class="form-group">
                        <label>IPK</label>
                        <input type="number" step="0.01" name="ipk" min="0" max="4" placeholder="Contoh: 3.60">
                    </div>
                </div>

                <div class="form-group">
                    <label>SKS Lulus</label>
                    <input type="number" name="sks_lulus" min="0" placeholder="Contoh: 80">
                </div>

                <div class="form-group">
                    <label>Upload File KHS</label>
                    <input type="file" name="file_khs" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="muted">Format: PDF, JPG, JPEG, PNG. Maksimal 5 MB.</small>
                </div>

                <div class="form-group">
                    <label>Catatan Mahasiswa</label>
                    <textarea name="catatan_mahasiswa" placeholder="Contoh: Mohon arahan untuk pengambilan mata kuliah semester depan."></textarea>
                </div>

                <button type="submit">Upload Progress</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Daftar Progress Studi</h2>

        <?php $__empty_1 = true; $__currentLoopData = $progressStudis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($progress->mahasiswa->name ?? '-'); ?></h3>

                <p>
                    <b>Semester:</b> <?php echo e($progress->semester ?? '-'); ?> <br>
                    <b>IP:</b> <?php echo e($progress->ip ?? '-'); ?> <br>
                    <b>IPK:</b> <?php echo e($progress->ipk ?? '-'); ?> <br>
                    <b>SKS Lulus:</b> <?php echo e($progress->sks_lulus ?? '-'); ?> <br>
                    <b>Status:</b> <?php echo e(strtoupper(str_replace('_', ' ', $progress->status))); ?>

                </p>

                <?php if($progress->file_khs): ?>
                    <p>
                        <b>File KHS:</b>
                        <a href="<?php echo e(asset('storage/' . $progress->file_khs)); ?>" target="_blank">Lihat File</a>
                    </p>
                <?php endif; ?>

                <?php if($progress->catatan_mahasiswa): ?>
                    <p>
                        <b>Catatan Mahasiswa:</b><br>
                        <?php echo e($progress->catatan_mahasiswa); ?>

                    </p>
                <?php endif; ?>

                <?php if($progress->catatan_dosen): ?>
                    <p>
                        <b>Catatan Dosen:</b><br>
                        <?php echo e($progress->catatan_dosen); ?>

                    </p>
                <?php endif; ?>

                <?php if(in_array($user->role, ['dosen', 'admin'])): ?>
                    <form action="<?php echo e(route('progress-studi.review', $progress)); ?>" method="POST" style="margin-top: 12px;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="form-group">
                            <label>Status Review</label>
                            <select name="status" required>
                                <option value="menunggu_review" <?php echo e($progress->status === 'menunggu_review' ? 'selected' : ''); ?>>Menunggu Review</option>
                                <option value="sudah_direview" <?php echo e($progress->status === 'sudah_direview' ? 'selected' : ''); ?>>Sudah Direview</option>
                                <option value="perlu_perbaikan" <?php echo e($progress->status === 'perlu_perbaikan' ? 'selected' : ''); ?>>Perlu Perbaikan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Catatan Dosen</label>
                            <textarea name="catatan_dosen" placeholder="Tulis catatan atau arahan untuk mahasiswa..."><?php echo e($progress->catatan_dosen); ?></textarea>
                        </div>

                        <button type="submit">Simpan Review</button>
                    </form>
                <?php endif; ?>

                <?php if($user->role === 'admin' || $progress->mahasiswa_id === $user->id): ?>
                    <form action="<?php echo e(route('progress-studi.destroy', $progress)); ?>" method="POST" style="margin-top: 12px;" onsubmit="return confirm('Yakin ingin menghapus progress ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn-danger" type="submit">Hapus Progress</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada progress studi.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Progress Studi'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/progress-studi/index.blade.php ENDPATH**/ ?>