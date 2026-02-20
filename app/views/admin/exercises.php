<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('admin') ?>" class="btn btn-ghost">← Админ-панель</a>
    <h1 style="margin:0">Упражнения</h1>
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
      <h2 class="card-title">Новое упражнение</h2>
      <form method="post" action="<?= url('admin-exercises', ['action' => 'add']) ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Название *
          <input type="text" name="name" required>
        </label>
        <label>
          Категория
          <select name="category_id">
            <option value="">—</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>
          Описание
          <textarea name="description" rows="3"></textarea>
        </label>
        <label>
          Техника выполнения
          <textarea name="technique" rows="4"></textarea>
        </label>
        <div class="row">
          <label>
            URL изображения
            <input type="url" name="image_url" placeholder="https://...">
          </label>
          <label>
            URL видео
            <input type="url" name="video_url" placeholder="https://...">
          </label>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
      </form>
    </div>
  </div>
  <?php else: ?>
  <div class="card card-accent" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Редактировать упражнение</h2>
      <form method="post" action="<?= url('admin-exercises', ['action' => 'edit', 'id' => $editing['id']]) ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Название *
          <input type="text" name="name" value="<?= e($editing['name']) ?>" required>
        </label>
        <label>
          Категория
          <select name="category_id">
            <option value="">—</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($editing['category_id'] ?? null) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>
          Описание
          <textarea name="description" rows="3"><?= e($editing['description'] ?? '') ?></textarea>
        </label>
        <label>
          Техника выполнения
          <textarea name="technique" rows="4"><?= e($editing['technique'] ?? '') ?></textarea>
        </label>
        <div class="row">
          <label>
            URL изображения
            <input type="url" name="image_url" value="<?= e($editing['image_url'] ?? '') ?>" placeholder="https://...">
          </label>
          <label>
            URL видео
            <input type="url" name="video_url" value="<?= e($editing['video_url'] ?? '') ?>" placeholder="https://...">
          </label>
        </div>
        <div style="display:flex;gap:10px">
          <button type="submit" class="btn btn-primary">Сохранить</button>
          <a href="<?= url('admin-exercises') ?>" class="btn btn-ghost">Отмена</a>
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
          <th>Категория</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exercises as $e): ?>
        <tr>
          <td><?= $e['id'] ?></td>
          <td><?= e($e['name']) ?></td>
          <td><?= e($e['category_name'] ?? '—') ?></td>
          <td>
            <a href="<?= url('admin-exercises', ['action' => 'edit', 'id' => $e['id']]) ?>" class="btn btn-ghost" style="padding:4px 8px;font-size:12px">Редактировать</a>
            <form method="post" action="<?= url('admin-exercises', ['action' => 'delete', 'id' => $e['id']]) ?>" style="display:inline" onsubmit="return confirm('Удалить упражнение?')">
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
