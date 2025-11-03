<?php
session_start();

// Ensure the faculty is logged in
if ($_SESSION["fidx"] == "" || $_SESSION["fidx"] == NULL) {
    header('Location: facultylogin.php');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];

// Include database connection
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlist_name'])) {
    $playlist_name = $_POST['playlist_name'];
    $user_id = $_SESSION["fidx"];

    // Check if the playlist already exists
    $check_playlist_sql = "SELECT id FROM playlists WHERE playlist_name = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_playlist_sql);
    $check_stmt->bind_param("si", $playlist_name, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Playlist exists, get its ID
        $check_stmt->bind_result($playlist_id);
        $check_stmt->fetch();
    } else {
        // Insert the new playlist
        $sql = "INSERT INTO playlists (user_id, playlist_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $playlist_name);
        
        if ($stmt->execute()) {
            $playlist_id = $stmt->insert_id; // Get the last inserted playlist ID
        } else {
            echo "Error creating playlist: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }
    $check_stmt->close();

    // Video details from form
    if (isset($_POST['videotitle'], $_POST['VideoURL'], $_POST['Videoinfo'], $_POST['category'], $_POST['subcategory'])) {
        $title = $_POST['videotitle'];
        $v_url = $_POST['VideoURL'];
        $v_info = $_POST['Videoinfo'];
        $category = $_POST['category'];
        $subcategory = $_POST['subcategory'];

        // Insert the video into the database
        $video_sql = "INSERT INTO video (V_Title, V_Url, V_Remarks, category, subcategory) VALUES (?, ?, ?, ?, ?)";
        $video_stmt = $conn->prepare($video_sql);
        $video_stmt->bind_param("sssss", $title, $v_url, $v_info, $category, $subcategory);
        
        if ($video_stmt->execute()) {
            // Get the last inserted video ID
            $video_id = $video_stmt->insert_id;

            // Link the video to the playlist
            $link_sql = "INSERT INTO playlist_videos (playlist_id, video_id) VALUES (?, ?)";
            $link_stmt = $conn->prepare($link_sql);
            $link_stmt->bind_param("ii", $playlist_id, $video_id);
            $link_stmt->execute();

            echo "
                <center>
                    <div class='alert alert-success fade in' style='margin-top:10px;'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>&times;</a>
                        <strong><h3 style='margin-top: 10px; margin-bottom: 10px;'>Video added Successfully to the playlist.</h3></strong>
                    </div>
                </center>
            ";
        } else {
            echo "Error adding video: " . $video_stmt->error;
        }
        
        $video_stmt->close();
    }
}

// Close the connection only once at the end of the script
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Add Videos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> <!-- Sidebar styles -->
    <style>
        .profile-pic {
            width: 100px; 
            height: 100px; 
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
    </style>
    <script>
        function updateSubcategories() {
            const category = document.getElementById("category").value;
            const subcategorySelect = document.getElementById("subcategory");
            let options = [];

            if (category === "Health") {
                options = ["Nursing", "Physiotherapy", "Pharmacology", "Radiology"];
            } else if (category === "Business Management") {
                options = ["Accounting", "Banking and Finance", "Logistics and Transport", "Human Resources"];
            } else if (category === "Engineering") {
                options = ["Computer Science", "Civil Engineering", "Electrical Engineering"];
            }

            // Clear existing options
            subcategorySelect.innerHTML = "";
            options.forEach(option => {
                const opt = document.createElement("option");
                opt.value = option;
                opt.innerHTML = option;
                subcategorySelect.appendChild(opt);
            });
        }
    </script>
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
    <h3>Add Video</h3>
    <fieldset>
        <form action="" method="POST" name="AddAssessment" enctype="multipart/form-data">
            <table class="table table-hover">
                <tr>
                    <td><strong>Playlist Name</strong></td>
                    <td><input type="text" class="form-control" name="playlist_name" required></td>
                </tr>
                <tr>
                    <td><strong>Video Title</strong></td>
                    <td><input type="text" class="form-control" name="videotitle" required></td>
                </tr>
                <tr>
                    <td><strong>Video URL</strong></td>
                    <td><textarea name="VideoURL" class="form-control" rows="1" cols="150" required></textarea></td>
                </tr>
                <tr>
                    <td><strong>Video Description</strong></td>
                    <td><textarea name="Videoinfo" class="form-control" rows="5" cols="150" required></textarea></td>
                </tr>
                <tr>
                    <td><strong>Category</strong></td>
                    <td>
                        <select id="category" name="category" class="form-control" onchange="updateSubcategories()" required>
                            <option value="">Select Category</option>
                            <option value="Health">Health</option>
                            <option value="Business Management">Business Management</option>
                            <option value="Engineering">Engineering</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Subcategory</strong></td>
                    <td>
                        <select id="subcategory" name="subcategory" class="form-control" required>
                            <option value="">Select Subcategory</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="submit" class="btn btn-success" style="border-radius:0%">Add Video</button>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
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

<?php 
// Ensure the database connection is closed
$conn->close();
?>