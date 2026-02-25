<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if (request_method() === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$endpoint = (string)($_GET['endpoint'] ?? 'health');

$send = static function (array $payload, int $status = 200): void {
  http_response_code($status);
  echo json_encode($payload);
  exit;
};

$requireUser = static function () use ($send): int {
  if (!is_logged_in()) {
    $send(['ok' => false, 'error' => 'Unauthorized'], 401);
  }

  $userId = auth_user_id();
  if (!$userId) {
    $send(['ok' => false, 'error' => 'Unauthorized'], 401);
  }

  return $userId;
};

if ($endpoint === 'health') {
  $send(['ok' => true, 'service' => 'ldo-backend', 'time' => now_dt()]);
}

if ($endpoint === 'profile') {
  $userId = $requireUser();

  if (request_method() === 'GET') {
    $send(['ok' => true, 'profile' => profile_get($userId)]);
  }

  if (request_method() === 'POST') {
    $body = json_decode((string)file_get_contents('php://input'), true) ?: [];
    profile_update($userId, [
      'height_cm' => isset($body['height_cm']) ? (int)$body['height_cm'] : null,
      'weight_kg' => isset($body['weight_kg']) ? (float)$body['weight_kg'] : null,
      'age' => isset($body['age']) ? (int)$body['age'] : null,
      'gender' => in_array(($body['gender'] ?? ''), ['male', 'female'], true) ? $body['gender'] : null,
      'activity_level' => in_array(($body['activity_level'] ?? ''), ['sedentary', 'light', 'moderate', 'active', 'very'], true) ? $body['activity_level'] : 'moderate',
      'goal' => in_array(($body['goal'] ?? ''), ['maintain', 'lose', 'gain'], true) ? $body['goal'] : 'maintain',
    ]);
    $send(['ok' => true, 'profile' => profile_get($userId)]);
  }

  $send(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if ($endpoint === 'foods') {
  $requireUser();
  $q = clean_str((string)($_GET['q'] ?? ''));
  $send(['ok' => true, 'foods' => food_items_list($q)]);
}

if ($endpoint === 'nutrition-today') {
  $userId = $requireUser();
  $date = today();
  $send([
    'ok' => true,
    'date' => $date,
    'total' => meal_total_by_date($userId, $date),
    'meals' => meal_logs_by_date($userId, $date),
  ]);
}

if ($endpoint === 'meal-add') {
  $userId = $requireUser();
  if (request_method() !== 'POST') {
    $send(['ok' => false, 'error' => 'Method not allowed'], 405);
  }

  $body = json_decode((string)file_get_contents('php://input'), true) ?: [];
  $mealType = in_array(($body['meal_type'] ?? ''), ['breakfast', 'lunch', 'dinner', 'snack'], true) ? (string)$body['meal_type'] : 'snack';
  $foodItemId = isset($body['food_item_id']) ? (int)$body['food_item_id'] : 0;
  $amountG = isset($body['amount_g']) ? (float)$body['amount_g'] : 0;

  if ($foodItemId <= 0 || $amountG <= 0) {
    $send(['ok' => false, 'error' => 'Некорректные данные для добавления продукта.'], 422);
  }

  $food = food_item_get($foodItemId);
  if (!$food) {
    $send(['ok' => false, 'error' => 'Продукт не найден.'], 404);
  }

  $todayLog = diary_log_by_date($userId, today());
  $logId = $todayLog ? (int)$todayLog['id'] : diary_log_add($userId, today(), 'Питание из React');
  $nutrition = meal_calculate_nutrition($foodItemId, null, $amountG);

  $mealId = meal_log_add(
    $logId,
    $mealType,
    $foodItemId,
    $food['name'] ?? null,
    $amountG,
    $nutrition['calories'],
    $nutrition['protein'],
    $nutrition['fat'],
    $nutrition['carbs']
  );

  $send(['ok' => true, 'meal_id' => $mealId]);
}

if ($endpoint === 'meal-delete') {
  $userId = $requireUser();
  if (request_method() !== 'POST') {
    $send(['ok' => false, 'error' => 'Method not allowed'], 405);
  }

  $body = json_decode((string)file_get_contents('php://input'), true) ?: [];
  $mealId = isset($body['meal_id']) ? (int)$body['meal_id'] : 0;

  if ($mealId <= 0) {
    $send(['ok' => false, 'error' => 'Некорректный meal_id.'], 422);
  }

  if (!meal_log_delete($mealId, $userId)) {
    $send(['ok' => false, 'error' => 'Не удалось удалить запись.'], 404);
  }

  $send(['ok' => true]);
}

$send(['ok' => false, 'error' => 'Not found'], 404);
