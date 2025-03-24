<?php
require_once "../../database/db.php";
require_once "../../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

header("Content-Type: application/json");

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Configure Cloudinary (Move these credentials to environment variables for security)
Configuration::instance([
    "cloud_name" => "travels2020",
    "api_key" => "518361389447684",
    "api_secret" => "xXsCep4oJUwi9YuKZDuxDWdyJxc"
]);

// Function to upload image to Cloudinary
function uploadImage($file, $folder) {
    if (!empty($file['tmp_name'])) {
        try {
            $upload = (new UploadApi())->upload($file['tmp_name'], [
                "folder" => $folder,
                "resource_type" => "auto"
            ]);
            return $upload["secure_url"] ?? "";
        } catch (Exception $e) {
            error_log("Cloudinary Upload Error: " . $e->getMessage());
            return "";
        }
    }
    return "";
}

// Retrieve and sanitize input data
$travelFrom = $_POST['travelFrom'] ?? '';
$travelTo = $_POST['travelTo'] ?? '';
$tourName = $_POST['tourName'] ?? '';
$destination = $_POST['destination'] ?? '';
$numAdults = isset($_POST['numAdults']) ? intval($_POST['numAdults']) : 0;
$numChildren = isset($_POST['numChildren']) ? intval($_POST['numChildren']) : 0;

// Decode passengers JSON data
$passengers = json_decode($_POST['passengers'] ?? '[]', true);

if (!is_array($passengers) || count($passengers) === 0) {
    echo json_encode(["success" => false, "message" => "Passenger data is missing."]);
    exit;
}

// Start database transaction
$conn->begin_transaction();

try {
    // Insert customer details
    $sql = "INSERT INTO customers_details (travel_from, travel_to, tour_name, destination, adults, children)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("ssssii", $travelFrom, $travelTo, $tourName, $destination, $numAdults, $numChildren);
    
    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $customerId = $conn->insert_id;
    $stmt->close();

    // Insert passengers data
    foreach ($passengers as $index => $passenger) {
        $passengerName = $passenger['passengerName'] ?? null;
        $mobileNumber = $passenger['mobileNumber'] ?? null;
        $email = $passenger['email'] ?? null;
        $dob = $passenger['dob'] ?? null;
        $anniversary = $passenger['anniversary'] ?? null;
        $panNumber = $passenger['panNumber'] ?? null;
        $passportNumber = $passenger['passportNumber'] ?? null;
        $passportIssueCity = $passenger['passportIssueCity'] ?? null;
        $passportIssueCountry = $passenger['passportIssueCountry'] ?? null;
        $passportIssueDate = $passenger['passportIssueDate'] ?? null;
        $passportExpiryDate = $passenger['passportExpiryDate'] ?? null;

        // Handle file uploads
        $passportFront = isset($_FILES['passportFront']['tmp_name'][$index]) 
                        ? uploadImage(["tmp_name" => $_FILES['passportFront']['tmp_name'][$index]], "Travels2020") 
                        : "";

        $passportBack = isset($_FILES['passportBack']['tmp_name'][$index]) 
                        ? uploadImage(["tmp_name" => $_FILES['passportBack']['tmp_name'][$index]], "Travels2020") 
                        : "";
 
        // Insert into passengers table
        $sql = "INSERT INTO passengers 
                (customer_id, passenger_name, mobile_number, email, dob, anniversary, pan_number, passport_number, 
                passport_issue_city, passport_issue_country, passport_issue_date, passport_expiry_date, 
                passport_front, passport_back) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param(
            "isssssssssssss",
            $customerId, $passengerName, $mobileNumber, $email, $dob, $anniversary,
            $panNumber, $passportNumber, $passportIssueCity, $passportIssueCountry,
            $passportIssueDate, $passportExpiryDate, $passportFront, $passportBack
        );

        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        $stmt->close();
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Customer and passengers saved successfully!"]);
} catch (Exception $e) {
    // Rollback transaction on failure
    $conn->rollback();
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Database error occurred", "error" => $e->getMessage()]);
}

$conn->close();
