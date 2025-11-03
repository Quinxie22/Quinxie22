<?php
session_start();

if ($_SESSION["umail"] == "" || $_SESSION["umail"] == NULL) {
    header('Location:AdminLogin.php');
}

$userid = $_SESSION["umail"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Faculty</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        :root {
            --primary-color: #66CCCC;
            --bg-color: #F0E4CC;
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid var(--primary-color);
            transition: border-color 0.3s;
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
            transition: background-color 0.3s;
            font-size: 1.1rem;
            width: 100%;
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
    <h3 class="welcome-msg">Welcome, <?php echo htmlspecialchars($userid); ?></h3>
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
    <h3 class="page-header">Add New Faculty</h3>
    <?php
    include("database.php");
    ?>
    <form action="" method="POST" name="addfaculty">
        <div class="form-group">
            <label for="fname">Faculty Name : <span style="color: #ff0000;">*</span></label>
            <input type="text" name="fname" class="form-control" id="fname" required>
        </div>

        <div class="form-group">
            <label for="faname">Father Name : <span style="color: #ff0000;">*</span></label>
            <input type="text" class="form-control" id="faname" name="faname" required>
        </div>

        <div class="form-group">
            <label for="addrs">Address : <span style="color: #ff0000;">*</span></label>
            <input type="text" class="form-control" name="addrs" required id="addrs">
        </div>

        <div class="form-group">
            <label for="gender">Gender :</label>
            <input type="radio" name="gender" value="Male" id="Gender_0" checked> Male
            <input type="radio" name="gender" value="Female" id="Gender_1"> Female
        </div>

        <div class="form-group">
            <label for="phno">Contact : <span style="color: #ff0000;">*</span></label>
            <input type="text" class="form-control" id="phno" name="phno" maxlength="10" required>
        </div>

        <div class="form-group">
            <label for="jdate">Joining Date : <span style="color: #ff0000;">*</span></label>
            <input type="date" class="form-control" id="jdate" name="jdate" placeholder="YYYY-MM-DD" required>
        </div>

        <div class="form-group">
            <label for="city">City : <span style="color: #ff0000;">*</span></label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>

        <div class="form-group">
            <label for="pass">Password : <span style="color: #ff0000;">*</span></label>
            <input type="password" class="form-control" name="pass" required id="pass">
        </div>

        <div class="form-group">
            <input type="submit" value="Add New Faculty" name="addnewfaculty" class="btn btn-success">
        </div>
    </form>

    <?php
    if (isset($_POST['addnewfaculty'])) {
        $tempfname = mysqli_real_escape_string($conn, $_POST['fname']);
        $tempfaname = mysqli_real_escape_string($conn, $_POST['faname']);
        $tempaddrs = mysqli_real_escape_string($conn, $_POST['addrs']);
        $tempgender = mysqli_real_escape_string($conn, $_POST['gender']);
        $tempphno = mysqli_real_escape_string($conn, $_POST['phno']);
        $tempjdate = mysqli_real_escape_string($conn, $_POST['jdate']);
        $tempcity = mysqli_real_escape_string($conn, $_POST['city']);
        $temppass = mysqli_real_escape_string($conn, $_POST['pass']);

        // Adding new faculty
        $sql = "INSERT INTO facutlytable (FName, FaName, Addrs, Gender, JDate, City, Pass, PhNo) 
                VALUES ('$tempfname', '$tempfaname', '$tempaddrs', '$tempgender', '$tempjdate', '$tempcity', '$temppass', '$tempphno')";

        if (mysqli_query($conn, $sql)) {
            echo "<br><div class='alert alert-success fade in'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                  <strong>Success!</strong> New Faculty Added. Faculty ID is: <strong>" . mysqli_insert_id($conn) . "</strong>
                  </div>";
        } else {
            // Error message if SQL query fails
            echo "<br><strong>New Faculty Adding Failure. Try Again</strong><br>Error Details: " . mysqli_error($conn);
        }
        // Close the connection
        mysqli_close($conn);
    }
    ?>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("expanded");
        document.querySelector(".container").classList.toggle("expanded");
    }
</script>

</body>
</html>