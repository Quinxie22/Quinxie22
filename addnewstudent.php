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
    <title>Add New Student</title>
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
    <h3 class="page-header">Add New Student Details</h3>
    <form action="" method="POST" name="register" enctype="multipart/form-data">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="fname" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="lname" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Parent Contact:</label>
            <input type="text" name="paphone" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" name="DOB" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="addrs" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Contact:</label>
            <input type="tel" name="phno" class="form-control" maxlength="10" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="pass" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Category:</label>
            <select name="category" id="category" class="form-control" onchange="updateSubcategories()" required>
                <option value="">Select Category</option>
                <option value="Health">Health</option>
                <option value="Business Management">Business Management</option>
                <option value="Engineering">Engineering</option>
            </select>
        </div>

        <div class="form-group">
            <label>Subcategory:</label>
            <select name="subcategory" id="subcategory" class="form-control" required></select>
        </div>

        <div class="form-group">
            <input type="submit" value="Submit Details" name="addnews" class="btn btn-success">
        </div>
    </form>

    <?php
    include("database.php");
    if (isset($_POST['addnews'])) {
        $tempfname = mysqli_real_escape_string($conn, $_POST['fname']);
        $templname = mysqli_real_escape_string($conn, $_POST['lname']);
        $temppaphone = mysqli_real_escape_string($conn, $_POST['paphone']);
        $tempdob = mysqli_real_escape_string($conn, $_POST['DOB']);
        $tempaddrs = mysqli_real_escape_string($conn, $_POST['addrs']);
        $tempgender = mysqli_real_escape_string($conn, $_POST['gender']);
        $tempphno = mysqli_real_escape_string($conn, $_POST['phno']);
        $tempeid = mysqli_real_escape_string($conn, $_POST['email']);
        $temppass = mysqli_real_escape_string($conn, $_POST['pass']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $subcategory = mysqli_real_escape_string($conn, $_POST['subcategory']);

        // Correcting the SQL query to match the columns
        $sql = "INSERT INTO studenttable (FName, LName, Paphone, DOB, Addrs, Gender, PhNo, Eid, Pass, category, subcategory) 
                VALUES ('$tempfname', '$templname', '$temppaphone', '$tempdob', '$tempaddrs', '$tempgender', '$tempphno', '$tempeid', '$temppass', '$category', '$subcategory')";

        if (mysqli_query($conn, $sql)) {
            echo "<center><div class='alert alert-success fade in' style='margin-top:10px;'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>&times;</a>
                  <h3 style='margin-top: 10px; margin-bottom: 10px;'>Admission Confirmed! Enrolment Number is: 
                  <span style='color:black'><strong>" . mysqli_insert_id($conn) . "</strong></span></h3></div></center>";
        } else {
            echo "<br><strong>Admission Failure. Try Again</strong><br>Error Details: " . mysqli_error($conn);
        }
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

    function updateSubcategories() {
        const category = document.getElementById('category').value;
        const subcategorySelect = document.getElementById('subcategory');
        subcategorySelect.innerHTML = ''; // Clear previous options

        if (category === "Health") {
            subcategorySelect.innerHTML += '<option value="Nutrition">Nutrition</option>';
            subcategorySelect.innerHTML += '<option value="Fitness">Fitness</option>';
            subcategorySelect.innerHTML += '<option value="Nursing">Nursing</option>';

        } else if (category === "Business Management") {
            subcategorySelect.innerHTML += '<option value="Marketing">Marketing</option>';
            subcategorySelect.innerHTML += '<option value="Finance">Finance</option>';
            subcategorySelect.innerHTML += '<option value="Logistics">logistics</option>';
        } else if (category === "Engineering") {
            subcategorySelect.innerHTML += '<option value="Mechanical">Mechanical</option>';
            subcategorySelect.innerHTML += '<option value="Electrical">Electrical</option>';
            subcategorySelect.innerHTML += '<option value="Computer Science">Computer Science</option>';
        }
    }
</script>

</body>
</html>