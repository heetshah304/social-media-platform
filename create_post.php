<?php
require 'db.php'; // Database connection
require 'session.php'; // Ensure user is logged in
require 'header.php'; // Include header with profile icon and logout button

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    // Sanitize input
    $content = htmlspecialchars($_POST['content']);
    $user_id = $_SESSION['user_id'];

    // Insert post into database
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        // Redirect to index.php to view all posts
        header("Location: index.php");
        exit();
    } else {
        $post_success = false;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .view-posts-button, .cancel-button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }

        .view-posts-button {
            background-color: #4CAF50;
        }

        .view-posts-button:hover {
            background-color: #45a049;
        }

        .cancel-button {
            background-color: #f44336;
        }

        .cancel-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h2>Create a New Post</h2>
    <?php if (isset($post_success) && $post_success): ?>
        <div class="success-message">
            Post successfully created!
        </div>
        <a href="index.php" class="view-posts-button">View All Posts</a>
    <?php elseif (isset($post_success) && !$post_success): ?>
        <div class="error-message">
            An error occurred while creating the post. Please try again.
        </div>
    <?php endif; ?>
    <form method="POST" action="create_post.php">
        <textarea name="content" rows="5" cols="40" placeholder="What's on your mind?" required></textarea><br><br>
        <button type="submit">Post</button>
    </form>
    <a href="index.php" class="cancel-button">View All Posts</a>
</body>
</html>
