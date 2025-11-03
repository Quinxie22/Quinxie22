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
    <title>Update Query Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar styles -->
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .action {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-success {
            background-color: #28a745; /* Green */
            border: none;
        }
        .btn-success:hover {
            background-color: #218838; /* Darker Green */
        }
        .alert {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
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
    <div class="row">
        <div class="col-md-12">
            <h3>Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>
            <?php 
                include('database.php');
                $editid = $_GET['gid'];
                $sql = "SELECT * FROM query WHERE Qid = $editid";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_array($result)) { 
            ?>
            <form action="" method="POST" name="update">
                <fieldset>
                    <legend>Query Details</legend>
                    <div class="form-group">
                        Query ID: <?php echo $row['Qid']; ?>
                    </div>
                    <div class="form-group" style="text-decoration:underline;">
                        <b>Query From:</b> <?php echo htmlspecialchars($row['Eid']); ?>
                    </div>
                    <div class="form-group">
                        Query: <br>
                        <textarea rows="5" class="form-control" cols="40" name="queryx"><?php echo htmlspecialchars($row['Query']); ?></textarea><br>
                    </div>
                    <div class="form-group">
                        Your Answer: <br>
                        <textarea rows="5" class="form-control" cols="40" name="ansx"><?php echo htmlspecialchars($row['Ans']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update" name="update" class="btn btn-success" style="border-radius:0%">
                    </div>
                </fieldset>
            </form>
            <?php
                }
                if (isset($_POST['update'])) {
                    $tempquery = $_POST['queryx'];
                    $tempans = $_POST['ansx'];
                    // Update the existing record of query
                    $sql = "UPDATE `query` SET Query='$tempquery', Ans='$tempans' WHERE Qid='$editid'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<br><div class='alert alert-success fade in'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Query Details have been updated.
                              </div>";
                    } else {
                        // Print error if SQL query fails
                        echo "<br><strong>Query Details Updating Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
                    }
                    // Close the connection
                    mysqli_close($conn);
                }
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