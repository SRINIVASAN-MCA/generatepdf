<?php
require_once "../database/db.php"; // Ensure this file has the database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customId = intval($_POST["id"] ?? 0); // Ensure integer input

    if ($customId > 0) {
        $stmt = $conn->prepare("DELETE FROM customers_details WHERE id = ?");
        $stmt->bind_param("i", $customId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Customer deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete Customer"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid Customer ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>