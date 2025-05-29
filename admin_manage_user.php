<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'admin' ) {
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Request</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="student-request">
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_manage.php">Manage</a>
            <a href="admin_archive.php">Archive</a> 
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div id="manage-user-options">
            <h2 onclick="switchToStudent()">Student</h2>
            <h2 onclick="switchToFaculty()">Faculty</h2>
            <h2 onclick="switchToAdmin()">Admin</h2>
        </div>

        <div id='manage-body'>
            <div id="manage-student-container">
                <div class="manage-main">
                    <search>
                        <form>
                            <input class="request-search-bar" placeholder="Search a student">
                            <button type="submit">Search</button>
                        </form>
                    </search>
                    <div class="manage-header">
                            <h3>Student Number</h3>
                            <h3>Student Name</h3>
                            <h3>Degree Program</h3>
                            <h3>Year Level</h3>
                            <h3>College</h3>
                            <h3>Action</h3>
                        </div>
                    <div class="manage-body">
                        <?php
                            include 'students.php';
                        ?>
                        <div class="no-user-found hidden">
                            <h4>No Student Found</h4>
                        </div>
                    </div>
                </div>
                <div class="manage-filter-container">
                    <h2>Filter</h2>
                    <div class="manage-filter-categories">
                        <div>
                            <label for="degree-program">Degree Program:</label>
                            <?php
                                include 'student_degree_program.php';
                            ?>
                        </div>
                        <div>
                            <label for="year-level">Year Level:</label>
                            <?php
                                include 'student_year_level.php';
                            ?>
                        </div>
                        <div>
                            <label for="college">College:</label>
                            <?php
                                include 'student_college.php';
                            ?>
                        </div>

                        <button onclick="filterStudents()">Filter Students</button>
                    </div>
                    
                </div>
            </div>
            <div id="manage-faculty-container" class='hidden'>
                <div class="manage-main">
                    <search>
                        <form>
                            <input class="request-search-bar" placeholder="Search a student">
                            <button type="submit">Search</button>
                        </form>
                    </search>
                    <div class="manage-header">
                            <h3>Faculty ID</h3>
                            <h3>Faculty Name</h3>
                            <h3>Division</h3>
                            <h3>Available?</h3>
                            <h3>Action</h3>
                        </div>
                    <div class="manage-body">
                        <?php
                            include 'faculty.php';
                        ?>
                        <div class="no-user-found hidden">
                            <h4>No Faculty Found</h4>
                        </div>
                    </div>
                </div>
                <div class="manage-filter-container">
                    <h2>Filter</h2>
                    <div class="manage-filter-categories">
                        <div>
                            <label for="division">Division:</label>
                            <?php
                                include 'faculty_division.php';
                            ?>
                        </div>

                        <button onclick="filterFaculty()">Filter Faculty</button>
                    </div>
                    
                </div>
            </div>
            <div id="manage-admin-container" class='hidden'>
                <div class="manage-main">
                    <search>
                        <form>
                            <input class="request-search-bar" placeholder="Search a student">
                            <button type="submit">Search</button>
                        </form>
                    </search>
                    <div class="manage-header">
                            <h3>Admin ID</h3>
                            <h3>Admin Name</h3>
                            <h3>Designation</h3>
                            <h3>Action</h3>
                        </div>
                    <div class="manage-body">
                        <?php
                            include 'admin.php';
                        ?>
                        <div class="no-user-found hidden">
                            <h4>No Student Found</h4>
                        </div>
                    </div>
                </div>
                <div class="manage-filter-container">
                    <h2>Filter</h2>
                    <div class="manage-filter-categories">
                        <div>
                            <label for="designation">Designation:</label>
                            <?php
                                include 'admin_designation.php';
                            ?>
                        </div>

                        <button onclick="filterAdmin()">Filter Admin</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>2023 Student Dashboard. All rights reserved.</p>
    </footer>

    <script src="manage_script.js"></script>
</body> 
</html>