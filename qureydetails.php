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
    <title>Faculty Dashboard | Query Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .edit {
            background-color: #3498db; /* Blue */
        }
        .action:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: 2px solid #e74c3c;
        }
        .modal-header {
            background-color: #e74c3c;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-body {
            font-size: 16px;
            text-align: center;
        }
        .modal-footer {
            border-top: none; /* Remove default border */
        }
        .btn-danger {
            background-color: #c0392b; /* Darker red */
            border-color: #c0392b;
        }
        .btn-danger:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        /* Adjust container padding */
        .container {
            padding-left: 260px; /* Adjust padding to ensure the table is not covered */
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
        <?php
        if (isset($_REQUEST['deleteid'])) {
            include("database.php");
            $deleteid = $_GET['deleteid'];
            $sql = "DELETE FROM `query` WHERE Qid = '$deleteid'";

            if (mysqli_query($conn, $sql)) {
                echo "
                    <br><br>
                    <div class='alert alert-success fade in'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong> Query Details have been deleted.
                    </div>";
            } else {
                echo "<br><strong>Query Details Deletion Failure. Try Again</strong><br> Error Details: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Welcome Faculty: <span style="color:#FF0004"><?php echo htmlspecialchars($fname); ?></span></h3>
            <?php
            include("database.php");
            $sql = "SELECT * FROM query";
            $result = mysqli_query($conn, $sql);
            // Table to display all queries posted by students or guests to faculty
            echo "<h3 class='page-header'>Query Details</h3>";
            echo "<table class='table table-striped table-hover' style='width:100%'>
                <tr>
                    <th>#</th>
                    <th>Student's Email</th>
                    <th>Query</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>";
            $count = 1;
            while ($row = mysqli_fetch_array($result)) {
                ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo htmlspecialchars($row['Eid']); ?></td>
                <td><?php echo htmlspecialchars($row['Query']); ?></td>
                <td><?php echo htmlspecialchars($row['Ans']); ?></td>
                <td>
                    <a href="updatequery.php?gid=<?php echo $row['Qid']; ?>" class="action edit">Edit</a>
                    <button class="action delete" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['Qid']; ?>">Delete</button>
                </td>
            </tr>
            <?php $count++; } ?>
            </table>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this query?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="confirmDeleteLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // JavaScript to handle the delete confirmation
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var queryId = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        var deleteUrl = 'qureydetails.php?deleteid=' + queryId; // Construct the delete URL
        modal.find('#confirmDeleteLink').attr('href', deleteUrl); // Set the link in the modal
    });

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