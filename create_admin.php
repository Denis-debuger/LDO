<?php
/**
 * Скрипт создания первого администратора
 * 
 * Использование:
 * 1. Откройте в браузере: http://localhost/LDO web-site/create_admin.php
 * 2. Или запустите через командную строку: php create_admin.php
 */

require_once __DIR__ . '/app/bootstrap.php';

$email = 'admin@ldo.local';
$password = 'admin123';

if (php_sapi_name() !== 'cli') {
  // Веб-интерфейс
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    
    if (!valid_email($email)) {
      $error = 'Некорректный email.';
    } elseif (!valid_password($password)) {
      $error = 'Пароль должен быть не короче 8 символов.';
    } else {
      $hash = password_hash($password, PASSWORD_BCRYPT);
      try {
        $stmt = db()->prepare('INSERT INTO users (email, password_hash, role, is_blocked, created_at) VALUES (?,?,?,?,?)');
        $stmt->execute([strtolower($email), $hash, 'admin', 0, now_dt()]);
        $userId = (int)db()->lastInsertId();
        
        $stmt2 = db()->prepare('INSERT INTO user_profiles (user_id, created_at, updated_at) VALUES (?,?,?)');
        $stmt2->execute([$userId, now_dt(), now_dt()]);
        
        $success = true;
      } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
          $error = 'Пользователь с таким email уже существует.';
        } else {
          $error = 'Ошибка: ' . $e->getMessage();
        }
      }
    }
  }
  ?>
  <!DOCTYPE html>
  <html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Создание администратора — LDO</title>
    <link rel="stylesheet" href="<?= e(asset_url('css/ldo.css')) ?>">
  </head>
  <body>
    <div class="container" style="max-width:480px;margin:80px auto">
      <h1 style="margin:0 0 24px 0">Создание администратора</h1>
      <?php if (isset($success)): ?>
      <div class="flash ok">
        Администратор создан! Email: <?= e($email) ?><br>
        <a href="<?= url('login') ?>" style="color:var(--accent);margin-top:8px;display:inline-block">Войти →</a>
      </div>
      <?php else: ?>
      <?php if (isset($error)): ?>
      <div class="flash err"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" class="form">
        <?= csrf_field() ?>
        <label>
          Email
          <input type="email" name="email" value="<?= e($email) ?>" required>
        </label>
        <label>
          Пароль (минимум 8 символов)
          <input type="password" name="password" value="<?= e($password) ?>" required minlength="8">
        </label>
        <button type="submit" class="btn btn-primary">Создать администратора</button>
      </form>
      <?php endif; ?>
    </div>
  </body>
  </html>
  <?php
  exit;
}

// CLI режим
echo "Создание администратора...\n";
echo "Email: $email\n";
echo "Пароль: $password\n\n";

$hash = password_hash($password, PASSWORD_BCRYPT);
try {
  $stmt = db()->prepare('INSERT INTO users (email, password_hash, role, is_blocked, created_at) VALUES (?,?,?,?,?)');
  $stmt->execute([strtolower($email), $hash, 'admin', 0, now_dt()]);
  $userId = (int)db()->lastInsertId();
  
  $stmt2 = db()->prepare('INSERT INTO user_profiles (user_id, created_at, updated_at) VALUES (?,?,?)');
  $stmt2->execute([$userId, now_dt(), now_dt()]);
  
  echo "✓ Администратор создан! ID: $userId\n";
  echo "Войдите с email: $email\n";
} catch (PDOException $e) {
  echo "✗ Ошибка: " . $e->getMessage() . "\n";
  exit(1);
}
