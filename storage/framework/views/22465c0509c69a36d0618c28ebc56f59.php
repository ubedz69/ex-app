

<?php $__env->startSection('title', 'Buat Post Blog — '.config('app.name')); ?>
<?php $__env->startSection('meta_description', 'Buat posting blog untuk Rai Raka Express.'); ?>
<?php $__env->startSection('meta_keywords', 'blog, ekspedisi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card" style="padding:28px;max-width:900px;margin:0 auto;">
        <h1 class="hero-title" style="font-size:32px;line-height:1.1;margin:0 0 6px 0;">Buat Post Blog</h1>
        <p class="hero-sub" style="margin:0 0 18px 0;color:#54617a;font-weight:600;">
            Isi judul, ringkasan, dan konten. Ringkasan akan ditampilkan di halaman Welcome.
        </p>

        <form method="POST" action="<?php echo e(url('/blog')); ?>" style="display:grid;grid-template-columns:1fr;gap:12px;">
            <?php echo csrf_field(); ?>

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="title">Judul</label>
                <input id="title" name="title" value="<?php echo e(old('title')); ?>" required
                       class="input"
                       style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="summary">Ringkasan (max 500)</label>
                <textarea id="summary" name="summary" rows="4" required
                          class="input"
                          style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;resize:vertical;"><?php echo e(old('summary')); ?></textarea>
                <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="content">Konten</label>
                <textarea id="content" name="content" rows="8" required
                          class="input"
                          style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;resize:vertical;"><?php echo e(old('content')); ?></textarea>
                <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <button type="submit" class="btn" style="padding:14px 18px;border-radius:12px;font-size:16px;font-weight:900;min-height:44px;">
                    Simpan Post
                </button>
                <a href="<?php echo e(url('/blog')); ?>" class="btn btn-outline"
                   style="padding:14px 18px;border-radius:12px;font-size:16px;font-weight:900;border:2px solid rgba(2,6,23,0.08);text-decoration:none;min-height:44px;">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Website\expedition-app\resources\views/blog/create.blade.php ENDPATH**/ ?>