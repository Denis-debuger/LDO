<?php
declare(strict_types=1);

$pageTitle = 'Калькулятор КБЖУ';
$current = 'kbju';
$profile = profile_get(auth_user_id());
$result = null;

$weight = (float)($profile['weight_kg'] ?? 0);
$height = (int)($profile['height_cm'] ?? 0);
$age = (int)($profile['age'] ?? 0);
$gender = $profile['gender'] ?? 'male';
$activity = $profile['activity_level'] ?? 'moderate';
$goal = $profile['goal'] ?? 'maintain';

if ($weight > 0 && $height > 0 && $age > 0) {
  $mult = kbju_get_activity_multiplier($activity);
  $cal = kbju_calc($weight, $height, $age, $gender, $mult);
  $cal = kbju_adjust_for_goal($cal, $goal);
  $result = kbju_split($cal, $goal);
}

$activityLabels = [
  'sedentary' => 'Минимальная',
  'light' => 'Лёгкая',
  'moderate' => 'Средняя',
  'active' => 'Высокая',
  'very' => 'Очень высокая',
];
$goalLabels = ['maintain' => 'Поддержание', 'lose' => 'Похудение', 'gain' => 'Набор массы'];

render('kbju', compact('pageTitle', 'current', 'profile', 'result', 'activityLabels', 'goalLabels'));
