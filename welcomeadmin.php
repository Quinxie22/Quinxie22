<?php
session_start();

if (empty($_SESSION["umail"])) {
    header('Location: AdminLogin.php');
    exit();
}

$userid = $_SESSION["umail"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .course-card {
            text-decoration: none;
        }
        .course-card button {
            width: 100%;
            padding: 15px;
            border: none;
            background-color: #66CCCC;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .course-card button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra <i class="fas fa-seedling"></i>
    </a>
    <h3>Welcome <?php echo htmlspecialchars($userid); ?></h3>


</div>

<div class="sidebar" id="sidebar">
    <div class="menu-item" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i> <span>Menu</span>
    </div>
    <a href="studentdetails.php" class="menu-item"><i class="fa fa-graduation-cap"></i> <span>Student Details</span></a>
    <a href="facultydetails.php" class="menu-item"><i class="fa fa-users"></i> <span>Faculty Details</span></a>
    <a href="guestdetails.php" class="menu-item"><i class="fa fa-user"></i> <span>Guest Details</span></a>
    <a href="logoutadmin.php" class="menu-item" style="color: red;"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="dashboard">
    <div class="button-grid">
        <a href="studentdetails.php" class="course-card">
            <button><i class="fa fa-graduation-cap"></i> Student Details</button>
        </a>
        <a href="facultydetails.php" class="course-card">
            <button><i class="fa fa-users"></i> Faculty Details</button>
        </a>
        <a href="guestdetails.php" class="course-card">
            <button><i class="fa fa-user"></i> Guest Details</button>
        </a>
        <a href="logoutadmin.php" class="course-card">
            <button style="background-color: red;"><i class="fa fa-sign-out-alt"></i> Logout</button>
        </a>
    </div>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("expanded");
        document.querySelector(".dashboard").classList.toggle("expanded");
    }
</script>

</body>
</html>