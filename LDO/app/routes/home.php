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

if (empty($homeMedia)) {
  $homeMedia = [
    [
      'type' => 'image',
      'src' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1400&q=80',
      'alt' => 'Фитнес-зал с атлетами на тренировке',
      'caption' => 'Комьюнити LDO',
    ],
    [
      'type' => 'image',
      'src' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=900&q=80',
      'alt' => 'Полезный сбалансированный рацион',
      'caption' => 'Питание без крайностей',
    ],
    [
      'type' => 'image',
      'src' => 'https://images.unsplash.com/photo-1549476464-37392f717541?auto=format&fit=crop&w=900&q=80',
      'alt' => 'План тренировки в приложении',
      'caption' => 'Отслеживайте прогресс каждый день',
    ],
  ];
}
render('home', compact('pageTitle', 'current', 'homeMedia'));
