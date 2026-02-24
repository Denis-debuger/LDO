<?php
declare(strict_types=1);

$pageTitle = 'Админ-панель';
$current = 'admin';
$stats = [
  'users' => db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
  'programs' => db()->query('SELECT COUNT(*) FROM workout_programs')->fetchColumn(),
  'exercises' => db()->query('SELECT COUNT(*) FROM exercises')->fetchColumn(),
  'articles' => db()->query('SELECT COUNT(*) FROM articles')->fetchColumn(),
  'published_articles' => db()->query('SELECT COUNT(*) FROM articles WHERE published=1')->fetchColumn(),
];

render('admin', compact('pageTitle', 'current', 'stats'));
