<div class="container">
  <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?= url('admin-articles') ?>" class="btn btn-ghost">← Статьи</a>
    <h1 style="margin:0"><?= isset($article) ? 'Редактировать статью' : 'Добавить статью' ?></h1>
  </div>

  <?php if ($error ?? null): ?>
  <div class="flash err"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= isset($article) ? url('admin-article-edit', ['id' => $article['id']]) : url('admin-article-add') ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    
    <div class="card card-accent">
      <div class="card-body">
        <h2 class="card-title">Основная информация</h2>
        <label>
          Заголовок *
          <input type="text" name="title" value="<?= e($article['title'] ?? '') ?>" required>
        </label>
        <label>
          URL (slug)
          <input type="text" name="slug" value="<?= e($article['slug'] ?? '') ?>" placeholder="Автоматически из заголовка">
        </label>
        <label>
          Категория
          <select name="category_id">
            <option value="">—</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($article['category_id'] ?? null) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>
          Краткое описание
          <textarea name="excerpt" rows="2"><?= e($article['excerpt'] ?? '') ?></textarea>
        </label>
        <label>
          Текст статьи *
          <textarea name="body" rows="12" required><?= e($article['body'] ?? '') ?></textarea>
        </label>
        <label style="display:flex;align-items:center;gap:8px">
          <input type="checkbox" name="published" value="1" <?= ($article['published'] ?? 0) ? 'checked' : '' ?>>
          <span>Опубликовать</span>
        </label>
      </div>
    </div>

    <div class="card card-accent" style="margin-top:24px">
      <div class="card-body">
        <h2 class="card-title">Медиа</h2>
        
        <label>
          Обложка (изображение)
          <?php if (!empty($article['cover_image'])): ?>
          <div style="margin:8px 0">
            <img src="<?= e(upload_url($article['cover_image'])) ?>" alt="Обложка" style="max-width:300px;border-radius:8px;border:var(--border)">
            <label style="display:flex;align-items:center;gap:8px;margin-top:8px">
              <input type="checkbox" name="remove_cover" value="1">
              <span>Удалить обложку</span>
            </label>
          </div>
          <?php endif; ?>
          <input type="file" name="cover_image" accept="image/jpeg,image/png,image/gif,image/webp">
          <span class="help">JPG, PNG, GIF, WebP, макс. 10 МБ</span>
        </label>

        <label>
          Видео
          <?php if (!empty($article['video_url'])): ?>
          <div style="margin:8px 0">
            <?php if (strpos($article['video_url'], 'http') === 0): ?>
            <a href="<?= e($article['video_url']) ?>" target="_blank" style="color:var(--accent)"><?= e($article['video_url']) ?></a>
            <?php else: ?>
            <video controls style="max-width:400px;border-radius:8px">
              <source src="<?= e(upload_url($article['video_url'])) ?>" type="video/mp4">
            </video>
            <?php endif; ?>
            <label style="display:flex;align-items:center;gap:8px;margin-top:8px">
              <input type="checkbox" name="remove_video" value="1">
              <span>Удалить видео</span>
            </label>
          </div>
          <?php endif; ?>
          <input type="file" name="video" accept="video/mp4,video/webm">
          <span class="help">MP4, WebM, макс. 10 МБ</span>
          <p class="help" style="margin-top:8px">Или укажите URL видео:</p>
          <input type="url" name="video_url" value="<?= e((strpos($article['video_url'] ?? '', 'http') === 0) ? ($article['video_url'] ?? '') : '') ?>" placeholder="https://youtube.com/watch?v=...">
        </label>
      </div>
    </div>

    <div style="margin-top:24px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary"><?= isset($article) ? 'Сохранить' : 'Создать' ?></button>
      <a href="<?= url('admin-articles') ?>" class="btn btn-ghost">Отмена</a>
    </div>
  </form>
</div>
