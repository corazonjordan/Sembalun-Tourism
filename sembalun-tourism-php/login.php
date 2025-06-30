<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // ✅ Tambahkan di sini
    $nama_pengguna = $row['username']; // atau $row['nama'] kalau pakai nama asli
    setcookie('nama_pengguna', $nama_pengguna, time() + (86400 * 7), "/");

    header("Location: index.php");
    exit;
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Sembalun Tourism</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<style>
.eye-icon {
  position: absolute;
  right: 0.75rem;
  top: 0.65rem;
  cursor: pointer;
  width: 1.25rem;
  height: 1.25rem;
  fill: #4B5563; /* text-gray-600 */
}
</style>
</head>

<script>
function toggleLoginPassword() {
  const input = document.getElementById('login-password');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<script>
function togglePasswordVisibility(id, icon) {
  const input = document.getElementById(id);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  icon.innerHTML = isHidden
    ? `<path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>`
    : `<path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/><line x1="3" y1="3" x2="21" y2="21" stroke="#4B5563" stroke-width="2"/>`;
}
</script>

<body class="relative text-white min-h-screen overflow-hidden pt-16">

  <!-- VIDEO BACKGROUND -->
  <div class="absolute inset-0 z-0">
    <video autoplay muted loop playsinline class="w-full h-full object-cover">
      <source src="assets/video/rinjani.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
  </div>

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 w-full z-20 bg-white bg-opacity-80 backdrop-blur shadow-md">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <a href="index.php" class="text-green-800 font-bold text-xl hover:text-green-900">🌄 Sembalun Tourism</a>
      <ul class="flex space-x-6 text-gray-800 font-semibold">
        <li><a href="index.php#beranda" class="hover:text-green-700">Beranda</a></li>
        <li><a href="index.php#destinasi" class="hover:text-green-700">Destinasi</a></li>
        <li><a href="index.php#produk" class="hover:text-green-700">Produk Lokal</a></li>
        <li><a href="index.php#tentang" class="hover:text-green-700">Tentang</a></li>
        <li><a href="register.php" class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800">Daftar</a></li>
      </ul>
    </div>
  </nav>

  <!-- LOGIN CARD -->
  <div class=<!-- LOGIN CARD (centered vertically like register) -->
<!-- LOGIN CARD (centered vertically like register) -->
<!-- LOGIN CARD -->
<div class="flex items-center justify-center pt-32 pb-20 px-4 relative z-10">
    <div class="bg-white bg-opacity-95 text-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
      <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Username</label>
          <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-300 bg-white text-gray-800">
        </div>
    <div>
  <label class="block text-sm font-medium text-gray-700">Password</label>
  <div class="relative">
    <input type="password" name="password" id="login-password" required class="w-full px-3 py-2 border border-gray-300 rounded bg-blue-50 pr-10">
    <svg onclick="togglePasswordVisibility('login-password', this)" xmlns="http://www.w3.org/2000/svg" class="eye-icon" viewBox="0 0 24 24">
      <path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
      <line x1="3" y1="3" x2="21" y2="21" stroke="#4B5563" stroke-width="2"/>
    </svg>
  </div>
</div>
        <button type="submit" class="w-full bg-green-800 text-white py-2 rounded hover:bg-green-900">Login</button>
        <div class="mt-4 text-sm text-center">
  <a href="register.php" class="text-green-700 hover:underline">Belum punya akun? Daftar</a>
  <span class="mx-2 text-gray-400">|</span>
  <a href="reset-password.php" class="text-green-700 hover:underline">Lupa password?</a>
</div>
      </form>
    </div>
  </div>
</body>
</html>
