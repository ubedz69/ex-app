

<?php $__env->startSection('title', 'Blog — '.config('app.name')); ?>
<?php $__env->startSection('meta_description', 'Blog Rai Raka Express'); ?>
<?php $__env->startSection('meta_keywords', 'blog'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card" style="padding:28px;max-width:900px;margin:0 auto;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
            <div>
                <h1 class="hero-title" style="font-size:32px;line-height:1.1;margin:0 0 6px 0;">Blog</h1>
                <p class="hero-sub" style="margin:0;color:#54617a;font-weight:600;">Posting dan ringkasan ditampilkan di halaman utama.</p>
            </div>
            <a href="<?php echo e(url('/blog/create')); ?>" class="btn" style="text-decoration:none;">Buat Post</a>
        </div>

        <?php if(count($posts) === 0): ?>
            <div style="padding:16px;border-radius:14px;border:2px solid rgba(2,6,23,0.06);background:#fff;color:#54617a;font-weight:700;">
                Belum ada posting blog.
            </div>
        <?php else: ?>
            <div style="display:grid;grid-template-columns:1fr;gap:12px;">
                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card" style="padding:16px;border-radius:14px;box-shadow:none;border:2px solid rgba(2,6,23,0.06);background:#fff;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                            <h3 style="margin:0;font-size:18px;font-weight:900;color:#0f172a;"><?php echo e($post['title'] ?? ''); ?></h3>
                            <div style="color:#54617a;font-weight:700;font-size:13px;"><?php echo e($post['created_at'] ?? ''); ?></div>
                        </div>
                        <p style="margin:8px 0 0 0;color:#56697f;font-weight:700;line-height:1.6;">
                            <?php echo e($post['summary'] ?? ''); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Website\expedition-app\resources\views/blog/index.blade.php ENDPATH**/ ?>