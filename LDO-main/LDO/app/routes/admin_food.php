<?php
declare(strict_types=1);

$pageTitle = 'Продукты и категории контента';
$current = 'admin';
$error = null;

if (is_post()) {
  csrf_validate();
  $form = (string)($_POST['form'] ?? '');
  if ($form === 'food') {
    $name = clean_str((string)($_POST['name'] ?? ''));
    if ($name !== '') {
      db()->prepare('INSERT INTO food_items (category_id,name,calories_per_100g,protein_per_100g,fat_per_100g,carbs_per_100g,created_at) VALUES (?,?,?,?,?,?,?)')
        ->execute([
          ($_POST['category_id'] ?? '') !== '' ? (int)$_POST['category_id'] : null,
          $name,
          (float)($_POST['calories'] ?? 0),
          (float)($_POST['protein'] ?? 0),
          (float)($_POST['fat'] ?? 0),
          (float)($_POST['carbs'] ?? 0),
          now_dt()
        ]);
      admin_audit_log('food_add', 'food_items', (int)db()->lastInsertId(), ['name' => $name]);
      flash_set('ok', 'Продукт добавлен.');
      redirect('admin-food');
    }
    $error = 'Название продукта обязательно.';
  }
  if ($form === 'food_category') {
    $name = clean_str((string)($_POST['name'] ?? ''));
    if ($name !== '') {
      db()->prepare('INSERT INTO food_categories (name, sort_order, created_at) VALUES (?,?,?)')->execute([$name, (int)($_POST['sort_order'] ?? 0), now_dt()]);
      admin_audit_log('food_category_add', 'food_categories', (int)db()->lastInsertId(), ['name' => $name]);
      flash_set('ok', 'Категория продуктов добавлена.');
      redirect('admin-food');
    }
  }
  if ($form === 'content_category') {
    $name = clean_str((string)($_POST['name'] ?? ''));
    if ($name !== '') {
      db()->prepare('INSERT INTO content_categories (name, type, sort_order, created_at) VALUES (?,?,?,?)')->execute([$name, (string)($_POST['type'] ?? 'general'), (int)($_POST['sort_order'] ?? 0), now_dt()]);
      admin_audit_log('content_category_add', 'content_categories', (int)db()->lastInsertId(), ['name' => $name]);
      flash_set('ok', 'Категория контента добавлена.');
      redirect('admin-food');
    }
  }
}

$foodItems = db()->query('SELECT f.*, c.name AS category_name FROM food_items f LEFT JOIN food_categories c ON c.id=f.category_id ORDER BY f.created_at DESC LIMIT 150')->fetchAll();
$foodCategories = db()->query('SELECT * FROM food_categories ORDER BY sort_order, name')->fetchAll();
$contentCategories = db()->query('SELECT * FROM content_categories ORDER BY type, sort_order, name')->fetchAll();
render('admin/food', compact('pageTitle', 'current', 'error', 'foodItems', 'foodCategories', 'contentCategories'));
