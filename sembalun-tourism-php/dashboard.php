<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $komentar = $_POST['komentar'] ?? '';
  $foto = $_FILES['foto']['name'] ?? '';
  $tmp = $_FILES['foto']['tmp_name'] ?? '';

  if ($foto && $tmp) {
    move_uploaded_file($tmp, "uploads/" . $foto);
  }

  $stmt = $conn->prepare("INSERT INTO komentar (user_id, komentar, foto) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $komentar, $foto);
  $stmt->execute();
}

// Ambil komentar user
$komentar_user = $conn->query("SELECT * FROM komentar WHERE user_id = $user_id ORDER BY tanggal DESC");
?>

<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Dashboard Anda</h1>

  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <textarea name="komentar" class="border p-2 w-full" placeholder="Tulis komentar..." required></textarea>
    <input type="file" name="foto" class="border p-2 w-full">
    <button class="bg-green-700 text-white px-4 py-2 rounded">Kirim</button>
  </form>

  <hr class="my-6">

  <h2 class="text-xl font-semibold mb-4">Komentar Anda</h2>
  <?php while ($row = $komentar_user->fetch_assoc()): ?>
    <div class="bg-white rounded shadow p-4 mb-4">
      <p><?= htmlspecialchars($row['komentar']) ?></p>
      <?php if ($row['foto']): ?>
        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" class="w-40 mt-2">
      <?php endif; ?>
      <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus komentar ini?')" class="text-red-600 text-sm">Hapus</a>
    </div>
  <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
