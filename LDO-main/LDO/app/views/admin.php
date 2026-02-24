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

  <div class="card section-spacer">
    <div class="card-body">
      <h2 class="card-title">Быстрые действия</h2>
      <div class="action-row">
        <a href="<?= url('admin-article-add') ?>" class="btn btn-primary">+ Новая статья</a>
        <a href="<?= url('admin-programs', ['action' => 'add']) ?>" class="btn btn-ghost">+ Новая программа</a>
        <a href="<?= url('admin-exercises', ['action' => 'add']) ?>" class="btn btn-ghost">+ Новое упражнение</a>
        <a href="<?= url('admin-users') ?>" class="btn btn-ghost">Список пользователей</a>
      </div>
    </div>
  </div>

  <div class="grid grid-2 section-spacer">
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

  <div class="card section-spacer">
    <div class="card-body">
      <h2 class="card-title">Что можно добавить дальше</h2>
      <ul class="muted list-ideas">
        <li>Модерация комментариев и пользовательского контента.</li>
        <li>Логи действий админов (кто и что менял).</li>
        <li>Панель безопасности: частота входов, блокировки, сбросы пароля.</li>
        <li>Управление справочником продуктов и категориями контента.</li>
        <li>Импорт/экспорт данных и резервное копирование.</li>
      </ul>
    </div>
  </div>
</div>
