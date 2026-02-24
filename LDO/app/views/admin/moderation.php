<div class="container">
  <h1 class="page-head">Модерация комментариев и UGC</h1>
  <p class="muted page-subtitle">Проверка пользовательского контента перед публикацией.</p>
  <?php if (!($moderationReady ?? true)): ?>
  <div class="flash err" style="margin-bottom:12px">Таблица <code>moderation_items</code> не найдена. Выполните миграцию <code>db/migrations/004_admin_security_moderation.sql</code>.</div>
  <?php endif; ?>
  <div class="table-wrap card"><div class="card-body">
    <table>
      <thead><tr><th>ID</th><th>Тип</th><th>Пользователь</th><th>Контент</th><th>Статус</th><th>Действие</th></tr></thead>
      <tbody>
      <?php foreach ($items as $i): ?>
        <tr>
          <td><?= (int)$i['id'] ?></td>
          <td><?= e($i['type']) ?></td>
          <td><?= e($i['email'] ?? '—') ?></td>
          <td style="max-width:360px"><?= e(mb_substr((string)$i['content'], 0, 180)) ?></td>
          <td><?= e($i['status']) ?></td>
          <td>
            <form method="post" style="display:flex;gap:6px;flex-wrap:wrap">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
              <button class="btn btn-ghost" name="action" value="approved">Одобрить</button>
              <button class="btn btn-ghost" name="action" value="rejected">Отклонить</button>
              <button class="btn btn-danger" name="action" value="deleted">Удалить</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
</div>
