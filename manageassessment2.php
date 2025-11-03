<?php
session_start();

if ($_SESSION["fidx"] == "" || $_SESSION["fidx"] == NULL) {
    header('Location: facultylogin');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];
?>

<?php include('fhead.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h3>Welcome Faculty: <a href="welcomefaculty.php"><span style="color:#FF0004"><?php echo $fname; ?></span></a></h3>

            <?php
            include('database.php');
            $editExamID = $_GET['editassid'];

            // Fetch exam details
            $sql = "SELECT * FROM examdetails WHERE ExamID = $editExamID";
            $rs = mysqli_query($conn, $sql);
            $examDetails = mysqli_fetch_array($rs);

            // Fetch the questions related to this exam
            $questionsSql = "SELECT QuestionID, Question FROM examquestions WHERE ExamID = $editExamID";
            $questionsRs = mysqli_query($conn, $questionsSql);
            $questions = [];
            while ($questionRow = mysqli_fetch_assoc($questionsRs)) {
                $questions[] = $questionRow;
            }
            ?>

            <fieldset>
                <legend><a href="manageassessment.php">Edit Assessment</a></legend>
                <form action="" method="POST" name="UpdateAssessment">
                    <table class="table table-hover">
                        <tr>
                            <td><strong>Exam ID</strong></td>
                            <td><?php echo $examDetails['ExamID']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Exam Name</strong></td>
                            <td>
                                <textarea name="ExamName" class="form-control" rows="1" cols="50"><?php echo $examDetails['ExamName']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Duration (minutes)</strong></td>
                            <td>
                                <input type="number" name="Duration" class="form-control" value="<?php echo $examDetails['Duration']; ?>" required />
                            </td>
                        </tr>

                        <?php
                        // Dynamically render each question
                        $questionCount = 1;
                        foreach ($questions as $question) {
                            echo "<tr>
                                    <td><strong>Q{$questionCount}</strong></td>
                                    <td>
                                        <textarea name='Q{$questionCount}' rows='5' class='form-control' cols='150'>{$question['Question']}</textarea>
                                    </td>
                                  </tr>";
                            $questionCount++;
                        }
                        ?>

                        <tr>
                            <td><button type="submit" name="update" class="btn btn-success" style="border-radius:0%">Update</button></td>
                        </tr>
                    </table>
                </form>
            </fieldset>

            <?php
            if (isset($_POST['update'])) {
                $E_name = $_POST['ExamName'];
                $Duration = $_POST['Duration'];

                // Start a transaction for updating exam and questions
                mysqli_begin_transaction($conn);
                try {
                    // Update the exam details
                    $updateExamSql = "UPDATE `examdetails` SET ExamName='$E_name', Duration='$Duration' WHERE ExamID=$editExamID";
                    mysqli_query($conn, $updateExamSql);

                    // Update the questions
                    $questionUpdateSuccess = true;
                    $questionCount = 1;
                    while (isset($_POST["Q{$questionCount}"])) {
                        $questionText = $_POST["Q{$questionCount}"];
                        $updateQuestionSql = "UPDATE `examquestions` SET Question='$questionText' WHERE ExamID=$editExamID AND QuestionID={$questions[$questionCount - 1]['QuestionID']}";
                        if (!mysqli_query($conn, $updateQuestionSql)) {
                            $questionUpdateSuccess = false;
                            break;
                        }
                        $questionCount++;
                    }

                    if ($questionUpdateSuccess) {
                        mysqli_commit($conn);
                        echo "
                            <br><br>
                            <div class='alert alert-success fade in'>
                            <a href='manageassessment.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Success!</strong> Assessment Updated.
                            </div>";
                    } else {
                        mysqli_rollback($conn);
                        echo "<br><strong>Error updating questions. Please try again.</strong>";
                    }
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    echo "<br><strong>Update failed: </strong>" . $e->getMessage();
                }
            }

            // Close the connection
            mysqli_close($conn);
            ?>
        </div>
    </div>
</div>

<?php include('allfoot.php'); ?>
