<?php
require_once "../../database/db.php";

header("Content-Type: application/json; charset=UTF-8");

// Ensure the database connection works
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$sql = "SELECT id, tour_name, travel_from, travel_to, adults, children FROM customers_details ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Database query failed"]);
    exit;
}

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

// Ensure no other output exists before JSON encoding
echo json_encode(["success" => true, "data" => $customers]);
exit;
