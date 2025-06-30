<?php
session_start();
session_unset();
// Hapus cookie nama pengguna
setcookie('nama_pengguna', '', time() - 3600, "/");
session_destroy();
header("Location: index.php");
exit;
