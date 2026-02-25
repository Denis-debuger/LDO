<?php
declare(strict_types=1);

$pageTitle = 'Прогресс';
$current = 'progress';
$userId = auth_user_id();
$entries = [];
$stmt = db()->prepare('SELECT * FROM progress_entries WHERE user_id = ? ORDER BY logged_at DESC LIMIT 30');
$stmt->execute([$userId]);
$entries = $stmt->fetchAll();
$weightLogs = weight_log_list($userId, 90);
$profile = profile_get($userId);
$targets = kbju_targets_from_profile($profile ?? []);
$todayNutrition = meal_total_by_date($userId, today());

$progressValue = static function (float $consumed, float $target, int $precision = 0): array {
  $safeTarget = $target > 0 ? $target : 1;
  return [
    'consumed' => round($consumed, $precision),
    'target' => round($target, $precision),
    'percent' => (int)min(100, round(($consumed / $safeTarget) * 100)),
  ];
};

$nutritionProgress = [
  'calories' => $progressValue((float)($todayNutrition['calories'] ?? 0), (float)($targets['calories'] ?? 0), 0),
  'protein' => $progressValue((float)($todayNutrition['protein'] ?? 0), (float)($targets['protein_g'] ?? 0), 1),
  'fat' => $progressValue((float)($todayNutrition['fat'] ?? 0), (float)($targets['fat_g'] ?? 0), 1),
  'carbs' => $progressValue((float)($todayNutrition['carbs'] ?? 0), (float)($targets['carbs_g'] ?? 0), 1),
];

render('progress', compact('pageTitle', 'current', 'entries', 'weightLogs', 'nutritionProgress'));
