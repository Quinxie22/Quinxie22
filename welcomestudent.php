<?php
// Start session and handle user authentication
ob_start();
session_start();

if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin');
    exit();
}

// Define timeout duration (e.g., 5 minutes)
$timeout_duration = 600; // 300 seconds = 5 minutes

// Check if the session is timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("Location: studentlogin"); // Redirect to login
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time


$userid = $_SESSION["sidx"]; // This corresponds to Eno in studenttable
$userfname = $_SESSION["fname"];
$sEno = $_SESSION["seno"];
$userlname = $_SESSION["lname"];
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';

include('database.php'); // Ensure this file contains the database connection

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch courses and their progress from the database
$sql = "SELECT v.V_id AS course_id, v.V_Title AS name, p.last_completed_section, p.completion_percentage
        FROM video v
        LEFT JOIN progress p ON v.V_id = p.V_id AND p.Eno = ?";

if (!empty($searchQuery)) {
    $sql .= " WHERE v.V_Title LIKE ?";
}

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("SQL Error: " . $conn->error);
}

if (!empty($searchQuery)) {
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("is", $userid, $searchParam);
} else {
    $stmt->bind_param("i", $userid);
}

$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Student Dashboard</title>
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

    <div class="dashboard" id="dashboard">
        <div class="welcome-card">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile Image" class="profile-image">
            <h1>Welcome back, <?php echo htmlspecialchars($userfname . " " . $userlname); ?>!</h1>
        </div>

        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <h2>
                    <a href="viewvideos.php?V_id=<?php echo $course['course_id']; ?>">
                        <?php echo htmlspecialchars($course["name"]); ?>
                    </a>
                </h2>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $course["completion_percentage"]; ?>%;">
                        <?php echo $course["completion_percentage"]; ?>%
                    </div>
                </div>
                <p>Last Completed Section: <?php echo htmlspecialchars($course["last_completed_section"]); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
    </div>

    <script src="sidebar.js" defer></script>
</body>
</html>
<?php ob_end_flush(); ?>