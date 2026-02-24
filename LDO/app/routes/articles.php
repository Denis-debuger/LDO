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

render('articles', compact('pageTitle', 'current', 'articles', 'categories'));
