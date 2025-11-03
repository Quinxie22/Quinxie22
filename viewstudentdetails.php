<?php
session_start();

if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar styles -->
</head>
<body>

<div class="header-bar">
<a href="#" class="logo">
            <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
            <i class="fas fa-seedling"></i>
        </a>
    <div class="profile-info">
        <span>Welcome, <strong><?php echo htmlspecialchars($fname); ?></strong></span>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="sidebar" id="sidebar">
    <div class="menu-item">
        <a href="mydetailsfaculty.php?myfid=<?php echo $userid; ?>">
            <i class="fa fa-user"></i> My Profile
        </a>
    </div>
    <div class="menu-item">
        <a href="viewstudentdetails.php">
            <i class="fa fa-graduation-cap"></i> Student Details
        </a>
    </div>
    <div class="menu-item">
        <a href="assessment.php">
            <i class="fa fa-pencil-square"></i> Assessment Section
        </a>
    </div>
    <div class="menu-item">
        <a href="examDetails.php">
            <i class="fa fa-file"></i> Publish Result
        </a>
    </div>
    <div class="menu-item">
        <a href="resultdetails.php">
            <i class="fa fa-indent"></i> Edit Result
        </a>
    </div>
    <div class="menu-item">
        <a href="qureydetails.php">
            <i class="fa fa-question"></i> Student's Query
        </a>
    </div>
    <div class="menu-item">
        <a href="videos.php">
            <i class="fa fa-video-camera"></i> Videos
        </a>
    </div>
</div>

<div class="container">    
    <h2 class='page-header'>Student Details</h2> <br>
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" class="search-bar" name="search" placeholder="Search students..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button class="search-icon" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php
    include("database.php");

    // Construct SQL query based on search input
    $sql = "SELECT * FROM studenttable";
    if ($searchQuery) {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $sql .= " WHERE FName LIKE '%$searchQuery%' OR LName LIKE '%$searchQuery%' OR Eid LIKE '%$searchQuery%' OR Addrs LIKE '%$searchQuery%'";
    }

    $result = mysqli_query($conn, $sql);
    echo "<table class='table table-striped table-hover' style='width:100%'>
    <tr>
        <th>Enrolment No.</th>
        <th>Name</th>
        <th>Parent Contact</th>
        <th>Email</th>
        <th>Address</th>
        <th>Gender</th>
        <th>Specialty</th>
        <th>DOB</th>
        <th>Contact</th>
    </tr>";

    while ($row = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Eno']); ?></td>
            <td><?php echo htmlspecialchars($row['FName']); ?> <?php echo htmlspecialchars($row['LName']); ?></td>
            <td><?php echo htmlspecialchars($row['Paphone']); ?></td> 
            <td><?php echo htmlspecialchars($row['Eid']); ?></td>
            <td><?php echo htmlspecialchars($row['Addrs']); ?></td>
            <td><?php echo htmlspecialchars($row['Gender']); ?></td>
            <td><?php echo htmlspecialchars($row['subcategory']); ?></td>
            <td><?php echo htmlspecialchars($row['DOB']); ?></td>
            <td><?php echo htmlspecialchars($row['PhNo']); ?></td>
        </tr>
    <?php } ?>
    </table>
</div>
<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
    const sidebar = document.getElementById("sidebar");
    const toggleButton = document.querySelector(".toggle-btn");

    function toggleSidebar() {
        sidebar.classList.toggle("active");
    }

    document.addEventListener("click", function(event) {
        if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });

    sidebar.addEventListener("mouseenter", function() {
        sidebar.classList.add("active");
    });

    sidebar.addEventListener("mouseleave", function() {
        sidebar.classList.remove("active");
    });
</script>

</body>
</html>
