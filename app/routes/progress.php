<?php
declare(strict_types=1);

$pageTitle = 'Прогресс';
$current = 'progress';
$userId = auth_user_id();
$entries = [];
$stmt = db()->prepare('SELECT * FROM progress_entries WHERE user_id = ? ORDER BY logged_at DESC LIMIT 30');
$stmt->execute([$userId]);
$entries = $stmt->fetchAll();
$weightLogs = weight_log_list($userId, 90);

render('progress', compact('pageTitle', 'current', 'entries', 'weightLogs'));
