<?php
session_start();

if ($_SESSION["sidx"] == "" || $_SESSION["sidx"] == NULL) {
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

// Get the user ID from the request
$varid = $_REQUEST['myds'];
$sql = "SELECT * FROM studenttable WHERE Eid='$varid'";
$result = mysqli_query($conn, $sql);

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | My Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <style>
        /* Custom styles */
        .profile-pic {
            width: 100px; 
            height: 100px; 
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .container {
            padding: 1rem; 
            max-width: 600px; 
            margin: auto; 
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .footer {
            text-align: center;
            padding: 1rem;
            background-color: #66CCCC;
            color: white;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #007bff;
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
            <legend>My Details</legend>
            <form action="" method="POST" name="update">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Profile Picture" class="profile-pic">
                <table class="table table-hover">
                    <tr>
                        <td><strong>Enrolment Number:</strong></td>
                        <td><?php echo htmlspecialchars($row['Eno']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>First Name:</strong></td>
                        <td><?php echo htmlspecialchars($row['FName']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Name:</strong></td>
                        <td><?php echo htmlspecialchars($row['LName']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Parent Phone Number:</strong></td>
                        <td><?php echo htmlspecialchars($row['Paphone']); ?></td> 
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td><?php echo htmlspecialchars($row['Addrs']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong></td>
                        <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Field of Studies:</strong></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td> <!-- Renamed category -->
                    </tr>
                    <tr>
                        <td><strong>Specialty:</strong></td>
                        <td><?php echo htmlspecialchars($row['subcategory']); ?></td> <!-- Renamed subcategory -->
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong></td>
                        <td><?php echo htmlspecialchars($row['DOB']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Contact:</strong></td>
                        <td><?php echo htmlspecialchars($row['PhNo']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($row['Eid']); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="updatedetailsfromstudent.php?eno=<?php echo htmlspecialchars($row['Eno']); ?>">
                                <input type="button" value="Edit" class="btn btn-info btn-sm">
                            </a>
                        </td>
                        <td>
                            <a href="welcomestudent.php?eno=<?php echo htmlspecialchars($row['Eno']); ?>">
                                <input type="button" value="Back" class="btn btn-info btn-sm">
                            </a>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
        <?php
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