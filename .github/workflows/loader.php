<?php
// loader.php
require_once 'auth.php';

$settings = loadJson('settings.json');
$version = $settings['loader_version'] ?? '1.0.0';

$key = $_GET['key'] ?? $_POST['key'] ?? '';

if (!$key) {
    die("Key girilmedi.");
}

$keys = loadJson('keys.json');

if (!isset($keys[$key])) {
    die("Geçersiz key.");
}

$keyData = $keys[$key];

if ($keyData['banned']) {
    die("Bu key banlandı.");
}

$expiry = $keyData['expiry'];
if ($expiry !== 'lifetime' && strtotime($expiry) < time()) {
    die("Key süresi doldu.");
}

echo "Loader Version: $version\n";
echo "Hoş geldin! Key aktif.\n";
// Buraya programını başlatabilirsin
// exec("yourprogram.exe"); gibi
?>