<?php

include('../connection.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        echo "All fields are required.";
        exit;
    }


    $query = "INSERT INTO job_seeker (full_name,email, username, password) VALUES('$full_name', '$email','$username', '$password')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header('location: login.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>Jobseeker Registration Form | Hamro Job</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/register.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="title">Job Seeker Register</div>
        <form
            name="registrationForm"
            action="register.php"
            method="post"
            onsubmit="return validateForm()">
            <div class="field">
                <input type="text" id="full_name" name="full_name" />
                <label for="full_name">Full Name</label>
                <span class="error" id="full_name_error"></span>
            </div>
            <div class="field">
                <input type="email" id="email" name="email" />
                <label for="email">Email</label>
                <span class="error" id="email_error"></span>
            </div>
            <div class="field">
                <input type="text" id="username" name="username" />
                <label for="username">Username</label>
                <span class="error" id="username_error"></span>
            </div>
            <div class="field">
                <input type="password" id="password" name="password" />
                <label for="password">Password</label>
                <span class="error" id="password_error"></span>
            </div>
            <div class="field">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password" />
                <label for="confirm_password">Confirm Password</label>
                <span class="error" id="confirm_password_error"></span>
            </div>
            <div class="field">
                <input type="submit" value="Creat an Account" />
            </div>
            <div class="signup-link">
                Already a member? <a href="login.php">Login now</a>
            </div>
        </form>
    </div>
</body>

</html>