<?php
session_start();

// Ensure the user is logged in
if (empty($_SESSION["sidx"])) {
    header('Location: studentlogin.php');
    exit();
}

$userid = $_SESSION["sidx"];
$userfname = $_SESSION["fname"];
$userlname = $_SESSION["lname"];
$userRole = $_SESSION["role"]; // 'student' or 'teacher'
$uploadDir = $userRole === 'teacher' ? 'faculty/' : 'uploads/'; // Directory based on role

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $fileName = $_FILES['document']['name'];
    $fileTmpPath = $_FILES['document']['tmp_name'];
    $fileType = $_FILES['document']['type'];
    $category = $_POST['category'];

    // Validate file type
    $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($fileTmpPath, $uploadDir . $fileName)) {
            // Insert into the database (ensure to implement this part)
            // $stmt = $conn->prepare("INSERT INTO documents (user_id, file_name, file_path, category) VALUES (?, ?, ?, ?)");
            // $stmt->bind_param("isss", $userid, $fileName, $uploadDir . $fileName, $category);
            // $stmt->execute();

            $message = "File uploaded successfully!";
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Invalid file type. Only PDF and DOCX are allowed.";
    }
}

// Fetch uploaded documents
$documents = []; // Fetch documents based on user role and category

$categories = ($userRole === 'teacher')
    ? ['submit', 'note', 'assignment', 'correction']
    : ['note', 'assignment', 'correction'];

// Fetch documents from the database based on the user role
// $query = "SELECT * FROM documents WHERE category IN ('" . implode("','", $categories) . "') AND user_id = ?";


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <style>
        :root {
            --primary-color: #66CCCC; /* Soothing Blue */
            --bg-color: #F0E4CC; /* Light Beige */
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', 'Arial', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .upload-form, .document-list {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .upload-form h2, .document-list h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .upload-form input[type="file"], .upload-form select {
            margin-bottom: 10px;
        }

        .document-list a {
            display: block;
            margin: 5px 0;
            color: #007bff;
            text-decoration: none;
        }

        .document-list a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0997f5; /* Darker shade on hover */
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Document Management</h1>
    <p>Welcome, <?php echo htmlspecialchars($userfname . " " . $userlname); ?></p>
</div>

<div class="upload-form">
    <h2>Upload Document</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="document" accept=".pdf,.docx" required>
        <select name="category" required>
            <option value="submit" <?php echo $userRole === 'student' ? 'selected' : ''; ?>>Submit</option>
            <option value="note">Note</option>
            <option value="assignment">Assignment</option>
            <option value="correction">Correction</option>
        </select>
        <input type="submit" value="Upload Document" class="btn-submit">
    </form>
</div>

<div class="document-list">
    <h3>Uploaded Documents</h3>
    <?php if (count($documents) > 0): ?>
        <ul>
            <?php foreach ($documents as $doc): ?>
                <li>
                    <a href="<?php echo $uploadDir . $doc['file_name']; ?>" download><?php echo htmlspecialchars($doc['file_name']); ?> (Download)</a>
                    <a href="<?php echo $uploadDir . $doc['file_name']; ?>" target="_blank">View</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No documents uploaded yet.</p>
    <?php endif; ?>
</div>

</body>
</html>