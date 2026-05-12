<?php
session_start();

if (isset($_SESSION['nama'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']     ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nama === '' || $password === '') {
        $error = 'Nama dan password tidak boleh kosong.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE nama = ? LIMIT 1');
        $stmt->execute([$nama]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id']   = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Nama atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="index.php">
    Nama: <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>