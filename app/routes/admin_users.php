<?php
declare(strict_types=1);

$pageTitle = 'Управление пользователями';
$current = 'admin';
$action = $_GET['action'] ?? '';
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'block' && $userId) {
  admin_user_toggle_block($userId);
  flash_set('ok', 'Статус пользователя изменён.');
  redirect('admin-users');
}
if ($action === 'delete' && $userId && is_post()) {
  csrf_validate();
  if (admin_user_delete($userId)) {
    flash_set('ok', 'Пользователь удалён.');
  }
  redirect('admin-users');
}

$users = admin_users_list();
render('admin/users', compact('pageTitle', 'current', 'users'));
