<?php
$baseUrl = base_path();
$mediaUrl = function ($src) use ($baseUrl) {
  if (strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0) return $src;
  return $baseUrl . ltrim($src, '/');
};
?>

<div class="home-hero" style="position:relative;min-height:60vh;display:flex;align-items:center;justify-content:center;background:var(--bg);overflow:hidden">
  <?php
  $hero = $homeMedia[0] ?? null;
  if ($hero):
    $src = $mediaUrl($hero['src']);
    $alt = e($hero['alt'] ?? '');
  ?>
  <?php if (($hero['type'] ?? '') === 'video'): ?>
  <video autoplay muted loop playsinline style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.4">
    <source src="<?= e($src) ?>" type="video/mp4">
  </video>
  <?php elseif (($hero['type'] ?? '') === 'gif' || strpos(strtolower($hero['src'] ?? ''), '.gif') !== false): ?>
  <img src="<?= e($src) ?>" alt="<?= $alt ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.3" onerror="this.style.display='none'">
  <?php else: ?>
  <img src="<?= e($src) ?>" alt="<?= $alt ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.25" onerror="this.style.display='none'">
  <?php endif; ?>
  <?php endif; ?>
  <div class="container" style="position:relative;z-index:1;text-align:center;max-width:640px;margin:0 auto">
    <h1 style="font-size:clamp(28px,5vw,42px);margin:0 0 12px 0;letter-spacing:.4px">
      LDO — Let's Do It
    </h1>
    <p class="muted" style="font-size:18px;margin-bottom:28px">
      Персональная фитнес-платформа. Контроль прогресса, тренировок и питания.
    </p>
    <?php if (!is_logged_in()): ?>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="<?= url('register') ?>" class="btn btn-primary">Начать</a>
      <a href="<?= url('login') ?>" class="btn btn-ghost">Войти</a>
    </div>
    <?php else: ?>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="<?= url('profile') ?>" class="btn btn-primary">Профиль</a>
      <a href="<?= url('kbju') ?>" class="btn btn-ghost">Калькулятор КБЖУ</a>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php if (count($homeMedia) > 1): ?>
<div class="container" style="padding:48px 24px 64px">
  <h2 style="margin:0 0 24px 0;font-size:20px;text-align:center" class="muted">Медиа-блоки</h2>
  <p class="muted" style="text-align:center;margin-bottom:32px;font-size:14px">
    Настройте блоки в <code style="color:var(--accent)">config/home_media.php</code> — добавьте свои изображения, видео или GIF.
  </p>
  <div class="home-media-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px">
    <?php for ($i = 1; $i < count($homeMedia); $i++):
      $block = $homeMedia[$i];
      $src = $mediaUrl($block['src']);
      $alt = e($block['alt'] ?? '');
    ?>
    <div class="card card-accent" style="overflow:hidden;padding:0">
      <?php if (($block['type'] ?? '') === 'video'): ?>
      <video controls loop style="width:100%;aspect-ratio:16/9;object-fit:cover;background:#000">
        <source src="<?= e($src) ?>" type="video/mp4">
      </video>
      <?php else: ?>
      <img src="<?= e($src) ?>" alt="<?= $alt ?>" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22225%22%3E%3Crect fill=%22%231C1C1C%22 width=%22400%22 height=%22225%22/%3E%3Ctext fill=%22%23B3B3B3%22 x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2214%22%3EВставьте изображение%3C/text%3E%3C/svg%3E'">
      <?php endif; ?>
      <?php if (!empty($block['caption'])): ?>
      <div class="card-body">
        <p class="muted" style="margin:0;font-size:14px"><?= e($block['caption']) ?></p>
      </div>
      <?php endif; ?>
    </div>
    <?php endfor; ?>
  </div>
</div>
<?php elseif (!empty($homeMedia)): ?>
<div class="container" style="padding:48px 24px 64px">
  <div class="card card-accent">
    <div class="card-body">
      <h2 class="card-title">Добавьте медиа-блоки</h2>
      <p class="muted">В <code>config/home_media.php</code> добавьте элементы в массив <code>$HOME_MEDIA</code>. Типы: <code>image</code>, <code>video</code>, <code>gif</code>. Файлы: <code>assets/images/home/</code> или <code>uploads/home/</code>.</p>
    </div>
  </div>
</div>
<?php else: ?>
<div class="container" style="padding:48px 24px 64px">
  <div class="card card-accent">
    <div class="card-body">
      <h2 class="card-title">Настройка медиа на главной</h2>
      <p class="muted">Создайте <code>config/home_media.php</code> и добавьте массив <code>$HOME_MEDIA</code> с блоками (image/video/gif). См. README.</p>
    </div>
  </div>
</div>
<?php endif; ?>
