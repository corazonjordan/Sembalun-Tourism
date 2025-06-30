<?php
session_start();
include 'includes/db.php';

// Jika user login dan mengirim komentar baru
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'])) {
  $user_id = $_SESSION['user_id'];
  $komentar = $_POST['komentar'];
  $foto = $_FILES['foto']['name'] ?? '';
  $tmp = $_FILES['foto']['tmp_name'] ?? '';

  if ($foto && $tmp) {
    move_uploaded_file($tmp, "uploads/" . $foto);
  }

  $stmt = $conn->prepare("INSERT INTO komentar (user_id, komentar, foto) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $komentar, $foto);
  $stmt->execute();
}

// Ambil semua komentar untuk ditampilkan ke semua pengunjung
$semua_komentar = $conn->query("SELECT komentar.*, users.username 
                                FROM komentar 
                                JOIN users ON komentar.user_id = users.id 
                                ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sembalun Tourism</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-100">

  <?php if (isset($_COOKIE['nama_pengguna'])): ?>
  <div id="cookie-message" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-100 text-green-800 px-4 py-2 rounded shadow-md text-sm font-medium z-50 transition-opacity duration-1000 max-w-fit">
    Selamat datang kembali, <strong><?= htmlspecialchars($_COOKIE['nama_pengguna']) ?></strong>!
  </div>
<?php endif; ?>

  <!-- Navbar -->
  <nav class="bg-white shadow-md fixed w-full z-30">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <a href="index.php" class="text-green-800 font-bold text-xl hover:text-green-600 transition duration-200">
  🌄 Sembalun Tourism
      </a>
      <ul class="flex space-x-6 text-gray-700 font-semibold">
  <li><a href="#beranda" class="hover:text-green-700">Beranda</a></li>
  <li><a href="#destinasi" class="hover:text-green-700">Destinasi</a></li>
  <li><a href="#produk" class="hover:text-green-700">Produk Lokal</a></li>
  <li><a href="#tentang" class="hover:text-green-700">Tentang</a></li>
  <?php if (isset($_SESSION['user_id'])): ?>
    <li><a href="logout.php" class="hover:text-red-600">Keluar (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
  <?php else: ?>
    <li><a href="login.php" class="hover:text-green-700">Masuk</a></li>
    <li><a href="register.php" class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800">Daftar</a></li>
  <?php endif; ?>
</ul>
    </div>
  </nav>

<!-- Hero Section dengan Video Background -->
<section id="beranda" class="relative w-full h-screen overflow-hidden pt-16">
  <!-- Video Background -->
  <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0 pointer-events-none">
    <source src="assets/video/rinjani.mp4" type="video/mp4">
    Browser Anda tidak mendukung video HTML5.
  </video>

  <!-- Overlay Gelap -->
  <div class="absolute top-0 left-0 w-full h-full bg-black opacity-30 z-10 pointer-events-none"></div>

  <!-- Konten Hero -->
  <div class="relative z-20 flex items-center justify-center h-full text-center px-4">
    <div class="text-white">
      <h2 class="text-4xl font-extrabold mb-4">Jelajahi Keindahan <span class="text-blue-400">Sembalun</span></h2>
      <p class="text-lg mb-6">Nikmati pesona Gunung Rinjani, rasakan kesegaran strawberry lokal, dan temukan pengalaman wisata tak terlupakan di Desa Sembalun</p>

      <!-- Form Search -->
      <form action="search.php" method="GET" class="flex justify-center">
        <input type="text" name="q" placeholder="Cari destinasi..." class="px-4 py-2 w-80 rounded-l-lg border border-gray-110 bg-gray-200 text-black">
        <button type="submit" class="px-5 py-2 bg-green-700 text-white rounded-r-lg hover:bg-green-800">Cari</button>
      </form>
    </div>
  </div>
</section>

  <!-- Destinasi -->
  <section id="destinasi" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl font-bold text-center mb-10 text-green-800">Destinasi Unggulan</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img src="assets/img/rinjani.jpg" alt="Gunung Rinjani" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-green-800">Gunung Rinjani</h3>
            <p class="text-sm text-gray-600 mt-2">Gunung tertinggi kedua di Indonesia, cocok untuk pendaki dan pecinta alam</p>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img src="assets/img/strawberry.jpg" alt="Kebun Strawberry" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-green-800">Kebun Strawberry</h3>
            <p class="text-sm text-gray-600 mt-2">Petik langsung dari kebunnya! Segar dan alami</p>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img src="assets/img/Desa belek.jpg" alt="Desa Belek" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-green-800">Desa Beleq</h3>
            <p class="text-sm text-gray-600 mt-2">Desa Beleq yang terletak kaki Gunung Rinjani menawarkan pengalaman mendalam tentang budaya dan sejarah lokal</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Produk Lokal -->
  <section id="produk" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl font-bold text-center mb-10 text-green-800">Produk Lokal Unggulan</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <!-- Produk 1 -->
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
          <img src="assets/img/jeruk.jpeg" alt="Jeruk Lokal" class="w-56 h-56 mx-auto mb-4 rounded-lg">
          <h3 class="text-lg font-semibold text-green-700">Jeruk Lokal</h3>
          <p class="text-sm text-gray-600">Manis, segar, dan ditanam langsung oleh petani Sembalun</p>
        </div>
        <!-- Produk 2 -->
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
          <img src="assets/img/sayur.jpg" alt="Sayuran Organik" class="w-56 h-56 mx-auto mb-4 rounded-lg">
          <h3 class="text-lg font-semibold text-green-700">Sayuran Organik</h3>
          <p class="text-sm text-gray-600">Sayuran sehat tanpa pestisida langsung dari kaki Rinjani</p>
        </div>
        <!-- Produk 3 -->
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
          <img src="assets/img/madu.jpg" alt="Madu Hutan" class="w-56 h-56 mx-auto mb-4 rounded-lg">
          <h3 class="text-lg font-semibold text-green-700">Madu Hutan</h3>
          <p class="text-sm text-gray-600">Madu alami dari lebah liar hutan Sembalun</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Tentang -->
  <section id="tentang" class="py-16 bg-white">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl font-bold text-green-800 mb-4">Tentang Desa Sembalun</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Desa Sembalun adalah permata tersembunyi di kaki Gunung Rinjani. Selain dikenal karena keindahan alamnya, Sembalun juga memiliki kekayaan budaya dan hasil pertanian berkualitas tinggi.</p>
    </div>
  </section>

  <!-- Lokasi Peta -->
<section id="lokasi" class="py-16 bg-white">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Lokasi Desa Sembalun</h2>
    <p class="text-gray-600 mb-6">Berikut adalah lokasi geografis dari Desa Sembalun di Kabupaten Lombok Timur, Nusa Tenggara Barat:</p>
    <div class="flex justify-center">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253504.78767180025!2d116.3269096!3d-8.3477294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcc2c383dee1735%3A0xa2512900bb4a9b4e!2sSembalun%2C%20East%20Lombok%20Regency%2C%20West%20Nusa%20Tenggara!5e0!3m2!1sen!2sid!4v1719495371392!5m2!1sen!2sid" 
        width="100%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade"
        class="rounded-lg w-full max-w-4xl">
      </iframe>
    </div>
  </div>
</section>

  <section id="komentar" class="py-16 bg-gray-100">
  <div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold text-center mb-6 text-green-800">Komentar Pengunjung</h2>

    <!-- Form komentar hanya ditampilkan jika user login -->
    <?php if (isset($_SESSION['user_id'])): ?>
      <form method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl mx-auto mb-8">
        <textarea name="komentar" class="border p-2 w-full rounded" placeholder="Tulis komentar..." required></textarea>
        <input type="file" name="foto" class="border p-2 w-full rounded">
        <button class="bg-green-700 text-white px-4 py-2 rounded">Kirim</button>
      </form>
    <?php endif; ?>

    <!-- Tampilkan semua komentar -->
    <?php while ($row = $semua_komentar->fetch_assoc()): ?>
      <div class="bg-white shadow rounded p-4 mb-4 max-w-xl mx-auto">
        <p class="text-sm text-gray-600 mb-2">
          <strong><?= htmlspecialchars($row['username']) ?></strong> - <?= date('d M Y, H:i', strtotime($row['tanggal'])) ?>
        </p>
        <p><?= htmlspecialchars($row['komentar']) ?></p>
        <?php if ($row['foto']): ?>
          <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" class="w-40 mt-2">
        <?php endif; ?>

        <!-- Tampilkan tombol hapus hanya jika user login dan komentar miliknya -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
          <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus komentar ini?')" class="text-red-600 text-sm">Hapus</a>
          <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline ml-4">Edit</a>

        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Footer -->
<footer class="bg-green-800 text-white py-6 mt-16">
  <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">

    <!-- Kiri: Brand -->
    <div class="text-center md:text-left">
      <h2 class="text-lg font-semibold">🌄 Sembalun Tourism</h2>
      <p class="text-sm">Menjelajahi keindahan alam & budaya lokal Sembalun</p>
    </div>

    <!-- Tengah: Kredit -->
    <div class="text-sm text-center md:text-center">
      &copy; <?= date('Y') ?> <br class="block md:hidden">
      <span class="md:inline-block">Sembalun Tourism. Dibuat dengan ❤️ oleh <strong>Mahasiswa Jarang Tidur</strong></span>
    </div>

    <!-- Kanan: Navigasi -->
    <div class="flex space-x-4 text-sm">
      <a href="#beranda" class="hover:text-gray-300 transition">Beranda</a>
      <a href="#destinasi" class="hover:text-gray-300 transition">Destinasi</a>
      <a href="#produk" class="hover:text-gray-300 transition">Produk Lokal</a>
      <a href="#tentang" class="hover:text-gray-300 transition">Tentang</a>
      <a href="#lokasi" class="hover:text-gray-300 transition">Lokasi</a>
    </div>

  </div>
</footer>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('cookie-message');
    if (msg) {
      setTimeout(() => {
        msg.classList.add('opacity-0'); // mulai fade out
        setTimeout(() => msg.remove(), 1000); // hapus dari DOM setelah animasi
      }, 4000); // tampil selama 4 detik
    }
  });
</script>

</body>
</html>
