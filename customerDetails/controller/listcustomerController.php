<?php
require_once "../database/db.php";

$sql = "SELECT id, 	customer_name, mobile_number, email, tour_name, travel_from, travel_to, adults, children, cost  FROM customers_details ORDER BY id DESC";
$result = $conn->query($sql);

$customer = [];
while ($row = $result->fetch_assoc()) {
    $customer[] = $row;
}

header('Content-Type: application/json');
echo json_encode($customer);
?>