<?php
session_start();
include("database.php");

if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

if (isset($_POST['video_id'])) {
    $videoId = $_POST['video_id'];

    // Delete from playlist_videos table first to break the association
    $sql = "DELETE FROM playlist_videos WHERE video_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $videoId);
    $stmt->execute();

    // Now delete the video from the video table
    $sql = "DELETE FROM video WHERE V_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $videoId);

    if ($stmt->execute()) {
        echo "Video deleted successfully.";
    } else {
        echo "Error deleting video.";
    }

    $stmt->close();
}

$conn->close();
?>
