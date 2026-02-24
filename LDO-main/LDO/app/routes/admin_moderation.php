<?php
declare(strict_types=1);

$pageTitle = 'Модерация контента';
$current = 'admin';
$status = (string)($_GET['status'] ?? 'pending');
$type = (string)($_GET['type'] ?? 'all');

if (is_post()) {
  csrf_validate();
  $id = (int)($_POST['id'] ?? 0);
  $action = (string)($_POST['action'] ?? '');
  if ($id > 0 && in_array($action, ['approved','rejected','deleted'], true)) {
    if ($action === 'deleted') {
      db()->prepare('DELETE FROM moderation_items WHERE id=?')->execute([$id]);
      admin_audit_log('moderation_delete', 'moderation_items', $id);
    } else {
      moderation_update_status($id, $action);
      admin_audit_log('moderation_' . $action, 'moderation_items', $id);
    }
    flash_set('ok', 'Статус контента обновлён.');
  }
  redirect('admin-moderation', ['status' => $status, 'type' => $type]);
}

$items = moderation_items($type, $status);
render('admin/moderation', compact('pageTitle', 'current', 'items', 'status', 'type'));
