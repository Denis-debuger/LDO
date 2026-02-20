<?php
declare(strict_types=1);

$pageTitle = 'Тренировочные программы';
$current = 'programs';
$programs = [];
$stmt = db()->query('SELECT id,name,level,description FROM workout_programs ORDER BY name');
if ($stmt) $programs = $stmt->fetchAll();

render('programs', compact('pageTitle', 'current', 'programs'));
