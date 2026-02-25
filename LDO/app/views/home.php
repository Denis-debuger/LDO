<?php
$baseUrl = base_path();
$mediaUrl = function ($src) use ($baseUrl) {
  if (strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0) return $src;
  return $baseUrl . ltrim($src, '/');
};

$hero = $homeMedia[0] ?? null;
$heroSrc = $hero ? $mediaUrl($hero['src']) : '';
$heroAlt = e($hero['alt'] ?? 'LDO hero');
?>

<div class="home-wrap">
  <div class="container">
    <section class="home-shell">
      <div class="home-topline">Pay later with Klarna</div>

      <div class="home-categories">
        <div class="home-tabs">
          <button type="button" class="home-tab is-active">Ladies</button>
          <button type="button" class="home-tab">Dudes</button>
          <button type="button" class="home-tab">Accessories</button>
          <button type="button" class="home-tab">Athlete club</button>
        </div>
        <div class="home-icons">
          <span>EN</span>
          <span>🔍</span>
          <span>👤</span>
          <span>🛍️</span>
        </div>
      </div>

      <div class="home-hero-card">
        <?php if ($hero): ?>
          <?php if (($hero['type'] ?? '') === 'video'): ?>
            <video autoplay muted loop playsinline class="home-hero-media">
              <source src="<?= e($heroSrc) ?>" type="video/mp4">
            </video>
          <?php else: ?>
            <img src="<?= e($heroSrc) ?>" alt="<?= $heroAlt ?>" class="home-hero-media" onerror="this.style.display='none'">
          <?php endif; ?>
        <?php endif; ?>

        <div class="home-hero-overlay"></div>
        <div class="home-hero-content">
          <h1>BLACKOUT</h1>
          <p>Check out the latest releases for Ladies &amp; Dudes</p>
          <?php if (!is_logged_in()): ?>
            <div class="home-actions">
              <a href="<?= url('register') ?>" class="btn btn-primary">SHOP</a>
              <a href="<?= url('login') ?>" class="btn btn-ghost">Войти</a>
            </div>
          <?php else: ?>
            <div class="home-actions">
              <a href="<?= url('profile') ?>" class="btn btn-primary">Профиль</a>
              <a href="<?= url('kbju') ?>" class="btn btn-ghost">КБЖУ</a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="home-tiles">
        <?php for ($i = 1; $i <= 2; $i++): ?>
          <?php $block = $homeMedia[$i] ?? null; ?>
          <article class="home-tile">
            <?php if ($block): ?>
              <?php $src = $mediaUrl($block['src']); ?>
              <?php if (($block['type'] ?? '') === 'video'): ?>
                <video controls loop class="home-tile-media">
                  <source src="<?= e($src) ?>" type="video/mp4">
                </video>
              <?php else: ?>
                <img src="<?= e($src) ?>" alt="<?= e($block['alt'] ?? 'media') ?>" class="home-tile-media" onerror="this.style.display='none'">
              <?php endif; ?>
              <div class="home-tile-caption"><?= e($block['caption'] ?? 'Collection') ?></div>
            <?php else: ?>
              <div class="home-tile-placeholder">Добавьте медиа в <code>config/home_media.php</code></div>
            <?php endif; ?>
          </article>
        <?php endfor; ?>
      </div>
    </section>
  </div>
</div>
