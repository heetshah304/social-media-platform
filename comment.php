<?php
require 'db.php'; // Database connection
require 'session.php'; // Ensure user is logged in

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id']) && isset($_POST['comment'])) {
    $post_id = intval($_POST['post_id']);
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect to index.php
        exit();
    } else {
        // Handle error
        echo "An error occurred. Please try again.";
    }
    
    $stmt->close();
}
?>
