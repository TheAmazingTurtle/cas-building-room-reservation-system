<!--History page for the admin which is same same lang siya sa student-dashboard huhu cause same design lang sila sa figma-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin History</title>
    <link rel="stylesheet" href="archiveStyle.css">
</head>
<body>
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
             <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_manage.php">Manage</a> 
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
            <h2>Request History / Archived Reservations</h2>
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
        <p>&copy; 2023 Admin History. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body> 
</html>