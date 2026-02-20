<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('admin') ?>" class="btn btn-ghost">← Админ-панель</a>
    <h1 style="margin:0">Статьи</h1>
    <a href="<?= url('admin-article-add') ?>" class="btn btn-primary">+ Добавить статью</a>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Заголовок</th>
          <th>Категория</th>
          <th>Статус</th>
          <th>Дата</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($articles as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= e($a['title']) ?></td>
          <td><?= e($a['category_name'] ?? '—') ?></td>
          <td><?= $a['published'] ? '<span style="color:var(--ok)">Опубликовано</span>' : '<span class="muted">Черновик</span>' ?></td>
          <td class="muted"><?= e($a['created_at']) ?></td>
          <td>
            <a href="<?= url('article', ['slug' => $a['slug']]) ?>" class="btn btn-ghost" style="padding:4px 8px;font-size:12px" target="_blank">Просмотр</a>
            <a href="<?= url('admin-article-edit', ['id' => $a['id']]) ?>" class="btn btn-ghost" style="padding:4px 8px;font-size:12px">Редактировать</a>
            <form method="post" action="<?= url('admin-articles', ['action' => 'delete', 'id' => $a['id']]) ?>" style="display:inline" onsubmit="return confirm('Удалить статью?')">
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
