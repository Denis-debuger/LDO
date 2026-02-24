<?php
declare(strict_types=1);

$pageTitle = 'Профиль';
$current = 'profile';
$userId = auth_user_id();
$profile = profile_get($userId);
$weightHistory = weight_log_list($userId);
$error = null;
$ok = null;

if (is_post()) {
  csrf_validate();
  $isAvatarForm = !empty($_FILES['avatar']['tmp_name']) || (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1');
  if ($isAvatarForm) {
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

$activityLabels = [
  'sedentary' => 'Минимальная',
  'light' => 'Лёгкая',
  'moderate' => 'Средняя',
  'active' => 'Высокая',
  'very' => 'Очень высокая',
];
$goalLabels = ['maintain' => 'Поддержание', 'lose' => 'Похудение', 'gain' => 'Набор массы'];

$stats = stats_calculate($userId);
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$chartData = stats_activity_chart($userId, $year);

render('profile', compact('pageTitle', 'current', 'profile', 'weightHistory', 'error', 'ok', 'activityLabels', 'goalLabels', 'stats', 'chartData'));
