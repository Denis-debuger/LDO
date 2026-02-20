<div class="container">
  <h1 style="margin:0 0 8px 0">Админ-панель</h1>
  <p class="muted" style="margin-bottom:24px">Управление системой</p>

  <div class="kpi">
    <div class="item"><span class="num"><?= e($stats['users']) ?></span> пользователей</div>
    <div class="item"><span class="num"><?= e($stats['programs']) ?></span> программ</div>
    <div class="item"><span class="num"><?= e($stats['exercises']) ?></span> упражнений</div>
    <div class="item"><span class="num"><?= e($stats['articles']) ?></span> статей</div>
    <div class="item"><span class="num"><?= e($stats['published_articles']) ?></span> опубликовано</div>
  </div>

  <div class="grid grid-2" style="margin-top:32px">
    <a href="<?= url('admin-users') ?>" class="card card-accent" style="display:block;text-decoration:none">
      <div class="card-body">
        <h2 class="card-title">Пользователи</h2>
        <p class="muted">Управление пользователями, блокировка, удаление</p>
      </div>
    </a>
    <a href="<?= url('admin-programs') ?>" class="card card-accent" style="display:block;text-decoration:none">
      <div class="card-body">
        <h2 class="card-title">Программы</h2>
        <p class="muted">Добавление и редактирование тренировочных программ</p>
      </div>
    </a>
    <a href="<?= url('admin-exercises') ?>" class="card card-accent" style="display:block;text-decoration:none">
      <div class="card-body">
        <h2 class="card-title">Упражнения</h2>
        <p class="muted">Справочник упражнений с описаниями и техникой</p>
      </div>
    </a>
    <a href="<?= url('admin-articles') ?>" class="card card-accent" style="display:block;text-decoration:none">
      <div class="card-body">
        <h2 class="card-title">Статьи</h2>
        <p class="muted">Создание и редактирование статей с медиа</p>
      </div>
    </a>
  </div>
</div>
