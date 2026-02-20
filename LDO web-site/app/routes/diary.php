<?php
declare(strict_types=1);

$pageTitle = 'Дневник тренировок';
$current = 'diary';
$userId = auth_user_id();
$logs = diary_logs($userId);

render('diary', compact('pageTitle', 'current', 'logs'));
