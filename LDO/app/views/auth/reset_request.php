<div class="auth-shell">
  <div class="auth-left">
    <div style="width:100%;max-width:360px">
      <h1 style="margin:0 0 24px 0;font-size:24px">Восстановление пароля</h1>
      <?php if ($error ?? null): ?>
      <div class="flash err" style="margin-bottom:16px"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= url('password-reset') ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Email
          <input type="email" name="email" required placeholder="user@example.com" value="<?= e($_POST['email'] ?? '') ?>">
        </label>

        <label>
          CAPTCHA: <?= e($captchaQuestion ?? '') ?>
          <input type="text" name="captcha" required inputmode="numeric" placeholder="Введите ответ">
        </label>
        <button type="submit" class="btn btn-primary">Отправить ссылку</button>
      </form>
      <?php if (defined('DEV_SHOW_RESET_LINK') && DEV_SHOW_RESET_LINK && ($resetLink ?? null)): ?>
      <div class="flash" style="margin-top:16px;word-break:break-all">
        <strong>Dev:</strong> <a href="<?= e($resetLink) ?>" style="color:var(--accent)"><?= e($resetLink) ?></a>
      </div>
      <?php endif; ?>
      <p class="muted" style="margin-top:16px"><a href="<?= url('login') ?>" style="color:var(--accent)">← Назад к входу</a></p>
    </div>
  </div>
  <div class="auth-right">
    <div class="slogan">
      <div class="big">LDO</div>
      <div class="sub">Ссылка действительна 1 час.</div>
    </div>
  </div>
</div>
