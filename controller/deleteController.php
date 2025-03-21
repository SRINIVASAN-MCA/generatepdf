<?php
require_once "../database/db.php"; // Ensure this file has the database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tripId = intval($_POST["trip_id"] ?? 0); // Ensure integer input

    if ($tripId > 0) {
        $stmt = $conn->prepare("DELETE FROM tour_booking WHERE id = ?");
        $stmt->bind_param("i", $tripId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Trip deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete trip"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid Trip ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>