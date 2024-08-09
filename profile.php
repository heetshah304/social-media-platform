<?php
require 'db.php'; // Database connection
require 'session.php'; // Ensure user is logged in

// Fetch user profile data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_image);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_profile_image = $_FILES['profile_image']['name'];

    if ($new_profile_image) {
        // Handle file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($new_profile_image);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
    } else {
        $target_file = $profile_image; // Keep old image if no new image is uploaded
    }

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_username, $new_email, $target_file, $user_id);

    if ($stmt->execute()) {
        $update_success = true;
    } else {
        $update_success = false;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Profile</h1>
            <a href="index.php" class="btn">Home</a>
            <a href="logout.php" class="btn">Logout</a>
        </header>

        <?php if (isset($update_success) && $update_success): ?>
            <div class="success-message">
                Profile successfully updated!
            </div>
        <?php elseif (isset($update_success) && !$update_success): ?>
            <div class="error-message">
                An error occurred while updating the profile. Please try again.
            </div>
        <?php endif; ?>

        <div class="profile-header">
            <img src="<?php echo $profile_image ? $profile_image : 'default-profile.png'; ?>" alt="Profile Image">
            <h2><?php echo htmlspecialchars($username); ?></h2>
        </div>

        <form class="profile-form" method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image">
            <button type="submit" name="update">Update Profile</button>
        </form>

        <!-- Button to view all posts -->
        <a href="index.php" class="btn">View All Posts</a>
    </div>
</body>
</html>
