<?php
session_start();

if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar styles -->
</head>
<body>

<div class="header-bar">
<a href="#" class="logo">
            <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
            <i class="fas fa-seedling"></i>
        </a>    <div class="profile-info">
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
    <h3>Welcome Faculty: <a href="welcomefaculty.php"><span style="color:#FF0004;"> <?php echo htmlspecialchars($fname); ?></span></a></h3>
    
    <?php
    include('database.php');
    $varid = $_REQUEST['myfid'];
    $sql = "SELECT * FROM facutlytable WHERE FID='$varid'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
    ?>
        <fieldset>
            <legend>My Details</legend>
            <table class="table table-hover">
                <tr><td><strong>ID:</strong></td><td><?php echo $row['FID']; ?></td></tr>
                <tr><td><strong>Name:</strong></td><td><?php echo $row['FName']; ?></td></tr>
                <tr><td><strong>Father's Name:</strong></td><td><?php echo $row['FaName']; ?></td></tr>
                <tr><td><strong>Address:</strong></td><td><?php echo $row['Addrs']; ?></td></tr>
                <tr><td><strong>Gender:</strong></td><td><?php echo $row['Gender']; ?></td></tr>
                <tr><td><strong>Date of Joining:</strong></td><td><?php echo $row['JDate']; ?></td></tr>
                <tr><td><strong>City:</strong></td><td><?php echo $row['City']; ?></td></tr>
                <tr><td><strong>Phone Number:</strong></td><td><?php echo $row['PhNo']; ?></td></tr>
                <tr>
                    <td colspan="2">
                        <a href="updatedetailsfromfaculty.php?myfid=<?php echo $row['FID']; ?>">
                            <button type="button" class="btn btn-info">Edit</button>
                        </a>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php
    }
    ?>
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


