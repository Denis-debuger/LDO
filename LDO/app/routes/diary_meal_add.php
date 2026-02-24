<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$logId = (int)($_POST['log_id'] ?? 0);
$userId = auth_user_id();
$log = diary_log_get($logId, $userId);

if (!$log) {
  flash_set('err', 'Тренировка не найдена.');
  redirect('diary');
}

$mealType = in_array($_POST['meal_type'] ?? '', ['breakfast', 'lunch', 'dinner', 'snack']) ? $_POST['meal_type'] : 'snack';
$foodItemId = isset($_POST['food_item_id']) && $_POST['food_item_id'] !== '' ? (int)$_POST['food_item_id'] : null;
$foodName = clean_str((string)($_POST['food_name'] ?? ''));
$amountG = (float)str_replace(',', '.', $_POST['amount_g'] ?? '0');

if ($amountG <= 0) {
  flash_set('err', 'Укажите количество продукта.');
  redirect('diary-view', ['id' => $logId]);
}

// Расчёт калорий и БЖУ
$nutrition = meal_calculate_nutrition($foodItemId, $foodName, $amountG);

// Если пользователь указал свои значения, используем их
if (isset($_POST['calories']) && $_POST['calories'] > 0) {
  $nutrition['calories'] = (float)$_POST['calories'];
}
if (isset($_POST['protein']) && $_POST['protein'] >= 0) {
  $nutrition['protein'] = (float)$_POST['protein'];
}
if (isset($_POST['fat']) && $_POST['fat'] >= 0) {
  $nutrition['fat'] = (float)$_POST['fat'];
}
if (isset($_POST['carbs']) && $_POST['carbs'] >= 0) {
  $nutrition['carbs'] = (float)$_POST['carbs'];
}

meal_log_add($logId, $mealType, $foodItemId, $foodName ?: null, $amountG, $nutrition['calories'], $nutrition['protein'], $nutrition['fat'], $nutrition['carbs']);

flash_set('ok', 'Приём пищи добавлен.');
redirect('diary-view', ['id' => $logId]);
