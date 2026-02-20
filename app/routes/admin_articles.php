<?php
declare(strict_types=1);

$pageTitle = 'Управление статьями';
$current = 'admin';
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'delete' && $id && is_post()) {
  csrf_validate();
  $article = admin_article_get($id);
  if ($article) {
    article_media_delete($article['cover_image'] ?? null);
    article_media_delete($article['video_url'] ?? null);
  }
  if (admin_article_delete($id)) {
    flash_set('ok', 'Статья удалена.');
  }
  redirect('admin-articles');
}

$articles = admin_articles_list(true);
render('admin/articles', compact('pageTitle', 'current', 'articles'));
