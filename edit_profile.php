<?php
session_start();
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$sql = "SELECT full_name, email, username, profile_picture, cv_file FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new profile data from the form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Handle file uploads
    $profile_picture = $user['profile_picture']; // Default to current profile picture
    $cv_file = $user['cv_file']; // Default to current CV file

    // Profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture_name = $_FILES['profile_picture']['name'];
        $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
        $profile_picture_ext = pathinfo($profile_picture_name, PATHINFO_EXTENSION);
        $profile_picture_new_name = 'profile_' . $user_id . '.' . $profile_picture_ext;

        $upload_dir = 'uploads/profile_pictures/';
        if (move_uploaded_file($profile_picture_tmp, $upload_dir . $profile_picture_new_name)) {
            $profile_picture = $upload_dir . $profile_picture_new_name;
        } else {
            echo "Error uploading profile picture.";
        }
    }

    // CV file upload
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {
        $cv_file_name = $_FILES['cv_file']['name'];
        $cv_file_tmp = $_FILES['cv_file']['tmp_name'];
        $cv_file_ext = pathinfo($cv_file_name, PATHINFO_EXTENSION);
        $cv_file_new_name = 'cv_' . $user_id . '.' . $cv_file_ext;

        $upload_dir = 'uploads/cvs/';
        if (move_uploaded_file($cv_file_tmp, $upload_dir . $cv_file_new_name)) {
            $cv_file = $upload_dir . $cv_file_new_name;
        } else {
            echo "Error uploading CV file.";
        }
    }

    // Update the profile in the database
    $update_sql = "UPDATE users SET full_name = '$full_name', email = '$email', username = '$username', profile_picture = '$profile_picture', cv_file = '$cv_file' WHERE user_id = '$user_id'";
    if ($conn->query($update_sql)) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .submit-btn {
            background-color: #00bcd4;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0097a7;
        }

        .file-input {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>Edit Profile</h1>
    </div>

    <div class="content">
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" class="file-input" accept="image/*">

                <label for="cv_file">Upload CV:</label>
                <input type="file" name="cv_file" class="file-input" accept=".pdf,.doc,.docx,.txt">

                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
