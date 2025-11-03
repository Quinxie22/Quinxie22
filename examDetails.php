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
    <style>
        .action {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .delete {
            background-color: #e74c3c; /* Red */
        }
        .make-result {
            background-color: #3498db; /* Blue */
        }
        .action:hover {
            transform: scale(1.1);
            cursor: pointer;
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
    <h3>Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>

    <div class="upload-form">
        <?php
        include("database.php");

        // Handle delete request
        if (isset($_REQUEST['deleteid'])) {
            $deleteid = $_GET['deleteid'];
            $sql = "DELETE FROM `examans` WHERE ExamID = $deleteid";
            if (mysqli_query($conn, $sql)) {
                echo "<div class='alert alert-success fade in'>
                        <strong>Success!</strong> Exam details deleted.
                      </div>";
            } else {
                echo "<div class='alert alert-danger fade in'>
                        <strong>Error:</strong> Exam Details Updation Failure. Try Again. <br>" . mysqli_error($conn) . "
                      </div>";
            }
        }

        // Fetch assessment details with exam name
        $sql = "SELECT e.ExamName, a.Senrl, a.Ans1, a.Ans2, a.Ans3, a.Ans4, a.Ans5, a.ExamID 
                FROM examans a 
                JOIN examdetails e ON a.ExamID = e.ExamID";  // Join with examdetails table
        $rs = mysqli_query($conn, $sql);
        ?>
        
        <h2 class="page-header">Assessment Details</h2>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Enrolment No.</th>
                    <th>Exam Name</th>
                    <th>Ans.1</th>
                    <th>Ans.2</th>
                    <th>Ans.3</th>
                    <th>Ans.4</th>
                    <th>Ans.5</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_array($rs)) {
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo htmlspecialchars($row['Senrl']); ?></td>
                    <td><?php echo htmlspecialchars($row['ExamName']); ?></td> <!-- Display Exam Name -->
                    <td><?php echo htmlspecialchars($row['Ans1']); ?></td>
                    <td><?php echo htmlspecialchars($row['Ans2']); ?></td>
                    <td><?php echo htmlspecialchars($row['Ans3']); ?></td>
                    <td><?php echo htmlspecialchars($row['Ans4']); ?></td>
                    <td><?php echo htmlspecialchars($row['Ans5']); ?></td>
                    <td>
                        <a href="examDetails.php?deleteid=<?php echo $row['ExamID']; ?>" class="action delete">Delete</a>
                        <a href="makeresult.php?makeid=<?php echo $row['ExamID']; ?>" class="action make-result">Make Result</a>
                    </td>
                </tr>
                <?php
                $count++;
                }
                ?>
            </tbody>
        </table>
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