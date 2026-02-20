<?php
declare(strict_types=1);

$pageTitle = 'Главная';
$current = 'home';
$homeMedia = [];
$configPath = dirname(__DIR__, 2) . '/config/home_media.php';
if (is_file($configPath)) {
  require $configPath;
  $homeMedia = $HOME_MEDIA ?? [];
}
render('home', compact('pageTitle', 'current', 'homeMedia'));
