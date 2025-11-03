<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospéra - Navigation</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #66CCCC;
            --secondary-color: #F5F5DC;
            --accent-color: #9CD8F4;
            --bg-color: #F0E4CC;
            --text-color: #333;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            --transition-speed: 0.3s;
            --link-hover-color: #dc3545;
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-color);
            border: none;
            box-shadow: var(--shadow);
            transition: var(--transition-speed);
        }

        .navbar-brand {
            color: var(--text-color) !important;
            font-weight: bold;
            font-size: 22px;
            text-transform: uppercase;
            transition: var(--transition-speed);
        }

        .navbar-brand:hover {
            color: var(--link-hover-color) !important;
        }

        .navbar-nav > li > a {
            color: var(--text-color) !important;
            font-size: 16px;
            padding: 12px 15px;
            transition: var(--transition-speed);
        }

        .navbar-nav > li > a:hover {
            color: var(--link-hover-color) !important;
            background-color: transparent;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            background-color: var(--secondary-color);
            border-radius: 5px;
            border: none;
            box-shadow: var(--shadow);
            display: none;
        }

        .open > .dropdown-menu {
            display: block;
        }

        .dropdown-menu > li > a {
            color: var(--text-color) !important;
            transition: var(--transition-speed);
        }

        .dropdown-menu > li > a:hover {
            background-color: var(--accent-color);
            color: var(--link-hover-color) !important;
        }

        /* Mobile Toggle Button */
        .navbar-toggle {
            border: none;
            background: var(--accent-color);
            padding: 10px;
        }

        .navbar-toggle .icon-bar {
            background-color: var(--text-color);
        }

        /* Responsive Fix */
        @media (max-width: 768px) {
            .navbar-nav {
                background-color: var(--secondary-color);
            }

            .navbar-nav > li > a {
                color: var(--text-color) !important;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <!-- Brand -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-book-open"></i> Prospéra
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="registrationform.php">Student Registration</a></li>
                    <li><a href="takeassessment.php">Take Assessment</a></li>
                    <li><a href="viewresult.php">Result</a></li>
                    <li><a href="postquerypublic.php">Post Query</a></li>

                    <!-- Dropdown for Login -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Login <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="studentlogin.php">Student Login</a></li>
                            <li><a href="facultylogin.php">Faculty Login</a></li>
                            <li><a href="adminlogin.php">Admin Login</a></li>
                        </ul>
                    </li> 
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- jQuery and Bootstrap Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>

<!-- Dropdown Fix Script -->
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>

</body>
</html>
