<?php
declare(strict_types=1);

$pageTitle = 'Калькулятор КБЖУ';
$current = 'kbju';
$profile = profile_get(auth_user_id());
$result = kbju_targets_from_profile($profile ?? []);
$todayNutrition = meal_total_by_date(auth_user_id(), today());
$todayMeals = meal_logs_by_date(auth_user_id(), today());

$activityLabels = [
  'sedentary' => 'Минимальная',
  'light' => 'Лёгкая',
  'moderate' => 'Средняя',
  'active' => 'Высокая',
  'very' => 'Очень высокая',
];
$goalLabels = ['maintain' => 'Поддержание', 'lose' => 'Похудение', 'gain' => 'Набор массы'];

render('kbju', compact('pageTitle', 'current', 'profile', 'result', 'activityLabels', 'goalLabels', 'todayNutrition', 'todayMeals'));
