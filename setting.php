<?php
// setting.php
// セッション開始
session_start();

// データベース接続
require 'config.php';

// ユーザー情報の取得
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="vieweport" content="width-device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link href="setting.css" rel="stylesheet">
    <!-- Font AwesomeのCDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* チャットリンクの無効化スタイル */
        a.disabled {
            pointer-events: none;  /* クリックを無効化 */
            cursor: default;        /*通常のマウスカーソルに変更 */
            text-decoration: none;  /* 下線も削除 */
        }
    </style>


</head>
<body>
    <header>
        <h1>マイページ</h1>
        <nav>
            <li><a href="menu.php"><i class="fas fa-search"></i> さがす</a></li>
            <li><a href="like.php"><i class="fas fa-heart"></i> いいね！</a></li>
            <li><a href="matching.php"><i class="fas fa-comments"></i> チャット</a></li>
            <li><a class="disabled"><i class="fas fa-user"></i> マイページ</a></li>
        </nav>

    </header>

    <main>
        
        <p>メールアドレス: <?php echo htmlspecialchars($user['email']); ?></p>
        <a href="edit_profile.php">プロフィールを編集する</a><br>
        <a href="setting2.php">各種設定</a><br>
        <a href="safe.php">安心・安全ガイド</a><br>
        <a href="logout.php">ログアウト</a>
    </main>
</body>
</html>
