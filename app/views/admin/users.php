<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('admin') ?>" class="btn btn-ghost">← Админ-панель</a>
    <h1 style="margin:0">Пользователи</h1>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Роль</th>
          <th>Статус</th>
          <th>Регистрация</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td>
            <?php if ($u['avatar_url']): ?>
            <img src="<?= e(upload_url($u['avatar_url'])) ?>" alt="" style="width:24px;height:24px;border-radius:50%;vertical-align:middle;margin-right:8px" onerror="this.style.display='none'">
            <?php endif; ?>
            <?= e($u['email']) ?>
          </td>
          <td><span class="pill"><?= e($u['role']) ?></span></td>
          <td><?= $u['is_blocked'] ? '<span style="color:var(--danger)">Заблокирован</span>' : '<span style="color:var(--ok)">Активен</span>' ?></td>
          <td class="muted"><?= e($u['created_at']) ?></td>
          <td>
            <a href="<?= url('admin-users', ['action' => 'block', 'id' => $u['id']]) ?>" class="btn btn-ghost" style="padding:4px 8px;font-size:12px"><?= $u['is_blocked'] ? 'Разблокировать' : 'Заблокировать' ?></a>
            <?php if ($u['role'] !== 'admin'): ?>
            <form method="post" action="<?= url('admin-users', ['action' => 'delete', 'id' => $u['id']]) ?>" style="display:inline" onsubmit="return confirm('Удалить пользователя?')">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-danger" style="padding:4px 8px;font-size:12px">Удалить</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
