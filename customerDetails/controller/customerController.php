<?php
require_once "../database/db.php";
require_once "../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Set JSON response header
header("Content-Type: application/json");

// Ensure the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Configure Cloudinary
Configuration::instance([
    "cloud_name" => "travels2020",
    "api_key" => "518361389447684",
    "api_secret" => "xXsCep4oJUwi9YuKZDuxDWdyJxc"
]);

// Retrieve and sanitize input data
$customerName = trim($_POST['customerName'] ?? '');
$mobileNumber = trim($_POST['mobileNumber'] ?? '');
$email = trim($_POST['email'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$anniversary = trim($_POST['anniversary'] ?? '');
$tourName = trim($_POST['tourName'] ?? '');
$destination = trim($_POST['destination'] ?? '');
$numAdults = intval($_POST['numAdults'] ?? 0);
$numChildren = intval($_POST['numChildren'] ?? 0);
$travelFrom = trim($_POST['travelFrom'] ?? '');
$travelTo = trim($_POST['travelTo'] ?? '');
$panNumber = trim($_POST['panNumber'] ?? '');
$passportNumber = trim($_POST['passportNumber'] ?? '');
$passportIssueCity = trim($_POST['passportIssueCity'] ?? '');
$passportIssueCountry = trim($_POST['passportIssueCountry'] ?? '');
$passportIssueDate = trim($_POST['passportIssueDate'] ?? '');
$passportExpiryDate = trim($_POST['passportExpiryDate'] ?? '');

// Function to upload an image to Cloudinary
function uploadImage($file, $folder)
{
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

// Handle image uploads
$passportFront = isset($_FILES['passportFront']) ? uploadImage($_FILES['passportFront'], "Travels2020") : "";
$passportBack = isset($_FILES['passportBack']) ? uploadImage($_FILES['passportBack'], "Travels2020") : "";

// Insert into database
$sql = "INSERT INTO customers_details 
        (customer_name, mobile_number, email, dob, anniversary, tour_name, destination, adults, children, travel_from, travel_to, pan_number, passport_number, pass_issue_city, pass_issue_country, pass_issue_date, pass_expiry_date, passport_front, passport_back) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL error: " . $conn->error]);
    exit;
}

// Bind parameters and execute the statement
$stmt->bind_param("sssssssiissssssssss", 
    $customerName, $mobileNumber, $email, $dob, $anniversary, $tourName, $destination, 
    $numAdults, $numChildren, $travelFrom, $travelTo, $panNumber, $passportNumber, 
    $passportIssueCity, $passportIssueCountry, $passportIssueDate, $passportExpiryDate,
    $passportFront, $passportBack
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Customer details saved successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
