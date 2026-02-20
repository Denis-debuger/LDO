<?php
declare(strict_types=1);

$pageTitle = 'Добавить статью';
$current = 'admin';
$error = null;
$ok = null;

if (is_post()) {
  csrf_validate();
  $title = clean_str((string)($_POST['title'] ?? ''));
  $slug = clean_str((string)($_POST['slug'] ?? ''));
  $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
  $excerpt = clean_str((string)($_POST['excerpt'] ?? ''));
  $body = (string)($_POST['body'] ?? '');
  $published = isset($_POST['published']) && $_POST['published'] === '1';
  
  if ($slug === '') $slug = mb_strtolower(preg_replace('/[^a-zа-я0-9]+/ui', '-', $title));
  
  $coverImage = null;
  if (!empty($_FILES['cover_image']['tmp_name'])) {
    $result = article_media_upload($_FILES['cover_image'], 'image');
    if ($result['ok']) $coverImage = $result['url'];
    else $error = $result['error'];
  }
  
  $videoUrl = null;
  if (!empty($_FILES['video']['tmp_name'])) {
    $result = article_media_upload($_FILES['video'], 'video');
    if ($result['ok']) $videoUrl = $result['url'];
    else $error = $result['error'] ?? 'Ошибка загрузки видео.';
  } elseif (!empty($_POST['video_url'])) {
    $videoUrl = clean_str((string)$_POST['video_url']);
  }
  
  if (!$error && $title && $body) {
    try {
      admin_article_add($title, $slug, $categoryId, $excerpt ?: null, $body, $coverImage, $videoUrl, $published);
      flash_set('ok', 'Статья добавлена.');
      redirect('admin-articles');
    } catch (PDOException $e) {
      $error = 'Ошибка: ' . $e->getMessage();
    }
  } elseif (!$error) {
    $error = 'Заполните заголовок и текст статьи.';
  }
}

$categories = admin_categories_list('article');
render('admin/article_edit', compact('pageTitle', 'current', 'categories', 'error', 'ok'));
