<?php
session_start();
include 'includes/db.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $user_id = $_SESSION['user_id'];

  // Cek apakah komentar milik user
  $check = $conn->prepare("SELECT * FROM komentar WHERE id = ? AND user_id = ?");
  $check->bind_param("ii", $id, $user_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $stmt = $conn->prepare("DELETE FROM komentar WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
}

header("Location: index.php#komentar");
exit;
