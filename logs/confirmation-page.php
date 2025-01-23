<?php

include 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $time = $_POST['time'];
    $employeeID = $_POST['employeeID'];
    $isCheckIn = $_POST['isCheckIn'];
    $employeeName = "Guest";
    if ($employeeID) {
        $sql = "SELECT first_name FROM employee WHERE employee_id = '$employeeID'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $employeeName = $row['first_name'];
        }
    }
    // Generate timekeeping_id
    $date = date('Ymd'); // Current date in YYYYMMDD format

    $timekeeping_id = $employeeID . $date;
    if ($isCheckIn == 1) {
        $fetchSql = "SELECT check_in FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
        $result = $conn->query($fetchSql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $checkInTime = $row['check_in'];

            if ($isCheckIn == 1) {
                if (empty($checkInTime)) {
                    // Update the check-in time
                    $actionType = "Checked-In";
                    $updateSql = "UPDATE timekeeping SET check_in = '$time' WHERE timekeeping_id = '$timekeeping_id'";
                } else {
                    // Update the check-out time
                    $actionType = "Checked-Out";
                    $updateSql = "UPDATE timekeeping SET check_out = '$time' WHERE timekeeping_id = '$timekeeping_id'";
                }
            }
        }
        $conn->query($updateSql);
        $conn->close();
    } else {
        // Fetch current break_times
        $fetchSql = "SELECT break_times FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
        $result = $conn->query($fetchSql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $breakTimes = json_decode($row['break_times'], true);
            if (count($breakTimes) % 2 == 0) {
                $actionType = "Break-out";
                // Append new break time
                $breakTimes[] = $time;
                $updateSql = "UPDATE timekeeping SET break_times = '" . json_encode($breakTimes) . "' WHERE timekeeping_id = '$timekeeping_id'";

            } else {
                $actionType = "Break-in";
                $lastBreakTime = end($breakTimes);
                $lastBreakTimestamp = strtotime($lastBreakTime);
                $currentTimestamp = strtotime($time);
                $minutesDiff = round(($currentTimestamp - $lastBreakTimestamp) / 60);
                $fetchSql = "SELECT total_minutes, breaks FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
                $result = $conn->query($fetchSql);
                $row = $result->fetch_assoc();
                $totalMinutes = $minutesDiff + $row['total_minutes'];
                $breaks = $row['breaks'] + 1;
                // Append new break time
                $breakTimes[] = $time;
                $updateSql = "UPDATE timekeeping SET break_times = '" . json_encode($breakTimes) . "', breaks = '$breaks', total_minutes = '$totalMinutes' WHERE timekeeping_id = '$timekeeping_id'";
            }
            $conn->query($updateSql);
            $conn->close();
        }
    }


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
    <!-- Main Container with Scale -->
    <div class="scale-125 transform origin-center">
        <!-- Main Content -->
        <div class="text-center w-full max-w-sm px-6 flex flex-col items-center justify-center">
            <!-- Logo and Jollibee Title -->
            <div class="flex items-center justify-center mb-8">
                <img src="../images/jabee logo.png" alt="Jollibee Logo" class="w-20 h-25 mr-8">
                <h1 class="text-red-600 text-[60px] tracking-wide" style="font-family: 'Jellee', sans-serif;">
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
                <button class="mt-6 bg-[#BB4947] text-white text-[24px] font-bold py-2.5
                        px-6 w-[200px] rounded-xl hover:bg-[#9E102D] transition duration-300">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</body>

</html>
