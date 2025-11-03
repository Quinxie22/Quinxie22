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

// Include database connection
include('database.php');

// Ensure $conn is initialized
if (!isset($conn)) {
    die("Database connection is not initialized.");
}

// Fetch student category and subcategory
$student_sql = "SELECT category, subcategory FROM studenttable WHERE Eno = ?";
$student_stmt = $conn->prepare($student_sql);
$student_stmt->bind_param('i', $userid);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student_info = $student_result->fetch_assoc();

// Check if $student_info is null
if (!$student_info) {
    // Set default category and subcategory if not found
    $student_category = "Engineering"; // Default category
    $student_subcategory = "Computer Science"; // Default subcategory
} else {
    // Fetch the category and subcategory from the result
    $student_category = $student_info['category'];
    $student_subcategory = $student_info['subcategory'];
}

// Define search query variable
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
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

        <?php 
        // Search functionality
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Fetch videos based on category and subcategory
        $sql = "SELECT 
                    v.V_id, 
                    v.V_Title, 
                    v.V_Remarks, 
                    IFNULL(p.progress, 0) AS progress 
                FROM video v
                LEFT JOIN progress p ON v.V_id = p.V_id AND p.Eno = ?
                WHERE v.V_Title LIKE ?
                AND v.category = ?
                AND v.subcategory = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $searchTerm = "%" . $search . "%";
            $stmt->bind_param('isss', $userid, $searchTerm, $student_category, $student_subcategory);
            $stmt->execute();
            $result = $stmt->get_result();

            echo "<h2 class='page-header'>Videos Details</h2>";
            echo "<table class='table table-striped'>
                <tr>
                    <th>#</th>
                    <th>Video Title</th>
                    <th>Description</th>
                    <th>Progress</th>
                    <th>View</th>        
                </tr>";

            $count = 1;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo htmlspecialchars($row['V_Title']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Remarks']); ?></td>
                    <td>
                        <div style="width: 100%; background-color: #e0e0e0; border-radius: 5px; overflow: hidden;">
                            <div style="width: <?php echo $row['progress']; ?>%; background-color: #28a745; color: white; text-align: center; padding: 2px 0;">
                                <?php echo $row['progress']; ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="viewvideos2.php?viewid=<?php echo $row['V_id']; ?>"> 
                            <input type="button" value="View" class="btn btn-info btn-sm">
                        </a>
                    </td>
                </tr>
                <?php
                $count++;
            }
            echo "</table>";
        } else {
            echo "<p>Error preparing statement: " . $conn->error . "</p>";
        }
        ?>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
    </div>

    <script src="sidebar.js" defer></script>

</body>
</html>

<?php 
// Ensure the database connection is closed
$conn->close();
?>
