<?php
include("database.php");

$playlistId = $_POST['playlist_id'];

// Fetch all videos for the selected playlist
$sql = "SELECT v.V_id, v.V_Title, v.V_Url, v.V_Remarks 
        FROM video v
        JOIN playlist_videos pv ON v.V_id = pv.video_id
        WHERE pv.playlist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $playlistId);
$stmt->execute();
$result = $stmt->get_result();

while ($video = $result->fetch_assoc()) {
    echo '<tr>
            <td>
                <strong>' . htmlspecialchars($video['V_Title']) . '</strong><br>
                <p>' . htmlspecialchars($video['V_Remarks']) . '</p>
            </td>
            <td>
                <a href="edit_video.php?video_id=' . $video['V_id'] . '" class="btn btn-edit btn-custom">Edit</a>
                <button class="btn btn-delete btn-custom delete-video" data-id="' . $video['V_id'] . '">Delete</button>
            </td>
          </tr>';
}
?>