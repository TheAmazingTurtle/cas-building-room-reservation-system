<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
            <a href="home.php">Dashboard</a>
            <a href="profile.php">Request</a>
            <a href="courses.php">Archive</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div>
            <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
            <div>
                <p>Student Number: XXXX XXXXX</p>
                <p>Name: Juan de la Cruz</p>
                <p>Degree Program: B.S. in Computer Science</p>
                <p>Year Level: x</p>
            </div>
        </div>
        <div>
            <h2>Upcoming Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Res ID</th>
                        <th>Requestee</th>
                        <th>Room Name</th>
                        <th>Res Schedule</th>
                        <th>Req Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan de la Cruz</td>
                        <td>Room 101</td>
                        <td>2023-10-01 10:00 - 2023-10-01 12:00</td>
                        <td>2023-09-25</td>
                        <td>Approved</td>
                        <td><a href="cancel.php?id=1">Cancel</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Student Dashboard. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body> 
</html>