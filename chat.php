<?php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$chat_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($chat_user_id === 0) {
    echo "ユーザーが見つかりません。";
    exit;
}

// チャット相手の情報を取得
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_user_id);
$stmt->execute();
$result = $stmt->get_result();
$chat_user = $result->fetch_assoc();
$stmt->close();

if (!$chat_user) {
    echo "ユーザーが見つかりません。";
    exit;
}

// メッセージ送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    
    if (!empty($message)) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $chat_user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// チャット履歴の取得
$sql = "SELECT m.message, m.sent_at, u.username 
        FROM messages m 
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND m.receiver_id = ?) 
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $chat_user_id, $chat_user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($chat_user['username']); ?>さんとのチャット</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .chat-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .message {
            margin-bottom: 10px;
        }

        .message .username {
            font-weight: bold;
        }

        .message .content {
            margin-left: 10px;
        }

        .message-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1><?php echo htmlspecialchars($chat_user['username']); ?>さんとのチャット</h1>

        <div class="chat-history">
            <?php if (empty($messages)): ?>
                <p>まだメッセージがありません。</p>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <span class="username"><?php echo htmlspecialchars($message['username']); ?>:</span>
                        <span class="content"><?php echo htmlspecialchars($message['message']); ?></span>
                        <span class="time"><?php echo htmlspecialchars($message['sent_at']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form action="" method="POST" class="message-form">
            <textarea name="message" rows="3" placeholder="メッセージを入力してください" required></textarea><br>
            <button type="submit">送信</button>
        </form>
    </div>
</body>
</html>