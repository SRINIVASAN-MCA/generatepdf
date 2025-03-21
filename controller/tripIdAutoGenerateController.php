<?php
require_once "../database/db.php";

$autoTripId = $_POST['autoTripId'] ?? '';

// **Check if the table exists first**
$checkTable = $conn->query("SHOW TABLES LIKE 'tour_booking'");
if ($checkTable->num_rows == 0) {
    die("Error: Table 'tour_booking' does not exist.");
}

if ($autoTripId == 0) {
    $query = "SELECT * FROM tour_booking WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Define the date prefix
    $datePrefix = date('dmy');

    if ($result->num_rows > 0) {
        // Fetch the last inserted record
        $row = $result->fetch_assoc();
        $lastTripId = $row['trip_id'];

        // Extract the numeric part from the last trip ID (last 4 digits)
        $numericPart = (int) substr($lastTripId, -4);

        // Increment the numeric part by 1
        $incrementedDigits = str_pad($numericPart + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // First trip ID if no records exist
        $incrementedDigits = "0001";
    }

    // Generate the new trip ID
    $tripId = "T2020" . $datePrefix . $incrementedDigits;

    echo $tripId;

    $stmt->close();
}
?>
