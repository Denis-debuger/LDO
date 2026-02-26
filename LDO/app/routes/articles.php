<?php
declare(strict_types=1);

$pageTitle = 'Статьи';
$current = 'articles';
$articles = [];
$categories = [];
$stmt = db()->query('SELECT id,name FROM article_categories ORDER BY sort_order, name');
if ($stmt) $categories = $stmt->fetchAll();
$stmt2 = db()->query('SELECT a.id,a.title,a.slug,a.excerpt,a.created_at,c.name as category_name FROM articles a LEFT JOIN article_categories c ON a.category_id = c.id WHERE a.published = 1 ORDER BY a.created_at DESC LIMIT 50');
if ($stmt2) $articles = $stmt2->fetchAll();

if (empty($articles)) {
  $articles = [
    [
      'title' => 'Как собрать рацион для набора мышечной массы',
      'slug' => '#',
      'excerpt' => 'Разбираем базовый расчёт калорий, распределение КБЖУ и примеры простых приёмов пищи на каждый день.',
      'created_at' => date('Y-m-d'),
      'category_name' => 'Питание',
    ],
    [
      'title' => '5 ошибок в силовых тренировках новичка',
      'slug' => '#',
      'excerpt' => 'Частые технические и программные ошибки, которые тормозят прогресс и увеличивают риск травм.',
      'created_at' => date('Y-m-d'),
      'category_name' => 'Тренировки',
    ],
    [
      'title' => 'Восстановление: сон, стресс и планирование нагрузки',
      'slug' => '#',
      'excerpt' => 'Почему восстановление так же важно, как подходы и повторения, и как отслеживать его в дневнике.',
      'created_at' => date('Y-m-d'),
      'category_name' => 'Здоровье',
    ],
  ];
}

render('articles', compact('pageTitle', 'current', 'articles', 'categories'));
