<?php
// create_poll.php
$host = "localhost";
$user = "splitxs1_admin";
$pass = "Sunil96241";
$db = "splitxs1_login";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $profile_photo = htmlspecialchars($_POST['profile_photo']);
    $question = htmlspecialchars($_POST['question']);
    $options = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST["option$i"])) {
            $options[] = htmlspecialchars($_POST["option$i"]);
        }
    }
    $options_str = json_encode($options, JSON_UNESCAPED_UNICODE);

    // Insert poll into polls table
    $stmt = $conn->prepare("INSERT INTO polls (name, profile_photo, question, options, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $profile_photo, $question, $options_str);
    $stmt->execute();
    $stmt->close();
    header("Location: feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Poll</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; margin:0; background:#f4f7fa; }
        .form-container {
            max-width: 400px; margin: 38px auto; background: #fff;
            border-radius: 14px; box-shadow: 0 2px 7px rgba(0,0,0,0.08);
            padding: 30px 26px;
        }
        h2 { text-align:center; color:#fe4935; margin-bottom:22px;}
        label { display:block; margin-top:12px; font-weight:500;}
        input[type="text"], textarea {
            width:100%; padding:10px 12px; margin-top:6px; border:1px solid #ddd;
            border-radius: 8px; font-size:16px;
        }
        textarea { resize: vertical; min-height: 60px;}
        button {
            width:100%; background: linear-gradient(135deg, #fe4935, #ff4166);
            color:#fff; border:none; border-radius:8px; padding:12px 0;
            font-size:18px; font-weight:600; margin-top:22px; cursor:pointer;
            transition:background 0.2s;
        }
        button:hover { background: linear-gradient(135deg, #ff4166, #fe4935);}
        .back-link { display:block; margin-top:20px; text-align:center; color:#4a90e2; text-decoration:none;}
        .opt-label { margin-top:5px;}
        @media (max-width: 600px) { .form-container { max-width: 96vw; padding: 18px 4vw; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Poll</h2>
        <form method="post">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Profile Photo URL</label>
            <input type="text" name="profile_photo" placeholder="https://...jpg" required>
            <label>Poll Question</label>
            <textarea name="question" maxlength="255" required></textarea>
            <label class="opt-label">Option 1</label>
            <input type="text" name="option1" maxlength="100" required>
            <label class="opt-label">Option 2</label>
            <input type="text" name="option2" maxlength="100" required>
            <label class="opt-label">Option 3</label>
            <input type="text" name="option3" maxlength="100">
            <label class="opt-label">Option 4</label>
            <input type="text" name="option4" maxlength="100">
            <label class="opt-label">Option 5</label>
            <input type="text" name="option5" maxlength="100">
            <button type="submit">Create Poll</button>
        </form>
        <a class="back-link" href="feed.php">&#8592; Back to Feed</a>
    </div>
</body>
</html>
