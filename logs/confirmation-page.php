<?php

include 'db_connect.php'; // Include your database connection
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $time = $_POST['time'];
    $employeeID = $_POST['employeeID'];
    $isCheckIn = $_POST['isCheckIn'];
    $employeeName = "Guest";

    if ($employeeID) {
        $sql = "SELECT first_name FROM employee WHERE employee_id = '$employeeID'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $employeeName = $row['first_name'];
        } else {
            error_log("Employee ID $employeeID not found in the database.");
        }
    }

    // Generate timekeeping_id
    $date = date('dmy'); // Current date in DDMMYY format
    $formattedEmployeeID = str_pad($employeeID, 4, '0', STR_PAD_LEFT); // Ensure employee ID is 4 digits
    $timekeeping_id = $date . $formattedEmployeeID;

    error_log("Generated Timekeeping ID: $timekeeping_id");

    if ($isCheckIn == 1) {
        $fetchSql = "SELECT check_in, status FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
        $result = $conn->query($fetchSql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $checkInTime = $row['check_in'];
            $status = $row['status'];

            if (empty($checkInTime)) {
                $actionType = "Checked-In";
                $updateSql = "UPDATE timekeeping SET check_in = '$time'";

                if ($status === "absent") {
                    $updateSql .= ", status = 'present'";
                }

                $updateSql .= " WHERE timekeeping_id = '$timekeeping_id'";
            } else {
                $actionType = "Checked-Out";
                $updateSql = "UPDATE timekeeping SET check_out = '$time' WHERE timekeeping_id = '$timekeeping_id'";
            }

            if (!empty($updateSql)) {
                if ($conn->query($updateSql)) {
                    error_log("Successfully executed query: $updateSql");
                } else {
                    error_log("Error executing query: " . $conn->error);
                }
            } else {
                error_log("No update query executed for check-in/check-out.");
            }
        } else {
            error_log("No matching timekeeping record found for ID: $timekeeping_id");
        }
    } else {
        $fetchSql = "SELECT break_times FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
        $result = $conn->query($fetchSql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $breakTimes = isset($row['break_times']) && $row['break_times'] !== null 
                ? json_decode($row['break_times'], true) 
                : [];

            if (empty($breakTimes) || end($breakTimes)['breakOut'] == null) {
                $actionType = "Break-out";
                $breakTimes[] = ["breakIn" => null, "breakOut" => $time];
                $updateSql = "UPDATE timekeeping SET break_times = '" . json_encode($breakTimes) . "' WHERE timekeeping_id = '$timekeeping_id'";
            } else {
                $lastBreakIndex = count($breakTimes) - 1;

                if ($breakTimes[$lastBreakIndex]['breakIn'] === null) {
                    $actionType = "Break-in";
                    $breakTimes[$lastBreakIndex]['breakIn'] = $time;

                    $lastBreakOut = $breakTimes[$lastBreakIndex]['breakOut'];
                    $lastBreakTimestamp = strtotime($lastBreakOut);
                    $currentTimestamp = strtotime($time);
                    $minutesDiff = number_format(($currentTimestamp - $lastBreakTimestamp) / 60, 4);

                    $fetchSql = "SELECT breaks FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
                    $result = $conn->query($fetchSql);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $totalMinutes = $minutesDiff + $row['breaks'];

                        $updateSql = "UPDATE timekeeping SET break_times = '" . json_encode($breakTimes) . "', breaks = '$totalMinutes' WHERE timekeeping_id = '$timekeeping_id'";
                    } else {
                        error_log("Failed to fetch current breaks for ID: $timekeeping_id");
                    }
                } else {
                    $actionType = "Break-out";
                    $breakTimes[] = ["breakIn" => null, "breakOut" => $time];
                    $updateSql = "UPDATE timekeeping SET break_times = '" . json_encode($breakTimes) . "' WHERE timekeeping_id = '$timekeeping_id'";
                }
            }

            if (!empty($updateSql)) {
                if ($conn->query($updateSql)) {
                    error_log("Successfully executed query: $updateSql");
                } else {
                    error_log("Error executing query: " . $conn->error);
                }
            } else {
                error_log("No update query executed for break-in/break-out.");
            }
        } else {
            error_log("No matching timekeeping record found for ID: $timekeeping_id");
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Page</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/jellee" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-[#FFE6A3]">
    <!-- Main Container -->
    <div class="text-center w-full max-w-sm px-6 flex flex-col items-center justify-center">
        <!-- Logo and Jollibee Title -->
        <div class="flex items-center justify-center mb-8">
            <img src="../images/jabee logo.png" alt="Jollibee Logo" class="w-20 h-25 mr-8">
            <h1 class="text-red-600 text-[42px] tracking-wide" style="font-family: 'Jellee', sans-serif;">
                Jollibee
            </h1>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[25px] shadow-lg p-6 relative flex flex-col items-center w-full h-[180px]">
            <!-- Check Icon -->
            <div
                class="flex items-center justify-center bg-[#BB4947] text-white w-16 h-16 rounded-full -mt-12 mb-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Text Content -->
            <p class="text-[32px] font-semibold text-[#121212] mb-1"><?php echo htmlspecialchars($employeeName); ?></p>
            <p class="text-[24px] text-[#121212]"><?php echo htmlspecialchars($actionType); ?> at
                <?php $formattedTime = date('g:i a', strtotime($time));
                echo htmlspecialchars($formattedTime); ?>
            </p>
        </div>

        <!-- Confirm Button -->
        <form method="POST" action="index.php">