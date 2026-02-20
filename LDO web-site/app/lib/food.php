<?php
declare(strict_types=1);

function food_items_list(string $search = ''): array
{
  if ($search) {
    $stmt = db()->prepare('SELECT * FROM food_items WHERE name LIKE ? ORDER BY name LIMIT 50');
    $stmt->execute(['%' . $search . '%']);
  } else {
    $stmt = db()->query('SELECT * FROM food_items ORDER BY name LIMIT 100');
  }
  return $stmt ? $stmt->fetchAll() : [];
}

function food_item_get(int $id): ?array
{
  $stmt = db()->prepare('SELECT * FROM food_items WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  return $stmt->fetch() ?: null;
}

function food_item_add(string $name, float $calories, float $protein = 0, float $fat = 0, float $carbs = 0): int
{
  $stmt = db()->prepare('INSERT INTO food_items (name, calories_per_100g, protein_per_100g, fat_per_100g, carbs_per_100g, created_at) VALUES (?,?,?,?,?,?)');
  $stmt->execute([$name, $calories, $protein, $fat, $carbs, now_dt()]);
  return (int)db()->lastInsertId();
}

function meal_logs_by_date(int $userId, string $date): array
{
  $stmt = db()->prepare('
    SELECT ml.*, fi.name as food_item_name
    FROM meal_logs ml
    JOIN workout_logs wl ON wl.id = ml.log_id
    LEFT JOIN food_items fi ON fi.id = ml.food_item_id
    WHERE wl.user_id = ? AND wl.logged_at = ?
    ORDER BY ml.meal_type, ml.created_at
  ');
  $stmt->execute([$userId, $date]);
  return $stmt->fetchAll();
}

function meal_log_add(int $logId, string $mealType, ?int $foodItemId, ?string $foodName, float $amountG, float $calories, float $protein = 0, float $fat = 0, float $carbs = 0): int
{
  $stmt = db()->prepare('INSERT INTO meal_logs (log_id, meal_type, food_item_id, food_name, amount_g, calories, protein, fat, carbs, created_at) VALUES (?,?,?,?,?,?,?,?,?,?)');
  $stmt->execute([$logId, $mealType, $foodItemId ?: null, $foodName ?: null, $amountG, $calories, $protein, $fat, $carbs, now_dt()]);
  return (int)db()->lastInsertId();
}

function meal_log_delete(int $mealId, int $userId): bool
{
  $stmt = db()->prepare('DELETE ml FROM meal_logs ml JOIN workout_logs wl ON wl.id=ml.log_id WHERE ml.id=? AND wl.user_id=?');
  $stmt->execute([$mealId, $userId]);
  return $stmt->rowCount() > 0;
}

function meal_calculate_nutrition(?int $foodItemId, ?string $foodName, float $amountG): array
{
  if ($foodItemId) {
    $item = food_item_get($foodItemId);
    if ($item) {
      $mult = $amountG / 100;
      return [
        'calories' => round($item['calories_per_100g'] * $mult, 1),
        'protein' => round($item['protein_per_100g'] * $mult, 1),
        'fat' => round($item['fat_per_100g'] * $mult, 1),
        'carbs' => round($item['carbs_per_100g'] * $mult, 1),
      ];
    }
  }
  
  // Если продукт не найден, возвращаем нули (пользователь может ввести свои значения)
  return ['calories' => 0, 'protein' => 0, 'fat' => 0, 'carbs' => 0];
}

function meal_total_by_date(int $userId, string $date): array
{
  $stmt = db()->prepare('
    SELECT 
      SUM(ml.calories) as total_calories,
      SUM(ml.protein) as total_protein,
      SUM(ml.fat) as total_fat,
      SUM(ml.carbs) as total_carbs
    FROM meal_logs ml
    JOIN workout_logs wl ON wl.id = ml.log_id
    WHERE wl.user_id = ? AND wl.logged_at = ?
  ');
  $stmt->execute([$userId, $date]);
  $row = $stmt->fetch();
  return [
    'calories' => round((float)($row['total_calories'] ?? 0), 0),
    'protein' => round((float)($row['total_protein'] ?? 0), 1),
    'fat' => round((float)($row['total_fat'] ?? 0), 1),
    'carbs' => round((float)($row['total_carbs'] ?? 0), 1),
  ];
}
