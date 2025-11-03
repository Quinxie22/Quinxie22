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
    <title>Update Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css">
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
    <h3> Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>

    <?php 
    include('database.php');
    $editid = $_GET['editid'];
    // Query to fetch existing result details
    $sql = "SELECT * FROM result WHERE RsID = $editid";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($result)) { 
    ?>
    <form action="" method="POST" name="update">
        <fieldset>
            <legend>Update Result Details</legend>
            <div class="form-group">
                Result ID: <?php echo htmlspecialchars($row['RsID']); ?>
            </div>
            <div class="form-group">
                Enrolment Number: <?php echo htmlspecialchars($row['Eno']); ?>
            </div>
            <div class="form-group">
                Marks:
                <select class="form-control" name="marks" required>
                    <option value="<?php echo htmlspecialchars($row['Marks']); ?>"><?php echo htmlspecialchars($row['Marks']); ?> (Current Result)</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="C+">C+</option>
                    <option value="C-">C-</option>
                    <option value="D+">D+</option>
                    <option value="D-">D-</option>
                    <option value="E+">E+</option>
                    <option value="E-">E-</option>
                    <option value="F+">F+</option>
                    <option value="F-">F-</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Result" name="update" class="btn btn-success" style="border-radius:0%">
            </div>
        </fieldset>
    </form>
    <?php
    }

    if (isset($_POST['update'])) {		
        $tempmarks = $_POST['marks'];	
        $sql = "UPDATE `result` SET Marks='$tempmarks' WHERE RsID=$editid"; 

        if (mysqli_query($conn, $sql)) {
            // Redirect to result details page after successful update
            header('Location: ResultDetails.php');
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "<div class='alert alert-danger fade in'>
                    <strong>Error:</strong> Result Updation Failure. Try Again. <br> Error Details: " . mysqli_error($conn) . "
                </div>";
        }
        // Close connection
        mysqli_close($conn);
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