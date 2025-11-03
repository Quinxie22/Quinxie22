<?php
session_start();

if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin.php');
    exit();
}

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$examDuration = 300; // Duration in seconds (e.g., 5 minutes)

$uploadDir = 'uploads/'; // Directory to store uploaded files

// Handle file upload (existing functionality)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $fileName = $_FILES['document']['name'];
    $fileTmpPath = $_FILES['document']['tmp_name'];
    $fileSize = $_FILES['document']['size'];
    $fileType = $_FILES['document']['type'];
    
    $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($fileTmpPath, $uploadDir . $fileName)) {
            $message = "File uploaded successfully!";
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Invalid file type. Only PDF and DOCX are allowed.";
    }
}

// Handle the automatic submission of answers after time is up
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['exam_answers'])) {
    // You would save the answers to the exam table in your database here
    // Example SQL query to save the answers:
    // $examAnswers = $_POST['exam_answers'];
    // $query = "INSERT INTO exam_table (user_id, answers) VALUES ('$userid', '$examAnswers')";
    // mysqli_query($connection, $query);

    echo "Exam answers submitted successfully!";
    exit(); // Exit after submitting answers to prevent reloading the page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar and general styles -->
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
        <i class="fas fa-seedling"></i>
    </a>
    <div class="profile-info">
        Welcome, <strong><?php echo htmlspecialchars($userfname . " " . $userlname); ?></strong>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <h3>Exam Time: <span id="timer"></span> remaining</h3>

    <h3>Press the Spacebar to answer the question:</h3>
    <div id="question-container">
        <!-- Add your question content here -->
        <p>What is the capital of France?</p>
    </div>

    <!-- Answer input area -->
    <div id="answer-area" contenteditable="true" class="answer-area" style="border: 1px solid #ccc; min-height: 100px; padding: 10px;">
        <!-- User answers will be typed here -->
        <p style="color: gray;">Your answer will appear here...</p>
    </div>

    <!-- Submit button -->
    <button id="submit-button" onclick="submitExam()" style="margin-top: 10px;">Submit Exam</button>

    <form id="exam-form" method="POST" style="display: none;">
        <input type="hidden" name="exam_answers" id="exam-answers">
    </form>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
// Set the exam duration (in seconds)
let examDuration = <?php echo $examDuration; ?>;
let timeRemaining = examDuration; // Time in seconds
let timerElement = document.getElementById('timer');
let answerContent = '';

// Function to update the timer display
function updateTimer() {
    let minutes = Math.floor(timeRemaining / 60);
    let seconds = timeRemaining % 60;
    timerElement.innerHTML = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

    if (timeRemaining <= 0) {
        submitExam(); // Automatically submit the exam when time runs out
    } else {
        timeRemaining--;
        setTimeout(updateTimer, 1000);
    }
}

// Function to handle the spacebar answering
document.addEventListener('keydown', function(event) {
    if (event.code === 'Space') {
        // Prevent default space behavior (scrolling down)
        event.preventDefault();

        // Append space to the answer content
        answerContent += ' ';
        updateAnswerArea();
    } else if (event.code === 'Enter') {
        // Allow new lines for multi-line answers (when "Enter" is pressed)
        event.preventDefault();
        answerContent += '\n';
        updateAnswerArea();
    } else if (event.code === 'Backspace') {
        // Prevent default backspace behavior (removal of characters)
        event.preventDefault();
        
        // Remove the last character from the answer content
        answerContent = answerContent.slice(0, -1);
        updateAnswerArea();
    } else if (event.code === 'ArrowUp' || event.code === 'ArrowDown' || event.code === 'ArrowLeft' || event.code === 'ArrowRight') {
        // Prevent cursor movement using arrow keys
        event.preventDefault();
    } else {
        // Handle regular typing (other keys)
        answerContent += event.key;
        updateAnswerArea();
    }
});

// Update the answer area after every change in content
function updateAnswerArea() {
    let answerArea = document.getElementById('answer-area');
    answerArea.innerText = answerContent;
    // Keep the cursor at the end of the input
    answerArea.focus();
    document.execCommand('selectAll', false, null);
    document.getSelection().collapseToEnd();
}

// Function to submit the exam answers
function submitExam() {
    // Set the content of the answer field
    document.getElementById('exam-answers').value = answerContent;

    // Submit the form
    document.getElementById('exam-form').submit();
}

// Start the timer when the page loads
updateTimer();
</script>

</body>
</html>
