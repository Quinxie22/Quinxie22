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
    <title>Add Assessment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css">
</head>
<body>

<div class="container">
    <h3>Welcome, <span style="color:#FF0004;"><?php echo htmlspecialchars($fname); ?></span></h3>

    <fieldset>
        <legend>Add Assessment</legend>
        <form action="process_assessment.php" method="POST" id="assessmentForm">
            <label for="ExamName"><strong>Assessment Name:</strong></label>
            <input type="text" name="ExamName" required>

            <label for="Duration"><strong>Duration (minutes):</strong></label>
            <input type="number" name="Duration" min="1" required>

            <div id="question-container">
                <label><strong>Questions:</strong></label>
                <div class="question-input">
                    <label>1.</label>
                    <textarea name="questions[]" rows="3" required></textarea>
                </div>
                <button type="button" class="add-question-btn" onclick="addQuestion()">Add Another Question</button>
            </div>

            <button type="submit" name="submit" class="btn btn-success">Submit Assessment</button>
        </form>
    </fieldset>
</div>

<script>
    let questionCount = 1;

    function addQuestion() {
        questionCount++;
        let container = document.getElementById("question-container");

        let newQuestion = document.createElement("div");
        newQuestion.classList.add("question-input");
        newQuestion.innerHTML = '<label>' + questionCount + '.</label> <textarea name="questions[]" rows="3" required></textarea>';

        container.insertBefore(newQuestion, container.querySelector(".add-question-btn"));
    }
</script>

</body>
</html>
