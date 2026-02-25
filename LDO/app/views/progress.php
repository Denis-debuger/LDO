<div class="container">
  <h1 style="margin:0 0 8px 0">Прогресс</h1>
  <p class="muted" style="margin-bottom:24px">Динамика веса и выполнение дневной нормы КБЖУ</p>

  <div class="card card-accent" style="margin-bottom:16px">
    <div class="card-body">
      <h2 class="card-title">КБЖУ за сегодня</h2>
      <div class="nutrition-progress">
        <div class="nutrition-row"><strong>Калории</strong><div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['calories']['percent']) ?>%"></span></div><span class="muted"><?= e((string)$nutritionProgress['calories']['consumed']) ?> / <?= e((string)$nutritionProgress['calories']['target']) ?> ккал</span></div>
        <div class="nutrition-row"><strong>Белки</strong><div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['protein']['percent']) ?>%"></span></div><span class="muted"><?= e((string)$nutritionProgress['protein']['consumed']) ?> / <?= e((string)$nutritionProgress['protein']['target']) ?> г</span></div>
        <div class="nutrition-row"><strong>Жиры</strong><div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['fat']['percent']) ?>%"></span></div><span class="muted"><?= e((string)$nutritionProgress['fat']['consumed']) ?> / <?= e((string)$nutritionProgress['fat']['target']) ?> г</span></div>
        <div class="nutrition-row"><strong>Углеводы</strong><div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['carbs']['percent']) ?>%"></span></div><span class="muted"><?= e((string)$nutritionProgress['carbs']['consumed']) ?> / <?= e((string)$nutritionProgress['carbs']['target']) ?> г</span></div>
      </div>
    </div>
  </div>

  <?php if (!empty($weightLogs)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <h2 class="card-title">Вес</h2>
      <div id="progress-weight-chart" style="height:220px;background:rgba(0,0,0,.2);border-radius:12px;padding:16px"></div>
      <script>window.LDO_WEIGHT_DATA = <?= json_encode(array_reverse($weightLogs)) ?>;</script>
    </div>
  </div>
  <?php else: ?>
  <div class="card card-accent"><div class="card-body"><p class="muted">Заполните вес в <a href="<?= url('profile') ?>" style="color:var(--accent)">профиле</a> для отображения графика.</p></div></div>
  <?php endif; ?>
</div>
