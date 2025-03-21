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
    $tripId = trim($_POST['tripId'] ?? '');
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
                echo json_encode(["success" => false, "message" => $e->getMessage()]);
                exit;
            }
        }
        return "";
    }

    // Fetch existing image URLs from the database
    $query = $conn->prepare("SELECT tour_image, ftimage, officer_image FROM tour_booking WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $query->bind_result($existingTourImage, $existingFlightImage, $existingOfficerImage);
    $query->fetch();
    $query->close();

    // Handle image uploads only if a new image is provided, otherwise keep the existing one
    $tourImageURL = !empty($_FILES['tourImages']['tmp_name']) ? uploadImage($_FILES['tourImages'], "Travels2020") : $existingTourImage;
    $flightImageURL = !empty($_FILES['flightimages']['tmp_name']) ? uploadImage($_FILES['flightimages'], "Travels2020") : $existingFlightImage;
    $officerImageURL = !empty($_FILES['officerimages']['tmp_name']) ? uploadImage($_FILES['officerimages'], "Travels2020") : $existingOfficerImage;

    // **UPDATE EXISTING TOUR BOOKING RECORD** using prepared statements
    $sql = "UPDATE tour_booking SET 
                trip_id = ?, 
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
                ftimage = ?, 
                officer_image = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiisssssssssi", 
        $tripId, $userName, $tourName, $checkIn, $checkOut, 
        $numAdults, $numChildren, $inclusion, $exclusion, 
        $cost, $tourImageURL, $notes, $hotel, $flight, 
        $flightImageURL, $officerImageURL, $id
    );

    if ($stmt->execute()) {

        // **UPDATE VACATION SUMMARY**
        if (isset($_POST['days']) && is_array($_POST['days'])) {
            foreach ($_POST['days'] as $key => $day) {
                $stay = $day['stay'] ?? '';
                $date = $day['date'] ?? '';
                $itinerary = $day['itinerary'] ?? '';
                $imageURL = ""; // Default empty, will fetch existing image if needed

                // Fetch existing image from the database
                $stmtFetch = $conn->prepare("SELECT image FROM vacation_summary WHERE fk_tour_booking = ? AND date = ?");
                $stmtFetch->bind_param("is", $id, $date);
                $stmtFetch->execute();
                $stmtFetch->bind_result($existingDayImage);
                $stmtFetch->fetch();
                $stmtFetch->close();

                // Handle day image upload
                if (!empty($_FILES['days']['name'][$key]['vsImages'])) {
                    $dayFile = $_FILES['days']['tmp_name'][$key]['vsImages'];
                    try {
                        // Upload the image to Cloudinary
                        $upload = (new UploadApi())->upload($dayFile, [
                            "folder" => "Travels2020/days",
                            "resource_type" => "auto"
                        ]);
                        $imageURL = $upload["secure_url"];
                    } catch (Exception $e) {
                        echo json_encode(["success" => false, "message" => $e->getMessage()]);
                        exit;
                    }
                } else {
                    $imageURL = $existingDayImage; // Keep existing image if no new upload
                }

                // **UPDATE VACATION SUMMARY**
                $stmtUpdate = $conn->prepare("UPDATE vacation_summary 
                                            SET stay=?, image=?, itinerary_content=? 
                                            WHERE fk_tour_booking=? AND date=?");
                $stmtUpdate->bind_param("sssis", $stay, $imageURL, $itinerary, $id, $date);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
        }

        echo json_encode(["success" => true, "message" => "Updated Successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error in update: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
