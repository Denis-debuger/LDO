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

      <section class="home-info-grid" style="margin-bottom:20px">
        <article class="card">
          <div class="card-body">
            <h3 class="card-title" style="margin-bottom:10px">С чего начать сегодня</h3>
            <p class="muted" style="margin-bottom:12px">Простой старт: заполните профиль, задайте цель по весу и выберите первую программу под ваш уровень.</p>
            <div class="home-actions">
              <a href="<?= url('profile') ?>" class="btn btn-primary">Заполнить профиль</a>
              <a href="<?= url('programs') ?>" class="btn btn-ghost">Открыть программы</a>
            </div>
          </div>
        </article>
        <article class="card">
          <div class="card-body">
            <h3 class="card-title" style="margin-bottom:10px">Полезные разделы</h3>
            <ul class="muted" style="margin:0;padding-left:18px;line-height:1.8">
              <li><a href="<?= url('diary') ?>">Дневник</a> — записи тренировок и питания.</li>
              <li><a href="<?= url('exercises') ?>">Упражнения</a> — база движений с техникой.</li>
              <li><a href="<?= url('progress') ?>">Прогресс</a> — динамика веса и привычек.</li>
              <li><a href="<?= url('articles') ?>">Статьи</a> — практические рекомендации.</li>
            </ul>
          </div>
        </article>
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
              <div class="home-tile-placeholder">
                <h3 style="margin:0 0 8px">Контент готов к заполнению</h3>
                <p class="muted" style="margin:0">Добавьте изображения в <code>config/home_media.php</code> или используйте разделы ниже для навигации.</p>
              </div>
            <?php endif; ?>
          </article>
        <?php endfor; ?>
      </div>

      <section class="home-info-grid" style="margin-top:20px">
        <article class="card">
          <div class="card-body">
            <h3 class="card-title" style="margin-bottom:10px">Силовые программы</h3>
            <p class="muted">Подборки на 4–12 недель: рост силы, гипертрофия и функциональная выносливость.</p>
          </div>
        </article>
        <article class="card">
          <div class="card-body">
            <h3 class="card-title" style="margin-bottom:10px">Питание и КБЖУ</h3>
            <p class="muted">Контроль калорий и макронутриентов с акцентом на устойчивый результат без жёстких ограничений.</p>
          </div>
        </article>
      </section>
    </section>
  </div>
</div>
