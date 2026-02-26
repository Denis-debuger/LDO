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
      <div class="home-topline">LDO — платформа тренировок, питания и прогресса</div>

      <div class="home-categories">
        <div class="home-tabs">
          <button type="button" class="home-tab is-active">Тренировки</button>
          <button type="button" class="home-tab">Питание</button>
          <button type="button" class="home-tab">Дневник</button>
          <button type="button" class="home-tab">Сообщество</button>
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
          <h1>Достигайте формы системно</h1>
          <p>Ведите дневник тренировок, контролируйте КБЖУ и отслеживайте изменения без хаоса.</p>
          <?php if (!is_logged_in()): ?>
            <div class="home-actions">
              <a href="<?= url('register') ?>" class="btn btn-primary">Начать</a>
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

      <section class="card card-accent" style="margin:20px 0">
        <div class="card-body">
          <h2 class="card-title">Что вы получите в LDO</h2>
          <ul class="muted" style="margin:0;padding-left:18px;line-height:1.8">
            <li>Готовые тренировочные программы для разных уровней подготовки.</li>
            <li>Базу упражнений с подсказками по технике и безопасному прогрессу.</li>
            <li>Инструменты учёта питания, КБЖУ и динамики веса.</li>
            <li>Раздел статей с практическими рекомендациями по восстановлению и режиму.</li>
          </ul>
        </div>
      </section>

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
