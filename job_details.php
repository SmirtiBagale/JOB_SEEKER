<?php
include('connection.php');

// Check if the job ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Use prepared statement to fetch job details safely
    $sql = "SELECT job_title, description, requirements, posted_date, status FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
    } else {
        // Job not found, show user-friendly message
        echo "<p>Sorry, the job you are looking for does not exist.</p>";
        exit;
    }
} else {
    // Invalid request, show user-friendly message
    echo "<p>Invalid request. Please try again.</p>";
    exit;
}

// Assuming user ID and email are stored in session (or you can set static values for testing)
$user_id = 123; // Replace with actual user ID, from session or login
$applicant_email = "johndoe@example.com"; // Replace with actual email from session or input

// Set the applied date to the current date and time
$applied_date = date("Y-m-d H:i:s");

// Handle form submission
$application_success = false; // Variable to track if application is successful
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user has already applied for the job
    $sql_check = "SELECT * FROM job_applications WHERE job_id = ? AND user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $id, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // User has already applied, flag success to display popup
        $application_success = true;
    } else {
        // Insert application into the job_applications table (with applicant_name and applicant_email)
        $applicant_name = "John Doe"; // Replace with actual applicant name from session or input
        $sql_insert = "INSERT INTO job_applications (job_id, user_id, applicant_name, applicant_email, applied_date) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iisss", $id, $user_id, $applicant_name, $applicant_email, $applied_date);
        
        if ($stmt_insert->execute()) {
            // Application was successful, set flag
            $application_success = true;
        } else {
            // Display the error
            echo "Error: " . $stmt_insert->error;
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .navbar {
            background-color: #00bcd4;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
        }

        .content {
            padding: 20px;
            background-color: white;
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .content h1 {
            color: #003366;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
        }

        .apply-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #00bcd4;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .apply-btn:hover {
            background-color: #008c9e;
        }

        a {
            text-decoration: none;
            color: #00bcd4;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Success Popup */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
        }

        .popup button {
            background-color: #fff;
            color: #4CAF50;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .popup button:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Job Details</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>

    <div class="content">
        <h1><?php echo htmlspecialchars($job['job_title']); ?></h1>
        <p><strong>Posted on:</strong> <?php echo htmlspecialchars($job['posted_date']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($job['status']); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        <p><strong>Requirements:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>

        <!-- Apply button -->
        <form method="POST">
            <button type="submit" class="apply-btn">Apply</button>
        </form>
    </div>

    <!-- Success Popup (only shows when application is successful) -->
    <div class="popup-overlay" id="popup-overlay"></div>
    <div class="popup" id="popup">
        <p>Applied Successfully!!!</p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Show the popup if the application was successful
        <?php if ($application_success): ?>
            document.getElementById('popup-overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        <?php endif; ?>

        // Close the popup when the button is clicked
        function closePopup() {
            document.getElementById('popup-overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>
</html>
