<?php
session_start();
include("database.php");

if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

if (isset($_GET['video_id'])) {
    $videoId = $_GET['video_id'];

    // Fetch video details
    $sql = "SELECT * FROM video WHERE V_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $videoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $video = $result->fetch_assoc();
    } else {
        echo "Video not found.";
        exit();
    }
} else {
    echo "No video ID provided.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $remarks = $_POST['remarks'];
    $url = $_POST['url'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    $update_sql = "UPDATE video SET V_Title = ?, V_Remarks = ?, V_Url = ?, category = ?, subcategory = ? WHERE V_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssssi', $title, $remarks, $url, $category, $subcategory, $videoId);

    if ($update_stmt->execute()) {
        header("Location: managevideos.php"); // Redirect after update
        exit();
    } else {
        echo "Error updating video.";
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
        <i class="fas fa-seedling"></i>
    </a>
    <div class="profile-info">
        <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION["fname"]); ?></strong></span>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="sidebar" id="sidebar">
    <div class="menu-item">
        <a href="mydetailsfaculty.php?myfid=<?php echo $_SESSION["fidx"]; ?>">
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
        <a href="managevideos.php">
            <i class="fa fa-video-camera"></i> Playlists
        </a>
    </div>
</div>

<div class="container">
    <h2>Edit Video</h2>
    <form method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($video['V_Title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks:</label>
            <textarea name="remarks" class="form-control" required><?php echo htmlspecialchars($video['V_Remarks']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="url">Video URL:</label>
            <input type="text" name="url" class="form-control" value="<?php echo htmlspecialchars($video['V_Url']); ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($video['category']); ?>" required>
        </div>

        <div class="form-group">
            <label for="subcategory">Subcategory:</label>
            <input type="text" name="subcategory" class="form-control" value="<?php echo htmlspecialchars($video['subcategory']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="managevideos.php" class="btn btn-secondary">Cancel</a>
    </form>
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