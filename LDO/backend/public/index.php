<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'ok' => true,
  'service' => 'ldo-backend',
  'api' => [
    'health' => '/api.php?endpoint=health',
    'profile' => '/api.php?endpoint=profile',
  ],
]);
