<div class="auth-shell auth-shell--modern">
  <div class="auth-left">
    <section class="auth-card" aria-labelledby="register-title">
      <p class="auth-eyebrow">LDO Platform</p>
      <h1 id="register-title" class="auth-title">Создайте аккаунт 🚀</h1>
      <p class="auth-lead">Присоединяйтесь к LDO и начните системно вести свой прогресс уже сегодня.</p>

      <?php if ($error ?? null): ?>
      <div class="flash err auth-alert"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= url('register') ?>" class="form auth-form">
        <?= csrf_field() ?>
        <label>
          Email
          <input type="email" name="email" required autocomplete="email" placeholder="user@example.com"
                 value="<?= e($_POST['email'] ?? '') ?>">
        </label>
        <label>
          Пароль <span class="help">не менее 8 символов</span>
          <input type="password" name="password" required autocomplete="new-password" minlength="8" placeholder="Минимум 8 символов">
        </label>
        <label>
          Повторите пароль
          <input type="password" name="password2" required autocomplete="new-password" minlength="8" placeholder="Повторите пароль">
        </label>
        <label>
          CAPTCHA: <?= e($captchaQuestion ?? '') ?>
          <input type="text" name="captcha" required inputmode="numeric" placeholder="Введите ответ">
        </label>

        <button type="submit" class="btn btn-primary auth-submit">Зарегистрироваться</button>
      </form>

      <div class="auth-links">
        <span>Уже есть аккаунт?</span>
        <a href="<?= url('login') ?>" class="auth-link">Войти</a>
      </div>
    </section>
  </div>

  <aside class="auth-right auth-right--register">
    <div class="slogan slogan--modern">
      <div class="big">LDO — Let's Do It.</div>
      <div class="sub">Начните управлять тренировками, задачами и привычками без хаоса.</div>
      <ul class="auth-points">
        <li>Единый дашборд для ваших целей</li>
        <li>Гибкие отчёты и визуализация прогресса</li>
        <li>Поддержка долгосрочной дисциплины</li>
      </ul>
    </div>
  </aside>
</div>
