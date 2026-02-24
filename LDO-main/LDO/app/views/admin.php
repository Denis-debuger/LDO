<div class="container">
  <h1 class="page-head">Админ-панель</h1>
  <p class="muted page-subtitle">Управление системой</p>

  <div class="kpi">
    <div class="item"><span class="num"><?= e($stats['users']) ?></span> пользователей</div>
    <div class="item"><span class="num"><?= e($stats['programs']) ?></span> программ</div>
    <div class="item"><span class="num"><?= e($stats['exercises']) ?></span> упражнений</div>
    <div class="item"><span class="num"><?= e($stats['articles']) ?></span> статей</div>
    <div class="item"><span class="num"><?= e($stats['published_articles']) ?></span> опубликовано</div>
  </div>

  <div class="grid grid-2 section-spacer">
    <a href="<?= url('admin-users') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Пользователи</h2><p class="muted">Блокировка и удаление аккаунтов</p></div></a>
    <a href="<?= url('admin-programs') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Программы</h2><p class="muted">Тренировочные программы</p></div></a>
    <a href="<?= url('admin-exercises') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Упражнения</h2><p class="muted">Справочник упражнений</p></div></a>
    <a href="<?= url('admin-articles') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Статьи</h2><p class="muted">Публикация контента</p></div></a>
    <a href="<?= url('admin-moderation') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Модерация</h2><p class="muted">Комментарии и пользовательский контент</p></div></a>
    <a href="<?= url('admin-security') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Безопасность</h2><p class="muted">Частота входов, блокировки, сбросы</p></div></a>
    <a href="<?= url('admin-food') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Продукты и категории</h2><p class="muted">Управление справочником и категориями контента</p></div></a>
    <a href="<?= url('admin-maintenance') ?>" class="card card-accent" style="display:block;text-decoration:none"><div class="card-body"><h2 class="card-title">Импорт/Экспорт/Бэкап</h2><p class="muted">Резервное копирование и обмен данными</p></div></a>
  </div>
</div>
