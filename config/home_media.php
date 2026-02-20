<?php
/**
 * Конфигурация медиа-блоков на главной странице.
 * Добавьте свои изображения, видео или GIF в массив $HOME_MEDIA.
 *
 * Формат каждого элемента:
 *   'type' => 'image' | 'video' | 'gif'
 *   'src'  => путь к файлу (например: uploads/home/hero.jpg или assets/images/home/banner.png)
 *   'alt'  => описание (для изображений)
 *   'caption' => подпись под блоком (необязательно)
 *
 * Видео: можно указать локальный файл (uploads/home/video.mp4) или URL (https://...)
 * GIF — как обычное изображение.
 */

$HOME_MEDIA = [
  // Блок 1: Главный баннер (hero) — фон заголовка. Можно использовать GIF, изображение или видео
  // Для GIF: 'type' => 'gif' или просто укажите путь к .gif файлу
  [
    'type' => 'gif',  // или 'image' для обычного изображения, 'video' для видео
    'src' => 'assets/images/home/hero.gif',  // или uploads/home/hero.gif
    'alt' => 'LDO — Let\'s Do It',
    'caption' => null,
  ],
  // Блок 2: Дополнительное изображение или видео
  [
    'type' => 'image',
    'src' => 'assets/images/home/block2.jpg',
    'alt' => 'Тренировки',
    'caption' => null,
  ],
  // Блок 3
  [
    'type' => 'image',
    'src' => 'assets/images/home/block3.gif',
    'alt' => 'Прогресс',
    'caption' => null,
  ],
];
