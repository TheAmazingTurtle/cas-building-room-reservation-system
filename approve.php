<!--Approval page for the admin--> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approve Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
            <a href="home.php">Dashboard</a>
            <a href="profile.php">Request</a>
            <a href="approve.php">Approval</a>
            <a href="manage.html">Manage</a> 
            <a href="courses.php">Archive</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div>
            <h1>Approve Requests</h1>
            <h3>Search</h3>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Room Requested</th>
                        <th>Time-in -- Time-out</th>
                        <th>Purpose of Booking</th>
                        <th>Name and Position</th>
                        <th>Approve</th>
                        <th>Decline</th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Room 202</td>
                        <td>2023-10-05 14:00 - 2023-10-05 16:00</td>
                        <td>Shooting for Project</td>
                        <td>Maria Clara - Student</td>
                        <td>2023-09-25</td>
                        <td><a href="accept.php?id=2">Approve</a></td>
                        <td><a href="cancel.php?id=2">Cancel</a></td>
                    </tr>
                </tbody>
            </table>
        <div>
    </main>

    <footer>
        <p>&copy; 2023 Admin Approve Requests. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body> 
</html>