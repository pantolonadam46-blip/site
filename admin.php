<?php
// admin.php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($_POST['username'] ?? '' === 'breaksoftware' && $_POST['password'] ?? '' === 'breaksoftwareeee') {
    $_SESSION['admin'] = true;
    header('Location: dashboard.php');
    exit;
}

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Giriş</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .login { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.2); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        h2 { text-align: center; }
    </style>
</head>
<body>
<div class="login">
    <h2>Admin Giriş</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Kullanıcı adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
    <p style="text-align:center; margin-top:15px; color:#666; font-size:0.9em;">
    </p>
</div>
</body>
</html>