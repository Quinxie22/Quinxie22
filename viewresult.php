<?php
session_start();

if ($_SESSION["sidx"] == "" || $_SESSION["sidx"] == NULL) {
    header('Location:studentlogin');
    exit();
}

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';


$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | View Results</title>
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
    <div class="row">
        <div class="col-md-12">
            <h3>Welcome <span style='color:red'><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span></h3>
            <?php 
                include('database.php');
                $seno = $_GET['seno'];
                // Query to fetch existing results
                $sql = "SELECT * FROM result WHERE Eno='$seno'";
                $rs = mysqli_query($conn, $sql);
                echo "<h2 class='page-header'>Result View</h2>";
                echo "<table class='table table-striped table-hover' style='width:100%'>
                <tr>
                <th>#</th>
                <th>Result ID</th>
                <th>Enrolment Number</th>
                <th>Marks</th>
                </tr>";
                $count = 1;
                while ($row = mysqli_fetch_array($rs)) {
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['RsID']; ?></td>
                <td><?php echo $row['Eno']; ?></td>
                <td>
                    <?php if ($row['Marks'] == 'Pass') {
                        echo '<div style="color:green;"><b>' . $row['Marks'] . '</b></div>';
                    } else {
                        echo '<div style="color:red;"><b>' . $row['Marks'] . '</b></div>';
                    } ?>
                </td>
            </tr>
            <?php
                $count++;
                }
            ?>
            </table>
        </div>
    </div>
</div>
<script src="sidebar.js" defer></script>

</body>
</html>