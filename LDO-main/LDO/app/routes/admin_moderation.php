<?php
declare(strict_types=1);

$pageTitle = 'Модерация контента';
$current = 'admin';
$status = (string)($_GET['status'] ?? 'pending');
$type = (string)($_GET['type'] ?? 'all');

$moderationReady = moderation_table_ready();

if (is_post()) {
  csrf_validate();
  if (!$moderationReady) {
    flash_set('err', 'Таблица moderation_items не найдена. Примените миграцию 004_admin_security_moderation.sql.');
    redirect('admin-moderation', ['status' => $status, 'type' => $type]);
  }
  $id = (int)($_POST['id'] ?? 0);
  $action = (string)($_POST['action'] ?? '');
  if ($id > 0 && in_array($action, ['approved','rejected','deleted'], true)) {
    $ok = false;
    if ($action === 'deleted') {
      $ok = moderation_delete_item($id);
      if ($ok) admin_audit_log('moderation_delete', 'moderation_items', $id);
    } else {
      $ok = moderation_update_status($id, $action);
      if ($ok) admin_audit_log('moderation_' . $action, 'moderation_items', $id);
    }
    flash_set($ok ? 'ok' : 'err', $ok ? 'Статус контента обновлён.' : 'Не удалось обновить статус.');
  }
  redirect('admin-moderation', ['status' => $status, 'type' => $type]);
}

$items = moderation_items($type, $status);
render('admin/moderation', compact('pageTitle', 'current', 'items', 'status', 'type', 'moderationReady'));
