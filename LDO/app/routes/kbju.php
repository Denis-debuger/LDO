<?php
declare(strict_types=1);

$pageTitle = 'Калькулятор КБЖУ';
$current = 'kbju';
$profile = profile_get(auth_user_id());
$result = null;

$result = kbju_targets_from_profile($profile ?? []);

$activityLabels = [
  'sedentary' => 'Минимальная',
  'light' => 'Лёгкая',
  'moderate' => 'Средняя',
  'active' => 'Высокая',
  'very' => 'Очень высокая',
];
$goalLabels = ['maintain' => 'Поддержание', 'lose' => 'Похудение', 'gain' => 'Набор массы'];

render('kbju', compact('pageTitle', 'current', 'profile', 'result', 'activityLabels', 'goalLabels'));
