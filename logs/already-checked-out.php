<?php
//Manila Timezone
date_default_timezone_set('Asia/Manila');
include 'db_connect.php';
$employeeID = $_GET['employeeID'] ?? null;
$employeeName = "Guest";

if ($employeeID) {
    $sql = "SELECT first_name FROM employee WHERE employee_id = '$employeeID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $employeeName = $row['first_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time In Page</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2 ?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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
    <script>
            function updateTime() {
                const timeElement = document.getElementById('time');
                const date = new Date();

                const hours = date.getHours();
                const minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'pm' : 'am';
                const formattedHours = hours % 12 || 12;
                const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

                const timeString = `${formattedHours}:${formattedMinutes} ${ampm}`;
                timeElement.textContent = timeString;
            }

            function initClock() {
                updateTime();
                setInterval(updateTime, 1000);
            }

            window.onload = () => {
                initClock();
            };
    </script>


</head>
<body class="flex items-center justify-center min-h-screen bg-[#FFE6A3]">
    <!-- Main Container -->
    <div class="text-center w-full max-w-2xl px-6 flex flex-col items-center justify-center mt-[-5%]">
        <div class="flex items-center justify-center mb-6">
            <img src="../images/jabee logo.png" alt="Jollibee Logo" class="w-20 h-25 mr-8">
            <h1 class="text-red-600 text-[60px] jollibee-font tracking-wide">Jollibee</h1>
        </div>
        <p class="midnight-color text-[42px] font-bold mb-4 -mt-5">Time Confirmation Portal</p>
        <div class="w-full sm:w-[450px] md:w-[500px] lg:w-[620px] xl:w-[700px] bg-white rounded-lg shadow-lg">
            <div class="bg-[#FDB55E] midnight-color text-[32px] py-2 rounded-t-lg text-center">
                <?php echo date('F j, Y'); ?>
            </div>
            <div class="p-6 text-center">
                <p id="time" class="midnight-color text-[60px] md:text-[70px] lg:text-[90px] xl:text-[110px] leading-none">
                    <?php echo date('g:i a'); ?>
                </p>
            </div>
        </div>
        <p class="midnight-color text-[40px] mt-4"> Hi, <?php echo htmlspecialchars($employeeName); ?>! You are already checked out.</p> 
        <div class="flex justify-center mt-6">
            <form method="GET" action="index.php">
                <button class="mt-6 bg-[#BB4947] text-white text-[28px] font-bold py-2.5
                      px-6 w-[250px] rounded-xl hover:bg-[#9E102D] transition duration-300">
                    Back
                </button>
            </form>
        </div>
    </div>
</body>
</html>
