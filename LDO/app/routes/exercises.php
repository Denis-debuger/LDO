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

if (empty($exercises)) {
  $exercises = [
    [
      'name' => 'Приседания со штангой',
      'category_name' => 'Ноги',
      'description' => 'Базовое многосуставное упражнение для развития силы ног и корпуса. Контролируйте глубину и нейтральное положение спины.',
    ],
    [
      'name' => 'Жим лёжа',
      'category_name' => 'Грудь',
      'description' => 'Классическое силовое движение для грудных мышц, трицепса и передней дельты. Держите лопатки сведёнными.',
    ],
    [
      'name' => 'Тяга верхнего блока',
      'category_name' => 'Спина',
      'description' => 'Подходит для укрепления широчайших и улучшения осанки. Выполняйте движение за счёт приведения локтей вниз.',
    ],
    [
      'name' => 'Планка',
      'category_name' => 'Кор',
      'description' => 'Статическое упражнение для стабилизаторов корпуса. Поддерживайте ровную линию тела без прогиба в пояснице.',
    ],
  ];
}

render('exercises', compact('pageTitle', 'current', 'categories', 'exercises'));
