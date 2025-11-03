<?php
// Start session and handle user authentication
session_start();

if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin');
    exit();
}

// Define timeout duration (e.g., 5 minutes)
$timeout_duration = 300; // 300 seconds = 5 minutes

// Check if the session is timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("Location: studentlogin"); // Redirect to login
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

$userid = $_SESSION["sidx"]; // This corresponds to Eno in studenttable
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$image = isset($_SESSION["image"]) && !empty($_SESSION["image"]) ? $_SESSION["image"] : 'profile.jpg';

include('database.php'); // Ensure this file contains the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra | Post Query</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css"> 
    <link rel="stylesheet" href="style.css"> <!-- Add your CSS file -->
</head>
<body>
    <div class="header-bar">
        <a href="welcomestudent.php" class="logo">
            <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra
            <i class="fas fa-seedling"></i>
        </a>
        
        <form method="GET" action="" class="search-container">
            <input type="text" name="search" class="search-bar" placeholder="Search courses..." value="">
            <button type="submit" class="search-icon"><i class="fas fa-search"></i></button>
        </form>
        
        <div class="profile-info">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile Image" class="profile-image">
            <span><?php echo htmlspecialchars($userfname . " " . $userlname); ?></span>
        </div>

        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="menu-item">
            <a href="mydetailsstudent.php?myds=<?php echo htmlspecialchars($userid); ?>">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewvideos.php?eid=<?php echo htmlspecialchars($userid); ?>">
                <i class="fas fa-play-circle"></i>
                <span>My Courses</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewresult.php?seno=<?php echo htmlspecialchars($sEno); ?>">
                <i class="fas fa-chart-bar"></i>
                <span>View Results</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="viewquery.php">
                <i class="fas fa-question"></i>
                <span>Queries</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="logoutstudent.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="container">
        <script>
            // JavaScript validation for query field and guest name
            function validateFormPublicQuery() {
                var query = document.forms["update"]["squeryx"].value;
                var gname = document.forms["update"]["gnamex"].value;
                if (query == null || query == "") {
                    alert("Query field must be filled out");
                    return false;
                }
                if (gname == null || gname == "") {
                    alert("Full Name must be filled out");
                    return false;
                }
            }

            function showErrorModal(message) {
                document.getElementById('errorMessage').innerText = message;
                document.getElementById('errorModal').style.display = 'block';
            }

            function closeErrorModal() {
                document.getElementById('errorModal').style.display = 'none';
            }
        </script>

        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <h3>Welcome <?php echo htmlspecialchars($userfname); ?></h3>
                <form action="" method="POST" name="update" onsubmit="return validateFormPublicQuery()">
                    <fieldset>
                        <legend>
                            <h3 style="padding-top: 25px;">Post Query Details</h3>
                        </legend>
                        <div class="control-group form-group">
                            <div class="controls">
                                <input placeholder="Full Name" type="text" class="form-control" id="gname" name="gnamex" maxlength="50" required>
                            </div>
                        </div>

                        <div class="control-group form-group">
                            <div class="controls">
                                <label>Query:</label>
                                <textarea class="form-control" rows="5" cols="40" id="queryx" name="squeryx" maxlength="200" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Post Query" name="update" class="btn btn-success" style="border-radius:0%">
                            <button type="reset" name="reset" class="btn btn-danger" style="border-radius:0%">Clear</button>
                        </div>
                    </fieldset>
                </form>

                <?php
                if (isset($_POST['update'])) {
                    include('database.php');
                    $tempsquery = mysqli_real_escape_string($conn, $_POST['squeryx']);
                    $tempgname = mysqli_real_escape_string($conn, $_POST['gnamex']);

                    // Get the user's email directly from the session
                    $tempseid = $_SESSION["email"]; // Assuming email is stored in the session

                    // Insert the query directly using the email from session
                    $sql = "INSERT INTO `query`(`Query`, `Eid`, `Ans`) VALUES ('$tempsquery', '$tempseid', NULL)"; // Using NULL for Ans
                    if (mysqli_query($conn, $sql)) {
                        echo "<br>
                        <div class='alert alert-success fade in'>
                        <strong>Success!</strong> Your Query Added Successfully. Ref. No: " . mysqli_insert_id($conn) . "
                        </div>";
                    } else {
                        echo "<script>showErrorModal('Error adding query: " . mysqli_error($conn) . "');</script>";
                    }

                    // Close the connection
                    mysqli_close($conn);
                }
                ?>
            </div>

            <div class="col-md-2"></div>
        </div>

        <!-- Error Modal -->
        <div id="errorModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
            <div class="modal-content" style="background-color:#fff; padding:20px; border-radius:8px; text-align:center;">
                <span class="close" onclick="closeErrorModal()" style="cursor:pointer; float:right;">&times;</span>
                <h2>Error</h2>
                <p id="errorMessage"></p>
                <button onclick="closeErrorModal()" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
    </div>

    <script src="sidebar.js" defer></script>
</body>
</html>