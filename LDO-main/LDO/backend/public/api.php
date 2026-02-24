<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if (request_method() === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$endpoint = (string)($_GET['endpoint'] ?? 'health');

if ($endpoint === 'health') {
  echo json_encode(['ok' => true, 'service' => 'ldo-backend', 'time' => now_dt()]);
  exit;
}

if ($endpoint === 'profile') {
  if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
  }

  $userId = auth_user_id();
  if (!$userId) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
  }

  if (request_method() === 'GET') {
    echo json_encode(['ok' => true, 'profile' => profile_get($userId)]);
    exit;
  }

  if (request_method() === 'POST') {
    $body = json_decode((string)file_get_contents('php://input'), true) ?: [];
    profile_update($userId, [
      'height_cm' => isset($body['height_cm']) ? (int)$body['height_cm'] : null,
      'weight_kg' => isset($body['weight_kg']) ? (float)$body['weight_kg'] : null,
      'age' => isset($body['age']) ? (int)$body['age'] : null,
      'gender' => in_array(($body['gender'] ?? ''), ['male', 'female'], true) ? $body['gender'] : null,
      'activity_level' => in_array(($body['activity_level'] ?? ''), ['sedentary', 'light', 'moderate', 'active', 'very'], true) ? $body['activity_level'] : 'moderate',
      'goal' => in_array(($body['goal'] ?? ''), ['maintain', 'lose', 'gain'], true) ? $body['goal'] : 'maintain',
    ]);
    echo json_encode(['ok' => true, 'profile' => profile_get($userId)]);
    exit;
  }
}

http_response_code(404);
echo json_encode(['ok' => false, 'error' => 'Not found']);
