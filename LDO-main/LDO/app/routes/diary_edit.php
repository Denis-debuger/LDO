<?php
declare(strict_types=1);

$id = (int)($_GET['id'] ?? $_POST['log_id'] ?? 0);
$userId = auth_user_id();
$log = $id ? diary_log_get($id, $userId) : null;

if (!$log) {
  flash_set('err', 'Запись не найдена.');
  redirect('diary');
}

if (is_post()) {
  csrf_validate();
  $date = (string)($_POST['logged_at'] ?? $log['logged_at']);
  $notes = clean_str((string)($_POST['notes'] ?? ''));
  $durationMin = isset($_POST['duration_min']) && $_POST['duration_min'] !== '' ? (int)$_POST['duration_min'] : null;
  $feeling = in_array($_POST['feeling'] ?? '', ['excellent', 'good', 'normal', 'tired', 'exhausted']) ? $_POST['feeling'] : null;
  $bodyWeight = isset($_POST['body_weight']) && $_POST['body_weight'] !== '' ? (float)str_replace(',', '.', $_POST['body_weight']) : null;
  diary_log_update($id, $userId, $date, $notes ?: null, $durationMin, $feeling, $bodyWeight);
  flash_set('ok', 'Тренировка обновлена.');
  redirect('diary-view', ['id' => $id]);
}

$pageTitle = 'Редактировать тренировку';
$current = 'diary';
render('diary_edit', compact('pageTitle', 'current', 'log'));
