<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "onlclassroom"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search request
$search = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
$response = ["video" => [], "studenttable" => [], "facultytable" => []];

if (!empty($search)) {
    // Search in video
    $sqlvideo = "SELECT V_id, V_title, V_Remarks FROM video 
                   WHERE V_title LIKE '%$search%' OR V_Remarks LIKE '%$search%'";
    $resultvideo = $conn->query($sqlvideo);
    while ($row = $resultvideo->fetch_assoc()) {
        $response["video"][] = $row;
    }

    // Search in students
    $sqlStudents = "SELECT Eno, FName, LName, Eid FROM studenttable 
                    WHERE FName LIKE '%$search%' OR LName LIKE '%$search%' OR Eid LIKE '%$search%'";
    $resultStudents = $conn->query($sqlStudents);
    while ($row = $resultStudents->fetch_assoc()) {
        $response["studenttable"][] = $row;
    }

    // Search in faculty
    $sqlFaculty = "SELECT FID, Fname, Faname FROM facultytable
                   WHERE Fname LIKE '%$search%' OR Faname LIKE '%$search%'";
    $resultFaculty = $conn->query($sqlFaculty);
    while ($row = $resultFaculty->fetch_assoc()) {
        $response["facultytable"][] = $row;
    }
}

// Return JSON if it's an AJAX request
if (isset($_GET['query'])) {
    echo json_encode($response);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra General Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
        }
        input {
            width: 60%;
            padding: 6px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #searchResults {
            border: 1px solid #ccc;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            position: absolute;
            width: 60%;
            padding: 6px;
            display: none;
        }
        .search-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }
        .search-item:hover {
            background-color: #f0f0f0;
        }
        h4 {
            margin: 10px 0;
            color: #007bff;
        }
    </style>
</head>
<body>
    <input type="text" id="searchInput" placeholder="Search anything..." autocomplete="off">
    <div id="searchResults"></div>

    <script>
        document.getElementById("searchInput").addEventListener("input", function() {
            let query = this.value.trim();
            let resultsContainer = document.getElementById("searchResults");
            resultsContainer.innerHTML = "";
            resultsContainer.style.display = "none";

            if (query.length > 2) { // Start searching after 3 characters
                fetch(`index.php?query=${encodeURIComponent(query)}`) // ✅ FIXED FETCH URL
                    .then(response => response.json())
                    .then(data => {
                        displayResults(data);
                    })
                    .catch(error => console.error("Error fetching search results:", error));
            }
        });

        function displayResults(data) {
            let resultsContainer = document.getElementById("searchResults");
            resultsContainer.innerHTML = ""; 
            resultsContainer.style.display = "block"; 

            if (data.video.length || data.studenttable.length || data.facultytable.length) {
                if (data.video.length) {
                    let videosection = createSection("Video", data.video, "V_title", "V_Remarks");
                    resultsContainer.appendChild(videosection);
                }

                if (data.studenttable.length) {
                    let studentSection = createSection("Students", data.studenttable, "FName", "LName", "Eid");
                    resultsContainer.appendChild(studentSection);
                }

                if (data.facultytable.length) {
                    let facultySection = createSection("Faculty", data.facultytable, "Fname", "Faname");
                    resultsContainer.appendChild(facultySection);
                }
            } else {
                resultsContainer.innerHTML = "<p>No results found.</p>";
            }
        }

        function createSection(title, items, key1, key2, key3 = null) {
            let section = document.createElement("div");
            section.innerHTML = `<h4>${title}</h4>`;
            items.forEach(item => {
                let text = key3 ? `${item[key1]} ${item[key2]} - ${item[key3]}` : `${item[key1]} - ${item[key2]}`;
                let div = document.createElement("div");
                div.classList.add("search-item");
                div.innerHTML = text;
                section.appendChild(div);
            });
            return section;
        }
    </script>

</body>
</html>
