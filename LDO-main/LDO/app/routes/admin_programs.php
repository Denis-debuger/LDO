<?php
declare(strict_types=1);

$pageTitle = 'Управление программами';
$current = 'admin';
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = null;
$ok = null;

if ($action === 'delete' && $id && is_post()) {
  csrf_validate();
  if (admin_program_delete($id)) {
    admin_audit_log('program_delete', 'workout_programs', $id);
    flash_set('ok', 'Программа удалена.');
  }
  redirect('admin-programs');
}

if (is_post() && ($action === 'add' || ($action === 'edit' && $id))) {
  csrf_validate();
  $name = clean_str((string)($_POST['name'] ?? ''));
  $level = in_array($_POST['level'] ?? '', ['beginner', 'intermediate', 'advanced', 'all']) ? $_POST['level'] : 'all';
  $description = clean_str((string)($_POST['description'] ?? ''));
  
  if ($name === '') {
    $error = 'Название обязательно.';
  } else {
    if ($action === 'add') {
      $newId = admin_program_add($name, $level, $description ?: null);
      admin_audit_log('program_add', 'workout_programs', $newId);
      $ok = 'Программа добавлена.';
    } else {
      admin_program_update($id, $name, $level, $description ?: null);
      admin_audit_log('program_update', 'workout_programs', $id);
      $ok = 'Программа обновлена.';
    }
  }
}

$programs = admin_programs_list();
$editing = $action === 'edit' && $id ? array_filter($programs, fn($p) => $p['id'] == $id)[0] ?? null : null;
render('admin/programs', compact('pageTitle', 'current', 'programs', 'editing', 'error', 'ok'));
