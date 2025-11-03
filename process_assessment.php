<?php
session_start();
include('database.php'); // Ensure this file contains the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $examName = mysqli_real_escape_string($conn, $_POST['ExamName']);
    $duration = intval($_POST['Duration']); // Convert duration to an integer
    $questions = $_POST['questions'];

    if (empty($examName) || empty($duration) || empty($questions)) {
        die("All fields are required.");
    }

    // Insert into ExamDetails
    $sql = "INSERT INTO ExamDetails (ExamName, Duration) VALUES ('$examName', '$duration')";
    if (mysqli_query($conn, $sql)) {
        $examID = mysqli_insert_id($conn); // Get the last inserted ExamID

        // Insert questions into ExamQuestions
        foreach ($questions as $question) {
            $question = mysqli_real_escape_string($conn, $question);
            $sql = "INSERT INTO ExamQuestions (ExamID, Question) VALUES ('$examID', '$question')";
            mysqli_query($conn, $sql);
        }

        // Redirect to assessment.php after successful insertion
        header('Location: assessment.php');
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'assessment.php';</script>";
    }

    mysqli_close($conn);
}
?>
