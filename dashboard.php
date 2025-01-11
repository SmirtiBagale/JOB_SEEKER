<?php include('connection.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro Job Dashboard</title>
    
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

        .search-container {
            display: flex;
            gap: 5px;
        }

        .search-container input {
            padding: 5px;
            border: none;
            border-radius: 5px;
        }

        .search-container button {
            padding: 5px 10px;
            border: none;
            background-color: white;
            color: #00bcd4;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #e0f7fa;
        }

        .icons span {
            margin: 0 10px;
            cursor: pointer;
            position: relative;
        }

        .icons span::after {
            content: attr(title);
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .icons span:hover::after {
            visibility: visible;
            opacity: 1;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .profile-dropdown a {
            color: #003366;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }

        .profile-dropdown a:hover {
            background-color: #e0f7fa;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            color: #003366;
        }

        #job-list {
            list-style-type: none;
            padding: 0;
        }

        #job-list li {
            background-color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        #job-list li a {
            text-decoration: none;
            color: #003366;
            display: inline-block;
            width: 100%;
        }

        #job-list li a:hover {
            text-decoration: underline;
        }

        /* Tooltip styling on hover */
        #job-list li a:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -40px;
            left: 10px;
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Hamro Job</h1>
        <div class="search-container">
            <input type="text" id="search" placeholder="Search jobs...">
            <button onclick="searchJobs()">Search</button>
        </div>
        <div class="icons">
            <span id="Home" title="Home">🏠</span>
            <span id="Applied jobs" title="Applied Jobs">📝</span>
            <span id="Profile" title="Profile" onclick="toggleDropdown()">👤</span>
            <div id="profile-dropdown" class="profile-dropdown">
                <a href="my_profile.php">My Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h2>Available Jobs</h2>
        <ul id="job-list">
            <?php
            $sql = "SELECT id, job_title, description, posted_date FROM jobs WHERE status = 'Available'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>
                            <a href='job_details.php?id=" . $row['id'] . "' 
                               title='Description: " . $row['description'] . " | Posted on: " . $row['posted_date'] . "'>
                               " . $row['job_title'] . "
                            </a>
                          </li>";
                }
            } else {
                echo "<p>No available jobs found.</p>";
            }
            ?>
        </ul>
    </div>
    <script>
        function searchJobs() {
            const searchValue = document.getElementById('search').value.toLowerCase();
            const jobs = document.querySelectorAll('#job-list li');

            jobs.forEach(job => {
                if (job.textContent.toLowerCase().includes(searchValue)) {
                    job.style.display = '';
                } else {
                    job.style.display = 'none';
                }
            });
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        window.onclick = function(event) {
            if (!event.target.matches('#Profile')) {
                const dropdown = document.getElementById('profile-dropdown');
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
