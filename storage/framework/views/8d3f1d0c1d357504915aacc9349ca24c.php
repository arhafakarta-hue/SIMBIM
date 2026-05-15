

<?php $__env->startSection('content'); ?>
<div class="container grid">
    <div class="card">
        <h1>Rekomendasi Mata Kuliah</h1>
        <p class="muted">Halaman ini digunakan dosen wali untuk memberikan rekomendasi mata kuliah kepada mahasiswa bimbingan.</p>

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

    <?php if(in_array($user->role, ['dosen', 'admin'])): ?>
        <div class="card">
            <h2>Tambah Rekomendasi</h2>

            <form action="<?php echo e(route('rekomendasi-matkul.store')); ?>" method="POST" class="grid">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Mahasiswa</label>
                    <select name="mahasiswa_id" required>
                        <option value="">Pilih mahasiswa</option>
                        <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mahasiswa->id); ?>"><?php echo e($mahasiswa->name); ?> - <?php echo e($mahasiswa->email); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Kode Mata Kuliah</label>
                        <input type="text" name="kode_mata_kuliah" placeholder="Contoh: SI204">
                    </div>

                    <div class="form-group">
                        <label>Nama Mata Kuliah</label>
                        <input type="text" name="nama_mata_kuliah" placeholder="Contoh: Analisis dan Perancangan Sistem" required>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>SKS</label>
                        <input type="number" name="sks" min="1" max="6" placeholder="Contoh: 3">
                    </div>

                    <div class="form-group">
                        <label>Semester Rekomendasi</label>
                        <input type="number" name="semester_rekomendasi" min="1" placeholder="Contoh: 5">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Rekomendasi</label>
                    <select name="status" required>
                        <option value="direkomendasikan">Direkomendasikan</option>
                        <option value="opsional">Opsional</option>
                        <option value="tidak_direkomendasikan">Tidak Direkomendasikan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Alasan Rekomendasi</label>
                    <textarea name="alasan_rekomendasi" placeholder="Contoh: Mata kuliah ini sesuai dengan progress akademik mahasiswa dan dapat diambil semester depan."></textarea>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" placeholder="Catatan tambahan jika ada..."></textarea>
                </div>

                <button type="submit">Simpan Rekomendasi</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Daftar Rekomendasi Mata Kuliah</h2>

        <?php $__empty_1 = true; $__currentLoopData = $rekomendasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekomendasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="info-card" style="margin-bottom: 16px;">
                <h3><?php echo e($rekomendasi->nama_mata_kuliah); ?></h3>

                <p>
                    <b>Kode:</b> <?php echo e($rekomendasi->kode_mata_kuliah ?? '-'); ?> <br>
                    <b>SKS:</b> <?php echo e($rekomendasi->sks ?? '-'); ?> <br>
                    <b>Semester Rekomendasi:</b> <?php echo e($rekomendasi->semester_rekomendasi ?? '-'); ?> <br>
                    <b>Status:</b> <?php echo e(strtoupper(str_replace('_', ' ', $rekomendasi->status))); ?> <br>
                    <b>Mahasiswa:</b> <?php echo e($rekomendasi->mahasiswa->name ?? '-'); ?> <br>
                    <b>Dosen:</b> <?php echo e($rekomendasi->dosen->name ?? '-'); ?>

                </p>

                <?php if($rekomendasi->alasan_rekomendasi): ?>
                    <p>
                        <b>Alasan Rekomendasi:</b><br>
                        <?php echo e($rekomendasi->alasan_rekomendasi); ?>

                    </p>
                <?php endif; ?>

                <?php if($rekomendasi->catatan_tambahan): ?>
                    <p>
                        <b>Catatan Tambahan:</b><br>
                        <?php echo e($rekomendasi->catatan_tambahan); ?>

                    </p>
                <?php endif; ?>

                <?php if(in_array($user->role, ['dosen', 'admin'])): ?>
                    <form action="<?php echo e(route('rekomendasi-matkul.destroy', $rekomendasi)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekomendasi ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn-danger" type="submit">Hapus Rekomendasi</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="muted">Belum ada rekomendasi mata kuliah.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Rekomendasi Mata Kuliah'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simbim-realtime-laravel\resources\views/rekomendasi-matkul/index.blade.php ENDPATH**/ ?>