<div class="auth-shell auth-shell--modern">
  <div class="auth-left">
    <section class="auth-card" aria-labelledby="login-title">
      <p class="auth-eyebrow">LDO Platform</p>
      <h1 id="login-title" class="auth-title">С возвращением 👋</h1>
      <p class="auth-lead">Войдите, чтобы продолжить работу над целями, привычками и прогрессом.</p>

      <?php if ($error ?? null): ?>
      <div class="flash err auth-alert"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= url('login') ?>" class="form auth-form">
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

        <button type="submit" class="btn btn-primary auth-submit">Войти</button>
      </form>

      <div class="auth-links">
        <a href="<?= url('password-reset') ?>" class="auth-link">Забыли пароль?</a>
        <span class="auth-dot">•</span>
        <a href="<?= url('register') ?>" class="auth-link">Создать аккаунт</a>
      </div>
    </section>
  </div>

  <aside class="auth-right auth-right--login">
    <div class="slogan slogan--modern">
      <div class="big">LDO — Let's Do It.</div>
      <div class="sub">Дисциплина, системность и результат в одном месте.</div>
      <ul class="auth-points">
        <li>Планируйте тренировки и питание</li>
        <li>Отслеживайте прогресс в реальном времени</li>
        <li>Двигайтесь к цели каждый день</li>
      </ul>
    </div>
  </aside>
</div>
