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
    <title>Manage Assessment</title>
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
    <h3>Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>

    <?php
    include("database.php");

    // Handle delete request
    if (isset($_GET['deleteid'])) {
        $deleteid = intval($_GET['deleteid']);
        $sql = "DELETE FROM examdetails WHERE ExamID = $deleteid";

        if (mysqli_query($conn, $sql)) {
            echo "<br><br>
                <div class='alert alert-success fade in'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong> Assessment details deleted.
                </div>";
        } else {
            echo "<br><strong>Error deleting assessment:</strong> " . mysqli_error($conn);
        }
    }

    // Fetch exam details with questions
    $sql = "SELECT ed.ExamID, ed.ExamName, ed.Duration, eq.Question
            FROM examdetails ed
            LEFT JOIN examquestions eq ON ed.ExamID = eq.ExamID
            ORDER BY ed.ExamID, eq.QuestionID";
    $rs = mysqli_query($conn, $sql);

    $exams = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $exams[$row['ExamID']]['ExamName'] = $row['ExamName'];
        $exams[$row['ExamID']]['Duration'] = $row['Duration'];
        $exams[$row['ExamID']]['Questions'][] = $row['Question'];
    }

    echo "<h2 class='page-header'>Assessment Details</h2>";
    echo "<table class='table table-striped table-hover' style='width:100%'>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Duration (min)</th>
                <th>Questions</th>
                <th>Actions</th>
            </tr>";

    $cnt = 1;
    foreach ($exams as $examID => $exam) {
        echo "<tr>
                <td>{$cnt}</td>
                <td>{$exam['ExamName']}</td>
                <td>{$exam['Duration']}</td>
                <td>";
        if (!empty($exam['Questions'])) {
            foreach ($exam['Questions'] as $question) {
                echo "- " . htmlspecialchars($question) . "<br>";
            }
        } else {
            echo "<i>No questions added</i>";
        }
        echo "</td>
                <td>
                    <a href='manageassessment.php?deleteid={$examID}'> 
                        <input type='button' value='Delete' class='btn btn-danger btn-sm' style='border-radius:0%'>
                    </a>
                    <a href='manageassessment2.php?editassid={$examID}'> 
                        <input type='button' value='Edit' class='btn btn-success btn-sm' style='border-radius:0%'>
                    </a>
                </td>
            </tr>";
        $cnt++;
    }

    echo "</table>";
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