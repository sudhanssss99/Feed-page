<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Use your actual session value. Example fallback:
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

$host = "localhost";
$user = "splitxs1_admin";
$pass = "Sunil96241";
$db = "splitxs1_login";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $image_url = '';
    $type = 'text';

    // Handle image upload if present
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $filename = uniqid().".".$ext;
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file;
                $type = 'image';
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_url, type) VALUES (?, ?, ?, ?)");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("isss", $user_id, $content, $image_url, $type);
    if (!$stmt->execute()) die("Execute failed: " . $stmt->error);
    $stmt->close();
    header("Location: feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; margin:0; background:#f4f7fa; }
        .form-container {
            max-width: 400px; margin: 38px auto; background: #fff;
            border-radius: 14px; box-shadow: 0 2px 7px rgba(0,0,0,0.08);
            padding: 30px 26px;
        }
        h2 { text-align:center; color:#ff4166; margin-bottom:22px;}
        label { display:block; margin-top:12px; font-weight:500;}
        textarea, input[type="file"] {
            width:100%; padding:10px 12px; margin-top:6px; border:1px solid #ddd;
            border-radius: 8px; font-size:16px;
        }
        textarea { resize: vertical; min-height: 80px;}
        button {
            width:100%; background: linear-gradient(135deg, #ff4166, #fe4935);
            color:#fff; border:none; border-radius:8px; padding:12px 0;
            font-size:18px; font-weight:600; margin-top:22px; cursor:pointer;
            transition:background 0.2s;
        }
        button:hover { background: linear-gradient(135deg, #fe4935, #ff4166);}
        .back-link { display:block; margin-top:20px; text-align:center; color:#4a90e2; text-decoration:none;}
        @media (max-width: 600px) { .form-container { max-width: 96vw; padding: 18px 4vw; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Post</h2>
        <form method="post" enctype="multipart/form-data">
            <label>What's on your mind?</label>
            <textarea name="content" maxlength="1000" required></textarea>
            <label>Image (optional)</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post</button>
        </form>
        <a class="back-link" href="feed.php">&#8592; Back to Feed</a>
    </div>
</body>
</html>L
