<?php
require_once "../database/db.php";
require_once "../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Configure Cloudinary
    Configuration::instance([
        "cloud_name" => "travels2020",
        "api_key" => "518361389447684",
        "api_secret" => "xXsCep4oJUwi9YuKZDuxDWdyJxc"
    ]);

    // Retrieve form data
    $tripId = $_POST['tripId'] ?? '';
    $userName = $_POST['userName'] ?? '';
    $tourName = $_POST['tourName'] ?? '';
    $checkIn = $_POST['checkIn'] ?? '';
    $checkOut = $_POST['checkOut'] ?? '';
    $numAdults = $_POST['numAdults'] ?? 0;
    $numChildren = $_POST['numChildren'] ?? 0;
    $inclusion = $_POST['inclusion'] ?? '';
    $exclusion = $_POST['exclusion'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $cost = $_POST['cost'] ?? '';
    $hotel = $_POST['hotel'] ?? '';
    $flight = $_POST['flight'] ?? '';

    // Handle image uploads
    // var_dump($folder,"fffffff");
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
                echo "Upload failed: " . $e->getMessage();
                exit;
            }
        }
        return "";
    }

    // $tourImageURL = isset($_FILES['tourImages']) ? uploadImage($_FILES['tourImages'], "Travels2020") : "";
    // $flightImageURL = isset($_FILES['flightimages']) ? uploadImage($_FILES['flightimages'], "Travels2020") : "";

    $tourImageURL = uploadImage($_FILES['tourImages'], "Travels2020");
    $flightImageURL = uploadImage($_FILES['flightimages'], "Travels2020");
    // $officerImageURL = uploadImage($_FILES['officerimages'], "Travels2020");

    // Insert tour details into database
    $sql = "INSERT INTO tour_booking (trip_id, username, tour_name, check_in, check_out, adults, children, inclusion, exclusion, cost, tour_image, notes, hotel, flight, ftimage) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiissssssss", $tripId, $userName, $tourName, $checkIn, $checkOut, $numAdults, $numChildren, $inclusion, $exclusion, $cost, $tourImageURL, $notes, $hotel, $flight, $flightImageURL);

    if ($stmt->execute()) {
        $tourId = $stmt->insert_id; // Get last inserted ID

        foreach ($_POST as $daysKey => $value) {
            // Check if the key starts with "days"
            if (str_starts_with($daysKey, 'days')) {
                // Loop through the value (which is an array)
                foreach ($value as $key => $dv) {
                    // Access the individual fields for each day
                    $stay = $_POST["$daysKey"][$key]['stay'] ?? ''; // Use $_POST to access the nested values
                    $date = $_POST["$daysKey"][$key]['date'] ?? '';
                    $itinerary = $_POST["$daysKey"][$key]['itinerary'] ?? '';
                    $imageURL = "";

                    // Handle day image upload
                    if (!empty($_FILES["$daysKey"]["name"][$key]["vsImages"])) {
                        $dayFile = $_FILES["$daysKey"]["tmp_name"][$key]["vsImages"];
                        try {
                            // Upload the image to Cloudinary
                            $upload = (new UploadApi())->upload($dayFile, [
                                "folder" => "Travels2020/days",
                                "resource_type" => "auto"
                            ]);
                            $imageURL = $upload["secure_url"];
                        } catch (Exception $e) {
                            echo "Upload failed for day image: " . $e->getMessage();
                            exit;
                        }
                    }

                    // Insert into vacation_summary
                    $sqlDay = "INSERT INTO vacation_summary (stay, date, image, itinerary_content, fk_tour_booking) 
                        VALUES (?, ?, ?, ?, ?)";
                    $stmtDay = $conn->prepare($sqlDay);
                    $stmtDay->bind_param("ssssi", $stay, $date, $imageURL, $itinerary, $tourId);
                    $stmtDay->execute();
                    $stmtDay->close();
                }
            }
        }


        echo $tourId; // Return tour ID for redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>