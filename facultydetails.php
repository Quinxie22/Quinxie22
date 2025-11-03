<?php
session_start();

if (empty($_SESSION["umail"])) {
    header('Location: AdminLogin.php');
    exit();
}

$userid = htmlspecialchars($_SESSION["umail"]); // Sanitize user ID for output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        :root {
            --primary-color: #66CCCC;
            --secondary-color: #F5F5DC;
            --accent-color: #9CD8F4;
            --bg-color: #F0E4CC;
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding-top: 70px; /* Space for the header */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .page-header {
            font-size: 26px;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .btn {
            border-radius: 0;
            transition: background-color var(--transition-speed);
        }

        .btn-success {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Search Container */
        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .search-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }

        .search-container button {
            padding: 10px;
            margin-left: 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #218838;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: 90%;
            max-width: 400px;
            text-align: center; /* Center text */
        }
    </style>
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <a href="#" class="logo">
        <i class="fas fa-book-open"></i> Prosp<span style="color:green;">é</span>ra <i class="fas fa-seedling"></i>
    </a>
    <h3 class="welcome-msg">Welcome, <?php echo htmlspecialchars($userid); ?></h3>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="menu-item" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i> <span>Menu</span>
    </div>
    <a href="studentdetails.php" class="menu-item"><i class="fa fa-graduation-cap"></i> <span>Student Details</span></a>
    <a href="facultydetails.php" class="menu-item"><i class="fa fa-users"></i> <span>Faculty Details</span></a>
    <a href="guestdetails.php" class="menu-item"><i class="fa fa-user"></i> <span>Guest Details</span></a>
    <a href="logoutadmin.php" class="menu-item" style="color: red;"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<!-- Main Content -->
<div class="container">
    <h3 class='page-header'>Faculty Details</h3>

    <a href="addnewfaculty.php"><button type="button" class="btn btn-success btn-sm">Add New Faculty</button></a>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search by name...">
        <button onclick="searchFaculty()">Search</button>
    </div>

    <?php
    include("database.php");
    if (isset($_REQUEST['deleteid'])) {
        $deleteid = $_GET['deleteid'];
        // Delete faculty Query
        $sql = "DELETE FROM `facutlytable` WHERE FID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $deleteid);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success fade in'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong> Faculty Details have been deleted.
                </div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting record: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    $sql = "SELECT * FROM facutlytable";
    $result = mysqli_query($conn, $sql);
    echo "<table class='table table-striped table-hover' id='facultyTable'>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Father Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Joining Date</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>";
    $count = 1;
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $row['FName']; ?></td>
            <td><?php echo $row['FaName']; ?></td>
            <td><?php echo $row['Addrs']; ?></td>
            <td><?php echo $row['Gender']; ?></td>
            <td><?php echo $row['JDate']; ?></td>
            <td><?php echo $row['City']; ?></td>
            <td><?php echo $row['PhNo']; ?></td>
            <td>
                <a href="updatefaculty.php?fid=<?php echo $row['FID']; ?>" class="btn btn-success btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete-button" data-id="<?php echo $row['FID']; ?>">Delete</button>
            </td>
        </tr>
        <?php $count++; } ?>
    </table>
</div>

<!-- Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to delete this faculty member?</p>
        <button id="confirmDelete" class="btn btn-danger">Delete</button>
        <button id="cancelDelete" class="btn btn-success">Cancel</button>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> Prospéra. All Rights Reserved.
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("expanded");
        document.querySelector(".container").classList.toggle("expanded");
    }

    // Search functionality
    function searchFaculty() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('facultyTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            const td = tr[i].getElementsByTagName('td');
            let found = false;

            for (let j = 1; j < td.length - 1; j++) { // Exclude the last column (Actions)
                if (td[j]) {
                    const textValue = td[j].textContent || td[j].innerText;
                    if (textValue.toLowerCase().indexOf(input) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? "" : "none"; // Show or hide the row
        }
    }

    // Modal functionality
    const deleteModal = document.getElementById('deleteModal');
    const closeModal = document.getElementById('closeModal');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');

    let deleteId;

    document.querySelectorAll('.delete-button').forEach(button => {
        button.onclick = function() {
            deleteId = this.getAttribute('data-id');
            deleteModal.style.display = 'flex'; // Show the modal
        };
    });

    closeModal.onclick = function() {
        deleteModal.style.display = 'none';
    };

    cancelDelete.onclick = function() {
        deleteModal.style.display = 'none';
    };

    confirmDelete.onclick = function() {
        window.location.href = 'facultydetails.php?deleteid=' + deleteId;
    };

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    };
</script>

</body>
</html>