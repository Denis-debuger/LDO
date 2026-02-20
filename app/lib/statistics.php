<?php
declare(strict_types=1);

function stats_calculate(int $userId): array
{
  // Activity (часы тренировок) - считаем количество тренировок * среднее время (1.5 часа)
  $stmt = db()->prepare('SELECT COUNT(*) as count FROM workout_logs WHERE user_id = ? AND logged_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
  $stmt->execute([$userId]);
  $workouts = $stmt->fetch();
  $activityHours = round(($workouts['count'] ?? 0) * 1.5, 1);
  $activityGoal = 64; // часов в месяц
  
  // Calories - берём из КБЖУ калькулятора
  $profile = profile_get($userId);
  $calories = 0;
  $caloriesGoal = 3000;
  if ($profile && $profile['weight_kg'] && $profile['height_cm'] && $profile['age']) {
    $mult = kbju_get_activity_multiplier($profile['activity_level'] ?? 'moderate');
    $cal = kbju_calc((float)$profile['weight_kg'], (int)$profile['height_cm'], (int)$profile['age'], $profile['gender'] ?? 'male', $mult);
    $cal = kbju_adjust_for_goal($cal, $profile['goal'] ?? 'maintain');
    $calories = round($cal);
    $caloriesGoal = round($cal * 1.2); // цель на 20% выше нормы
  }
  
  // Distance (км) - считаем из кардио упражнений или общий прогресс
  $stmt = db()->prepare('
    SELECT SUM(wle.reps_count * wle.sets_count) as total_reps
    FROM workout_log_exercises wle
    JOIN workout_logs wl ON wl.id = wle.log_id
    WHERE wl.user_id = ? AND wl.logged_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
  ');
  $stmt->execute([$userId]);
  $reps = $stmt->fetch();
  $distance = round(($reps['total_reps'] ?? 0) / 100, 0); // примерный расчёт
  $distanceGoal = 220;
  
  // Steps (повторения/шаги) - среднее количество повторений
  $stmt = db()->prepare('
    SELECT AVG(wle.reps_count * wle.sets_count) as avg_reps
    FROM workout_log_exercises wle
    JOIN workout_logs wl ON wl.id = wle.log_id
    WHERE wl.user_id = ? AND wl.logged_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND wle.reps_count IS NOT NULL
  ');
  $stmt->execute([$userId]);
  $avg = $stmt->fetch();
  $steps = round($avg['avg_reps'] ?? 0, 0);
  $stepsGoal = 10000;
  
  return [
    'activity' => ['value' => $activityHours, 'goal' => $activityGoal, 'unit' => 'ч', 'label' => 'Активность'],
    'calories' => ['value' => $calories, 'goal' => $caloriesGoal, 'unit' => 'ккал', 'label' => 'Калории'],
    'distance' => ['value' => $distance, 'goal' => $distanceGoal, 'unit' => 'км', 'label' => 'Дистанция'],
    'steps' => ['value' => $steps, 'goal' => $stepsGoal, 'unit' => 'повт', 'label' => 'Повторения'],
  ];
}

function stats_activity_chart(int $userId, int $year = null): array
{
  if ($year === null) $year = (int)date('Y');
  
  $stmt = db()->prepare('
    SELECT 
      DATE_FORMAT(logged_at, "%Y-%m") as month,
      COUNT(*) as workouts,
      SUM((SELECT COUNT(*) FROM workout_log_exercises wle WHERE wle.log_id = wl.id)) as exercises
    FROM workout_logs wl
    WHERE wl.user_id = ? AND YEAR(logged_at) = ?
    GROUP BY DATE_FORMAT(logged_at, "%Y-%m")
    ORDER BY month DESC
    LIMIT 12
  ');
  $stmt->execute([$userId, $year]);
  $data = $stmt->fetchAll();
  
  // Формируем данные для графика (12 месяцев)
  $months = [];
  $monthNames = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
  
  for ($i = 11; $i >= 0; $i--) {
    $date = date('Y-m', strtotime("-$i months", strtotime("$year-01-01")));
    $monthData = array_filter($data, fn($d) => $d['month'] === $date);
    $monthData = reset($monthData);
    
    $months[] = [
      'month' => $monthNames[(int)date('n', strtotime($date . '-01')) - 1],
      'workouts' => (int)($monthData['workouts'] ?? 0),
      'exercises' => (int)($monthData['exercises'] ?? 0),
    ];
  }
  
  return [
    'year' => $year,
    'months' => $months,
    'max' => max(array_merge(array_column($months, 'workouts'), array_column($months, 'exercises'))) ?: 1,
  ];
}
