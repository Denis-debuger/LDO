<div class="container">
  <h1 class="page-head">Импорт / экспорт / резервное копирование</h1>
  <div class="grid grid-2">
    <div class="card"><div class="card-body"><h2 class="card-title">Экспорт JSON</h2><form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="action" value="export_json"><label>Таблица<select name="table"><option>food_items</option><option>content_categories</option><option>moderation_items</option><option>users</option></select></label><button class="btn btn-primary">Скачать JSON</button></form><hr style="border-color:rgba(255,255,255,.1)"><form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="action" value="backup_sql"><button class="btn btn-ghost">Скачать SQL backup</button></form></div></div>
    <div class="card"><div class="card-body"><h2 class="card-title">Импорт продуктов</h2><form method="post" enctype="multipart/form-data" class="form"><?= csrf_field() ?><input type="hidden" name="action" value="import_food_json"><label>JSON файл<input type="file" name="import_file" accept="application/json"></label><button class="btn btn-primary">Импортировать</button></form><p class="muted">Поддерживается импорт массива `rows` с полями `name`, `calories_per_100g`, `protein_per_100g`, `fat_per_100g`, `carbs_per_100g`.</p></div></div>
  </div>
</div>
