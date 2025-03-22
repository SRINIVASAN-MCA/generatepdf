<?php
require_once "../database/db.php";
require_once "../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Ensure a valid request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {

    // Configure Cloudinary
    Configuration::instance([
        "cloud_name" => "travels2020",
        "api_key" => "518361389447684",
        "api_secret" => "xXsCep4oJUwi9YuKZDuxDWdyJxc"
    ]);

    // Retrieve and sanitize form data
    $id = intval($_GET['id']);
    $userName = trim($_POST['userName'] ?? '');
    $tourName = trim($_POST['tourName'] ?? '');
    $checkIn = trim($_POST['checkIn'] ?? '');
    $checkOut = trim($_POST['checkOut'] ?? '');
    $numAdults = intval($_POST['numAdults'] ?? 0);
    $numChildren = intval($_POST['numChildren'] ?? 0);
    $inclusion = trim($_POST['inclusion'] ?? '');
    $exclusion = trim($_POST['exclusion'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $cost = trim($_POST['cost'] ?? '');
    $hotel = trim($_POST['hotel'] ?? '');
    $flight = trim($_POST['flight'] ?? '');

    // Fetch existing `trip_id` and images from the database
    $query = $conn->prepare("SELECT trip_id, tour_image, ftimage FROM tour_booking WHERE id = ?");
    if (!$query) {
        echo json_encode(["success" => false, "message" => "Database Error: " . $conn->error]);
        exit;
    }
    
    $query->bind_param("i", $id);
    $query->execute();
    $query->bind_result($existingTripId, $existingTourImage, $existingFlightImage);
    
    if (!$query->fetch()) {
        echo json_encode(["success" => false, "message" => "Error: No record found for the given ID."]);
        exit;
    }
    
    $query->close();
    $tripId = $existingTripId; // Always keep the existing trip_id

    // Function to handle image uploads
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
                echo json_encode(["success" => false, "message" => "Image Upload Error: " . $e->getMessage()]);
                exit;
            }
        }
        return "";
    }

    // Handle image uploads only if a new image is provided
    $tourImageURL = !empty($_FILES['tourImages']['tmp_name']) ? uploadImage($_FILES['tourImages'], "Travels2020") : $existingTourImage;
    $flightImageURL = !empty($_FILES['flightimages']['tmp_name']) ? uploadImage($_FILES['flightimages'], "Travels2020") : $existingFlightImage;

    // **Update query (trip_id remains unchanged)**
    $sql = "UPDATE tour_booking SET 
                username = ?, 
                tour_name = ?, 
                check_in = ?, 
                check_out = ?, 
                adults = ?, 
                children = ?, 
                inclusion = ?, 
                exclusion = ?, 
                cost = ?, 
                tour_image = ?, 
                notes = ?, 
                hotel = ?, 
                flight = ?, 
                ftimage = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sssssiisssssssi", 
        $userName, $tourName, $checkIn, $checkOut, 
        $numAdults, $numChildren, $inclusion, $exclusion, 
        $cost, $tourImageURL, $notes, $hotel, $flight, 
        $flightImageURL, $id
    );

    if ($stmt->execute()) {

        // **Update Vacation Summary**
        if (isset($_POST['days']) && is_array($_POST['days'])) {
            foreach ($_POST['days'] as $key => $day) {
                $stay = $day['stay'] ?? '';
                $date = $day['date'] ?? '';
                $itinerary = $day['itinerary'] ?? '';
                $imageURL = ""; // Default empty, will fetch existing image if needed

                // Fetch existing image from the database
                $stmtFetch = $conn->prepare("SELECT image FROM vacation_summary WHERE fk_tour_booking = ? AND date = ?");
                if (!$stmtFetch) {
                    echo json_encode(["success" => false, "message" => "Vacation Summary Fetch Error: " . $conn->error]);
                    exit;
                }

                $stmtFetch->bind_param("is", $id, $date);
                $stmtFetch->execute();
                $stmtFetch->bind_result($existingDayImage);
                $stmtFetch->fetch();
                $stmtFetch->close();

                // Handle day image upload
                if (isset($_FILES['days']['tmp_name'][$key]['vsImages']) && !empty($_FILES['days']['tmp_name'][$key]['vsImages'])) {
                    $dayFile = $_FILES['days']['tmp_name'][$key]['vsImages'];
                    try {
                        // Upload the image to Cloudinary
                        $upload = (new UploadApi())->upload($dayFile, [
                            "folder" => "Travels2020/days",
                            "resource_type" => "auto"
                        ]);
                        $imageURL = $upload["secure_url"];
                    } catch (Exception $e) {
                        echo json_encode(["success" => false, "message" => "Vacation Image Upload Error: " . $e->getMessage()]);
                        exit;
                    }
                } else {
                    $imageURL = $existingDayImage; // Keep existing image if no new upload
                }

                // **Update Vacation Summary**
                $stmtUpdate = $conn->prepare("UPDATE vacation_summary 
                                              SET stay=?, image=?, itinerary_content=? 
                                              WHERE fk_tour_booking=? AND date=?");
                if (!$stmtUpdate) {
                    echo json_encode(["success" => false, "message" => "Vacation Summary Update Error: " . $conn->error]);
                    exit;
                }

                $stmtUpdate->bind_param("sssis", $stay, $imageURL, $itinerary, $id, $date);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
        }

        echo json_encode(["success" => true, "message" => "Updated Successfully", "trip_id" => $tripId]);
    } else {
        echo json_encode(["success" => false, "message" => "Error in update: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
