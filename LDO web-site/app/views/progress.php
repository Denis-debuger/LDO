<div class="container">
  <h1 style="margin:0 0 8px 0">Прогресс</h1>
  <p class="muted" style="margin-bottom:24px">Динамика веса и показателей</p>

  <?php if (!empty($weightLogs)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <h2 class="card-title">Вес</h2>
      <div id="progress-weight-chart" style="height:220px;background:rgba(0,0,0,.2);border-radius:12px;padding:16px"></div>
      <script>
        window.LDO_WEIGHT_DATA = <?= json_encode(array_reverse($weightLogs)) ?>;
      </script>
    </div>
  </div>
  <?php else: ?>
  <div class="card card-accent">
    <div class="card-body">
      <p class="muted">Заполните вес в <a href="<?= url('profile') ?>" style="color:var(--accent)">профиле</a> для отображения графика.</p>
    </div>
  </div>
  <?php endif; ?>
</div>
