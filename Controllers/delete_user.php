<?php
include './db.php';

// Admin check
session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

$user_id = $_GET['id'];

$sql_delete = "DELETE FROM user WHERE idUser = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param('i', $user_id);

if ($stmt->execute()) {
    echo "Xóa thành công.";
    header('Location: ../Admin/dashboard.php');
} else {
    echo "Error: Không thể xóa.";
}

$stmt->close();
$conn->close();
?>