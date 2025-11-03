<?php
// Start session and handle user authentication
session_start();

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

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';

// Include database connection
include('database.php');

// Fetch Eid based on user's email
$sql = "SELECT Eid FROM studenttable WHERE 	Eid = ?"; // Adjust 'email' to your actual column name
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["Eid"]); // Assuming the email is stored in the session
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $seid = $row['Eid']; // Get the Eid from the result
} else {
    echo "<script>alert('Error: No record found for the given email.'); window.location.href='viewquery.php';</script>";
    exit();
}

// Query to fetch existing records of queries for the specific Eid
$sql = "SELECT * FROM query WHERE Eid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seid); // Assuming Eid is an integer
$stmt->execute();
$rs = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | My Queries</title>
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
        <div class="menu-item">
            <a href="mydetailsstudent.php?myds=<?php echo htmlspecialchars($userid); ?>">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewvideos.php?eid=<?php echo htmlspecialchars($userid); ?>">
                <i class="fas fa-play-circle"></i>
                <span>My Courses</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewresult.php?seno=<?php echo htmlspecialchars($sEno); ?>">
                <i class="fas fa-chart-bar"></i>
                <span>View Results</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewquery.php">
                <i class="fas fa-question"></i>
                <span>Queries</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="takeassessment.php">
                <i class="fas fa-file-alt"></i>
                <span>Assessment</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="logoutstudent.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <h3> Welcome <span style='color:red'><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span></h3>
                <h2 class='page-header'>My Queries</h2>
                <table class='table table-striped table-hover' style='width:100%'>
                    <tr>
                        <th>#</th>
                        <th>Query</th>
                        <th>Answer</th>						
                    </tr>
                    <?php
                    $count = 1;
                    while ($row = $rs->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo htmlspecialchars($row['Query']); ?></td>
                        <td><?php echo htmlspecialchars($row['Ans']); ?></td>
                    </tr>
                    <?php
                    $count++;
                    }
                    ?>
                </table>
                <a href="askquery.php?eid=<?php echo htmlspecialchars($userid); ?>"> 
                    <button type="button" class="btn btn-success" style="border-radius:0%">Ask New Query</button>
                </a>
            </div>

            <div class="col-md-2"></div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
    </div>

    <script src="sidebar.js" defer></script>
</body>
</html>