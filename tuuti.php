<?php
// 設定を保存するためのPHPコード
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $like_notification = $_POST['like_notification'] ?? 'OFF';
    $match_notification = $_POST['match_notification'] ?? 'OFF';
    $message_notification = $_POST['message_notification'] ?? 'OFF';

    // 設定を配列として保存
    $settings = [
        'like_notification' => $like_notification,
        'match_notification' => $match_notification,
        'message_notification' => $message_notification
    ];

    // JSON形式で設定を保存
    file_put_contents('settings.json', json_encode($settings));
}

// 設定ファイルが存在するかチェック
if (file_exists('settings.json')) {
    // 設定を読み込む
    $settings = json_decode(file_get_contents('settings.json'), true);
    $like_notification = $settings['like_notification'] ?? 'OFF';
    $match_notification = $settings['match_notification'] ?? 'OFF';
    $message_notification = $settings['message_notification'] ?? 'OFF';
} else {
    // ファイルが存在しない場合はデフォルト値を設定
    $like_notification = 'OFF';
    $match_notification = 'OFF';
    $message_notification = 'OFF';
}
?>

<head>
    <link rel="stylesheet" href="tuuti.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center; /* テキストを中央寄せに */
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .notification-toggle {
            display: inline-flex; /* 中央寄せを維持するために inline-flex に変更 */
            margin-bottom: 20px;
        }

        .button {
            margin: 0 10px;
            padding: 10px 20px;
            border: 2px solid #007bff;
            border-radius: 50px;
            background-color: white;
            color: #007bff;
            cursor: pointer;
            width: 500px;
            transition: background-color 0.3s, color 0.3s;
        }

        .button:hover {
            background-color: #007bff;
            color: white;
        }

        input[type="radio"] {
            display: none; /* ラジオボタンを非表示にする */
        }

        input[type="radio"]:checked + .button {
            background-color: #007bff;
            color: white;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>通知設定</h1>
<form method="post" action="">
    <!-- マイページに戻るボタン -->
    <a href="setting2.php" class="">戻る</a>
    
    <h2>いいね</h2>
    <div class="notification-toggle">
        <input type="radio" id="like_ON" name="like_notification" value="ON" <?php if ($like_notification == 'ON') echo 'checked'; ?>>
        <label for="like_ON" class="button">ON</label>
        
        <input type="radio" id="like_OFF" name="like_notification" value="OFF" <?php if ($like_notification == 'OFF') echo 'checked'; ?>>
        <label for="like_OFF" class="button">OFF</label>
    </div>

    <h3>マッチング時</h3>
    <div class="notification-toggle">
        <input type="radio" id="match_ON" name="match_notification" value="ON" <?php if ($match_notification == 'ON') echo 'checked'; ?>>
        <label for="match_ON" class="button">ON</label>
        
        <input type="radio" id="match_OFF" name="match_notification" value="OFF" <?php if ($match_notification == 'OFF') echo 'checked'; ?>>
        <label for="match_OFF" class="button">OFF</label>
    </div>

    <h4>メッセージ</h4>
    <div class="notification-toggle">
        <input type="radio" id="message_ON" name="message_notification" value="ON" <?php if ($message_notification == 'ON') echo 'checked'; ?>>
        <label for="message_ON" class="button">ON</label>
        
        <input type="radio" id="message_OFF" name="message_notification" value="OFF" <?php if ($message_notification == 'OFF') echo 'checked'; ?>>
        <label for="message_OFF" class="button">OFF</label>
    </div>

    </br><input type="submit" value="保存">
</form>




