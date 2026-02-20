<div class="container">
  <h1 style="margin:0 0 8px 0">Редактировать тренировку</h1>
  <p class="muted" style="margin-bottom:24px">
    <a href="<?= url('diary-view', ['id' => $log['id']]) ?>" style="color:var(--accent)">← Назад к тренировке</a>
  </p>

  <div class="card card-accent">
    <div class="card-body">
      <form method="post" action="<?= url('diary-edit', ['id' => $log['id']]) ?>" class="form">
        <?= csrf_field() ?>
        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
        <label>
          Дата
          <input type="date" name="logged_at" value="<?= e($log['logged_at']) ?>" required>
        </label>
        <div class="row">
          <label>
            Длительность (минуты)
            <input type="number" name="duration_min" min="0" value="<?= e($log['duration_min'] ?? '') ?>" placeholder="60">
          </label>
          <label>
            Вес тела (кг)
            <input type="number" name="body_weight" min="0" step="0.1" value="<?= e($log['body_weight'] ?? '') ?>" placeholder="70">
          </label>
        </div>
        <label>
          Самочувствие
          <select name="feeling">
            <option value="">—</option>
            <option value="excellent" <?= ($log['feeling'] ?? '') === 'excellent' ? 'selected' : '' ?>>Отлично</option>
            <option value="good" <?= ($log['feeling'] ?? '') === 'good' ? 'selected' : '' ?>>Хорошо</option>
            <option value="normal" <?= ($log['feeling'] ?? '') === 'normal' ? 'selected' : '' ?>>Нормально</option>
            <option value="tired" <?= ($log['feeling'] ?? '') === 'tired' ? 'selected' : '' ?>>Устал</option>
            <option value="exhausted" <?= ($log['feeling'] ?? '') === 'exhausted' ? 'selected' : '' ?>>Измотан</option>
          </select>
        </label>
        <label>
          Заметки
          <textarea name="notes" rows="3" placeholder="Необязательно"><?= e($log['notes'] ?? '') ?></textarea>
        </label>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </form>
    </div>
  </div>
</div>
