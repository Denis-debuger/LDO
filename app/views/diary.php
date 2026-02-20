<div class="container">
  <h1 class="page-head">Дневник тренировок</h1>
  <p class="muted page-subtitle">Фиксируйте тренировки и упражнения</p>

  <div class="toggle-row">
    <button type="button" class="btn btn-primary" onclick="document.getElementById('diary-add-form').classList.toggle('is-hidden')">+ Добавить тренировку</button>
  </div>

  <div id="diary-add-form" class="card card-accent is-hidden" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Новая тренировка</h2>
      <form method="post" action="<?= url('diary-add') ?>" class="form">
        <?= csrf_field() ?>
        <div class="row">
          <label>
            Дата
            <input type="date" name="logged_at" value="<?= e(today()) ?>" required>
          </label>
          <label>
            Заметки
            <input type="text" name="notes" placeholder="Необязательно">
          </label>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
      </form>
    </div>
  </div>

  <?php if (empty($logs)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <p class="muted">Пока нет записей. Нажмите «Добавить тренировку».</p>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="table-wrap">
      <table>
      <thead>
        <tr>
          <th>Дата</th>
          <th>Упражнений</th>
          <th>Заметки</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $l): ?>
        <tr>
          <td><a href="<?= url('diary-view', ['id' => $l['id']]) ?>" style="color:var(--accent)"><?= e($l['logged_at']) ?></a></td>
          <td><?= e($l['ex_count']) ?></td>
          <td class="muted"><?= e(mb_substr($l['notes'] ?? '', 0, 50)) ?><?= mb_strlen($l['notes'] ?? '') > 50 ? '…' : '' ?></td>
          <td>
            <a href="<?= url('diary-view', ['id' => $l['id']]) ?>" class="btn btn-ghost" style="padding:6px 10px;font-size:13px">Открыть</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>

