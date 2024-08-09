<?php
require 'session.php'; // Ensure user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Media Platform</title>
    <style>
        .header {
            background-color: #f1f1f1;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-icon {
            display: inline-block;
            padding: 10px;
            border-radius: 50%;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            margin-right: 20px;
        }

        .profile-icon:hover {
            background-color: #45a049;
        }

        .logout-button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="profile.php" class="profile-icon">
            <?php
            // Display user profile picture or initial
            echo isset($_SESSION['profile_image']) ? '<img src="'. $_SESSION['profile_image'] .'" alt="Profile" width="30" height="30">' : strtoupper(substr($_SESSION['username'], 0, 1));
            ?>
        </a>
        <form method="POST" action="logout.php" style="margin: 0;">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
    <br>
</body>
</html>
