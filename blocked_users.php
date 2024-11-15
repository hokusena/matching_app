<?php
// blocked_users.php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ブロックしたユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
        FROM blocks b
        JOIN users u ON b.blocked_id = u.id
        JOIN profiles p ON u.id = p.user_id
        WHERE b.blocker_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$blocked_users = [];

while ($row = $result->fetch_assoc()) {
    $blocked_users[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ブロックしたユーザー一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
            justify-items: center;
        }

        .user-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }

        .user-item img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }

        .unblock-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .unblock-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>ブロックしたユーザー一覧</h1>
    </header>

    <div class="user-list">
        <?php if (empty($blocked_users)): ?>
            <p>ブロックしたユーザーはいません。</p>
        <?php else: ?>
            <?php foreach ($blocked_users as $user): ?>
                <div class="user-item">
                    <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真">
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>
                    <p><?php echo htmlspecialchars($user['bio']); ?></p>
                    <form action="unblock_user.php" method="POST">
                        <input type="hidden" name="blocked_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="unblock-button">ブロック解除</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
