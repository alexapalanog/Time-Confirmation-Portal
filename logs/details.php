<?php
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'] ?? 'Unknown';
    $timestamp = $_POST['timestamp'] ?? 'Unknown';
    $employeeID = $_POST['employeeID'] ??'Unknown';

    echo "<h1>Card Details</h1>";
    echo "<p>UID: $uid</p>";
    echo "<p>Timestamp: " . date('F j, Y, g:i a', strtotime($timestamp)) . "</p>";
    echo "<p>Employee ID: $employeeID</p>";
} else {
    echo "<p>No card data received.</p>";
}
?>
