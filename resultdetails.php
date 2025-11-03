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
    <h3>Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>
    
    <div class="row">
        <div class="col-md-12">
            <h2 class='page-header'>Result Details</h2>
            
            <?php
            include('database.php');

            if (isset($_REQUEST['deleteid'])) {
                $deleteid = $_GET['deleteid'];
                $sql = "DELETE FROM `result` WHERE RsID = $deleteid";
                if (mysqli_query($conn, $sql)) {
                    echo "
                    <br><br>
                    <div class='alert alert-success fade in'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong>Success!</strong> Result details deleted.
                    </div>
                    ";
                } else {
                    echo "<br><strong>Result Details Deletion Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
                }
            }

            // Join result and student tables
            $sql = "SELECT r.RsID, r.Eno, r.Marks, s.FName, s.LName FROM result r JOIN studenttable s ON r.Eno = s.Eno";
            $rs = mysqli_query($conn, $sql);
            echo "<table class='table table-striped table-hover' style='width:100%'>
            <tr>
                <th>Result ID</th>
                <th>Enrolment No.</th>
                <th>Student Name</th>
                <th>Result</th>
                <th>Actions</th>		
            </tr>";

            while ($row = mysqli_fetch_array($rs)) {
                echo "<tr>
                    <td>{$row['RsID']}</td>
                    <td>{$row['Eno']}</td>
                    <td>" . htmlspecialchars($row['FName'] . " " . $row['LName']) . "</td>
                    <td>" . ($row['Marks'] == 'Pass' ? "<div style='color:green;'><b>{$row['Marks']}</b></div>" : ($row['Marks'] == 'Fail' ? "<div style='color:red;'><b>{$row['Marks']}</b></div>" : "<b>{$row['Marks']}</b>")) . "</td>
                    <td>
                        <a href='updateresultdetails.php?editid={$row['RsID']}'><input type='button' Value='Edit' class='btn btn-success btn-sm' style='border-radius:0%'></a>
                        <a href='resultdetails.php?deleteid={$row['RsID']}'><input type='button' Value='Delete' class='btn btn-danger btn-sm' style='border-radius:0%'></a>
                    </td>
                </tr>";
            }
            echo "</table>";

            mysqli_close($conn);
            ?>
        </div>
    </div>
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