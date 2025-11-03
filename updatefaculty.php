<?php
session_start();

if ($_SESSION["umail"] == "" || $_SESSION["umail"] == NULL) {
    header('Location: AdminLogin.php');
}

$userid = $_SESSION["umail"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Faculty Details</title>
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
    <h3 class='page-header'>Update Faculty Details</h3>
    <?php
    include("database.php");
    $new2 = $_GET['fid'];

    $sql = "SELECT * FROM facutlytable WHERE FID = $new2";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($result)) {
    ?>
        <form action="" method="POST" name="update">
            <div class="form-group">
                Faculty ID: <?php echo $row['FID']; ?>
            </div>
            <div class="form-group">
                Faculty Name: <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($row['FName']); ?>">
            </div>
            <div class="form-group">
                Father Name: <input type="text" name="faname" class="form-control" value="<?php echo htmlspecialchars($row['FaName']); ?>"><br>
            </div>
            <div class="form-group">
                Address: <input type="text" name="addrs" class="form-control" value="<?php echo htmlspecialchars($row['Addrs']); ?>"><br>
            </div>
            <div class="form-group">
                Gender: <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($row['Gender']); ?>"><br>
            </div>
            <div class="form-group">
                Phone Number: <input type="tel" name="phno" class="form-control" value="<?php echo htmlspecialchars($row['PhNo']); ?>" maxlength="10"><br>
            </div>
            <div class="form-group">
                Joining Date: <input type="date" name="jdate" class="form-control" value="<?php echo htmlspecialchars($row['JDate']); ?>" readonly> <br>
            </div>
            <div class="form-group">
                City: <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($row['City']); ?>"><br>
            </div>
            <div class="form-group">
                Password: <input type="text" name="pass" class="form-control" value="<?php echo htmlspecialchars($row['Pass']); ?>" maxlength="10"><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Make Changes" name="update" class="btn btn-success">
            </div>
        </form>
    <?php
    }

    if (isset($_POST['update'])) {
        $tempfname = mysqli_real_escape_string($conn, $_POST['fname']);
        $tempfaname = mysqli_real_escape_string($conn, $_POST['faname']);
        $tempaddrs = mysqli_real_escape_string($conn, $_POST['addrs']);
        $tempgender = mysqli_real_escape_string($conn, $_POST['gender']);
        $tempphno = mysqli_real_escape_string($conn, $_POST['phno']);
        $tempcity = mysqli_real_escape_string($conn, $_POST['city']);
        $temppass = mysqli_real_escape_string($conn, $_POST['pass']);

        // SQL query to update the existing faculty
        $sql = "UPDATE facutlytable SET FName='$tempfname', FaName='$tempfaname', Addrs='$tempaddrs', 
                Gender='$tempgender', City='$tempcity', Pass='$temppass', PhNo='$tempphno' WHERE FID=$new2";

        if (mysqli_query($conn, $sql)) {
            echo "
            <div class='alert alert-success fade in'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Success!</strong> Faculty details have been updated.
            </div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating record: " . mysqli_error($conn) . "</div>";
        }

        // Close connection
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