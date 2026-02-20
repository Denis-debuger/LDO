<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = $id ? admin_article_get($id) : null;

if (!$article) {
  flash_set('err', 'Статья не найдена.');
  redirect('admin-articles');
}

$pageTitle = 'Редактировать статью';
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
  
  $coverImage = $article['cover_image'];
  if (!empty($_FILES['cover_image']['tmp_name'])) {
    article_media_delete($coverImage);
    $result = article_media_upload($_FILES['cover_image'], 'image');
    if ($result['ok']) $coverImage = $result['url'];
    else $error = $result['error'];
  }
  
  $videoUrl = $article['video_url'];
  if (!empty($_FILES['video']['tmp_name'])) {
    article_media_delete($videoUrl);
    $result = article_media_upload($_FILES['video'], 'video');
    if ($result['ok']) $videoUrl = $result['url'];
    else $error = $result['error'] ?? 'Ошибка загрузки видео.';
  } elseif (!empty($_POST['video_url'])) {
    $videoUrl = clean_str((string)$_POST['video_url']);
  }
  
  if (isset($_POST['remove_cover']) && $_POST['remove_cover'] === '1') {
    article_media_delete($coverImage);
    $coverImage = null;
  }
  if (isset($_POST['remove_video']) && $_POST['remove_video'] === '1') {
    article_media_delete($videoUrl);
    $videoUrl = null;
  }
  
  if (!$error && $title && $body) {
    admin_article_update($id, $title, $slug, $categoryId, $excerpt ?: null, $body, $coverImage, $videoUrl, $published);
    flash_set('ok', 'Статья обновлена.');
    redirect('admin-articles');
  } elseif (!$error) {
    $error = 'Заполните заголовок и текст статьи.';
  }
}

$categories = admin_categories_list('article');
render('admin/article_edit', compact('pageTitle', 'current', 'article', 'categories', 'error', 'ok'));
