<?php
declare(strict_types=1);

$id = (int)($_GET['id'] ?? 0);
$userId = auth_user_id();
$log = $id ? diary_log_get($id, $userId) : null;

if (!$log) {
  flash_set('err', 'Запись не найдена.');
  redirect('diary');
}

$exercises = diary_log_exercises($log['id']);
$exerciseList = [];
$stmt = db()->query('SELECT id, name FROM exercises ORDER BY name');
if ($stmt) $exerciseList = $stmt->fetchAll();

$meals = meal_logs_by_date($userId, $log['logged_at']);
$mealTotal = meal_total_by_date($userId, $log['logged_at']);
$foodItems = food_items_list();

$pageTitle = 'Тренировка ' . $log['logged_at'];
$current = 'diary';
render('diary_view', compact('pageTitle', 'current', 'log', 'exercises', 'exerciseList', 'meals', 'mealTotal', 'foodItems'));
