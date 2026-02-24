<?php
declare(strict_types=1);

$pageTitle = 'Управление упражнениями';
$current = 'admin';
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = null;
$ok = null;

if ($action === 'delete' && $id && is_post()) {
  csrf_validate();
  if (admin_exercise_delete($id)) {
    flash_set('ok', 'Упражнение удалено.');
  }
  redirect('admin-exercises');
}

if (is_post() && ($action === 'add' || ($action === 'edit' && $id))) {
  csrf_validate();
  $name = clean_str((string)($_POST['name'] ?? ''));
  $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
  $description = clean_str((string)($_POST['description'] ?? ''));
  $technique = clean_str((string)($_POST['technique'] ?? ''));
  $imageUrl = clean_str((string)($_POST['image_url'] ?? ''));
  $videoUrl = clean_str((string)($_POST['video_url'] ?? ''));
  
  if ($name === '') {
    $error = 'Название обязательно.';
  } else {
    if ($action === 'add') {
      admin_exercise_add($name, $categoryId, $description ?: null, $technique ?: null, $imageUrl ?: null, $videoUrl ?: null);
      $ok = 'Упражнение добавлено.';
    } else {
      admin_exercise_update($id, $name, $categoryId, $description ?: null, $technique ?: null, $imageUrl ?: null, $videoUrl ?: null);
      $ok = 'Упражнение обновлено.';
    }
  }
}

$exercises = admin_exercises_list();
$categories = admin_categories_list('exercise');
$editing = $action === 'edit' && $id ? array_filter($exercises, fn($e) => $e['id'] == $id)[0] ?? null : null;
render('admin/exercises', compact('pageTitle', 'current', 'exercises', 'categories', 'editing', 'error', 'ok'));
