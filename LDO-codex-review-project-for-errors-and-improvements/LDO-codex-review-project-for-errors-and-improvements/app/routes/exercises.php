<?php
declare(strict_types=1);

$pageTitle = 'Справочник упражнений';
$current = 'exercises';
$categories = [];
$exercises = [];
$stmt = db()->query('SELECT id,name FROM exercise_categories ORDER BY sort_order, name');
if ($stmt) $categories = $stmt->fetchAll();
$stmt2 = db()->query('SELECT e.id,e.name,e.description,c.name as category_name FROM exercises e LEFT JOIN exercise_categories c ON e.category_id = c.id ORDER BY c.sort_order, e.name');
if ($stmt2) $exercises = $stmt2->fetchAll();

render('exercises', compact('pageTitle', 'current', 'categories', 'exercises'));
