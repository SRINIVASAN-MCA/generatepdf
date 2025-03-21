<?php
require_once "../database/db.php";

$sql = "SELECT id, trip_id, username, tour_name, check_in, check_out, adults, children, cost  FROM tour_booking ORDER BY id DESC";
$result = $conn->query($sql);

$trips = [];
while ($row = $result->fetch_assoc()) {
    $trips[] = $row;
}

header('Content-Type: application/json');
echo json_encode($trips);
?>