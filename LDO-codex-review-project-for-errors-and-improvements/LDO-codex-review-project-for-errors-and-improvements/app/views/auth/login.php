<div class="auth-shell">
  <div class="auth-left">
    <div style="width:100%;max-width:360px">
      <h1 style="margin:0 0 24px 0;font-size:24px">Вход в LDO</h1>
      <?php if ($error ?? null): ?>
      <div class="flash err" style="margin-bottom:16px"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= url('login') ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Email
          <input type="email" name="email" required autocomplete="email" placeholder="user@example.com"
                 value="<?= e($_POST['email'] ?? '') ?>">
        </label>
        <label>
          Пароль
          <input type="password" name="password" required autocomplete="current-password" minlength="8" placeholder="Минимум 8 символов">
        </label>

        <label>
          CAPTCHA: <?= e($captchaQuestion ?? '') ?>
          <input type="text" name="captcha" required inputmode="numeric" placeholder="Введите ответ">
        </label>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <button type="submit" class="btn btn-primary">Войти</button>
          <a href="<?= url('password-reset') ?>" class="btn btn-ghost">Забыли пароль?</a>
        </div>
      </form>
    </div>
  </div>
  <div class="auth-right">
    <div class="slogan">
      <div class="big">LDO — Let's Do It.</div>
      <div class="sub">Дисциплина. Прогресс. Результат.</div>
    </div>
  </div>
</div>
