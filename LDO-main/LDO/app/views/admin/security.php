<div class="container">
  <h1 class="page-head">Панель безопасности</h1>
  <div class="kpi">
    <div class="item"><span class="num"><?= (int)$stats['login_success'] ?></span> успешных входов</div>
    <div class="item"><span class="num"><?= (int)$stats['login_failed'] ?></span> неуспешных входов</div>
    <div class="item"><span class="num"><?= (int)$stats['login_blocked'] ?></span> попыток в блок</div>
    <div class="item"><span class="num"><?= (int)$stats['password_reset_request'] ?></span> запросов сброса</div>
    <div class="item"><span class="num"><?= (int)$stats['blocked_users'] ?></span> заблокировано</div>
  </div>
  <div class="grid grid-2 section-spacer">
    <div class="card"><div class="card-body table-wrap"><h2 class="card-title">События входа и сброса</h2><table><thead><tr><th>Время</th><th>Событие</th><th>Email</th><th>IP</th></tr></thead><tbody><?php foreach($events as $e): ?><tr><td><?= e($e['created_at']) ?></td><td><?= e($e['event_type']) ?></td><td><?= e($e['email'] ?: ($e['user_email'] ?? '—')) ?></td><td><?= e($e['ip_address']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
    <div class="card"><div class="card-body table-wrap"><h2 class="card-title">Логи действий админов</h2><table><thead><tr><th>Время</th><th>Админ</th><th>Действие</th><th>Сущность</th></tr></thead><tbody><?php foreach($auditLogs as $l): ?><tr><td><?= e($l['created_at']) ?></td><td><?= e($l['admin_email'] ?? '—') ?></td><td><?= e($l['action']) ?></td><td><?= e($l['target_type']) ?> #<?= e((string)($l['target_id'] ?? '')) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
  </div>
</div>
