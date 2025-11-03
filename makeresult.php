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
    <title>Make Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Include your CSS for styling -->
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
    $make = $_GET['makeid'];
    // Selecting data from the result table in the database
    $sql = "SELECT * FROM examans WHERE ExamID=$make";
    $rs = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_array($rs)) {
        ?>
        <fieldset>
            <legend>Make Result</legend>
            <form action="" method="POST" name="makeresult">
                <table class="table table-hover">
                    <tr>
                        <td><strong>Enrolment Number:</strong></td>
                        <td><?php $eno = $row['Senrl']; echo htmlspecialchars($eno); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Exam ID:</strong></td>
                        <td><?php $ExamID = $row['ExamID']; echo htmlspecialchars($ExamID); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Marks:</strong></td>
                        <td>
                            <select class="form-control" name="marks" required>
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
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" name="make" class="btn btn-success" style="border-radius:0%">Publish</button>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
        <?php
    }

    if (isset($_POST['make'])) {
        $mark = $_POST['marks'];
        $sql = "INSERT INTO `result`(`Eno`, `Ex_ID`, `Marks`) VALUES ('$eno', '$ExamID', '$mark')";

        if (mysqli_query($conn, $sql)) {
            echo "
            <div class='alert alert-success fade in'>
                <a href='ResultDetails.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Success!</strong> Result Updated.
            </div>
            ";
        } else {
            // Error message if SQL query fails
            echo "<br><strong>Result Updation Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
        }
        
        // Close the connection
        mysqli_close($conn);
    }
    ?>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
    // Add any necessary JavaScript for sidebar toggle or other functionalities
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }
</script>

</body>
</html>