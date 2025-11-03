<?php
session_start();

if (empty($_SESSION["fidx"])) {
    header('Location: facultylogin.php');
    exit();
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];

include('database.php');

// Prepare to fetch faculty details
$new2 = $_GET['myfid'];
$stmt = $conn->prepare("SELECT * FROM facutlytable WHERE FID = ?");
$stmt->bind_param("s", $new2);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tempfname = $_POST['fname'];
    $tempfaname = $_POST['faname'];
    $tempaddrs = $_POST['addrs'];
    $tempgender = $_POST['gender'];
    $tempphno = $_POST['phno'];
    $tempcity = $_POST['city'];
    $temppass = $_POST['pass'];

    // Prepare update statement
    $updateStmt = $conn->prepare("UPDATE facutlytable SET FName=?, FaName=?, Addrs=?, Gender=?, City=?, Pass=?, PhNo=? WHERE FID=?");
    $updateStmt->bind_param("ssssssss", $tempfname, $tempfaname, $tempaddrs, $tempgender, $tempcity, $temppass, $tempphno, $new2);

    if ($updateStmt->execute()) {
        $message = "<div class='alert alert-success'>Faculty Details updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error updating details: " . $updateStmt->error . "</div>";
    }
    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Faculty Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="faculty.css">

</head>
<body>

<div class="header-bar">
<a href="#" class="logo">
            <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
            <i class="fas fa-seedling"></i>
        </a>    <div class="profile-info">
        <span>Welcome, <strong><?php echo htmlspecialchars($fname); ?></strong></span>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="sidebar" id="sidebar">
    <div class="menu-item"><a href="mydetailsfaculty.php?myfid=<?php echo $userid; ?>"><i class="fa fa-user"></i> My Profile</a></div>
    <div class="menu-item"><a href="viewstudentdetails.php"><i class="fa fa-graduation-cap"></i> Student Details</a></div>
    <div class="menu-item"><a href="assessment.php"><i class="fa fa-pencil-square"></i> Assessment Section</a></div>
    <div class="menu-item"><a href="examDetails.php"><i class="fa fa-file"></i> Publish Result</a></div>
    <div class="menu-item"><a href="resultdetails.php"><i class="fa fa-indent"></i> Edit Result</a></div>
    <div class="menu-item"><a href="qureydetails.php"><i class="fa fa-question"></i> Student's Query</a></div>
    <div class="menu-item"><a href="videos.php"><i class="fa fa-video-camera"></i> Videos</a></div>
</div>

<div class="container">
    <h3>Welcome Faculty: <span style="color:#FF0004;"><?php echo htmlspecialchars($fname); ?></span></h3>
    
    <?php if (isset($message)) echo $message; ?>

    <form action="" method="POST" name="update">
        <div class="form-group">Faculty ID: <?php echo htmlspecialchars($faculty['FID']); ?></div>
        <div class="form-group">Faculty Name: <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($faculty['FName']); ?>"></div>
        <div class="form-group">Father Name: <input type="text" name="faname" class="form-control" value="<?php echo htmlspecialchars($faculty['FaName']); ?>"></div>
        <div class="form-group">Address: <input type="text" name="addrs" class="form-control" value="<?php echo htmlspecialchars($faculty['Addrs']); ?>"></div>
        <div class="form-group">Gender: <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($faculty['Gender']); ?>"></div>
        <div class="form-group">Phone Number: <input type="tel" name="phno" class="form-control" value="<?php echo htmlspecialchars($faculty['PhNo']); ?>" maxlength="10"></div>
        <div class="form-group">Joining Date: <input type="date" name="jdate" class="form-control" value="<?php echo htmlspecialchars($faculty['JDate']); ?>" readonly></div>
        <div class="form-group">City: <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($faculty['City']); ?>"></div>
        <div class="form-group">Password: <input type="text" name="pass" class="form-control" value="<?php echo htmlspecialchars($faculty['Pass']); ?>" maxlength="10"></div>
        <div class="form-group"><input type="submit" value="Update!" name="update" class="btn"></div>
    </form>
</div>

<div class="footer">&copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.</div>

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