<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('diary') ?>" class="btn btn-ghost">← Дневник</a>
    <h1 style="margin:0">Тренировка <?= e($log['logged_at']) ?></h1>
    <a href="<?= url('diary-edit', ['id' => $log['id']]) ?>" class="btn btn-ghost">Редактировать</a>
    <form method="post" action="<?= url('diary-delete') ?>" style="display:inline" onsubmit="return confirm('Удалить тренировку?')">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $log['id'] ?>">
      <button type="submit" class="btn btn-danger">Удалить</button>
    </form>
  </div>

  <!-- Детали тренировки -->
  <div class="card card-accent" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Детали тренировки</h2>
      <div class="kpi">
        <?php if ($log['duration_min']): ?>
        <div class="item">
          <div class="num"><?= e($log['duration_min']) ?></div>
          <div class="muted">минут</div>
        </div>
        <?php endif; ?>
        <?php if ($log['body_weight']): ?>
        <div class="item">
          <div class="num"><?= e($log['body_weight']) ?></div>
          <div class="muted">кг (вес тела)</div>
        </div>
        <?php endif; ?>
        <?php if ($log['feeling']): ?>
        <div class="item">
          <div class="num"><?= e([
            'excellent' => 'Отлично',
            'good' => 'Хорошо',
            'normal' => 'Нормально',
            'tired' => 'Устал',
            'exhausted' => 'Измотан',
          ][$log['feeling']] ?? '—') ?></div>
          <div class="muted">Самочувствие</div>
        </div>
        <?php endif; ?>
      </div>
      <?php if ($log['notes']): ?>
      <p class="muted" style="margin-top:12px"><?= e($log['notes']) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Питание -->
  <div class="card card-accent" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Питание</h2>
      
      <?php if (!empty($mealTotal['calories'])): ?>
      <div class="kpi" style="margin-bottom:16px">
        <div class="item">
          <div class="num"><?= e($mealTotal['calories']) ?></div>
          <div class="muted">ккал</div>
        </div>
        <div class="item">
          <div class="num"><?= e($mealTotal['protein']) ?></div>
          <div class="muted">г белка</div>
        </div>
        <div class="item">
          <div class="num"><?= e($mealTotal['fat']) ?></div>
          <div class="muted">г жиров</div>
        </div>
        <div class="item">
          <div class="num"><?= e($mealTotal['carbs']) ?></div>
          <div class="muted">г углеводов</div>
        </div>
      </div>
      <?php endif; ?>

      <form method="post" action="<?= url('diary-meal-add') ?>" class="form" id="meal-form">
        <?= csrf_field() ?>
        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
        <div class="row">
          <label>
            Приём пищи
            <select name="meal_type" required>
              <option value="breakfast">Завтрак</option>
              <option value="lunch">Обед</option>
              <option value="dinner">Ужин</option>
              <option value="snack">Перекус</option>
            </select>
          </label>
          <label>
            Продукт из базы
            <select name="food_item_id" id="food-select">
              <option value="">— или введите свой —</option>
              <?php foreach ($foodItems as $food): ?>
              <option value="<?= $food['id'] ?>" data-cal="<?= $food['calories_per_100g'] ?>" data-prot="<?= $food['protein_per_100g'] ?>" data-fat="<?= $food['fat_per_100g'] ?>" data-carbs="<?= $food['carbs_per_100g'] ?>"><?= e($food['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <div class="row">
          <label>
            Свой продукт
            <input type="text" name="food_name" id="food-name" placeholder="Название продукта">
          </label>
          <label>
            Количество (г)
            <input type="number" name="amount_g" id="amount-g" min="0" step="1" value="100" required>
          </label>
        </div>
        <div class="row" id="nutrition-row" style="display:none">
          <label>
            Калории
            <input type="number" name="calories" id="calories-input" min="0" step="0.1">
          </label>
          <label>
            Белки (г)
            <input type="number" name="protein" id="protein-input" min="0" step="0.1">
          </label>
          <label>
            Жиры (г)
            <input type="number" name="fat" id="fat-input" min="0" step="0.1">
          </label>
          <label>
            Углеводы (г)
            <input type="number" name="carbs" id="carbs-input" min="0" step="0.1">
          </label>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
      </form>

      <?php if (!empty($meals)): ?>
      <div style="margin-top:24px">
        <h3 style="font-size:16px;margin-bottom:12px">Приёмы пищи</h3>
        <?php
        $mealLabels = ['breakfast' => 'Завтрак', 'lunch' => 'Обед', 'dinner' => 'Ужин', 'snack' => 'Перекус'];
        $grouped = [];
        foreach ($meals as $m) {
          $type = $m['meal_type'];
          if (!isset($grouped[$type])) $grouped[$type] = [];
          $grouped[$type][] = $m;
        }
        foreach ($grouped as $type => $items):
        ?>
        <div style="margin-bottom:16px;padding:12px;background:rgba(0,0,0,.2);border-radius:8px">
          <strong><?= e($mealLabels[$type] ?? $type) ?></strong>
          <?php foreach ($items as $meal): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;padding:8px;background:rgba(255,255,255,.02);border-radius:6px">
            <div>
              <strong><?= e($meal['food_item_name'] ?? $meal['food_name'] ?? 'Продукт') ?></strong>
              <span class="muted" style="margin-left:8px"><?= e($meal['amount_g']) ?> г</span>
            </div>
            <div style="text-align:right">
              <div style="color:var(--accent);font-weight:700"><?= e($meal['calories']) ?> ккал</div>
              <div class="muted" style="font-size:12px">Б:<?= e($meal['protein']) ?> Ж:<?= e($meal['fat']) ?> У:<?= e($meal['carbs']) ?></div>
            </div>
            <form method="post" action="<?= url('diary-meal-delete') ?>" style="display:inline;margin-left:12px" onsubmit="return confirm('Удалить?')">
              <?= csrf_field() ?>
              <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
              <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
              <button type="submit" class="btn btn-ghost" style="padding:4px 8px;font-size:12px">×</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Упражнения -->
  <div class="card card-accent" style="margin-bottom:24px">
    <div class="card-body">
      <h2 class="card-title">Добавить упражнение</h2>
      <form method="post" action="<?= url('diary-exercise-add') ?>" class="form">
        <?= csrf_field() ?>
        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
        <div class="row">
          <label>
            Упражнение из справочника
            <select name="exercise_id" id="diary-ex-select">
              <option value="">— или введите своё ниже —</option>
              <?php foreach ($exerciseList as $ex): ?>
              <option value="<?= $ex['id'] ?>"><?= e($ex['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>
            Своё упражнение
            <input type="text" name="exercise_name" id="diary-ex-custom" placeholder="Например: Жим лёжа">
          </label>
        </div>
        <div class="row">
          <label>
            Вес (кг)
            <input type="number" name="weight_kg" min="0" step="0.5" placeholder="60">
          </label>
          <label>
            Подходы
            <input type="number" name="sets_count" min="1" value="3">
          </label>
          <label>
            Повторения
            <input type="number" name="reps_count" min="0" placeholder="10">
          </label>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
      </form>
    </div>
  </div>

  <?php if (empty($exercises)): ?>
  <p class="muted">Пока нет упражнений в этой тренировке.</p>
  <?php else: ?>
  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Упражнение</th>
          <th>Вес</th>
          <th>Подходы</th>
          <th>Повторения</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exercises as $ex): ?>
        <tr>
          <td><?= e($ex['display_name']) ?></td>
          <td><?= $ex['weight_kg'] ? e($ex['weight_kg']) . ' кг' : '—' ?></td>
          <td><?= e($ex['sets_count']) ?></td>
          <td><?= $ex['reps_count'] !== null ? e($ex['reps_count']) : '—' ?></td>
          <td>
            <form method="post" action="<?= url('diary-exercise-delete') ?>" style="display:inline" onsubmit="return confirm('Удалить?')">
              <?= csrf_field() ?>
              <input type="hidden" name="ex_id" value="<?= $ex['id'] ?>">
              <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
              <button type="submit" class="btn btn-ghost" style="padding:4px 8px;font-size:12px">×</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<script>
(function(){
  var sel = document.getElementById('diary-ex-select');
  var custom = document.getElementById('diary-ex-custom');
  if (sel && custom) {
    sel.onchange = function(){ if(sel.value) custom.value=''; };
    custom.oninput = function(){ if(custom.value) sel.value=''; };
  }
  
  // Автоматический расчёт калорий при выборе продукта
  var foodSelect = document.getElementById('food-select');
  var amountInput = document.getElementById('amount-g');
  var caloriesInput = document.getElementById('calories-input');
  var proteinInput = document.getElementById('protein-input');
  var fatInput = document.getElementById('fat-input');
  var carbsInput = document.getElementById('carbs-input');
  var nutritionRow = document.getElementById('nutrition-row');
  var foodNameInput = document.getElementById('food-name');
  
  function calculateNutrition() {
    var foodOption = foodSelect.options[foodSelect.selectedIndex];
    var amount = parseFloat(amountInput.value) || 0;
    
    if (foodOption.value && foodOption.dataset.cal) {
      var mult = amount / 100;
      caloriesInput.value = (parseFloat(foodOption.dataset.cal) * mult).toFixed(1);
      proteinInput.value = (parseFloat(foodOption.dataset.prot) * mult).toFixed(1);
      fatInput.value = (parseFloat(foodOption.dataset.fat) * mult).toFixed(1);
      carbsInput.value = (parseFloat(foodOption.dataset.carbs) * mult).toFixed(1);
      nutritionRow.style.display = 'grid';
    } else if (foodNameInput.value) {
      nutritionRow.style.display = 'grid';
    } else {
      nutritionRow.style.display = 'none';
    }
  }
  
  if (foodSelect) {
    foodSelect.onchange = function() {
      if (this.value) foodNameInput.value = '';
      calculateNutrition();
    };
  }
  
  if (amountInput) {
    amountInput.oninput = calculateNutrition;
  }
  
  if (foodNameInput) {
    foodNameInput.oninput = function() {
      if (this.value) foodSelect.value = '';
      calculateNutrition();
    };
  }
})();
</script>
