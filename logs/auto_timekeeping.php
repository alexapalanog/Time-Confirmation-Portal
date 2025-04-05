<?php
// Manila Timezone
date_default_timezone_set('Asia/Manila');
include 'db_connect.php';

// Get current date
$currentDate = date('Y-m-d');

// Query to get all employees without a timekeeping record for today
$query = "SELECT employee_id, uid FROM employee WHERE employee_id NOT IN (
    SELECT employee_id FROM timekeeping WHERE date = ?
)";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $employeeId = $row['employee_id'];
        $uid = $row['uid'];

        // Generate timekeeping_id based on the designed format
        $date = date('dmy'); // Current date in DDMMYY format
        $formattedEmployeeID = str_pad($employeeId, 4, '0', STR_PAD_LEFT); // Ensure employee ID is 4 digits
        $timekeeping_id = $date . $formattedEmployeeID;

        // Insert default timekeeping record
        $insertQuery = "INSERT INTO `techbees`.`timekeeping` 
        (`timekeeping_id`, `employee_id`, `uid`, `date`, `status`, `breaks`, `total_hours`, `target_hours`, `remarks`, `check_in`, `check_out`, `break_times`, `undertime`, `overtime`, `late`, `daytype`, `night_hours`) 
        VALUES (?, ?, NULL, ?, 'absent', 0, 0, 8, '--', NULL, NULL, NULL, 0, 0, 0, 'ordinary', 0)";

        if ($insertStmt = $conn->prepare($insertQuery)) {
            $insertStmt->bind_param('sis', $timekeeping_id, $employeeId, $currentDate);
            $insertStmt->execute();
            $insertStmt->close();
        }
    }

    $stmt->close();
}

$conn->close();