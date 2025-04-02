<?php
// Manila Timezone
date_default_timezone_set('Asia/Manila');
include 'db_connect.php';


$employeeID = $_GET['employeeID'] ?? null;
$employeeName = "Guest";
$totalMinutes = 0;

if ($employeeID) {
    $sql = "SELECT first_name FROM employee WHERE employee_id = '$employeeID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $employeeName = $row['first_name'];
    }
}
if ($employeeID) {
    // Generate timekeeping_id
    $date = date('dmy'); // Current date in DDMMYY format
    $formattedEmployeeID = str_pad($employeeID, 4, '0', STR_PAD_LEFT); // Pad employee ID to 4 digits
    $timekeeping_id = $date . $formattedEmployeeID;

    // Fetch total_minutes
    $fetchSql = "SELECT breaks FROM timekeeping WHERE timekeeping_id = '$timekeeping_id'";
    $result = $conn->query($fetchSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalMinutes = $row['breaks'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Break In Page</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/jellee" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .jollibee-font {
            font-family: 'Jellee', sans-serif;
        }

        .midnight-color {
            color: #121212;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-[#FFE6A3]">
    <!-- Main Container with margin-top to adjust the positioning -->
    <div class="text-center w-full max-w-2xl px-6 flex flex-col items-center justify-center mt-[-5%]">
        <!-- Logo and Jollibee Title -->
        <div class="flex items-center justify-center mb-6">
            <img src="../images/jabee logo.png" alt="Jollibee Logo" class="w-20 h-25 mr-8">
            <h1 class="text-red-600 text-[60px] jollibee-font tracking-wide">Jollibee</h1>
        </div>
        <!-- Time Confirmation Portal-->
        <p class="midnight-color text-[42px] font-bold mb-4 -mt-5">Time Confirmation Portal</p>
        <!-- Date and Time Card (Responsive Width) -->
        <div class="w-full sm:w-[450px] md:w-[500px] lg:w-[620px] xl:w-[700px] bg-white rounded-lg shadow-lg">
            <!-- Date -->
            <div class="bg-[#FDB55E] midnight-color text-[32px] py-2 rounded-t-lg text-center">
                <?php echo date('F j, Y'); ?>
            </div>
            <div class="p-6 text-center">
                <!-- Time -->
                <p class="midnight-color text-[60px] md:text-[70px] lg:text-[90px] xl:text-[100px] leading-none">
                    <?php echo date('g:i a'); ?>
                </p>
            </div>
        </div>
        <!-- -->
        <p class="midnight-color text-[40px] mt-4">Hi, <?php echo htmlspecialchars($employeeName); ?>!</p>
        <p class="midnight-color text-[28px]">Choose your action</p>
        <!-- Buttons in the Same Row -->
        <div class="flex justify-center mt-6 space-x-4">
            <form method="POST" action="confirmation-page.php">
                <input type="hidden" name="time" value="<?php echo date('H:i:s'); ?>">
                <input type="hidden" name="employeeID" value="<?php echo htmlspecialchars($employeeID); ?>">
                <input type="hidden" name="isCheckIn" value="0">
                <button class="mt-6 bg-[#BB4947] text-white text-[28px] font-bold py-2.5
                      px-6 w-[250px] rounded-xl hover:bg-[#9E102D] transition duration-300" name="action"
                    value="Break Out">
                    Break Out
                </button>
            </form>
            <form method="POST" action="confirmation-page.php">
                <input type="hidden" name="time" value="<?php echo date('H:i:s'); ?>">
                <input type="hidden" name="employeeID" value="<?php echo htmlspecialchars($employeeID); ?>">
                <input type="hidden" name="isCheckIn" value="1">
                <button class="mt-6 text-white text-[28px] font-bold py-2.5 px-6 w-[250px] rounded-xl transition duration-300 
                    <?php echo ($totalMinutes < 60) ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#BB4947] hover:bg-[#9E102D]'; ?>" 
                    name="action" value="Time-Out" <?php echo ($totalMinutes < 60) ? 'disabled' : ''; ?>>
                    Time-Out
                </button>
            </form>
        </div>
    </div>
</body>

</html>