<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('admin') ?>" class="btn btn-ghost">← Админ-панель</a>
    <h1 style="margin:0">Программы тренировок</h1>
    <?php if (!$editing): ?>
    <button onclick="document.getElementById('add-form').classList.toggle('hide')" class="btn btn-primary">+ Добавить</button>
    <?php endif; ?>
  </div>

  <?php if ($error ?? null): ?>
  <div class="flash err"><?= e($error) ?></div>
  <?php endif; ?>
  <?php if ($ok ?? null): ?>
  <div class="flash ok"><?= e($ok) ?></div>
  <?php endif; ?>

  <?php if (!$editing): ?>
  <div id="add-form" class="card card-accent hide" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Новая программа</h2>
      <form method="post" action="<?= url('admin-programs', ['action' => 'add']) ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Название
          <input type="text" name="name" required>
        </label>
        <label>
          Уровень
          <select name="level">
            <option value="all">Все</option>
            <option value="beginner">Начинающий</option>
            <option value="intermediate">Средний</option>
            <option value="advanced">Продвинутый</option>
          </select>
        </label>
        <label>
          Описание
          <textarea name="description" rows="3"></textarea>
        </label>
        <button type="submit" class="btn btn-primary">Добавить</button>
      </form>
    </div>
  </div>
  <?php else: ?>
  <div class="card card-accent" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Редактировать программу</h2>
      <form method="post" action="<?= url('admin-programs', ['action' => 'edit', 'id' => $editing['id']]) ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Название
          <input type="text" name="name" value="<?= e($editing['name']) ?>" required>
        </label>
        <label>
          Уровень
          <select name="level">
            <option value="all" <?= $editing['level'] === 'all' ? 'selected' : '' ?>>Все</option>
            <option value="beginner" <?= $editing['level'] === 'beginner' ? 'selected' : '' ?>>Начинающий</option>
            <option value="intermediate" <?= $editing['level'] === 'intermediate' ? 'selected' : '' ?>>Средний</option>
            <option value="advanced" <?= $editing['level'] === 'advanced' ? 'selected' : '' ?>>Продвинутый</option>
          </select>
        </label>
        <label>
          Описание
          <textarea name="description" rows="3"><?= e($editing['description'] ?? '') ?></textarea>
        </label>
        <div style="display:flex;gap:10px">
          <button type="submit" class="btn btn-primary">Сохранить</button>
          <a href="<?= url('admin-programs') ?>" class="btn btn-ghost">Отмена</a>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Уровень</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($programs as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= e($p['name']) ?></td>
          <td><span class="pill"><?= e($p['level']) ?></span></td>
          <td>
            <a href="<?= url('admin-programs', ['action' => 'edit', 'id' => $p['id']]) ?>" class="btn btn-ghost" style="padding:4px 8px;font-size:12px">Редактировать</a>
            <form method="post" action="<?= url('admin-programs', ['action' => 'delete', 'id' => $p['id']]) ?>" style="display:inline" onsubmit="return confirm('Удалить программу?')">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-danger" style="padding:4px 8px;font-size:12px">Удалить</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<style>.hide{display:none!important}</style>
