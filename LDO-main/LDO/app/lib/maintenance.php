<?php
declare(strict_types=1);

function export_table_json(string $table): string
{
  $allowed = ['users','food_items','article_categories','exercise_categories','content_categories','moderation_items'];
  if (!in_array($table, $allowed, true)) throw new RuntimeException('Недопустимая таблица');
  $rows = db()->query("SELECT * FROM {$table}")->fetchAll();
  return json_encode(['table'=>$table,'rows'=>$rows,'exported_at'=>now_dt()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function import_food_items_json(string $json): int
{
  $data = json_decode($json, true);
  if (!is_array($data) || !isset($data['rows']) || !is_array($data['rows'])) return 0;
  $count = 0;
  $stmt = db()->prepare('INSERT INTO food_items (category_id,name,calories_per_100g,protein_per_100g,fat_per_100g,carbs_per_100g,created_at) VALUES (?,?,?,?,?,?,?)');
  foreach ($data['rows'] as $r) {
    if (empty($r['name'])) continue;
    $stmt->execute([
      isset($r['category_id']) ? (int)$r['category_id'] : null,
      (string)$r['name'],
      (float)($r['calories_per_100g'] ?? 0),
      (float)($r['protein_per_100g'] ?? 0),
      (float)($r['fat_per_100g'] ?? 0),
      (float)($r['carbs_per_100g'] ?? 0),
      now_dt(),
    ]);
    $count++;
  }
  return $count;
}

function backup_sql_dump(): string
{
  $tables = ['users','user_profiles','articles','food_items','moderation_items','admin_audit_logs','auth_security_events'];
  $out = "-- LDO backup generated at " . now_dt() . "\n\n";
  foreach ($tables as $table) {
    $rows = db()->query("SELECT * FROM {$table}")->fetchAll();
    foreach ($rows as $row) {
      $cols = array_keys($row);
      $vals = array_map(fn($v) => $v === null ? 'NULL' : db()->quote((string)$v), array_values($row));
      $out .= "INSERT INTO {$table} (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n";
    }
    $out .= "\n";
  }
  return $out;
}
