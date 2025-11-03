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
$sEno = $_SESSION["seno"];
$exid = $_GET['exid'];

include('database.php'); // Ensure this file contains the database connection

// Query to fetch student and exam details
$sql = "SELECT * FROM studenttable WHERE Eno='$sEno'";
$sql2 = "SELECT * FROM examdetails WHERE ExamID='$exid'";
$sql3 = "SELECT QuestionID, Question FROM examquestions WHERE ExamID='$exid'";
$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Check if the student query returned a result
if (mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_array($result);
} else {
    die("Student not found.");
}

// Check if the exam details query returned a result
if (mysqli_num_rows($result2) > 0) {
    $examDetails = mysqli_fetch_array($result2);
    $examDurationInMinutes = $examDetails['Duration']; // Duration in minutes
} else {
    die("Exam details not found.");
}

// Start output buffering
ob_start(); // Start buffering output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Assessment Details</title>
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

<div class="container mt-5" style="margin-top: 70px;">
    <h3>Welcome <span style='color:red'><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span></h3>

    <fieldset class="border p-4">
        <legend class="w-auto">Assessment Details</legend>
        
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td><strong>Enrolment Number:</strong></td>
                        <td><?php echo htmlspecialchars($student['Eno']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Student's Name:</strong></td>
                        <td><?php echo htmlspecialchars($student['FName'] . " " . $student['LName']); ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td><strong>Course:</strong></td>
                        <td><?php echo htmlspecialchars($student['subcategory']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Applied For:</strong></td>
                        <td><?php echo htmlspecialchars($exid); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>
        <h3 class="text-danger">Answer The Following Questions:</h3>
        <br>

        <div id="timer" style="font-size: 20px; font-weight: bold; color: red;"></div> <!-- Timer will show here -->
        
        <form id="exam-form" method="POST">
            <?php 
            // Dynamically render each question
            $questionCount = 1;
            while ($question = mysqli_fetch_assoc($result3)) { ?>
                <div>
                    <h4><strong>Q<?php echo $questionCount; ?>. <?php echo htmlspecialchars($question['Question']); ?></strong></h4>
                    <textarea name="Q<?php echo $questionCount; ?>" rows="5" class="form-control" required></textarea>
                </div>
                <br>
            <?php 
                $questionCount++;
            } ?>
            <br>
            <button type="submit" id="submit-button" name="done" class="btn btn-success" style="border-radius:0; margin-top: 10px;">Submit Exam</button>
        </form>
    </fieldset>

    <?php
    if (isset($_POST['done'])) {
        $Ex_id = $exid;
        $tempsname = htmlspecialchars($student['FName'] . " " . $student['LName']);
        $answers = [];
        $questionCount = 1;

        // Collect the answers dynamically
        while ($questionCount <= mysqli_num_rows($result3)) {
            $answers[] = htmlspecialchars($_POST["Q$questionCount"]);
            $questionCount++;
        }

        // Insert answers into the database
        $sql = "INSERT INTO examans (ExamID, Senrl, Sname, " . implode(",", array_map(function($i) { return "Ans$i"; }, range(1, count($answers)))) . ") VALUES ('$Ex_id', '$sEno', '$tempsname', '" . implode("','", $answers) . "')";

        if (mysqli_query($conn, $sql)) {
            // Redirect after successful submission
            header('Location: welcomestudent.php');
            exit(); // Ensure script stops after redirection
        } else {
            echo "<div class='alert alert-danger fade in mt-3'>
                    <strong>Error:</strong> Assessment submitting failure. Try again. <br> Error Details: " . mysqli_error($conn) . "
                  </div>";
        }
        mysqli_close($conn);
    }
    ?>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
// Use the exam duration from PHP and set the timer (in minutes)
let examDurationInMinutes = <?php echo $examDurationInMinutes; ?>; // Duration in minutes
let examDurationInSeconds = examDurationInMinutes * 60; // Convert minutes to seconds for countdown
let timeRemaining = examDurationInSeconds; // Time in seconds
let timerElement = document.getElementById('timer');

// Function to update the timer display
function updateTimer() {
    let minutes = Math.floor(timeRemaining / 60);
    let seconds = timeRemaining % 60;
    timerElement.innerHTML = `Time Left: ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

    if (timeRemaining <= 0) {
        submitExam(); // Automatically submit the exam when time runs out
    } else {
        timeRemaining--;
        setTimeout(updateTimer, 1000);
    }
}

// Function to submit the exam answers
function submitExam() {
    let examForm = document.getElementById('exam-form');
    examForm.submit(); // Submit the form
}

// Start the timer when the page loads
updateTimer();
</script>

<script src="sidebar.js" defer></script>

</body>
</html>