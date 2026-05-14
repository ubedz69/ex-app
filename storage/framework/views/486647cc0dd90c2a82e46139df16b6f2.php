

<?php $__env->startSection('title', 'Contact — '.config('app.name')); ?>
<?php $__env->startSection('meta_description', 'Hubungi Rai Raka Express untuk kebutuhan ekspedisi internasional. Dapatkan penawaran pengiriman door-to-door ke luar negeri, termasuk Jepang, dengan proses cepat dan mudah dilacak.'); ?>
<?php $__env->startSection('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card" style="padding:28px;max-width:900px;margin:0 auto;">
        <h1 class="hero-title" style="font-size:32px;line-height:1.1;margin-bottom:8px;">Hubungi Rai Raka Express</h1>
        <h2 style="margin:0 0 18px 0;font-size:18px;font-weight:900;color:#0f172a;opacity:.95;">Penawaran Ekspedisi Internasional Cepat & Mudah</h2>

        <p style="margin:0 0 18px 0;color:#54617a;font-weight:600;line-height:1.6;">
            Ceritakan kebutuhan pengiriman Anda. Tim kami akan membantu konsultasi dan memberikan informasi yang Anda perlukan.
        </p>

        <?php if(session('status')): ?>
            <div style="margin:0 0 16px 0;padding:12px 14px;border-radius:12px;border:2px solid rgba(11,93,167,0.18);background:rgba(11,93,167,0.05);color:#0B5DA7;font-weight:900;">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div style="margin:0 0 14px 0;padding:14px;border-radius:14px;border:2px solid rgba(2,6,23,0.06);background:#fff;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div style="min-width:200px;">
                <h3 style="margin:0;font-size:16px;font-weight:900;">Konsultasi via WhatsApp</h3>
                <p style="margin:6px 0 0 0;color:#54617a;font-weight:700;line-height:1.5;">Klik untuk chat langsung.</p>
            </div>
            <a href="https://wa.me/6285121112486" target="_blank" rel="noopener noreferrer"
               class="btn btn-outline"
               style="padding:14px 18px;border-radius:12px;font-size:16px;font-weight:900;border:2px solid rgba(2,6,23,0.08);text-decoration:none;background:#25D366;color:#fff;white-space:nowrap;display:inline-flex;align-items:center;gap:10px;min-height:44px;">
                <span aria-hidden="true" style="width:26px;height:26px;border-radius:8px;background:rgba(255,255,255,0.2);display:inline-flex;align-items:center;justify-content:center;font-weight:900;">☎</span>
                Chat via WhatsApp
            </a>
        </div>

        <form action="<?php echo e(url('/contact')); ?>" method="POST" style="display:grid;grid-template-columns:1fr;gap:12px;">
            <?php echo csrf_field(); ?>

            <div>
                <label for="name" style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;">Nama</label>
                <input id="name" name="name" value="<?php echo e(old('name')); ?>" required
                       class="input"
                       style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;">
                <?php $__errorArgs = ['name'];
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
                <label for="phone" style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;">Nomor Handphone / WhatsApp</label>
                <input id="phone" name="phone" type="tel" value="<?php echo e(old('phone')); ?>" required
                       class="input"
                       placeholder="Contoh: 08xxxxxxxxxx"
                       style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;">
                <?php $__errorArgs = ['phone'];
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
                <label for="message" style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;">Pesan</label>
                <textarea id="message" name="message" rows="5" required
                          class="input"
                          style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;resize:vertical;"><?php echo e(old('message')); ?></textarea>
                <?php $__errorArgs = ['message'];
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
                    Kirim Pesan
                </button>
            </div>
        </form>

        <div style="margin-top:18px;padding:16px;border-radius:14px;border:2px solid rgba(2,6,23,0.06);background:#fff;">
            <h3 style="margin:0 0 8px 0;font-size:16px;font-weight:900;">Informasi yang membantu</h3>
            <ul style="margin:0;padding-left:18px;color:#54617a;font-weight:700;line-height:1.7;">
                <li>Tujuan negara</li>
                <li>Jenis barang & perkiraan berat/volume</li>
                <li>Estimasi kebutuhan waktu</li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Website\expedition-app\resources\views/contact.blade.php ENDPATH**/ ?>