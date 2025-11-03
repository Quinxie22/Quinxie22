<?php
session_start();

if (empty($_SESSION["umail"])) {
    header('Location: AdminLogin.php');
    exit();
}
$userid = $_SESSION["umail"];

// Include database connection
include("database.php");

$new3 = $_GET['eno'];

// Query to print the existing record of the student
$sql = "SELECT * FROM studenttable WHERE Eno = $new3";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        :root {
            --primary-color: #66CCCC;
            --secondary-color: #F5F5DC;
            --accent-color: #9CD8F4;
            --bg-color: #F0E4CC;
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            --transition-speed: 0.3s;
            --link-hover-color: #dc3545;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1.2rem;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 70px; /* Space for the header */
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: 90%;
            max-width: 600px; /* Maximum width for the form */
        }

        .page-header {
            margin-bottom: 20px;
            font-size: 26px;
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

        .btn-success {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color var(--transition-speed);
            font-size: 1.1rem;
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

        /* Enhanced Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }

        th, td {
            padding: 15px; /* Increased padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1rem; /* Consistent font size */
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            font-size: 1.1rem; /* Slightly larger header font */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        tr:hover {
            background-color: #f1f1f1; /* Highlight on hover */
        }
    </style>
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra <i class="fas fa-seedling"></i>
    </a>
    <h3 class="welcome-msg">Welcome, <?php echo htmlspecialchars($userid); ?></h3>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
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
<div class="dashboard">
<h3 class="page-header">Update Student Details</h3>
    <div class="container">
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <form action="" method="POST" name="update">
                <div class="form-group">
                    Enrolment number: <?php echo htmlspecialchars($row['Eno']); ?>
                </div>
                <div class="form-group">
                    First Name: <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($row['FName']); ?>">
                </div>
                <div class="form-group">
                    Last Name: <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($row['LName']); ?>"><br>
                </div>
                <div class="form-group">
                    Parent Contact: <input type="text" name="paphone" class="form-control" value="<?php echo htmlspecialchars($row['Paphone']); ?>"><br>
                </div>
                <div class="form-group">
                    Address: <input type="text" name="addrs" class="form-control" value="<?php echo htmlspecialchars($row['Addrs']); ?>"><br>
                </div>
                <div class="form-group">
                    Gender: <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($row['Gender']); ?>"><br>
                </div>
                <div class="form-group">
                    Category: <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($row['category']); ?>" readonly><br>
                </div>
                <div class="form-group">
                    Subcategory: <input type="text" name="subcategory" class="form-control" value="<?php echo htmlspecialchars($row['subcategory']); ?>"><br>
                </div>
                <div class="form-group">
                    D.O.B.: <input type="text" name="DOB" class="form-control" value="<?php echo htmlspecialchars($row['DOB']); ?>" readonly><br>
                </div>
                <div class="form-group">
                    Phone Number: <input type="text" name="phno" class="form-control" value="<?php echo htmlspecialchars($row['PhNo']); ?>" maxlength="10"><br>
                </div>
                <div class="form-group">
                    Email: <input type="text" name="email" class="form-control" value="<?php echo htmlspecialchars($row['Eid']); ?>" readonly><br>
                </div>
                <div class="form-group">
                    Password: <input type="text" name="pass" class="form-control" value="<?php echo htmlspecialchars($row['Pass']); ?>"><br>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" value="Update" name="update" class="btn btn-success">
                </div>
            </form>
        <?php } ?>

        <?php
        if (isset($_POST['update'])) {
            $tempfname = mysqli_real_escape_string($conn, $_POST['fname']);
            $templname = mysqli_real_escape_string($conn, $_POST['lname']);
            $temppaphone = mysqli_real_escape_string($conn, $_POST['paphone']);
            $tempaddrs = mysqli_real_escape_string($conn, $_POST['addrs']);
            $tempgender = mysqli_real_escape_string($conn, $_POST['gender']);
            $tempcategory = mysqli_real_escape_string($conn, $_POST['category']);
            $tempsubcategory = mysqli_real_escape_string($conn, $_POST['subcategory']);
            $tempphno = mysqli_real_escape_string($conn, $_POST['phno']);
            $tempeid = mysqli_real_escape_string($conn, $_POST['email']);
            $temppass = mysqli_real_escape_string($conn, $_POST['pass']);

            // Update the existing record of the student
            $sql = "UPDATE studenttable SET FName='$tempfname', LName='$templname', Paphone='$temppaphone', 
                    Gender='$tempgender', category='$tempcategory', subcategory='$tempsubcategory', 
                    Addrs='$tempaddrs', PhNo='$tempphno', Eid='$tempeid', Pass='$temppass' 
                    WHERE Eno=$new3";

            if (mysqli_query($conn, $sql)) {
                echo "
                <br><br>
                <div class='alert alert-success fade in'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong> Student details have been updated.
                </div>
                ";
            } else {
                // Print error if SQL query fails
                echo "<br><strong>Student Update Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
            }

            // Close connection
            mysqli_close($conn);
        }
        ?>
    </div>
</div>

<!-- Footer -->
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