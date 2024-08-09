<?php
require 'db.php';
require 'session.php'; 
require 'header.php'; 

// Fetch all posts along with user details
$query = "
    SELECT posts.id AS post_id, posts.content, posts.created_at, users.username, users.profile_image 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
";
$result = $conn->query($query);

// Functions to get likes and comments
function getLikes($conn, $post_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($likes_count);
    $stmt->fetch();
    $stmt->close();
    return $likes_count;
}

function getComments($conn, $post_id) {
    $stmt = $conn->prepare("SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();
    return $comments;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .create-post-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .create-post-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Recent Posts</h2>

    <!-- Create Post Button -->
    <a href="create_post.php" class="create-post-button">Create Post</a>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; margin-bottom: 10px; padding: 10px;">
            <img src="<?php echo $row['profile_image']; ?>" alt="Profile Image" width="50" height="50" style="float:left; margin-right:10px;">
            <strong><?php echo $row['username']; ?></strong> <br>
            <small><?php echo $row['created_at']; ?></small>
            <p><?php echo htmlspecialchars($row['content']); ?></p>
            
            <!-- Like Button -->
            <form method="POST" action="like.php" style="display: inline;">
                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                <button type="submit"><?php echo getLikes($conn, $row['post_id']); ?> Likes</button>
            </form>

            <!-- Display Comments -->
            <div>
                <h4>Comments</h4>
                <?php
                $comments = getComments($conn, $row['post_id']);
                foreach ($comments as $comment) {
                    echo "<strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['comment']) . "<br>";
                }
                ?>
            </div>

            <!-- Comment Form -->
            <form method="POST" action="comment.php">
                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                <textarea name="comment" rows="2" cols="30" placeholder="Add a comment..." required></textarea><br>
                <button type="submit">Comment</button>
            </form>
        </div>
    <?php endwhile; ?>

    <?php $conn->close(); ?>
</body>
</html>
