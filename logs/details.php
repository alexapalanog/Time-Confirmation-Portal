<?php
date_default_timezone_set('Asia/Manila');

include 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $timestamp = $_POST['timestamp'];
    $employeeID = $_POST['employeeID'];

    // Debugging: Log POST data
    error_log("POST Data: UID=$uid, Timestamp=$timestamp, EmployeeID=$employeeID");

    // Validate if employeeID and uid match
    $validateSql = "SELECT * FROM employee WHERE employee_id = '$employeeID' AND uid = '$uid'";
    $validateResult = $conn->query($validateSql);

    // Debugging: Log validation query and result
    error_log("Validation SQL Query: $validateSql");
    if ($validateResult) {
        error_log("Validation Query Result: " . $validateResult->num_rows . " rows found");
    } else {
        error_log("Validation Query Error: " . $conn->error);
    }

    if ($validateResult->num_rows === 0) {
        // Debugging: Log mismatch and redirect
        error_log("Employee ID and UID do not match. Redirecting to error page.");
        header("Location: error.php?error=invalid_credentials");
        exit();
    }

    // Generate timekeeping_id in the format DDMMYY0001
    $date = date('dmy'); // Current date in DDMMYY format
    $formattedEmployeeID = str_pad($employeeID, 4, '0', STR_PAD_LEFT); // Pad employee ID to 4 digits
    $timekeeping_id = $date . $formattedEmployeeID;

    // Debugging: Log generated timekeeping_id
    error_log("Generated Timekeeping ID: $timekeeping_id");

    // Check if timekeeping_id exists
    $checkSql = "SELECT * FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
    $result = $conn->query($checkSql);

    // Debugging: Log SQL query and result
    error_log("SQL Query: $checkSql");
    if ($result) {
        error_log("SQL Query Result: " . $result->num_rows . " rows found");
    } else {
        error_log("SQL Query Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Debugging: Log fetched row data
        error_log("Fetched Row: " . json_encode($row));

        // Check if check-in time exists
        if (empty($row['check_in'])) {
            // Debugging: Log redirection to time-in.php
            error_log("No check-in time found. Redirecting to time-in.php");
            header("Location: time-in.php?employeeID=$employeeID");
            exit();
        }

        // Check if check-out time exists
        if (!empty($row['check_out'])) {
            // Debugging: Log redirection to already-checked-out.php
            error_log("Check-out time already exists. Redirecting to already-checked-out.php");
            header("Location: already-checked-out.php?employeeID=$employeeID");
            exit();
        }

        $breakTimes = json_decode($row['break_times'], true);

        // Debugging: Log break times
        error_log("Break Times: " . json_encode($breakTimes));

        // Check the last entry in break_times
        if (!empty($breakTimes)) {
            $lastBreak = end($breakTimes);
            if ($lastBreak['breakIn'] == null) {
                // Debugging: Log redirection to break-in.php
                error_log("Last break has a null breakIn. Redirecting to break-in.php");
                header("Location: break-in.php?employeeID=$employeeID");
            } else {
                // Debugging: Log redirection to break-out.php
                error_log("Last break has a non-null breakIn. Redirecting to break-out.php");
                header("Location: break-out.php?employeeID=$employeeID");
            }
        } else {
            // Debugging: Log redirection to break-in.php
            error_log("No break times found. Redirecting to break-out.php");
            header("Location: break-out.php?employeeID=$employeeID");
        }
        exit();
    } else {
        // Debugging: Log no timekeeping_id found
        error_log("No timekeeping record found for ID: $timekeeping_id");
    }
    $conn->close();
}
?>