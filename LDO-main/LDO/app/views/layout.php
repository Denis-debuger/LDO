<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>LDO</title>
  <link rel="stylesheet" href="<?= e(asset_url('css/ldo.css')) ?>">
</head>
<body>
  <header class="navbar">
    <nav class="nav-inner">
      <a href="<?= url('home') ?>" class="brand">
        <span class="brand-badge">LDO</span>
        <span>Let's Do It</span>
      </a>
      <div class="nav-links">
        <?php if (is_logged_in()): ?>
          <a href="<?= url('profile') ?>" class="nav-link <?= ($current ?? '') === 'profile' ? 'active' : '' ?>">Профиль</a>
          <a href="<?= url('programs') ?>" class="nav-link <?= ($current ?? '') === 'programs' ? 'active' : '' ?>">Программы</a>
          <a href="<?= url('diary') ?>" class="nav-link <?= ($current ?? '') === 'diary' ? 'active' : '' ?>">Дневник</a>
          <a href="<?= url('kbju') ?>" class="nav-link <?= ($current ?? '') === 'kbju' ? 'active' : '' ?>">КБЖУ</a>
          <a href="<?= url('exercises') ?>" class="nav-link <?= ($current ?? '') === 'exercises' ? 'active' : '' ?>">Упражнения</a>
          <a href="<?= url('progress') ?>" class="nav-link <?= ($current ?? '') === 'progress' ? 'active' : '' ?>">Прогресс</a>
        <?php endif; ?>
        <a href="<?= url('articles') ?>" class="nav-link <?= ($current ?? '') === 'articles' ? 'active' : '' ?>">Статьи</a>
        <?php if (auth_role() === 'admin'): ?>
          <a href="<?= url('admin') ?>" class="nav-link <?= ($current ?? '') === 'admin' ? 'active' : '' ?>">Админ</a>
        <?php endif; ?>
      </div>
      <div class="nav-right">
        <?php if (is_logged_in()): ?>
          <?php 
          $userId = auth_user_id();
          $u = user_by_id($userId);
          $av = avatar_url($userId);
          ?>
          <?php if ($av && $av !== ''): ?>
          <img src="<?= e(upload_url($av)) ?>" alt="Аватар" class="nav-avatar" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="pill"><?= e($u['email'] ?? '') ?></span>
          <a href="<?= url('logout') ?>" class="btn btn-ghost">Выход</a>
        <?php else: ?>
          <a href="<?= url('login') ?>" class="btn btn-primary">Вход</a>
          <a href="<?= url('register') ?>" class="btn btn-ghost">Регистрация</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <main>
    <?php
    $flash = flash_get();
    if ($flash):
      $cls = ($flash['type'] ?? '') === 'ok' ? 'ok' : 'err';
    ?>
    <div class="container">
      <div class="flash <?= $cls ?>"><?= e($flash['message'] ?? '') ?></div>
    </div>
    <?php endif; ?>

    <?= $content ?? '' ?>
  </main>

  <footer class="footer">
    <div class="container">LDO — Let's Do It. Персональная фитнес-платформа.</div>
  </footer>

  <script src="<?= e(asset_url('js/main.js')) ?>"></script>
  <?= $footerScripts ?? '' ?>
</body>
</html>
