<div class="container">
  <h1 class="page-head">Справочник продуктов и категории контента</h1>
  <?php if ($error): ?><div class="flash err"><?= e($error) ?></div><?php endif; ?>
  <div class="grid grid-2">
    <div class="card"><div class="card-body">
      <h2 class="card-title">Добавить продукт</h2>
      <form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="form" value="food"><label>Название<input name="name"></label><div class="row"><label>Категория<select name="category_id"><option value="">—</option><?php foreach($foodCategories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></label><label>Калории/100г<input name="calories" type="number" step="0.1"></label></div><div class="row"><label>Белки<input name="protein" type="number" step="0.1"></label><label>Жиры<input name="fat" type="number" step="0.1"></label></div><label>Углеводы<input name="carbs" type="number" step="0.1"></label><button class="btn btn-primary">Добавить</button></form>
    </div></div>
    <div class="card"><div class="card-body">
      <h2 class="card-title">Добавить категорию</h2>
      <form method="post" class="form" style="margin-bottom:16px"><?= csrf_field() ?><input type="hidden" name="form" value="food_category"><label>Категория продуктов<input name="name"></label><label>Порядок<input type="number" name="sort_order" value="0"></label><button class="btn btn-ghost">Добавить категорию продуктов</button></form>
      <form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="form" value="content_category"><label>Категория контента<input name="name"></label><div class="row"><label>Тип<select name="type"><option value="general">general</option><option value="article">article</option><option value="video">video</option></select></label><label>Порядок<input type="number" name="sort_order" value="0"></label></div><button class="btn btn-ghost">Добавить категорию контента</button></form>
    </div></div>
  </div>
  <div class="card section-spacer"><div class="card-body table-wrap"><h2 class="card-title">Продукты</h2><table><thead><tr><th>ID</th><th>Название</th><th>Категория</th><th>Ккал</th><th>Б/Ж/У</th></tr></thead><tbody><?php foreach($foodItems as $f): ?><tr><td><?= (int)$f['id'] ?></td><td><?= e($f['name']) ?></td><td><?= e($f['category_name'] ?? '—') ?></td><td><?= e((string)$f['calories_per_100g']) ?></td><td><?= e((string)$f['protein_per_100g']) ?>/<?= e((string)$f['fat_per_100g']) ?>/<?= e((string)$f['carbs_per_100g']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
