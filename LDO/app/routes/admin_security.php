<?php
declare(strict_types=1);

$pageTitle = 'Панель безопасности';
$current = 'admin';
$stats = security_panel_stats(14);
$events = db()->query('SELECT e.*, u.email AS user_email FROM auth_security_events e LEFT JOIN users u ON u.id=e.user_id ORDER BY e.created_at DESC LIMIT 100')->fetchAll();
$auditLogs = admin_audit_recent(100);

render('admin/security', compact('pageTitle', 'current', 'stats', 'events', 'auditLogs'));
