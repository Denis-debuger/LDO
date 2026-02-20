<div class="container">
  <article class="card card-accent">
    <div class="card-body">
      <?php if (!empty($article['category_name'])): ?>
      <span class="pill" style="margin-bottom:12px;display:inline-block"><?= e($article['category_name']) ?></span>
      <?php endif; ?>
      
      <?php if (!empty($article['cover_image'])): ?>
      <div style="margin-bottom:20px">
        <img src="<?= e(upload_url($article['cover_image'])) ?>" alt="Обложка" style="width:100%;max-height:400px;object-fit:cover;border-radius:12px;border:var(--border)">
      </div>
      <?php endif; ?>
      
      <h1 style="margin:0 0 12px 0"><?= e($article['title']) ?></h1>
      <p class="muted" style="font-size:14px;margin-bottom:20px"><?= e($article['created_at']) ?></p>
      
      <?php if (!empty($article['excerpt'])): ?>
      <p style="font-size:18px;color:var(--muted);margin-bottom:24px;font-weight:500"><?= e($article['excerpt']) ?></p>
      <?php endif; ?>
      
      <?php if (!empty($article['video_url'])): ?>
      <div style="margin-bottom:24px">
        <?php if (strpos($article['video_url'], 'http') === 0): ?>
        <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;background:#000">
          <iframe src="<?= e($article['video_url']) ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" allowfullscreen></iframe>
        </div>
        <?php else: ?>
        <video controls style="width:100%;border-radius:12px;background:#000">
          <source src="<?= e(upload_url($article['video_url'])) ?>" type="video/mp4">
        </video>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      
      <div style="line-height:1.7">
        <?= nl2br(e($article['body'])) ?>
      </div>
      <p style="margin-top:24px"><a href="<?= url('articles') ?>" style="color:var(--accent)">← К списку статей</a></p>
    </div>
  </article>
</div>
