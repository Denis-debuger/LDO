<div class="auth-shell">
  <div class="auth-left">
    <div style="width:100%;max-width:360px">
      <h1 style="margin:0 0 24px 0;font-size:24px">Регистрация</h1>
      <?php if ($error ?? null): ?>
      <div class="flash err" style="margin-bottom:16px"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= url('register') ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Email
          <input type="email" name="email" required autocomplete="email" placeholder="user@example.com"
                 value="<?= e($_POST['email'] ?? '') ?>">
        </label>
        <label>
          Пароль <span class="help">не менее 8 символов</span>
          <input type="password" name="password" required autocomplete="new-password" minlength="8">
        </label>
        <label>
          Повторите пароль
          <input type="password" name="password2" required autocomplete="new-password" minlength="8">
        </label>

        <label>
          CAPTCHA: <?= e($captchaQuestion ?? '') ?>
          <input type="text" name="captcha" required inputmode="numeric" placeholder="Введите ответ">
        </label>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
      </form>
      <p class="muted" style="margin-top:16px;font-size:14px">
        Уже есть аккаунт? <a href="<?= url('login') ?>" style="color:var(--accent)">Войти</a>
      </p>
    </div>
  </div>
  <div class="auth-right">
    <div class="slogan">
      <div class="big">LDO — Let's Do It.</div>
      <div class="sub">Начните контролировать прогресс уже сегодня.</div>
    </div>
  </div>
</div>
