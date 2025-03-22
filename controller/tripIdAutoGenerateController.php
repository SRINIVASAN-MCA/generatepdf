<?php
require_once "../database/db.php";

$autoTripId = $_POST['autoTripId'] ?? '';

// **Check if the table exists first**
$checkTable = $conn->query("SHOW TABLES LIKE 'tour_booking'");
if ($checkTable->num_rows == 0) {
    die("Error: Table 'tour_booking' does not exist.");
}

// Define the date prefix
$datePrefix = date('dmy');

if (!empty($_POST['id'])) {  
    // **Updating an existing trip (keep the same trip_id)**
    $id = intval($_POST['id']);
    
    $query = "SELECT trip_id FROM tour_booking WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch and keep the existing trip ID
        $row = $result->fetch_assoc();
        $tripId = $row['trip_id'];
    } else {
        die("Error: No record found for the given ID.");
    }

    $stmt->close();
} else {
    // **Creating a new trip (generate a new trip ID)**
    $query = "SELECT trip_id FROM tour_booking WHERE trip_id LIKE ? ORDER BY trip_id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $tripPrefix = "T2020" . $datePrefix . "%";
    $stmt->bind_param("s", $tripPrefix);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the last trip ID
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

    $stmt->close();
}

// **Return the trip ID (for debugging)**
echo $tripId;
?>
