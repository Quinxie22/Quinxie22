<?php
session_start();
if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];

include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Playlists</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="faculty.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Confirmation Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.6); 
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto; 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 80%; 
            max-width: 400px; 
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .close {
            font-size: 20px;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            margin: 15px 0;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }

        /* Styled Table */
        .table-container {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center; /* Center align table content */
        }

        /* Button Styles */
        .btn-custom {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            margin: 5px; /* Space between buttons */
            cursor: pointer;
            border: none; /* Remove border */
            color: white; /* Text color for buttons */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete:hover {
            background-color: #c82333;
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

    <h2 class="page-header">Playlists</h2>
    <ul class="list-group">
        <?php
        $sql = "SELECT * FROM playlists";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="list-group-item playlist-item" data-id="' . $row['playlist_id'] . '">
                    <strong>' . htmlspecialchars($row['name']) . '</strong> - ' . htmlspecialchars($row['description']) . '
                  </li>';
        }
        ?>
    </ul>

    <h2 class="page-header">Videos in Selected Playlist</h2>
    <h3 id="selectedPlaylistTitle" style="color: #007bff; margin-bottom: 15px;"></h3>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Video Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="videoTableBody">
                <!-- Video list will be loaded here dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Deletion</h5>
            <span class="close" style="cursor:pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this video? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button id="confirmDelete" class="btn btn-danger">Delete</button>
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let videoIdToDelete;

    $(".playlist-item").click(function() {
        var playlistId = $(this).data("id");
        var playlistTitle = $(this).find("strong").text();
        $("#selectedPlaylistTitle").text("Playlist: " + playlistTitle);

        $.ajax({
            url: "fetch_videos.php",
            type: "POST",
            data: { playlist_id: playlistId },
            success: function(response) {
                $("#videoTableBody").html(response);
            },
            error: function() {
                $("#videoTableBody").html("<tr><td colspan='2'>Error loading videos.</td></tr>");
            }
        });
    });

    $(document).on("click", ".delete-video", function() {
        videoIdToDelete = $(this).data("id");
        $("#deleteModal").css("display", "flex");
    });

    $("#confirmDelete").click(function() {
        $.ajax({
            url: "delete_video.php",
            type: "POST",
            data: { video_id: videoIdToDelete },
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function() {
                alert("Error deleting the video.");
            }
        });
        $("#deleteModal").css("display", "none");
    });

    $(".close, #cancelDelete").click(function() {
        $("#deleteModal").css("display", "none");
    });
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

<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

</body>
</html>