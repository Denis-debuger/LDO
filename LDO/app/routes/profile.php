<?php
declare(strict_types=1);

$pageTitle = 'Профиль';
$current = 'profile';
$userId = auth_user_id();
$profile = profile_get($userId);
$weightHistory = weight_log_list($userId);
$foodItems = food_items_list();
$todayMeals = meal_logs_by_date($userId, today());
$error = null;
$ok = null;

if (is_post()) {
  csrf_validate();
  $formType = (string)($_POST['form_type'] ?? '');
  $isMealAddForm = $formType === 'meal_add';
  $isMealDeleteForm = $formType === 'meal_delete';
  $isAvatarForm = !empty($_FILES['avatar']['tmp_name']) || (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1');

  if ($isMealAddForm) {
    $mealType = in_array($_POST['meal_type'] ?? '', ['breakfast', 'lunch', 'dinner', 'snack'], true) ? (string)$_POST['meal_type'] : 'snack';
    $foodItemId = isset($_POST['food_item_id']) && $_POST['food_item_id'] !== '' ? (int)$_POST['food_item_id'] : null;
    $amountG = (float)str_replace(',', '.', (string)($_POST['amount_g'] ?? '0'));

    if (!$foodItemId || $amountG <= 0) {
      $error = 'Выберите продукт и укажите количество в граммах.';
    } else {
      $todayLog = diary_log_by_date($userId, today());
      $logId = $todayLog ? (int)$todayLog['id'] : diary_log_add($userId, today(), 'Питание из профиля');
      $selectedFood = food_item_get($foodItemId);
      $nutrition = meal_calculate_nutrition($foodItemId, null, $amountG);
      meal_log_add($logId, $mealType, $foodItemId, $selectedFood['name'] ?? null, $amountG, $nutrition['calories'], $nutrition['protein'], $nutrition['fat'], $nutrition['carbs']);
      $ok = 'Продукт добавлен в рацион за сегодня.';
    }
    $profile = profile_get($userId);
  } elseif ($isMealDeleteForm) {
    $mealId = (int)($_POST['meal_id'] ?? 0);
    if ($mealId > 0 && meal_log_delete($mealId, $userId)) {
      $ok = 'Продукт удалён из рациона.';
    } else {
      $error = 'Не удалось удалить запись о питании.';
    }
    $profile = profile_get($userId);
  } elseif ($isAvatarForm) {
    if (!empty($_FILES['avatar']['tmp_name'])) {
      $avResult = avatar_upload($userId, $_FILES['avatar']);
      if ($avResult['ok']) $ok = 'Аватар обновлён.';
      else $error = $avResult['error'] ?? 'Ошибка загрузки аватара.';
    } elseif (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
      avatar_remove($userId);
      $ok = 'Аватар удалён.';
    }
    $profile = profile_get($userId);
  } else {
    $height = isset($_POST['height_cm']) ? (int)$_POST['height_cm'] : null;
    $weight = isset($_POST['weight_kg']) ? (float)str_replace(',', '.', $_POST['weight_kg']) : null;
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $gender = in_array($_POST['gender'] ?? '', ['male', 'female']) ? $_POST['gender'] : null;
    $activity = in_array($_POST['activity_level'] ?? '', ['sedentary', 'light', 'moderate', 'active', 'very']) ? $_POST['activity_level'] : 'moderate';
    $goal = in_array($_POST['goal'] ?? '', ['maintain', 'lose', 'gain']) ? $_POST['goal'] : 'maintain';

    profile_update($userId, [
      'height_cm' => $height ?: null,
      'weight_kg' => $weight ?: null,
      'age' => $age ?: null,
      'gender' => $gender,
      'activity_level' => $activity,
      'goal' => $goal,
    ]);

    if ($weight > 0) {
      weight_log_add($userId, $weight, today());
    }

    $profile = profile_get($userId);
    $weightHistory = weight_log_list($userId);
    $ok = 'Профиль обновлён.';
  }
}

$todayMeals = meal_logs_by_date($userId, today());

$activityLabels = [
  'sedentary' => 'Минимальная',
  'light' => 'Лёгкая',
  'moderate' => 'Средняя',
  'active' => 'Высокая',
  'very' => 'Очень высокая',
];
$goalLabels = ['maintain' => 'Поддержание', 'lose' => 'Похудение', 'gain' => 'Набор массы'];

$targets = kbju_targets_from_profile($profile ?? []);
$todayNutrition = meal_total_by_date($userId, today());

$progressValue = static function (float $consumed, float $target, int $precision = 0): array {
  $safeTarget = $target > 0 ? $target : 1;
  $percent = (int)min(100, round(($consumed / $safeTarget) * 100));
  return ['consumed' => round($consumed, $precision), 'target' => round($target, $precision), 'percent' => $percent];
};

$nutritionProgress = [
  'calories' => $progressValue((float)($todayNutrition['calories'] ?? 0), (float)($targets['calories'] ?? 0), 0),
  'protein' => $progressValue((float)($todayNutrition['protein'] ?? 0), (float)($targets['protein_g'] ?? 0), 1),
  'fat' => $progressValue((float)($todayNutrition['fat'] ?? 0), (float)($targets['fat_g'] ?? 0), 1),
  'carbs' => $progressValue((float)($todayNutrition['carbs'] ?? 0), (float)($targets['carbs_g'] ?? 0), 1),
];

$currentWeight = (float)($profile['weight_kg'] ?? 0);
$firstLoggedWeight = !empty($weightHistory) ? (float)($weightHistory[count($weightHistory) - 1]['weight_kg'] ?? $currentWeight) : $currentWeight;
$goal = (string)($profile['goal'] ?? 'maintain');
$targetWeight = $goal === 'lose' ? max(35, $firstLoggedWeight * 0.9) : ($goal === 'gain' ? $firstLoggedWeight * 1.08 : $firstLoggedWeight);
$distanceFromStart = abs($currentWeight - $firstLoggedWeight);
$goalDistance = max(0.1, abs($targetWeight - $firstLoggedWeight));
$weightPercent = (int)min(100, round(($distanceFromStart / $goalDistance) * 100));
if ($goal === 'maintain') {
  $weightPercent = (int)max(0, 100 - abs($currentWeight - $targetWeight) * 20);
}
$weightStats = ['current' => $currentWeight, 'target' => $targetWeight, 'percent' => $weightPercent];

$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$chartData = stats_activity_chart($userId, $year);

render('profile', compact('pageTitle', 'current', 'profile', 'weightHistory', 'error', 'ok', 'activityLabels', 'goalLabels', 'chartData', 'nutritionProgress', 'weightStats', 'foodItems', 'todayMeals'));
