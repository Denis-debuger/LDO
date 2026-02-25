<div class="container">
  <h1 style="margin:0 0 8px 0">Личный кабинет</h1>
  <p class="muted" style="margin-bottom:24px">Параметры для расчёта КБЖУ и отслеживания прогресса</p>

  <?php if ($error ?? null): ?>
  <div class="flash err" style="margin-bottom:20px"><?= e($error) ?></div>
  <?php endif; ?>
  <?php if ($ok ?? null): ?>
  <div class="flash ok" style="margin-bottom:20px"><?= e($ok) ?></div>
  <?php endif; ?>

  <div class="card card-accent" style="margin-bottom:20px">
    <div class="card-body">
      <h2 class="card-title">Статистика</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="value"><?= e(number_format((float)$weightStats['current'], 1, '.', '')) ?> кг</div>
          <div class="label">Контроль веса (текущий вес)</div>
          <div class="progress-line"><span style="width:<?= e((string)$weightStats['percent']) ?>%"></span></div>
          <div class="muted" style="font-size:12px;margin-top:8px">Цель: <?= e(number_format((float)$weightStats['target'], 1, '.', '')) ?> кг</div>
        </div>
        <div class="stat-card">
          <div class="value"><?= e((string)$nutritionProgress['calories']['consumed']) ?> / <?= e((string)$nutritionProgress['calories']['target']) ?></div>
          <div class="label">Калории за сегодня</div>
          <div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['calories']['percent']) ?>%"></span></div>
        </div>
      </div>

      <div class="nutrition-progress" style="margin-top:16px">
        <div class="nutrition-row">
          <strong>Белки</strong>
          <div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['protein']['percent']) ?>%"></span></div>
          <span class="muted"><?= e((string)$nutritionProgress['protein']['consumed']) ?> / <?= e((string)$nutritionProgress['protein']['target']) ?> г</span>
        </div>
        <div class="nutrition-row">
          <strong>Жиры</strong>
          <div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['fat']['percent']) ?>%"></span></div>
          <span class="muted"><?= e((string)$nutritionProgress['fat']['consumed']) ?> / <?= e((string)$nutritionProgress['fat']['target']) ?> г</span>
        </div>
        <div class="nutrition-row">
          <strong>Углеводы</strong>
          <div class="progress-line"><span style="width:<?= e((string)$nutritionProgress['carbs']['percent']) ?>%"></span></div>
          <span class="muted"><?= e((string)$nutritionProgress['carbs']['consumed']) ?> / <?= e((string)$nutritionProgress['carbs']['target']) ?> г</span>
        </div>
      </div>

      <div class="chart-container">
        <div class="chart-header">
          <div class="chart-legend">
            <div class="chart-legend-item"><div class="chart-legend-color" style="background:#00D4FF"></div><span>Тренировки: <?= array_sum(array_column($chartData['months'], 'workouts')) ?></span></div>
            <div class="chart-legend-item"><div class="chart-legend-color" style="background:#FFB84D"></div><span>Упражнения: <?= array_sum(array_column($chartData['months'], 'exercises')) ?></span></div>
          </div>
          <div class="chart-year-nav">
            <button onclick="changeYear(<?= $chartData['year'] - 1 ?>)">←</button>
            <span><?= $chartData['year'] ?></span>
            <button onclick="changeYear(<?= $chartData['year'] + 1 ?>)">→</button>
          </div>
        </div>
        <svg class="chart-svg" id="activity-chart" viewBox="0 0 800 280">
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#00D4FF;stop-opacity:0.6" /><stop offset="100%" style="stop-color:#00D4FF;stop-opacity:0.1" /></linearGradient>
            <linearGradient id="grad2" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#FFB84D;stop-opacity:0.6" /><stop offset="100%" style="stop-color:#FFB84D;stop-opacity:0.1" /></linearGradient>
          </defs>
          <g stroke="rgba(255,255,255,.05)"><?php for ($i = 0; $i <= 5; $i++): ?><line x1="60" y1="<?= 40 + $i * 48 ?>" x2="740" y2="<?= 40 + $i * 48 ?>"></line><?php endfor; ?></g>
          <g fill="var(--muted)" font-size="11" text-anchor="end"><?php for ($i = 0; $i <= 5; $i++): ?><text x="55" y="<?= 45 + $i * 48 ?>"><?= round($chartData['max'] * (5 - $i) / 5) ?></text><?php endfor; ?></g>
          <g fill="var(--muted)" font-size="11" text-anchor="middle"><?php foreach ($chartData['months'] as $idx => $m): ?><text x="<?= 60 + ($idx + 0.5) * (680 / 12) ?>" y="270"><?= e($m['month']) ?></text><?php endforeach; ?></g>
          <path id="area1" fill="url(#grad1)" opacity="0.6"></path><path id="area2" fill="url(#grad2)" opacity="0.6"></path>
          <polyline id="line1" fill="none" stroke="#00D4FF" stroke-width="2" points=""></polyline><polyline id="line2" fill="none" stroke="#FFB84D" stroke-width="2" points=""></polyline>
        </svg>
      </div>
      <script>
        window.CHART_DATA = <?= json_encode($chartData) ?>;
        drawChart();
        function drawChart() {
          const data = window.CHART_DATA; const months = data.months; const max = Math.max(data.max || 1, 1);
          const width = 680, height = 200, startX = 60, startY = 40, stepX = width / 12;
          let path1 = `M ${startX} ${startY + height}`, path2 = `M ${startX} ${startY + height}`; let points1 = [], points2 = [];
          months.forEach((m, i) => { const x = startX + (i + 0.5) * stepX; const y1 = startY + height - (m.workouts / max) * height; const y2 = startY + height - (m.exercises / max) * height; path1 += ` L ${x} ${y1}`; path2 += ` L ${x} ${y2}`; points1.push(`${x},${y1}`); points2.push(`${x},${y2}`); });
          path1 += ` L ${startX + width} ${startY + height} Z`; path2 += ` L ${startX + width} ${startY + height} Z`;
          document.getElementById('area1').setAttribute('d', path1); document.getElementById('area2').setAttribute('d', path2);
          document.getElementById('line1').setAttribute('points', points1.join(' ')); document.getElementById('line2').setAttribute('points', points2.join(' '));
        }
        function changeYear(year) { window.location.href = '<?= url('profile') ?>?year=' + year; }
      </script>
    </div>
  </div>

  <div class="grid grid-2">
    <div class="card card-accent"><div class="card-body">
      <div id="profile-tabs" class="react-tabs-shell" role="tablist">
        <button type="button" class="react-tab-btn" data-tab-target="profile" aria-selected="true">Профиль</button>
        <button type="button" class="react-tab-btn" data-tab-target="avatar" aria-selected="false">Аватар</button>
      </div>

      <section data-tab-panel="profile">
        <h2 class="card-title">Мои данные</h2>
        <form method="post" action="<?= url('profile') ?>" class="form">
          <?= csrf_field() ?>
          <div class="row">
            <label>Рост (см)<input type="number" name="height_cm" min="100" max="250" step="1" value="<?= e($profile['height_cm'] ?? '') ?>" placeholder="170"></label>
            <label>Вес (кг)<input type="number" name="weight_kg" min="30" max="300" step="0.1" value="<?= e($profile['weight_kg'] ?? '') ?>" placeholder="70"></label>
          </div>
          <div class="row">
            <label>Возраст<input type="number" name="age" min="10" max="120" value="<?= e($profile['age'] ?? '') ?>" placeholder="25"></label>
            <label>Пол
              <select name="gender"><option value="">—</option><option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Мужской</option><option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Женский</option></select>
            </label>
          </div>
          <label>Уровень активности<select name="activity_level"><?php foreach ($activityLabels as $k => $v): ?><option value="<?= e($k) ?>" <?= ($profile['activity_level'] ?? 'moderate') === $k ? 'selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?></select></label>
          <label>Цель<select name="goal"><?php foreach ($goalLabels as $k => $v): ?><option value="<?= e($k) ?>" <?= ($profile['goal'] ?? 'maintain') === $k ? 'selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?></select></label>
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
      </section>

      <section data-tab-panel="avatar" class="is-hidden">
        <h2 class="card-title">Аватар</h2>
        <?php $avatarUrl = ($profile['avatar_url'] ?? null) ? upload_url($profile['avatar_url']) : null; ?>
        <?php if ($avatarUrl): ?>
        <div style="margin-bottom:16px">
          <img src="<?= e($avatarUrl) ?>" alt="Аватар" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--accent)" onerror="this.style.display='none'">
          <form method="post" action="<?= url('profile') ?>" style="display:inline;margin-left:12px"><?= csrf_field() ?><input type="hidden" name="remove_avatar" value="1"><button type="submit" class="btn btn-ghost" style="font-size:13px">Удалить</button></form>
        </div>
        <?php endif; ?>
        <form method="post" action="<?= url('profile') ?>" enctype="multipart/form-data" class="form"><?= csrf_field() ?><label>Загрузить аватар (JPG, PNG, GIF, WebP, макс. 2 МБ)<input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp"></label><button type="submit" class="btn btn-primary">Загрузить</button></form>
      </section>
    </div></div>

    <div class="card card-accent"><div class="card-body">
      <?php
      $hCm = isset($profile['height_cm']) ? (float)$profile['height_cm'] : 0.0;
      $wKg = isset($profile['weight_kg']) ? (float)$profile['weight_kg'] : 0.0;
      $bmi = null; $bmiLabel = null;
      if ($hCm > 0 && $wKg > 0) { $hM = $hCm / 100; $bmi = $wKg / ($hM * $hM); if ($bmi < 18.5) $bmiLabel = 'Дефицит массы'; elseif ($bmi < 25) $bmiLabel = 'Норма'; elseif ($bmi < 30) $bmiLabel = 'Избыточная масса'; else $bmiLabel = 'Ожирение'; }
      ?>
      <h2 class="card-title">Калькулятор ИМТ</h2>
      <div class="kpi" style="margin-bottom:12px"><div class="item"><div class="num" id="bmi-value"><?= $bmi ? e(number_format($bmi, 1, '.', '')) : '—' ?></div><div class="muted" id="bmi-label"><?= e($bmiLabel ?? 'Заполните рост и вес') ?></div></div></div>
      <p class="muted" style="font-size:12px;margin:0 0 16px 0">ИМТ = вес (кг) / рост² (м)</p>
      <h2 class="card-title">Динамика веса</h2>
      <?php if (empty($weightHistory)): ?>
      <p class="muted">Пока нет записей. Сохраните вес в форме слева.</p>
      <?php else: ?>
      <div class="kpi" style="margin-bottom:16px"><div class="item"><div class="num"><?= e($weightHistory[0]['weight_kg'] ?? '—') ?></div><div class="muted">кг сейчас</div></div></div>
      <div id="weight-chart" style="height:200px;background:rgba(0,0,0,.2);border-radius:12px;display:flex;align-items:flex-end;padding:12px;gap:4px">
      <?php $points = array_reverse(array_slice($weightHistory, 0, 14)); $max = max(array_column($points, 'weight_kg')) ?: 1; foreach ($points as $p): $h = ($p['weight_kg'] / $max) * 100; ?>
        <div title="<?= e($p['logged_at']) ?>: <?= e($p['weight_kg']) ?> кг" style="flex:1;background:var(--accent);border-radius:4px;min-height:4px;height:<?= $h ?>%"></div>
      <?php endforeach; ?>
      </div><p class="muted" style="margin-top:12px;font-size:12px">Последние 14 записей</p>
      <?php endif; ?>
    </div></div>
  </div>

<script>
(function(){
  var h = document.querySelector('input[name="height_cm"]'); var w = document.querySelector('input[name="weight_kg"]');
  var bmiValue = document.getElementById('bmi-value'); var bmiLabel = document.getElementById('bmi-label'); if (!h || !w || !bmiValue || !bmiLabel) return;
  function upd() { var hc = parseFloat(h.value || '0'); var wk = parseFloat((w.value || '0').replace(',', '.')); if (hc > 0 && wk > 0) { var hm = hc / 100; var bmi = wk / (hm * hm); bmiValue.textContent = bmi.toFixed(1); if (bmi < 18.5) bmiLabel.textContent = 'Дефицит массы'; else if (bmi < 25) bmiLabel.textContent = 'Норма'; else if (bmi < 30) bmiLabel.textContent = 'Избыточная масса'; else bmiLabel.textContent = 'Ожирение'; } }
  h.addEventListener('input', upd); w.addEventListener('input', upd);
})();
</script>
</div>
