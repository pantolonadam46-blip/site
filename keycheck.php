<?php
// keycheck.php
require_once 'auth.php';

header('Content-Type: application/json');

// Key parametresini al
$key = $_GET['key'] ?? $_POST['key'] ?? '';

// Key yoksa hata
if (empty($key)) {
    echo json_encode([
        'success' => false,
        'message' => 'Key belirtilmedi.'
    ]);
    exit;
}

$keys = loadJson('keys.json');

// Key yoksa
if (!isset($keys[$key])) {
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz key.'
    ]);
    exit;
}

$keyData = $keys[$key];

// Ban kontrolü
if ($keyData['banned']) {
    echo json_encode([
        'success' => false,
        'message' => 'Bu key banlandı.'
    ]);
    exit;
}

// Süre kontrolü
$expiry = $keyData['expiry'];
if ($expiry !== 'lifetime' && strtotime($expiry) < time()) {
    echo json_encode([
        'success' => false,
        'message' => 'Key süresi doldu.',
        'expired_at' => $expiry
    ]);
    exit;
}

// Başarılı
echo json_encode([
    'success' => true,
    'message' => 'Key aktif.',
    'key' => $key,
    'created' => $keyData['created'],
    'expiry' => $expiry,
    'loader_version' => $settings['loader_version'] ?? '1.0.0'
]);
?>