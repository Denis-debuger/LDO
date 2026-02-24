<div class="container">
  <h1 style="margin:0 0 8px 0">Справочник упражнений</h1>
  <p class="muted" style="margin-bottom:24px">База упражнений с описанием техники</p>

  <?php if (empty($exercises)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <p class="muted">Упражнения пока не добавлены.</p>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Упражнение</th>
          <th>Категория</th>
          <th>Описание</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exercises as $e): ?>
        <tr>
          <td><?= e($e['name']) ?></td>
          <td><?= e($e['category_name'] ?? '—') ?></td>
          <td class="muted" style="max-width:320px"><?= e(mb_substr($e['description'] ?? '', 0, 120)) ?><?= mb_strlen($e['description'] ?? '') > 120 ? '…' : '' ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
