<?php
// like_user.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$liked_user_id = $_POST['liked_user_id'];
$user_id = $_SESSION['user_id'];

// いいねをデータベースに保存
$sql = "INSERT INTO likes (user_id, liked_user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $liked_user_id);

if ($stmt->execute()) {
    // 相手がすでに自分に「いいね」をしているかを確認
    $check_match_sql = "SELECT * FROM likes WHERE user_id = ? AND liked_user_id = ?";
    $check_match_stmt = $conn->prepare($check_match_sql);
    $check_match_stmt->bind_param("ii", $liked_user_id, $user_id);
    $check_match_stmt->execute();
    $check_match_result = $check_match_stmt->get_result();
    
    // 相手も自分に「いいね」していた場合、マッチングを成立
    if ($check_match_result->num_rows > 0) {
        // マッチング情報を保存
        $insert_match_sql = "INSERT INTO matches (user_id, matched_user_id) VALUES (?, ?)";
        $insert_match_stmt = $conn->prepare($insert_match_sql);
        $insert_match_stmt->bind_param("ii", $user_id, $liked_user_id);
        $insert_match_stmt->execute();
        
        // セッションにメッセージを保存
        $_SESSION['match_message'] = "マッチングが成立しました！";
    }

    // メニューにリダイレクト
    header("Location: menu.php");
} else {
    echo "エラーが発生しました: " . $stmt->error;
}

$stmt->close();
$conn->close();
