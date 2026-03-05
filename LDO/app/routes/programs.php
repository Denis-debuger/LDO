<?php
declare(strict_types=1);

$pageTitle = 'Тренировочные программы';
$current = 'programs';
$programs = [];
$stmt = db()->query('SELECT id,name,level,description FROM workout_programs ORDER BY name');
if ($stmt) $programs = $stmt->fetchAll();

if (empty($programs)) {
  $programs = [
    [
      'name' => 'Старт 3x в неделю',
      'level' => 'Новичок',
      'description' => 'Базовая программа на 6 недель: техника приседа, жима и тяги, умеренный объём и акцент на восстановление.',
    ],
    [
      'name' => 'Силовой фундамент',
      'level' => 'Средний',
      'description' => '4 тренировки в неделю с прогрессией рабочих весов и контролем нагрузки через дневник самочувствия.',
    ],
    [
      'name' => 'Рекомпозиция тела',
      'level' => 'Любой',
      'description' => 'Комбинация силовых и круговых блоков для снижения процента жира и поддержания мышечной массы.',
    ],
  ];
}

render('programs', compact('pageTitle', 'current', 'programs'));
