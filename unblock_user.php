<?php
// unblock_user.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$blocker_id = $_SESSION['user_id'];
$blocked_id = isset($_POST['blocked_id']) ? (int)$_POST['blocked_id'] : 0;

$sql = "DELETE FROM blocks WHERE blocker_id = ? AND blocked_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $blocker_id, $blocked_id);

if ($stmt->execute()) {
    echo "ユーザーのブロックを解除しました。";
} else {
    echo "解除に失敗しました: " . $stmt->error;
}

$stmt->close();
$conn->close();
