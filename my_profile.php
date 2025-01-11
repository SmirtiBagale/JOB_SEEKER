<?php
session_start();
include('connection.php');

// Check if user is logged in (this assumes you are storing the user id in the session)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT full_name, email, username, jobseeker_id FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #00bcd4;
            color: white;
            padding: 10px 20px;
        }

        .navbar h1 {
            margin: 0;
        }

        .content {
            padding: 20px;
        }

        .profile-header {
            text-align: center;
        }

        .profile-header h2 {
            color: #003366;
        }

        .profile-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .profile-details p {
            font-size: 18px;
            margin: 10px 0;
        }

        .edit-profile-btn {
            display: block;
            background-color: #00bcd4;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            text-align: center;
            margin: 20px auto;
            cursor: pointer;
            width: 200px;
        }

        .edit-profile-btn:hover {
            background-color: #0097a7;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Hamro Job</h1>
    </div>

    <div class="content">
        <div class="profile-header">
            <h2>Welcome, <?php echo $user['full_name']; ?></h2>
            <p>Your Profile Review</p>
        </div>

        <div class="profile-details">
            <p><strong>Full Name:</strong> <?php echo $user['full_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Jobseeker ID:</strong> <?php echo $user['jobseeker_id']; ?></p>
        </div>

        <a href="edit_profile.php"><button class="edit-profile-btn">Edit Profile</button></a>
    </div>
</body>
</html>
