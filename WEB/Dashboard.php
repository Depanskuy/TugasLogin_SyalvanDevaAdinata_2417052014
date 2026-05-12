<?php

session_start();

if (!isset($_SESSION['nama'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

$nama_session = $_SESSION['nama'];
$is_admin     = ($nama_session === 'admin');

if ($is_admin && isset($_GET['hapus'])) {
    $hapus_id = (int) $_GET['hapus'];

    if ($hapus_id > 0) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$hapus_id]);
    }

    header('Location: dashboard.php');
    exit;
}

$users = [];

if ($is_admin) {
    $stmt  = $pdo->query('SELECT id, nama FROM users ORDER BY id DESC');
    $users = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<h1>Selamat Datang, <?= htmlspecialchars($nama_session) ?>!</h1>

<a href="logout.php">Logout</a>

<?php if ($is_admin): ?>

    <h2>Menu Admin: Kelola Pengguna</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['nama']) ?></td>
                <td>
                    <a href="edit.php?id=<?= (int)$u['id'] ?>">Edit</a>

                    <a href="dashboard.php?hapus=<?= (int)$u['id'] ?>"
                       onclick="return confirm('Hapus pengguna <?= htmlspecialchars(addslashes($u['nama'])) ?>?')">
                       Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($users)): ?>
            <tr>
                <td colspan="3">Tidak ada data pengguna.</td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table>

<?php else: ?>

    <h2>Halo, <?= htmlspecialchars($nama_session) ?>!</h2>
    <p>Anda berhasil masuk sebagai pengguna reguler.</p>

<?php endif; ?>

</body>
</html>