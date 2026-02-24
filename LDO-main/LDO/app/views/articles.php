<div class="container">
  <h1 style="margin:0 0 8px 0">Статьи</h1>
  <p class="muted" style="margin-bottom:24px">Образовательный контент о питании и тренировках</p>

  <?php if (empty($articles)): ?>
  <div class="card card-accent">
    <div class="card-body">
      <p class="muted">Статьи пока не опубликованы.</p>
    </div>
  </div>
  <?php else: ?>
  <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr))">
    <?php foreach ($articles as $a): ?>
    <a href="<?= url('article', ['slug' => $a['slug']]) ?>" class="card card-accent" style="display:block">
      <div class="card-body">
        <h2 class="card-title"><?= e($a['title']) ?></h2>
        <?php if (!empty($a['category_name'])): ?>
        <span class="pill" style="margin-bottom:8px;display:inline-block"><?= e($a['category_name']) ?></span>
        <?php endif; ?>
        <p class="muted" style="font-size:14px"><?= e(mb_substr($a['excerpt'] ?? $a['title'], 0, 120)) ?>…</p>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
