<?php
// feed.php
// DB: splitxs1_login | Table: users
$host = "localhost";
$user = "splitxs1_admin";
$pass = "Sunil96241";
$db = "splitxs1_login";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Feed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --main-bg: #f4f7fa;
            --card-bg: #fff;
            --border: #e0e0e0;
            --text: #333;
            --accent: #4a90e2;
            --fab-gradient: linear-gradient(135deg, #ff4166 0%, #fe4935 100%);
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--main-bg);
            padding-bottom: 100px;
        }
        .feed-container {
            max-width: 600px;
            margin: 2vw auto;
            padding: 0 4vw 40px;
        }
        .post {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 20px;
            padding: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .post-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
        }
        .post-header .name {
            font-weight: 600;
            font-size: 16px;
            color: var(--text);
        }
        .post-header .date {
            font-size: 12px;
            color: gray;
        }
        .post-content {
            font-size: 15px;
            color: var(--text);
            margin: 10px 0;
            word-break: break-word;
        }
        .post-media img, .post-media video {
            width: 100%;
            max-height: 400px;
            border-radius: 12px;
            margin-top: 10px;
        }
        .post-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 12px;
            font-size: 18px;
            color: gray;
        }
        .post-actions i:hover {
            color: var(--accent);
            cursor: pointer;
        }
        .fab-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        .fab-options {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-bottom: 12px;
            pointer-events: none;
        }
        .fab-option-box {
            display: flex;
            align-items: center;
            min-width: 92px;
            background: var(--fab-gradient);
            color: #fff;
            border: none;
            border-radius: 28px;
            padding: 9px 18px;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.23);
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            transition: opacity 0.28s cubic-bezier(.43,1.32,.64,1), transform 0.22s cubic-bezier(.43,1.32,.64,1);
            cursor: pointer;
            pointer-events: auto;
        }
        .fab-option-box .fab-option-icon {
            font-size: 22px;
            margin-right: 9px;
        }
        .fab-options.show .fab-option-box {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .fab-option-box:active {
            transform: scale(0.97);
            filter: brightness(0.96);
        }
        .fab-button {
            background: var(--fab-gradient);
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: filter 0.18s;
        }
        .fab-button:hover {
            filter: brightness(1.09);
        }
        .fab-options {
            transition: margin-bottom 0.22s cubic-bezier(.43,1.32,.64,1);
        }
        .fab-options:not(.show) .fab-option-box {
            pointer-events: none;
        }
        @media (max-width: 600px) {
            .feed-container {
                max-width: 100vw;
                margin: 0;
                padding: 0 2vw 90px;
            }
            .post {
                padding: 12px;
                border-radius: 12px;
            }
            .post-header img {
                width: 34px;
                height: 34px;
            }
            .fab-container {
                right: 10px;
                bottom: 10px;
            }
            .fab-button {
                width: 52px;
                height: 52px;
                font-size: 28px;
            }
            .fab-option-box {
                font-size: 16px;
                min-width: 76px;
                padding: 8px 14px;
            }
            .fab-option-box .fab-option-icon {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="feed-container">
        <?php
        $sql = "SELECT * FROM users WHERE post_text IS NOT NULL OR poll_question IS NOT NULL ORDER BY id DESC";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $name = htmlspecialchars($row['name']);
                $photo = htmlspecialchars($row['profile_photo']);
                $date = date('M d, Y h:i A', strtotime($row['created_at']));
                $text = nl2br(htmlspecialchars($row['post_text']));
                $poll = htmlspecialchars($row['poll_question']);
                $option1 = htmlspecialchars($row['poll_option1']);
                $option2 = htmlspecialchars($row['poll_option2']);
                $media = htmlspecialchars($row['media_url']);
                $ext = pathinfo($media, PATHINFO_EXTENSION);
        ?>
        <div class="post">
            <div class="post-header">
                <img src="<?= $photo ?>" alt="Profile">
                <div>
                    <div class="name"><?= $name ?></div>
                    <div class="date"><?= $date ?></div>
                </div>
            </div>
            <?php if ($poll): ?>
                <div class="post-content"><strong><?= $poll ?></strong></div>
                <div style="margin-top:10px;">
                    <button style="margin: 4px;"><?= $option1 ?></button>
                    <button style="margin: 4px;"><?= $option2 ?></button>
                </div>
            <?php elseif ($text): ?>
                <div class="post-content"><?= $text ?></div>
            <?php endif; ?>
            <?php if ($media): ?>
            <div class="post-media">
                <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?= $media ?>" alt="Post Image">
                <?php elseif (in_array(strtolower($ext), ['mp4', 'webm', 'ogg'])): ?>
                    <video controls><source src="<?= $media ?>"></video>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="post-actions">
                <i class="fa-regular fa-heart"></i>
                <i class="fa-regular fa-comment"></i>
                <i class="fa-solid fa-share"></i>
            </div>
        </div>
        <?php }} else { echo "<p>No posts or polls yet.</p>"; } ?>
    </div>

    <!-- Floating Action Button -->
    <div class="fab-container">
        <div class="fab-options" id="fabOptions">
            <button class="fab-option-box" onclick="window.location.href='create_post.php'">
                <span class="fab-option-icon"><i class="fa-solid fa-pen"></i></span>Post
            </button>
            <button class="fab-option-box" onclick="window.location.href='create_poll.php'">
                <span class="fab-option-icon"><i class="fa-solid fa-chart-simple"></i></span>Poll
            </button>
        </div>
        <button class="fab-button" id="fabMainBtn" aria-label="More options">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <script>
        const fabBtn = document.getElementById('fabMainBtn');
        const fabOptions = document.getElementById('fabOptions');
        let fabOpen = false;
        fabBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            fabOpen = !fabOpen;
            fabOptions.classList.toggle('show');
            fabBtn.innerHTML = fabOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-plus"></i>';
        });
        document.addEventListener('click', function(e) {
            if (fabOpen && !e.target.closest('.fab-container')) {
                fabOpen = false;
                fabOptions.classList.remove('show');
                fabBtn.innerHTML = '<i class="fa-solid fa-plus"></i>';
            }
        });
        fabOptions.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
