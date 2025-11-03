<?php
ob_start(); // Start output buffering
include('allhead.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="registration.css"> <!-- Link to external CSS -->
</head>
<body>

<div class="container">
    <form name="register" method="POST" enctype="multipart/form-data">
        <!-- Step 1 -->
        <div id="step1" class="step active">
            <h2>Step 1: Personal Information</h2>

            <label>First Name:</label>
            <input type="text" name="fname" required>

            <label>Last Name:</label>
            <input type="text" name="lname" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" required>

            <label>Address:</label>
            <input type="text" name="addrs" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label>Phone Number:</label>
            <input type="text" name="phno" required>

            <label>Parent's Phone Number:</label>
            <input type="text" name="paphone" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="pass" required>

            <label>Category:</label>
            <select name="category" id="category" onchange="updateSubcategories()" required>
                <option value="">Select Category</option>
                <option value="Health">Health</option>
                <option value="Business Management">Business Management</option>
                <option value="Engineering">Engineering</option>
            </select>

            <button type="button" onclick="validateStep1()">Next</button>
        </div>

        <!-- Step 2 -->
        <div id="step2" class="step">
            <h2>Step 2: Additional Information</h2>

            <label>Subcategory:</label>
            <select name="subcategory" id="subcategory" required></select>

            <label>Upload Profile Image:</label>
            <input type="file" name="profileImage" accept=".jpg, .jpeg, .png" required>

            <button type="button" onclick="validateStep2()">Submit</button>
        </div>
    </form>
</div>

<script src="script.js"></script> <!-- Link to external JavaScript -->
</body>
</html>

<?php
include("database.php");

if (!$conn) {
    die("<div class='alert'>Connection failed: " . mysqli_connect_error() . "</div>");
}

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $addrs = $_POST['addrs'];
    $gender = $_POST['gender'];
    $phno = $_POST['phno'];
    $paphone = $_POST['paphone'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Handle file upload
    $imageName = $_FILES['profileImage']['name'];
    $imageTmpName = $_FILES['profileImage']['tmp_name'];
    $imageFolder = "uploads/" . basename($imageName);
    
    if (!move_uploaded_file($imageTmpName, $imageFolder)) {
        die("<center><div class='alert'>Error uploading image</div></center>");
    }

    // Insert data into the database
    $sql = "INSERT INTO `studenttable` (`FName`, `LName`, `DOB`, `Addrs`, `Gender`, `PhNo`, `Paphone`, `Eid`, `Pass`, `image`, `category`, `subcategory`) 
            VALUES ('$fname','$lname','$dob','$addrs','$gender','$phno','$paphone','$email','$pass','$imageFolder','$category','$subcategory')";

    if (mysqli_query($conn, $sql)) {
        ob_end_clean(); // Clear output before redirecting
        echo "<script>
                alert('Registration successful! Redirecting to login page...');
                window.location.href = 'studentlogin.php';
              </script>";
        exit();
    } else {
        echo "<center><div class='alert'>Error: " . mysqli_error($conn) . "</div></center>";
    }
}
?>
