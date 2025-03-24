<?php
require_once "../../database/db.php"; // Ensure database connection

header("Content-Type: application/json; charset=UTF-8");

// Ensure it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Get and sanitize input
$customId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

if ($customId <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid Customer ID"]);
    exit;
}

// Prepare the SQL statement
$stmt = $conn->prepare("DELETE FROM customers_details WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $customId);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Customer deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete customer"]);
}

// Close connections
$stmt->close();
$conn->close();
exit;
?>
