<?php

session_start();

if (
    !isset($_SESSION['nama']) ||
    $_SESSION['nama'] !== 'admin' ||
    !isset($_GET['id'])
) {
    header('Location: dashboard.php');
    exit;
}

require_once 'koneksi.php';

$id = (int) $_GET['id'];

if ($id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nama FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$id]);

$user = $stmt->fetch();

if (!$user) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_baru     = trim($_POST['nama'] ?? '');
    $password_baru = trim($_POST['password'] ?? '');

    if ($nama_baru === '') {

        $error = 'Nama tidak boleh kosong.';

    } elseif ($password_baru === '') {

        $error = 'Password tidak boleh kosong.';

    } else {

        $hash_password = password_hash($password_baru, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare(
            'UPDATE users SET nama = ?, password = ? WHERE id = ?'
        );

        $stmt->execute([
            $nama_baru,
            $hash_password,
            $id
        ]);

        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
</head>
<body>

<h1>Edit Pengguna</h1>

<p>
    ID: <?= htmlspecialchars($id) ?>
</p>

<p>
    Nama Saat Ini:
    <strong><?= htmlspecialchars($user['nama']) ?></strong>
</p>

<?php if ($error): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="edit.php?id=<?= (int)$id ?>">

    <p>
        <label for="nama">Nama Baru</label><br>

        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= htmlspecialchars($_POST['nama'] ?? $user['nama']) ?>"
        >
    </p>

    <p>
        <label for="password">Password Baru</label><br>

        <input
            type="password"
            id="password"
            name="password"
        >
    </p>

    <button type="submit">Simpan</button>

    <a href="dashboard.php">Batal</a>

</form>

</body>
</html>