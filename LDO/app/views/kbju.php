<div class="container">
  <h1 style="margin:0 0 8px 0">Калькулятор КБЖУ</h1>
  <p class="muted" style="margin-bottom:24px">Расчёт суточной нормы калорий и БЖУ на основе данных профиля</p>

  <?php if (!$profile || !$profile['weight_kg'] || !$profile['height_cm'] || !$profile['age']): ?>
  <div class="card card-accent">
    <div class="card-body">
      <p>Заполните <a href="<?= url('profile') ?>" style="color:var(--accent)">профиль</a>: рост, вес, возраст и пол — для расчёта.</p>
    </div>
  </div>
  <?php else: ?>
  <div class="grid grid-2">
    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title">Ваши параметры</h2>
        <p class="muted">Данные берутся из профиля. Измените их в <a href="<?= url('profile') ?>" style="color:var(--accent)">личном кабинете</a>.</p>
        <div class="kpi">
          <div class="item"><span class="num"><?= e($profile['weight_kg']) ?></span> кг</div>
          <div class="item"><span class="num"><?= e($profile['height_cm']) ?></span> см</div>
          <div class="item"><span class="num"><?= e($profile['age']) ?></span> лет</div>
          <div class="item"><?= e($activityLabels[$profile['activity_level']] ?? '—') ?></div>
          <div class="item"><?= e($goalLabels[$profile['goal']] ?? '—') ?></div>
        </div>
      </div>
    </div>

    <?php if ($result): ?>
    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title">Суточная норма</h2>
        <div class="kpi">
          <div class="item">
            <div class="num"><?= e($result['calories']) ?></div>
            <div class="muted">ккал</div>
          </div>
          <div class="item">
            <div class="num"><?= e($result['protein_g']) ?></div>
            <div class="muted">г белка</div>
          </div>
          <div class="item">
            <div class="num"><?= e($result['fat_g']) ?></div>
            <div class="muted">г жиров</div>
          </div>
          <div class="item">
            <div class="num"><?= e($result['carbs_g']) ?></div>
            <div class="muted">г углеводов</div>
          </div>
        </div>
        <p class="muted" style="margin-top:12px;font-size:13px">
          Б: <?= e($result['protein_kcal']) ?> ккал · Ж: <?= e($result['fat_kcal']) ?> ккал · У: <?= e($result['carbs_kcal']) ?> ккал
        </p>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
