<?php
session_start();

if (empty($_SESSION["umail"])) {
    header('Location: AdminLogin.php');
    exit();
}

$userid = htmlspecialchars($_SESSION["umail"]); // Sanitize user ID for output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Guest Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        :root {
            --primary-color: #66CCCC;
            --bg-color: #F0E4CC;
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding-top: 70px; /* Space for the header */
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .page-header {
            font-size: 26px;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid var(--primary-color);
            transition: border-color var(--transition-speed);
            font-size: 1.1rem;
        }

        .form-control:focus {
            border-color: #004494;
            outline: none;
        }

        .btn {
            border-radius: 0;
            transition: background-color var(--transition-speed);
        }

        .btn-success {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: var(--shadow);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert a.close {
            float: right;
            font-weight: bold;
            color: #000;
            text-decoration: none;
        }

        .alert a.close:hover {
            color: red;
        }
    </style>
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra <i class="fas fa-seedling"></i>
    </a>
    <h3 class="welcome-msg">Welcome, <?php echo $userid; ?></h3>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="menu-item" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i> <span>Menu</span>
    </div>
    <a href="studentdetails.php" class="menu-item"><i class="fa fa-graduation-cap"></i> <span>Student Details</span></a>
    <a href="facultydetails.php" class="menu-item"><i class="fa fa-users"></i> <span>Faculty Details</span></a>
    <a href="guestdetails.php" class="menu-item"><i class="fa fa-user"></i> <span>Guest Details</span></a>
    <a href="logoutadmin.php" class="menu-item" style="color: red;"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<!-- Main Content -->
<div class="container">
    <h3 class="page-header">Welcome <a href="welcomeadmin">Admin</a> </h3>
    
    <?php
    include("database.php");
    $new2 = $_GET['gid'];
    $sql = "SELECT * FROM guest WHERE GuEid='$new2'";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <form action="" method="POST" name="update">
            <div class="form-group">
                <label>Guest Email ID:</label>
                <div><?php echo $row['GuEid']; ?></div>
            </div>
            <div class="form-group">
                <label>Guest Name:</label>
                <input type="text" name="gname" class="form-control" value="<?php echo $row['Gname']; ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" name="update" class="btn btn-success">
            </div>
        </form>
        <?php
    }

    if (isset($_POST['update'])) {
        $tempgname = mysqli_real_escape_string($conn, $_POST['gname']);
        // Update the existing record of guest
        $sql = "UPDATE `guest` SET Gname='$tempgname' WHERE GuEid='$new2'";
        
        if (mysqli_query($conn, $sql)) {
            echo "<br><div class='alert alert-success fade in'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong> Guest details have been updated.
                </div>";
        } else {
            // Print error if SQL query fails
            echo "<br><strong>Guest Details Updating Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
        }
        // Close the connection
        mysqli_close($conn);
    }
    ?>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("expanded");
        document.querySelector(".container").classList.toggle("expanded");
    }
</script>

</body>
</html>