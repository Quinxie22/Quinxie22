<?php
session_start();

if ($_SESSION["sidx"] == "" || $_SESSION["sidx"] == NULL) {
    header('Location:studentlogin.php');
    exit();
}

// Define timeout duration (e.g., 5 minutes)
$timeout_duration = 300; // 300 seconds = 5 minutes

// Check if the session is timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("Location: studentlogin"); // Redirect to login
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time


$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';

// If $_SESSION["image"] is not set, you can default it here
if (!isset($_SESSION["image"])) {
    $_SESSION["image"] = 'profile.jpg';
}

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Include database connection
include('database.php');

$new3 = $_GET['eno'];

// Fetch student details
$sql = "SELECT * FROM studenttable WHERE Eno=$new3";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Update Student Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <style>   
        .profile-pic {
            width: 100px; /* Fixed width */
            height: 100px; /* Fixed height */
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="header-bar">
    <a href="welcomestudent.php" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
        <i class="fas fa-seedling"></i>
    </a>
    
    <form method="GET" action="" class="search-container">
        <input type="text" name="search" class="search-bar" placeholder="Search courses..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="search-icon"><i class="fas fa-search"></i></button>
    </form>
    
    <div class="profile-info">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile Image" class="profile-image">
        <span><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span>
    </div>

    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="sidebar" id="sidebar">
    <div class="menu-item" data-tooltip="My Profile">
        <a href="mydetailsstudent.php?myds=<?php echo htmlspecialchars($userid); ?>">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </a>
        <div class="description-card">View and edit your profile information.</div>
    </div>
    <div class="menu-item" data-tooltip="My Courses">
        <a href="viewvideos.php?eid=<?php echo htmlspecialchars($userid); ?>">
            <i class="fas fa-play-circle"></i>
            <span>My Courses</span>
        </a>
        <div class="description-card">Access your enrolled courses and materials.</div>
    </div>
    <div class="menu-item" data-tooltip="View Results">
        <a href="viewresult.php?seno=<?php echo htmlspecialchars($sEno); ?>">
            <i class="fas fa-chart-bar"></i>
            <span>View Results</span>
        </a>
        <div class="description-card">Check your academic results and performance.</div>
    </div>
    <div class="menu-item" data-tooltip="Queries">
        <a href="viewquery.php">
            <i class="fas fa-question"></i>
            <span>Queries</span>
        </a>
        <div class="description-card">Ask questions and get support.</div>
    </div>
    <div class="menu-item" data-tooltip="Assessment">
        <a href="takeassessment.php">
            <i class="fas fa-file-alt"></i>
            <span>Assessment</span>
        </a>
        <div class="description-card">Take assessments and quizzes.</div>
    </div>
    <div class="menu-item" data-tooltip="Logout">
        <a href="logoutstudent.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <div class="description-card">Sign out of your account.</div>
    </div>
</div>

<div class="container">
    <?php
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <fieldset>
        <form action="" method="POST" name="update" enctype="multipart/form-data">
            <div class="form-group profile-pic-container">
                <img src="<?php echo htmlspecialchars($row['image'] ? $row['image'] : 'profile.jpg'); ?>" alt="Profile Picture" class="profile-pic">
                <input type="file" name="profile_pic" class="file-input" accept="image/*">
            </div>
            <div class="form-group">
                <label>Enrolment number:</label> <?php echo htmlspecialchars($row['Eno']); ?>
            </div>
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($row['FName']); ?>">
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($row['LName']); ?>">
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="addrs" class="form-control" value="<?php echo htmlspecialchars($row['Addrs']); ?>">
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($row['Gender']); ?>">
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phno" class="form-control" value="<?php echo htmlspecialchars($row['PhNo']); ?>" maxlength="10">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="text" name="email" class="form-control" value="<?php echo htmlspecialchars($row['Eid']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="text" name="pass" class="form-control" value="<?php echo htmlspecialchars($row['Pass']); ?>">
            </div>
            <div class="form-group">
                <label>Field of Studies:</label>
                <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($row['category']); ?>" readonly> <!-- Read-only -->
            </div>
            <div class="form-group">
                <label>Specialty:</label>
                <input type="text" name="subcategory" class="form-control" value="<?php echo htmlspecialchars($row['subcategory']); ?>" readonly> <!-- Read-only -->
            </div>
            <div class="form-group">
                <label>Parent's Phone Number:</label>
                <input type="text" name="paphone" class="form-control" value="<?php echo htmlspecialchars($row['Paphone']); ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="Update!" name="update" class="btn btn-info btn-sm">
                <a href="welcomestudent.php?eno=<?php echo htmlspecialchars($row['Eno']); ?>">
                    <input type="button" value="Back" class="btn btn-info btn-sm">
                </a>
            </div>
        </form>
    </fieldset>
        <?php
    }
    ?>

    <?php
    if (isset($_POST['update'])) {
        $name = $_FILES['profile_pic']['name'];
        $tmp_name = $_FILES['profile_pic']['tmp_name'];

        // Handling file upload if a new image is selected
        if (!empty($name)) {
            $location = 'uploads/' . $name;
            move_uploaded_file($tmp_name, $location);
        } else {
            $location = $_SESSION["image"];
        }

        $update_query = "UPDATE studenttable SET 
            FName = '" . $_POST['fname'] . "', 
            LName = '" . $_POST['lname'] . "', 
            Addrs = '" . $_POST['addrs'] . "', 
            Gender = '" . $_POST['gender'] . "', 
            PhNo = '" . $_POST['phno'] . "', 
            Paphone = '" . $_POST['paphone'] . "', 
            Pass = '" . $_POST['pass'] . "',
            image = '$location'
            WHERE Eno = '$new3'";

        $run_query = mysqli_query($conn, $update_query);

        if ($run_query) {
            $_SESSION["image"] = $location;
            // Redirect to the profile page with a success message
            echo "<script>
                alert('Profile updated successfully!');
                window.location.href = 'mydetailsstudent.php?myds=$userid';
            </script>";
        } else {
            echo "<div class='alert' style='color:red;'>Error: Unable to update</div>";
        }
    }
    ?>

</div>

<script src="sidebar.js" defer></script>

</body>
</html>

<?php 
// Ensure the database connection is closed
$conn->close();
?>
