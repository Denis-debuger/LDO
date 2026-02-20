<div class="container">
  <h1 style="margin:0 0 8px 0">Личный кабинет</h1>
  <p class="muted" style="margin-bottom:24px">Параметры для расчёта КБЖУ и отслеживания прогресса</p>

  <?php if ($error ?? null): ?>
  <div class="flash err" style="margin-bottom:20px"><?= e($error) ?></div>
  <?php endif; ?>
  <?php if ($ok ?? null): ?>
  <div class="flash ok" style="margin-bottom:20px"><?= e($ok) ?></div>
  <?php endif; ?>

  <!-- Статистика -->
  <div class="card card-accent" style="margin-bottom:32px">
    <div class="card-body">
      <h2 class="card-title" style="margin-bottom:24px">Статистика</h2>
      <div class="stats-grid">
        <?php
        $statColors = [
          'activity' => '#00D4FF',
          'calories' => '#FF6B9D',
          'distance' => '#FFB84D',
          'steps' => '#B794F6',
        ];
        foreach (['activity', 'calories', 'distance', 'steps'] as $key):
          $stat = $stats[$key] ?? ['value' => 0, 'goal' => 100, 'unit' => '', 'label' => ''];
          $percent = min(100, ($stat['value'] / max(1, $stat['goal'])) * 100);
          $color = $statColors[$key] ?? '#FFD100';
          $circumference = 2 * M_PI * 54; // радиус 54px
          $offset = $circumference - ($percent / 100) * $circumference;
        ?>
        <div>
          <div class="stat-circle">
            <svg viewBox="0 0 120 120">
              <circle class="circle-bg" cx="60" cy="60" r="54"></circle>
              <circle class="circle-progress" cx="60" cy="60" r="54" 
                      stroke="<?= e($color) ?>" 
                      stroke-dasharray="<?= $circumference ?>" 
                      stroke-dashoffset="<?= $offset ?>"></circle>
            </svg>
            <div class="stat-value">
              <span class="num"><?= e($stat['value']) ?></span>
              <span class="unit"><?= e($stat['unit']) ?></span>
            </div>
          </div>
          <div class="stat-label"><?= e($stat['label']) ?></div>
          <div class="stat-goal">Цель: <?= e($stat['goal']) ?><?= e($stat['unit']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- График активности -->
      <div class="chart-container">
        <div class="chart-header">
          <div class="chart-legend">
            <div class="chart-legend-item">
              <div class="chart-legend-color" style="background:#00D4FF"></div>
              <span>Тренировки: <?= array_sum(array_column($chartData['months'], 'workouts')) ?></span>
            </div>
            <div class="chart-legend-item">
              <div class="chart-legend-color" style="background:#FFB84D"></div>
              <span>Упражнения: <?= array_sum(array_column($chartData['months'], 'exercises')) ?></span>
            </div>
          </div>
          <div class="chart-year-nav">
            <button onclick="changeYear(<?= $chartData['year'] - 1 ?>)">←</button>
            <span><?= $chartData['year'] ?></span>
            <button onclick="changeYear(<?= $chartData['year'] + 1 ?>)">→</button>
          </div>
        </div>
        <svg class="chart-svg" id="activity-chart" viewBox="0 0 800 280">
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" style="stop-color:#00D4FF;stop-opacity:0.6" />
              <stop offset="100%" style="stop-color:#00D4FF;stop-opacity:0.1" />
            </linearGradient>
            <linearGradient id="grad2" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" style="stop-color:#FFB84D;stop-opacity:0.6" />
              <stop offset="100%" style="stop-color:#FFB84D;stop-opacity:0.1" />
            </linearGradient>
          </defs>
          <!-- Сетка -->
          <g stroke="rgba(255,255,255,.05)">
            <?php for ($i = 0; $i <= 5; $i++): ?>
            <line x1="60" y1="<?= 40 + $i * 48 ?>" x2="740" y2="<?= 40 + $i * 48 ?>"></line>
            <?php endfor; ?>
          </g>
          <!-- Подписи Y -->
          <g fill="var(--muted)" font-size="11" text-anchor="end">
            <?php for ($i = 0; $i <= 5; $i++): ?>
            <text x="55" y="<?= 45 + $i * 48 ?>"><?= round($chartData['max'] * (5 - $i) / 5) ?></text>
            <?php endfor; ?>
          </g>
          <!-- Подписи X -->
          <g fill="var(--muted)" font-size="11" text-anchor="middle">
            <?php foreach ($chartData['months'] as $idx => $m): ?>
            <text x="<?= 60 + ($idx + 0.5) * (680 / 12) ?>" y="270"><?= e($m['month']) ?></text>
            <?php endforeach; ?>
          </g>
          <!-- Области графика -->
          <path id="area1" fill="url(#grad1)" opacity="0.6"></path>
          <path id="area2" fill="url(#grad2)" opacity="0.6"></path>
          <!-- Линии графика -->
          <polyline id="line1" fill="none" stroke="#00D4FF" stroke-width="2" points=""></polyline>
          <polyline id="line2" fill="none" stroke="#FFB84D" stroke-width="2" points=""></polyline>
        </svg>
      </div>
      <script>
        window.CHART_DATA = <?= json_encode($chartData) ?>;
        drawChart();
        function drawChart() {
          const data = window.CHART_DATA;
          const months = data.months;
          const max = Math.max(data.max || 1, 1);
          const width = 680;
          const height = 200;
          const startX = 60;
          const startY = 40;
          const stepX = width / 12;
          
          let path1 = `M ${startX} ${startY + height}`;
          let path2 = `M ${startX} ${startY + height}`;
          let points1 = [];
          let points2 = [];
          
          months.forEach((m, i) => {
            const x = startX + (i + 0.5) * stepX;
            const y1 = startY + height - (m.workouts / max) * height;
            const y2 = startY + height - (m.exercises / max) * height;
            
            path1 += ` L ${x} ${y1}`;
            path2 += ` L ${x} ${y2}`;
            points1.push(`${x},${y1}`);
            points2.push(`${x},${y2}`);
          });
          
          path1 += ` L ${startX + width} ${startY + height} Z`;
          path2 += ` L ${startX + width} ${startY + height} Z`;
          
          document.getElementById('area1').setAttribute('d', path1);
          document.getElementById('area2').setAttribute('d', path2);
          document.getElementById('line1').setAttribute('points', points1.join(' '));
          document.getElementById('line2').setAttribute('points', points2.join(' '));
        }
        function changeYear(year) {
          window.location.href = '<?= url('profile') ?>?year=' + year;
        }
      </script>
    </div>
  </div>

  <div class="grid grid-2">
    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title">Аватар</h2>
        <?php $avatarUrl = ($profile['avatar_url'] ?? null) ? upload_url($profile['avatar_url']) : null; ?>
        <?php if ($avatarUrl): ?>
        <div style="margin-bottom:16px">
          <img src="<?= e($avatarUrl) ?>" alt="Аватар" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--accent)" onerror="this.style.display='none'">
          <form method="post" action="<?= url('profile') ?>" style="display:inline;margin-left:12px">
            <?= csrf_field() ?>
            <input type="hidden" name="remove_avatar" value="1">
            <button type="submit" class="btn btn-ghost" style="font-size:13px">Удалить</button>
          </form>
        </div>
        <?php endif; ?>
        <form method="post" action="<?= url('profile') ?>" enctype="multipart/form-data" class="form" style="margin-bottom:24px">
          <?= csrf_field() ?>
          <label>
            Загрузить аватар (JPG, PNG, GIF, WebP, макс. 2 МБ)
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">
          </label>
          <button type="submit" class="btn btn-primary">Загрузить</button>
        </form>
        <h2 class="card-title">Мои данные</h2>
        <form method="post" action="<?= url('profile') ?>" class="form">
          <?= csrf_field() ?>
          <div class="row">
            <label>
              Рост (см)
              <input type="number" name="height_cm" min="100" max="250" step="1"
                     value="<?= e($profile['height_cm'] ?? '') ?>" placeholder="170">
            </label>
            <label>
              Вес (кг)
              <input type="number" name="weight_kg" min="30" max="300" step="0.1"
                     value="<?= e($profile['weight_kg'] ?? '') ?>" placeholder="70">
            </label>
          </div>
          <div class="row">
            <label>
              Возраст
              <input type="number" name="age" min="10" max="120" value="<?= e($profile['age'] ?? '') ?>" placeholder="25">
            </label>
            <label>
              Пол
              <select name="gender">
                <option value="">—</option>
                <option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Мужской</option>
                <option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Женский</option>
              </select>
            </label>
          </div>
          <label>
            Уровень активности
            <select name="activity_level">
              <?php foreach ($activityLabels as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($profile['activity_level'] ?? 'moderate') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>
            Цель
            <select name="goal">
              <?php foreach ($goalLabels as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($profile['goal'] ?? 'maintain') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
      </div>
    </div>

    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title">Динамика веса</h2>
        <?php if (empty($weightHistory)): ?>
        <p class="muted">Пока нет записей. Сохраните вес в форме слева.</p>
        <?php else: ?>
        <div class="kpi" style="margin-bottom:16px">
          <div class="item">
            <div class="num"><?= e($weightHistory[0]['weight_kg'] ?? '—') ?></div>
            <div class="muted">кг сейчас</div>
          </div>
        </div>
        <div id="weight-chart" style="height:200px;background:rgba(0,0,0,.2);border-radius:12px;display:flex;align-items:flex-end;padding:12px;gap:4px">
          <?php
          $points = array_reverse(array_slice($weightHistory, 0, 14));
          $max = max(array_column($points, 'weight_kg')) ?: 1;
          foreach ($points as $p):
            $h = ($p['weight_kg'] / $max) * 100;
          ?>
          <div title="<?= e($p['logged_at']) ?>: <?= e($p['weight_kg']) ?> кг" style="flex:1;background:var(--accent);border-radius:4px;min-height:4px;height:<?= $h ?>%"></div>
          <?php endforeach; ?>
        </div>
        <p class="muted" style="margin-top:12px;font-size:12px">Последние 14 записей</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
