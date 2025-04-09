<?php
//Manila Timezone
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Time Confirmation Portal</title>
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
        
        <!-- Error Card -->
        <div class="w-full sm:w-[450px] md:w-[500px] lg:w-[620px] xl:w-[700px] bg-white rounded-lg shadow-lg">
            <!-- Header -->
            <div class="bg-[#BB4947] text-white text-[32px] py-2 rounded-t-lg text-center font-bold">
                Error
            </div>
            <div class="p-8 text-center">
                <!-- Error Message -->
                <p class="midnight-color text-[24px] mb-6">
                    Invalid credentials. <br> Please check your Employee ID and UID.
                </p>
                
                <!-- Return Button -->
                <a href="index.php" class="inline-block mt-4 bg-[#BB4947] text-white text-[22px] font-bold py-2.5
                    px-6 rounded-xl hover:bg-[#9E102D] transition duration-300">
                    Return to Login
                </a>
            </div>
        </div>
        
        <!-- Current Date and Time Display
        <p class="midnight-color text-[18px] mt-8">
            <?php echo date('F j, Y - g:i a'); ?>
        </p> -->
    </div>
</body>
</html>