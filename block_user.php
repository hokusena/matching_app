<?php
// block_user.php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$blocker_id = $_SESSION['user_id'];
$blocked_id = isset($_POST['blocked_id']) ? (int)$_POST['blocked_id'] : 0;

// ブロックデータを保存
$sql = "INSERT INTO blocks (blocker_id, blocked_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE blocked_at = NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $blocker_id, $blocked_id);

if ($stmt->execute()) {
    echo "ユーザーをブロックしました。";
} else {
    echo "ブロックに失敗しました: " . $stmt->error;
}

$stmt->close();
$conn->close();
