<?php
// Retrieve the recorded time and action from the POST request
$recordedTime = isset($_POST['time']) ? $_POST['time'] : 'N/A';
$actionType = isset($_POST['action']) ? $_POST['action'] : 'Unknown Action';
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
            <h1 
                class="text-red-600 text-[42px] tracking-wide"
                style="font-family: 'Jellee', sans-serif;">
                Jollibee
            </h1>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[25px] shadow-lg p-6 relative flex flex-col items-center w-full h-[180px]">
            <!-- Check Icon -->
            <div 
                class="flex items-center justify-center bg-[#BB4947] text-white w-16 h-16 rounded-full -mt-12 mb-4 shadow-md">
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-10 w-10" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Text Content -->
            <p class="text-[32px] font-semibold text-[#121212] mb-1">Name</p>
            <p class="text-[24px] text-[#121212]"><?php echo htmlspecialchars($actionType); ?> at <?php echo htmlspecialchars($recordedTime); ?></p>
        </div>

        <!-- Confirm Button -->
        <form method="POST" action="index.php">
            <button class="mt-6 bg-[#BB4947] text-white text-[24px] font-bold py-2.5
                    px-6 w-[200px] rounded-xl hover:bg-[#9E102D] transition duration-300">
                Confirm
            </button>
        </form>
    </div>
</body>
</html>
