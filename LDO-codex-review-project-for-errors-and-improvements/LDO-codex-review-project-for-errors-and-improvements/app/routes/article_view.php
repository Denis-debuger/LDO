<?php
declare(strict_types=1);

$slug = (string)($_GET['slug'] ?? '');
if ($slug === '') { redirect('articles'); }

$stmt = db()->prepare('SELECT a.*, c.name as category_name FROM articles a LEFT JOIN article_categories c ON a.category_id = c.id WHERE a.slug = ? AND a.published = 1 LIMIT 1');
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
  flash_set('err', 'Статья не найдена.');
  redirect('articles');
}

$pageTitle = $article['title'];
$current = 'articles';
render('article', compact('pageTitle', 'current', 'article'));
