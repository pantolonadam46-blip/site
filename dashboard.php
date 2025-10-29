<?php
// dashboard.php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: admin.php');
    exit;
}

require_once 'auth.php';

$keys = loadJson('keys.json');
$settings = loadJson('settings.json');
$message = '';

// Key Oluştur
if (isset($_POST['create'])) {
    $duration = $_POST['duration'];
    $key = generateKey();
    
    $expiry = 'lifetime';
    if ($duration !== 'lifetime') {
        $expiry = date('Y-m-d H:i:s', strtotime("+$duration"));
    }

    $keys[$key] = [
        'created' => date('Y-m-d H:i:s'),
        'expiry' => $expiry,
        'banned' => false
    ];
    saveJson('keys.json', $keys);
    $message = "Key oluşturuldu: <strong>$key</strong>";
}

// Key Sil
if (isset($_GET['delete'])) {
    $k = $_GET['delete'];
    if (isset($keys[$k])) {
        unset($keys[$k]);
        saveJson('keys.json', $keys);
        $message = "Key silindi.";
    }
}

// Key Banla / Ban Kaldır
if (isset($_GET['ban'])) {
    $k = $_GET['ban'];
    if (isset($keys[$k])) {
        $keys[$k]['banned'] = !$keys[$k]['banned'];
        saveJson('keys.json', $keys);
        $message = "Key durumu güncellendi.";
    }
}

// Loader Versiyon Güncelle
if (isset($_POST['update_version'])) {
    $settings['loader_version'] = $_POST['loader_version'];
    saveJson('settings.json', $settings);
    $message = "Loader versiyonu güncellendi.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>KeyAuth Dashboard</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; margin: 20px; background: #f4f4f4; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, button { padding: 8px; margin: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
        .btn { padding: 6px 12px; text-decoration: none; color: white; border-radius: 4px; }
        .ban { background: #dc3545; }
        .unban { background: #28a745; }
        .delete { background: #ffc107; color: black; }
        .msg { padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>KeyAuth Dashboard</h1>
    <a href="admin.php?logout=1" style="float:right; color:red;">Çıkış Yap</a>

    <?php if ($message) echo "<div class='msg'>$message</div>"; ?>

    <!-- Loader Versiyon -->
    <form method="post" style="margin:20px 0; padding:15px; background:#e9ecef; border-radius:8px;">
        <h3>Loader Versiyonu</h3>
        <input type="text" name="loader_version" value="<?= $settings['loader_version'] ?? '1.0.0' ?>" required>
        <button type="submit" name="update_version">Güncelle</button>
    </form>

    <!-- Key Oluştur -->
    <form method="post" style="margin:20px 0; padding:15px; background:#d1ecf1; border-radius:8px;">
        <h3>Yeni Key Oluştur</h3>
        <select name="duration" required>
            <option value="lifetime">Lifetime</option>
            <option value="1 day">1 Gün</option>
            <option value="7 days">7 Gün</option>
            <option value="1 week">1 Hafta</option>
            <option value="30 days">30 Gün</option>
        </select>
        <button type="submit" name="create">Oluştur</button>
    </form>

    <!-- Key Listesi -->
    <h3>Lisans Anahtarları</h3>
    <table>
        <tr>
            <th>Key</th>
            <th>Oluşturulma</th>
            <th>Süre Bitiş</th>
            <th>Durum</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($keys as $k => $data): ?>
        <tr>
            <td><code><?= htmlspecialchars($k) ?></code></td>
            <td><?= $data['created'] ?></td>
            <td><?= $data['expiry'] === 'lifetime' ? 'Ömür Boyu' : $data['expiry'] ?></td>
            <td><?= $data['banned'] ? '<span style="color:red">Banlı</span>' : '<span style="color:green">Aktif</span>' ?></td>
            <td>
                <?php if ($data['banned']): ?>
                    <a href="?ban=<?= $k ?>" class="btn unban">Ban Kaldır</a>
                <?php else: ?>
                    <a href="?ban=<?= $k ?>" class="btn ban">Banla</a>
                <?php endif; ?>
                <a href="?delete=<?= $k ?>" class="btn delete" onclick="return confirm('Silinsin mi?')">Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>