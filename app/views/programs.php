<div class="container">
  <h1 style="margin:0 0 8px 0">Тренировочные программы</h1>
  <p class="muted" style="margin-bottom:24px">Готовые программы для разных уровней подготовки</p>

  <?php if (empty($programs)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <p class="muted">Программы пока не добавлены. Администратор может добавить их в админ-панели.</p>
    </div>
  </div>
  <?php else: ?>
  <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(280px,1fr))">
    <?php foreach ($programs as $p): ?>
    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title"><?= e($p['name']) ?></h2>
        <p class="muted" style="font-size:13px">
          Уровень: <?= e($p['level']) ?>
        </p>
        <?php if (!empty($p['description'])): ?>
        <p style="font-size:14px"><?= e($p['description']) ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
