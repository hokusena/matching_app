<?php
// search.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$search_keyword = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_keyword = $_GET['search'];

    // 検索クエリの作成
    $sql_search = "
        SELECT * 
        FROM profiles 
        WHERE CONCAT(
            gender, ' ', 
            bio, ' ', 
            photo_path, ' ', 
            nickname, ' ', 
            age, ' ', 
            languages, ' ', 
            tall, ' ', 
            category, ' ', 
            blood, ' ', 
            figure, ' ', 
            beer, ' ', 
            MBTI, ' ', 
            college, ' ', 
            Birthplace, ' ', 
            birthdate
        ) LIKE ?";

    $stmt_search = $conn->prepare($sql_search);
    $search_param = '%' . $search_keyword . '%';
    $stmt_search->bind_param('s', $search_param);
    $stmt_search->execute();
    $result = $stmt_search->get_result();

    // 検索結果を表示
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['nickname']) . "</h3>";
            echo "<p>性別: " . htmlspecialchars($row['gender']) . "</p>";
            echo "<p>自己紹介: " . htmlspecialchars($row['bio']) . "</p>";
            echo "<p>年齢: " . htmlspecialchars($row['age']) . "</p>";
            echo "<p>言語: " . htmlspecialchars($row['languages']) . "</p>";
            echo "<p>身長: " . htmlspecialchars($row['tall']) . "</p>";
            echo "<p>学年: " . htmlspecialchars($row['category']) . "</p>";
            echo "<p>血液型: " . htmlspecialchars($row['blood']) . "</p>";
            echo "<p>体型: " . htmlspecialchars($row['figure']) . "</p>";
            echo "<p>お酒: " . htmlspecialchars($row['beer']) . "</p>";
            echo "<p>MBTI: " . htmlspecialchars($row['MBTI']) . "</p>";
            echo "<p>大学: " . htmlspecialchars($row['college']) . "</p>";
            echo "<p>出身地: " . htmlspecialchars($row['Birthplace']) . "</p>";
            echo "<p>生年月日: " . htmlspecialchars($row['birthdate']) . "</p>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>該当するプロフィールが見つかりませんでした。</p>";
    }

    $stmt_search->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール検索</title>
</head>
<body>
    <form action="search.php" method="get">
        <input type="text" name="search" placeholder="キーワードを入力" value="<?php echo htmlspecialchars($search_keyword); ?>">
        <button type="submit">検索</button>
    </form>
</body>
</html>
