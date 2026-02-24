<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$route = trim((string)($_GET['r'] ?? ''));
if ($route === '') $route = 'home';

$routes = [
  'home'       => ['handler' => 'home',      'auth' => false],
  'login'      => ['handler' => 'auth_login', 'auth' => false],
  'register'   => ['handler' => 'auth_register', 'auth' => false],
  'logout'     => ['handler' => 'auth_logout', 'auth' => true],
  'profile'    => ['handler' => 'profile',   'auth' => true],
  'kbju'       => ['handler' => 'kbju',      'auth' => true],
  'programs'   => ['handler' => 'programs',  'auth' => true],
  'exercises'  => ['handler' => 'exercises', 'auth' => true],
  'diary'      => ['handler' => 'diary',    'auth' => true],
  'diary-add'  => ['handler' => 'diary_add', 'auth' => true],
  'diary-view' => ['handler' => 'diary_view', 'auth' => true],
  'diary-edit' => ['handler' => 'diary_edit', 'auth' => true],
  'diary-delete' => ['handler' => 'diary_delete', 'auth' => true],
  'diary-exercise-add' => ['handler' => 'diary_exercise_add', 'auth' => true],
  'diary-exercise-delete' => ['handler' => 'diary_exercise_delete', 'auth' => true],
  'diary-meal-add' => ['handler' => 'diary_meal_add', 'auth' => true],
  'diary-meal-delete' => ['handler' => 'diary_meal_delete', 'auth' => true],
  'progress'   => ['handler' => 'progress', 'auth' => true],
  'articles'   => ['handler' => 'articles', 'auth' => false],
  'article'    => ['handler' => 'article_view', 'auth' => false],
  'password-reset' => ['handler' => 'password_reset', 'auth' => false],
  'admin'      => ['handler' => 'admin',    'auth' => 'admin'],
  'admin-users' => ['handler' => 'admin_users', 'auth' => 'admin'],
  'admin-programs' => ['handler' => 'admin_programs', 'auth' => 'admin'],
  'admin-exercises' => ['handler' => 'admin_exercises', 'auth' => 'admin'],
  'admin-articles' => ['handler' => 'admin_articles', 'auth' => 'admin'],
  'admin-article-edit' => ['handler' => 'admin_article_edit', 'auth' => 'admin'],
  'admin-article-add' => ['handler' => 'admin_article_add', 'auth' => 'admin'],
];

if (!isset($routes[$route])) {
  http_response_code(404);
  echo 'Страница не найдена.';
  exit;
}

$cfg = $routes[$route];
if ($cfg['auth'] === true) require_login();
if ($cfg['auth'] === 'admin') require_admin();

$handler = $cfg['handler'];
if (is_string($handler)) {
  require __DIR__ . '/app/routes/' . $handler . '.php';
} else {
  $handler();
}
