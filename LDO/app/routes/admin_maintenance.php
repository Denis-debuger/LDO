<?php
declare(strict_types=1);

$pageTitle = 'Импорт / Экспорт / Бэкап';
$current = 'admin';
$error = null;

if (is_post()) {
  csrf_validate();
  $action = (string)($_POST['action'] ?? '');
  if ($action === 'export_json') {
    $table = (string)($_POST['table'] ?? 'food_items');
    $json = export_table_json($table);
    admin_audit_log('export_json', $table, null);
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $table . '_' . date('Ymd_His') . '.json"');
    echo $json;
    exit;
  }
  if ($action === 'backup_sql') {
    $sql = backup_sql_dump();
    admin_audit_log('backup_sql', 'system', null);
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="ldo_backup_' . date('Ymd_His') . '.sql"');
    echo $sql;
    exit;
  }
  if ($action === 'import_food_json' && !empty($_FILES['import_file']['tmp_name'])) {
    $json = (string)file_get_contents($_FILES['import_file']['tmp_name']);
    $count = import_food_items_json($json);
    admin_audit_log('import_food_json', 'food_items', null, ['count' => $count]);
    flash_set('ok', 'Импортировано продуктов: ' . $count);
    redirect('admin-maintenance');
  }
}

render('admin/maintenance', compact('pageTitle', 'current', 'error'));
