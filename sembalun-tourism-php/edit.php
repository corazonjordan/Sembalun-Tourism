<?php
session_start();
include 'includes/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
// Ambil ID dari URL
$id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "sembalun");

// Ambil data lama
$result = $conn->query("SELECT * FROM komentar WHERE id = $id");
$data = $result->fetch_assoc();
$gambarLama = $data['foto']; // 

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $komentar = $_POST['komentar'];
    $gambar = $_FILES['gambar']['name'] ?? '';

    // Cek apakah ada gambar baru diunggah
    if ($_FILES['gambar']['name']) {
        $gambarBaru = uniqid() . '-' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambarBaru);

        // Hapus gambar lama jika ada
        if (!empty($gambarLama) && file_exists('uploads/' . $gambarLama)) {
            unlink('uploads/' . $gambarLama);
        }
    } else {
        $gambarBaru = $gambarLama;
    }

    // Update ke database
    $stmt = $conn->prepare("UPDATE komentar SET komentar = ?, foto = ? WHERE id = ?");
    $stmt->bind_param("ssi", $komentar, $gambarBaru, $id);
    $stmt->execute();

    // Redirect ke dashboard
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Komentar - Sembalun Tourism</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-10 px-4">
  <div class="max-w-xl mx-auto bg-white shadow-lg p-6 rounded-lg">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Edit Komentar</h1>
    <form action="" method="POST" enctype="multipart/form-data">
      <div>
        <label class="block text-sm font-medium text-gray-700">Komentar</label>
        <textarea name="komentar" required class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800"><?= htmlspecialchars($data['komentar']) ?></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
        <?php if (!empty($data['gambar']) && file_exists('uploads/' . $data['gambar'])): ?>
  <img src="uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar lama" class="w-32 h-32 object-cover rounded-md mt-1">
<?php else: ?>
  <p class="text-sm text-gray-500">Gambar tidak tersedia</p>
<?php endif; ?>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Ganti Gambar (Opsional)</label>
        <input type="file" name="gambar" accept="image/*" class="mt-1">
      </div>

      <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
    <div class="mt-4">
      <a href="index.php" class="text-green-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
