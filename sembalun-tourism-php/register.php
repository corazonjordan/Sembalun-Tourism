<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $cek = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $cek->bind_param("s", $username);
  $cek->execute();
  $result = $cek->get_result();

  if ($result->num_rows > 0) {
    $error = "Username sudah digunakan.";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
      // Login otomatis setelah berhasil register
      $_SESSION['user_id'] = $stmt->insert_id;
      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit;
    } else {
      $error = "Gagal mendaftar. Silakan coba lagi.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar - Sembalun Tourism</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<style>
.eye-icon {
  position: absolute;
  top: 0.65rem;
  right: 0.75rem;
  width: 1.25rem;
  height: 1.25rem;
  cursor: pointer;
  fill: #4B5563; /* text-gray-600 */
}
</style>
</head>

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

  // Ganti ikon sesuai status
  icon.innerHTML = isHidden
    ? `<path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>`
    : `<path d="M1 1l22 22" /><path d="M17.94 17.94A10.94 10.94 0 0112 20c-5 0-9.27-3.11-11-7.5a11.24 11.24 0 012.61-3.95M10.47 10.47a2.5 2.5 0 013.06 3.06M3.6 3.6A11.19 11.19 0 0112 4c5 0 9.27 3.11 11 7.5a11.24 11.24 0 01-4.2 4.88"/>`;
}
</script>

<body class="relative text-white min-h-screen overflow-x-hidden">

  <!-- VIDEO BACKGROUND -->
  <div class="absolute inset-0 z-0">
    <video autoplay muted loop playsinline class="w-full h-full object-cover">
      <source src="assets/video/rinjani.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
  </div>

  <!-- Navbar -->
  <nav class="fixed top-0 left-0 w-full z-20 bg-white bg-opacity-80 backdrop-blur shadow-md">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-green-800 font-bold text-xl">🌄 Sembalun Tourism</h1>
      <ul class="flex space-x-6 text-gray-700 font-semibold">
        <li><a href="index.php#beranda" class="hover:text-green-700">Beranda</a></li>
        <li><a href="index.php#destinasi" class="hover:text-green-700">Destinasi</a></li>
        <li><a href="index.php#produk" class="hover:text-green-700">Produk Lokal</a></li>
        <li><a href="index.php#tentang" class="hover:text-green-700">Tentang</a></li>
        <li><a href="login.php" class="hover:text-green-700">Masuk</a></li>
      </ul>
    </div>
  </nav>

  <!-- Register Card -->
<div class="flex items-center justify-center min-h-screen px-4 relative z-10">
  <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md text-gray-800">
    <h2 class="text-2xl font-bold text-center mb-6">Daftar</h2>
    <?php if (isset($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-300 bg-white text-gray-800">
      </div>
     <div>
  <label class="block text-sm font-medium text-gray-700">Password</label>
  <div class="relative">
    <input type="password" name="password" id="password" required
      class="w-full px-3 py-2 border border-gray-300 rounded bg-blue-50 pr-10 text-gray-800"
      pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{6,}"
      title="Minimal 6 karakter, huruf besar, angka, dan simbol.">
    <svg onclick="togglePasswordVisibility('password', this)" xmlns="http://www.w3.org/2000/svg" class="eye-icon absolute right-3 top-3 w-5 h-5 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
     <path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
      <line x1="3" y1="3" x2="21" y2="21" stroke="#4B5563" stroke-width="2"/>
    </svg>
  </div>
</div>

<div>
  <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
  <div class="relative">
    <input type="password" name="confirm_password" id="confirm_password" required
      class="w-full px-3 py-2 border border-gray-300 rounded bg-blue-50 pr-10 text-gray-800">
    <svg onclick="togglePasswordVisibility('confirm_password', this)" xmlns="http://www.w3.org/2000/svg" class="eye-icon absolute right-3 top-3 w-5 h-5 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M12 4.5C7 4.5 2.73 8.11 1 12c1.73 3.89 6 7.5 11 7.5s9.27-3.61 11-7.5c-1.73-3.89-6-7.5-11-7.5zm0 13a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
      <line x1="3" y1="3" x2="21" y2="21" stroke="#4B5563" stroke-width="2"/>
    </svg>
  </div>
</div>
      <button type="submit" class="w-full bg-green-800 text-white py-2 rounded hover:bg-green-900">Daftar</button>
      <div class="mt-4 text-sm text-center">
  <span class="text-gray-600">Sudah punya akun?</span>
  <a href="login.php" class="text-green-700 hover:underline ml-1">Masuk</a>
</div>
    </form>
  </div>
</div>
</html>
