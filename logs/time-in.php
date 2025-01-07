<?php
//Manila Timezone
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
    <link href="https://fonts.cdnfonts.com/css/jellee-heavy" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .jollibee-font {
            font-family: 'Jellee Heavy', sans-serif;
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
            <h1 class="text-red-600 text-[60px] font-extrabold jollibee-font tracking-wide">Jollibee</h1>
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
                <p class="midnight-color text-[60px] md:text-[70px] lg:text-[90px] xl:text-[110px] leading-none">
                    <?php echo date('g:i a');?>
                </p>
            </div>
        </div>
        <!-- -->
        <p class="midnight-color text-[40px] mt-4">Hi, Name!</p>
        <p class="midnight-color text-[28px]">Click 'Time-In' to confirm your attendance</p>
        <!-- Time-In Button -->
        <div>
            <button class="mt-6 bg-[#BB4947] text-white text-[28px] font-bold py-2.5
                px-6 w-[250px] rounded-xl hover:bg-[#9E102D] transition duration-300">
                Time-In
            </button>
        </div>        
    </div>
</body>
</html>
