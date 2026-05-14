<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $__env->yieldContent('title', 'Rai Raka Express'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Cepat cek nomor resi DHL & FedEx. Pelacakan cepat, aman, mudah.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', ''); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?php echo e(asset('images/favicon.svg')); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body>
    <a href="#main-content" class="skip-link">Lanjut ke konten</a>
    <div id="announcements" aria-live="polite" class="sr-only" aria-atomic="true"></div>
    <header class="site-header">
        <div class="topbar" style="background:transparent;border-bottom:1px solid rgba(15,23,42,0.03);">
            <div class="container header-inner">
                <div class="top-left">
                    <small>Pilih negara / bahasa</small>
                </div>
                <div class="top-right">
                    <a href="<?php echo e(url('/contact')); ?>" class="btn btn-ghost">Help & Support</a>
                </div>
            </div>
        </div>

        <div class="container header-inner" style="padding-top:18px;padding-bottom:18px;">
<a href="/" class="logo-link"><img src="<?php echo e(asset('images/logo-compact.png')); ?>" alt="Rai Raka Express" class="logo-img"></a>

            <button id="menu-button" aria-controls="main-nav" aria-expanded="false" class="btn btn-ghost" aria-label="Toggle navigation">Menu</button>

            <nav id="main-nav" class="main-nav" role="navigation" aria-label="Primary navigation">
                <a href="<?php echo e(url('/about')); ?>">About</a>
                <a href="<?php echo e(url('/services')); ?>">Services</a>
                <a href="<?php echo e(url('/tracking')); ?>">Tracking</a>
                <a href="<?php echo e(url('/locations')); ?>">Locations</a>
                <a href="<?php echo e(url('/contact')); ?>">Contact</a>
            </nav>

            <div class="header-actions" style="display:flex;align-items:center;gap:12px">
                <form action="<?php echo e(url('/tracking')); ?>" method="GET" class="inline-track-form" style="display:flex;gap:8px;align-items:center" role="search" aria-label="Search tracking">
                    <label for="header-tracking" class="sr-only">Nomor resi</label>
                    <input id="header-tracking" type="search" name="tracking_number" placeholder="Masukkan nomor AWB" class="input" style="width:220px;padding:8px 10px;" aria-describedby="header-track-help" maxlength="12" pattern="(\d{10}|\d{12})" inputmode="numeric" title="Masukkan 10 atau 12 nomor AWB">
                    <span id="header-track-help" class="sr-only">Masukkan nomor AWB lalu tekan Track</span>
                    <button type="submit" class="btn" style="padding:8px 12px">Track</button>
                </form>

                <a href="<?php echo e(url('/services')); ?>" class="btn btn-outline" style="background:transparent;color:#000;border:1px solid rgba(2,6,23,0.08);text-decoration:none;">Get a Quote</a>
            </div>
        </div>
    </header>

    <main id="main-content" class="site-main" role="main" tabindex="-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
<small>&copy; <?php echo e(date('Y')); ?> Rai Raka Express — Logistics & Ekspedisi</small>
        </div>
    </footer>

    <script src="<?php echo e(asset('js/accessibility.js')); ?>" defer></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Website\expedition-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>