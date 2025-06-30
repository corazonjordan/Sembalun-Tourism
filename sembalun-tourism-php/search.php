<?php
include 'includes/db.php';

$q = $_GET['q'] ?? '';
$hasil_destinasi = [];
$hasil_produk = [];

if ($q !== '') {
  // Pencarian destinasi
  $stmt1 = $conn->prepare("SELECT * FROM wisata WHERE nama_wisata LIKE ? OR deskripsi LIKE ?");
  $searchTerm = "%" . $q . "%";
  $stmt1->bind_param("ss", $searchTerm, $searchTerm);
  $stmt1->execute();
  $hasil_destinasi = $stmt1->get_result();

  // Pencarian produk
  $stmt2 = $conn->prepare("SELECT * FROM wisata WHERE nama_wisata LIKE ? OR deskripsi LIKE ?");
  $stmt2->bind_param("ss", $searchTerm, $searchTerm);
  $stmt2->execute();
  $hasil_produk = $stmt2->get_result();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Pencarian - Sembalun Tourism</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen pt-24 pb-10 px-6">

  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-green-800 mb-6">Hasil Pencarian: "<?= htmlspecialchars($q) ?>"</h1>

    <?php if ($hasil_destinasi && $hasil_destinasi->num_rows > 0): ?>
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Destinasi</h2>
      <div class="grid md:grid-cols-3 gap-6 mb-10">
        <?php while ($row = $hasil_destinasi->fetch_assoc()): ?>
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_wisata']) ?>" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="text-lg font-semibold text-green-700"><?= htmlspecialchars($row['nama_wisata']) ?></h3>
              <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($row['deskripsi']) ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

    <?php if ($hasil_produk && $hasil_produk->num_rows > 0): ?>
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Produk Lokal</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <?php while ($row = $hasil_produk->fetch_assoc()): ?>
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_wisata']) ?>" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="text-lg font-semibold text-green-700"><?= htmlspecialchars($row['nama_wisata']) ?></h3>
              <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($row['deskripsi']) ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

    <?php if (($hasil_destinasi->num_rows + $hasil_produk->num_rows) === 0): ?>
      <p class="text-gray-600 mt-4">Tidak ada hasil ditemukan untuk kata kunci tersebut.</p>
    <?php endif; ?>
  </div>

</body>
</html>
