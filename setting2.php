<?php
// 設定を保存するためのPHPコード
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $setting1 = $_POST['setting1'];
    $setting2 = $_POST['setting2'];

    // 設定を保存する (ファイルがない場合は新しく作成します)
    file_put_contents('settings.json', json_encode(['setting1' => $setting1, 'setting2' => $setting2]));
}

// 設定ファイルが存在するかチェック
if (file_exists('settings.json')) {
    // 設定を読み込む
    $settings = json_decode(file_get_contents('settings.json'), true);
    $setting1 = $settings['setting1'] ?? '';
    $setting2 = $settings['setting2'] ?? '';
} else {
    // ファイルが存在しない場合はデフォルト値を設定
    $setting1 = '';
    $setting2 = '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定ページ</title>
    <link href="setting2.css" rel="stylesheet">

    <style>
        h1 {
            text-align: center; /* テキストを中央に配置 */
        }

        header {
            text-align: left; /* ヘッダー内のリンクを左寄せ */
            margin-left: 20px; /* 左側に少し余白を追加 */
        }
        .button {
    display: block; /* ボタンをブロック要素にして横幅を100%にする */
    width: 90%; /* 横幅を親要素いっぱいに広げる */
    padding: 10px; /* 内側の余白（上下左右の均等な余白） */
    background-color: white; /* ボタンの背景色を白に */
    color: #4CAF50; /* ボタンの文字色を緑に */
    text-decoration: none; /* 下線を消す */
    border-radius: 5px; /* 角を丸くする */
    border: 1px solid #4CAF50; /* 緑色の枠線を追加 */
}

.button:hover {
    background-color: #f0f0f0; /* ホバー時の背景色を少しグレーにする */
}

    </style>

</head>
<body>
    <h1>アプリの各種設定</h1>
    <header>
        <li>
        <a href="setting.php">マイページに戻る</a><br><br>
        <a href="frienderguide.php" class="button">friender使い方</a><br>
        <a href="tuuti.php" class="button">通知設定</a><br>
        <a href="blocked_users.php" class="button">ブロック</a><br>
        </li>
    </header>
</body>
</html>