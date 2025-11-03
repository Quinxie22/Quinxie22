<?php
session_start();

if ($_SESSION["fidx"] == "" || $_SESSION["fidx"] == NULL) {
    header('Location: facultylogin.php');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            transition: background 0.3s, color 0.3s;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 400px;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }

        .close {
            font-size: 22px;
            color: #777;
            cursor: pointer;
            transition: 0.2s;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            margin: 20px 0;
        }

        .modal-footer {
            text-align: right;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #aaa;
            color: white;
        }

        .btn-secondary:hover {
            background: #888;
        }

        /* Dark Mode Toggle */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 25px;
        }

        .toggle-switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ccc;
            border-radius: 25px;
            transition: 0.3s;
        }

        .slider::before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        input:checked + .slider {
            background: #4CAF50;
        }

        input:checked + .slider::before {
            transform: translateX(25px);
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
        <i class="fas fa-seedling"></i>
    </a>
    <div class="profile-info">
        <span>Welcome, <strong><?php echo htmlspecialchars($fname); ?></strong></span>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="menu-item"><a href="viewstudentdetails.php"><i class="fa fa-graduation-cap"></i> Student Details</a></div>
    <div class="menu-item"><a href="assessment.php"><i class="fa fa-pencil-square"></i> Assessment Section</a></div>
    <div class="menu-item"><a href="examDetails.php"><i class="fa fa-file"></i> Publish Result</a></div>
    <div class="menu-item"><a href="resultdetails.php"><i class="fa fa-indent"></i> Edit Result</a></div>
    <div class="menu-item"><a href="qureydetails.php"><i class="fa fa-question"></i> Student's Query</a></div>
    <div class="menu-item"><a href="videos.php"><i class="fa fa-video-camera"></i> Videos</a></div>
    <div class="menu-item"><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></div>
    <div class="menu-item"><a href="logoutfaculty.php"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
</div>

<!-- Main Content -->
<div class="container">
    <h3 class="text-center">Settings</h3>
    <button id="openSettings" class="btn btn-primary">Open Settings</button>
</div>

<!-- Settings Modal -->
<div id="settingsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Settings</h5>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p><strong>Edit My Profile</strong><br>
                <a href="edit_profile.php" class="btn btn-link">Edit Profile</a>
            </p>
            <p><strong>Dark Mode</strong></p>
            <div class="toggle-container">
                <label class="toggle-switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider"></span>
                </label>
                <span>Enable Dark Mode</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary close">Close</button>
        </div>
    </div>
</div>

<script>
    const settingsModal = document.getElementById("settingsModal");
    const openSettings = document.getElementById("openSettings");
    const closeButtons = document.querySelectorAll(".close");
    const darkModeToggle = document.getElementById("darkModeToggle");

    openSettings.onclick = () => settingsModal.style.display = "flex";
    closeButtons.forEach(btn => btn.onclick = () => settingsModal.style.display = "none");
    window.onclick = event => { if (event.target === settingsModal) settingsModal.style.display = "none"; };
    darkModeToggle.onchange = () => document.body.classList.toggle("dark-mode", darkModeToggle.checked);


    
    const sidebar = document.getElementById("sidebar");
    const toggleButton = document.querySelector(".toggle-btn");

    function toggleSidebar() {
        sidebar.classList.toggle("active");
    }

    document.addEventListener("click", function(event) {
        if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });

    sidebar.addEventListener("mouseenter", function() {
        sidebar.classList.add("active");
    });

    sidebar.addEventListener("mouseleave", function() {
        sidebar.classList.remove("active");
    });
</script>

</body>
</html>
