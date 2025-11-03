<?php
session_start();

// Redirect if not logged in
if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin');
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

// Retrieve session variables
$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';


// Fetch profile picture from the database
include('database.php'); // Ensure database connection

$sql = "SELECT image FROM studenttable WHERE Eno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$image= $user['image'] ?? 'profile.jpg'; // Default image

// Fetch video details based on the video ID
$video_id = $_GET['viewid'] ?? null;
if ($video_id === null) {
    die("Invalid video ID.");
}

$sql = "SELECT * FROM video WHERE V_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();
$video = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Redirect if no video found
if (!$video) {
    header("Location: viewvideos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Video Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">

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
        <div class="video-card">
            <h1 class="video-title">Title: <?php echo htmlspecialchars($video['V_Title']); ?></h1>
            <div class="video-url">
                <?php echo $video['V_Url']; ?>
            </div>
            <p class="video-description">Video Description: <?php echo htmlspecialchars($video['V_Remarks']); ?></p>
            <a href="viewvideos.php" class="btn">Back</a>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
    </div>

    <script src="sidebar.js" defer></script>

</body>
</html>