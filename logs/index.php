<?php
// Manila Timezone
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time In Page</title>
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
    <script>
        // JavaScript to handle dynamic time updates
        function updateTime() {
            const timeElement = document.getElementById('time');
            const date = new Date();

            const hours = date.getHours();
            const minutes = date.getMinutes();
            const ampm = hours >= 12 ? 'pm' : 'am';
            const formattedHours = hours % 12 || 12; // Convert to 12-hour format
            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

            const timeString = `${formattedHours}:${formattedMinutes} ${ampm}`;
            timeElement.textContent = timeString;
        }

        function initClock() {
            updateTime(); // Update time immediately on page load
            setInterval(updateTime, 1000); // Update every second
        }

        // Execute clock function after DOM loads
        window.onload = initClock;
    </script>
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
                <p id="time" class="midnight-color text-[60px] md:text-[70px] lg:text-[90px] xl:text-[110px] leading-none">
                    <?php echo date('g:i a'); ?>
                </p>
            </div>
        </div>
        <p class="midnight-color text-[40px] mt-4">Please Tap Your Card</p> 
    </div>
</body>
</html>
