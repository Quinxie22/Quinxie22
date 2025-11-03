<?php
session_start();

if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin.php');
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

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';

include('database.php'); // Ensure this file contains the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Take Assessment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">

</head>
<body>
<div class="header-bar">
    <a href="welcomestudent.php" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
        <i class="fas fa-seedling"></i>
    </a>
    
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

<div class="container mt-5">
    <h3>Welcome <span style='color:red'><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span></h3>
    <h2 class="mb-4">Take Assessment</h2>

    <?php 
    // Query to fetch existing records of Assessment
    $sql = "SELECT * FROM examdetails";
    $rs = mysqli_query($conn, $sql);
    ?>
    
    <table class='table table-striped table-hover'>
        <thead>
            <tr>
                <th>#</th>
                <th>Assessment Name</th>
                <th>Action</th>					
            </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        while ($row = mysqli_fetch_array($rs)) {
        ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo htmlspecialchars($row['ExamName']); ?></td>
                <td>
                    <a href="takeassessment2.php?exid=<?php echo $row['ExamID']; ?>">
                        <button type="button" class="btn btn-success" style="border-radius:0%">Start</button>
                    </a>
                </td>
            </tr>
        <?php
            $count++;
        }
        ?>
        </tbody>
    </table>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>
<script src="sidebar.js" defer></script>

</body>
</html>