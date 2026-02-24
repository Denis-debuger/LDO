<div class="auth-shell">
  <div class="auth-left">
    <div style="width:100%;max-width:360px">
      <h1 style="margin:0 0 24px 0;font-size:24px">Новый пароль</h1>
      <?php if ($error ?? null): ?>
      <div class="flash err" style="margin-bottom:16px"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= url('password-reset', ['token' => $token]) ?>" class="form">
        <?= csrf_field() ?>
        <label>
          Новый пароль <span class="help">не менее 8 символов</span>
          <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>
        <label>
          Повторите пароль
          <input type="password" name="password2" required minlength="8" autocomplete="new-password">
        </label>
        <button type="submit" class="btn btn-primary">Сохранить пароль</button>
      </form>
      <p class="muted" style="margin-top:16px"><a href="<?= url('login') ?>" style="color:var(--accent)">← Назад к входу</a></p>
    </div>
  </div>
  <div class="auth-right">
    <div class="slogan">
      <div class="big">LDO — Let's Do It.</div>
    </div>
  </div>
</div>
