<?php
require_once "../database/db.php"; // Ensure this path is correct

header("Content-Type: application/json");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $tripId = intval($_GET["id"]);

    $stmt = $conn->prepare("SELECT * FROM tour_booking WHERE id = ?");
    $stmt->bind_param("i", $tripId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $trip = $result->fetch_assoc();
        $stmt = $conn->prepare("SELECT * FROM vacation_summary WHERE fk_tour_booking = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $vacationSummaryResult = $stmt->get_result();

        $vacationSummaryList = []; // Initialize an array to store multiple records
        while ($row = $vacationSummaryResult->fetch_assoc()) {
            $vacationSummaryList[] = $row; // Append each row to the array
        }

        echo json_encode(["success" => true, "trip" => $trip, "vacation_summary" => $vacationSummaryList]);
    } else {
        echo json_encode(["success" => false, "message" => "Trip not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid Trip ID"]);
}

$conn->close();
?>