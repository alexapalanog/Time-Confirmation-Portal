<?php
date_default_timezone_set('Asia/Manila');

include 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $timestamp = $_POST['timestamp'];
    $employeeID = $_POST['employeeID'];

    // Generate timekeeping_id
    $date = date('Ymd'); // Current date in YYYYMMDD format
    $timekeeping_id = $employeeID . $date;

    // Check if timekeeping_id exists
    $checkSql = "SELECT * FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $breakTimes = json_decode($row['break_times'], true);

        // Check if the number of break times is odd or even
        if (count($breakTimes) % 2 == 0) {
            // Even: Redirect to break-in.php
            header("Location: break-out.php?employeeID=$employeeID");
        } else {
            // Odd: Redirect to break-out.php
            header("Location: break-in.php?employeeID=$employeeID");
        }
        exit();
    } else {
        // Insert new entry
        $insertSql = "INSERT INTO timekeeping (timekeeping_id, employee_id, uid, break_times, total_minutes, date)
              VALUES ('$timekeeping_id', '$employeeID', '$uid', '[]', 0, CURDATE())"; 

        if ($conn->query($insertSql) === TRUE) {
            // Redirect to time-in.php if new record is created, passing employeeID
            header("Location: time-in.php?employeeID=$employeeID");
            exit();
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>