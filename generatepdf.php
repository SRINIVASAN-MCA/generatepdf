<?php
require_once "database/db.php";
require_once "vendor/autoload.php"; // Composer's autoload

use Mpdf\Mpdf;

$tourId = $_GET['id'] ?? '';

if (!empty($tourId) && is_numeric($tourId)) {
    try {
        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 40,
            'margin_bottom' => 40,
            'margin_left' => 15,
            'margin_right' => 15
        ]);

        // Common Header
        $header = '<a href="http://www.travels2020.com"><img src="images/Header.jpg" width="100%" /></a>';

        // Common Footer
        $footer = '<a href="https://www.google.com/maps/place/Travels2020/@12.9901923,80.2539563,17z/data=!3m1!4b1!4m6!3m5!1s0x3a525d799e2de9e9:0xb9c456c8c7ba873d!8m2!3d12.9901923!4d80.2539563!16s%2Fg%2F11bcdznhzc?entry=ttu&g_ep=EgoyMDI1MDIwMy4wIKXMDSoASAFQAw%3D%3D">
        <img src="images/Footer.jpg" width="100%" style="margin-top:20px;" /></a>';

        // Set the header and footer for every page
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        // $mpdf->SetTopMargin(40); 
        // $mpdf->Ln(25);

        // Fetch Tour Data
        $sql = "SELECT tb.*, vs.* FROM tour_booking tb 
                LEFT JOIN vacation_summary vs ON vs.fk_tour_booking = tb.id 
                WHERE tb.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tourId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("No records found.");
        }

        $checkInDate = new DateTime($row["check_in"]);
        $checkOutDate = new DateTime($row["check_out"]);
        $duration = $checkInDate->diff($checkOutDate)->days + 1;
        $nights = $duration - 1;

        $whatsappLink = "https://wa.me/919445552020?text=" . urlencode("Hello, I would like to know more information about our tour " . $row['tour_name'] . " trip Id: " . $row['trip_id']);
        $razorpayLink = "https://pages.razorpay.com/travels2020";
        // $whatsappIcon = "<img src='images/whatsApp_icon.png' width='20' height='20' style='vertical-align: middle;' alt='WhatsApp Icon'/>";
        // HTML Content
        $html = "<style>                    
                body { font-family: Arial, sans-serif; font-size: 12pt; }
                h1 { text-align: center; font-size: 20pt; margin-bottom: 5px; font-weight: bold; color:rgb(11, 66, 105); }
                span { text-align: center; font-size: 14pt; margin-bottom: 10px; font-weight: bold; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; page-break-inside: avoid; }
                th, td { padding: 10px; text-align: left; border: 1px solid #000; }
                .header { background-color: #A4B5C1; color: #fff; font-weight: bold; }
                .theaders { background-color:rgb(237, 241, 242); color: #fff; font-weight: bold; }
                .box { border: 1px solid #000; padding: 10px; margin-top: 10px; text-align: center; 
                    border-radius: 10px; background-color: #f5f5f5; page-break-inside: avoid; }
                .info-container { display: flex; justify-content: space-between; width: 100%; margin-top: 10px; }
                p { margin: 10px 0; }
                h2 { page-break-inside: avoid; }
            </style>
            ";
        $html .= "<table width='100%' style='margin-top: 10px; border: none;'>
            <tr>
                <td style='font-weight: bold; font-size: 15pt; text-align: left; border: none;'>Hey, " . htmlspecialchars($row['username']) . "</td>
                <td style='text-align: right; white-space: nowrap; border: none;'><strong>Generated On:</strong> " . date('d-m-Y') . "</td>
            </tr>
          </table>";

        $html .= "<h1>" . strtoupper(htmlspecialchars($row['tour_name'])) . "<br/><span> ( {$nights} Nights / {$duration} Days)</span></h1>";
        $html .= "<img src='" . htmlspecialchars($row['tour_image']) . "' width='100%' height='50%' />";

        $html .= "<div class='box'>
        <table width='100%'>
            <tr class='theaders'>
                <td><strong>TRIP ID:</strong></td>
                <td>{$row['trip_id']}</td>
            </tr>
            <tr>
                <td><strong>NO. OF PASSENGER:</strong></td>
                <td>{$row['adults']} adults, {$row['children']} " . ($row['children'] == 1 ? 'child' : 'children') . "</td>
            </tr>
            <tr class='theaders'>
                <td><strong>DEPARTURE:</strong></td>
                <td>{$checkInDate->format('d-m-Y')}</td>
            </tr>
            <tr>
                <td><strong>ARRIVAL:</strong></td>
                <td>{$checkOutDate->format('d-m-Y')}</td>
            </tr>
        </table>
        </div>";

        $html .= "<pagebreak />";



        // Fetch Vacation Summary
        $stmtVacation = $conn->prepare("SELECT * FROM vacation_summary WHERE fk_tour_booking = ?");
        $stmtVacation->bind_param("i", $tourId);
        $stmtVacation->execute();
        $vacationResults = $stmtVacation->get_result()->fetch_all(MYSQLI_ASSOC);
        $dayCount = 1;
        // $date = date('d-m-Y', strtotime($vacationRow['date']));

        foreach ($vacationResults as $vacationRow) {
            $formattedDate = date('d-m-Y', strtotime($vacationRow['date'])); // Format date
            $html .= "<div class='box' style='border: 1px solid #ddd; padding: 15px; border-radius: 8px; width: 100%;'>
                        <table>
                            <tr class='header'><td> <h3>Day {$dayCount}</h3></td><td>{$vacationRow['stay']}</td><td>{$formattedDate}</td></tr>
                            <tr> <td><img src='" . htmlspecialchars($vacationRow['image']) . "' style='width: 100%; max-width: 200px; border-radius: 5px;' /></td><td colspan='2'>{$vacationRow['itinerary_content']}</td></tr>
                        </table>
                      </div>";
            $dayCount++;
        }

        // $html .= "<pagebreak />";

        /// Cost Section
        $html .= "<h2 style='text-align: left; background-color: yellow; padding: 10px; margin: 10px 0;'>LAND PACKAGE COST: " . htmlspecialchars($row['cost']) . " per person</h2>";

        if (!empty($row['hotel']) && is_string($row['hotel'])) {
            // $html .= "<h2>Hotel Details:</h2><p>" . nl2br(htmlspecialchars($row['hotel'], ENT_QUOTES)) . "</p>";
            $html .= "<h2>Hotel Details:</h2>" . $row['hotel'];
        }


        if (!empty($row['flight']) && is_string($row['flight'])) {
            $html .= "<h2>Flight Details:</h2>" . $row['flight'];
            // $html .= "<h1>" . strtoupper(htmlspecialchars($row['flight'])) . "</h1>";

            if (!empty($row['ftimage']) && filter_var($row['ftimage'], FILTER_VALIDATE_URL)) {
                $html .= "<img src='" . htmlspecialchars($row['ftimage']) . "' width='100%' height='50%' />";
            }
        }


        // if (!empty($row['flight']) && is_string($row['flight'])) {
        //     $html .= "<h1>" . strtoupper(htmlspecialchars($row['flight'])) . "</h1>";
        //     $html .= "<img src='" . htmlspecialchars($row['ftimage']) . "' width='100%' height='50%' />";
        //     $html .= "<h2>flight Details:</h2><p>" . nl2br(htmlspecialchars($row['flight'])) . "</p>";
        // }

        if (!empty($row['inclusion']) && !empty($row['exclusion'])) {
            $html .= "<h2>Inclusion:</h2>" . $row['inclusion'];
            $html .= "<h2>Exclusion:</h2>" . $row['exclusion'];
        }

        // $html .= "<pagebreak />";

        if (!empty($row['notes']) && is_string($row['notes'])) {
            $html .= "<h2>Important Notes:</h2>" . $row['notes'];
        }

        // Final Contact Section
        $html .= "<div class='box'>
    <table width='100%' border='0' style='border-collapse: collapse;'>
        <tr>
            <td width='15%' align='left' style='border: none;'>
                <img src='images/pothy.png' width='100' height='100' style='border-radius: 50%;' alt='Avatar'>
            </td>
            <td width='55%' align='center' style='border: none;'>
                <p>Your trip is planned by travels2020 <br />Anbu Pothy <br/><span>1500+ trips planned</span></p>
                <p>For any queries or requests?</p>
            </td>
            <td width='30%' align='right' style='border: none;'>
                <p>Say Hello<br />
                    <a href='{$whatsappLink}' target='_blank' style='display: inline-block; text-decoration: none; font-weight: bold; color: green;'>
                        <img src='images/WhatsApp_icon.png' width='20' height='20' style='vertical-align: middle;' /> WhatsApp
                    </a>
                </p>
            </td>
        </tr>
    </table>
</div>";

        // Payment Section
        $html .= "<div class='box'>
            <table width='100%' border='0' style='border-collapse: collapse;'>
                <tr>
                    <td width='70%' align='left' style='border: none;'>
                        <h2>Bank Details</h2>
                        <p><strong>Bank:</strong> HDFC</p>
                        <p><strong>Account:</strong> Arctictern Consultancy Services Pvt Ltd</p>
                        <p><strong>Account No.:</strong> 50200044220791</p>
                        <p><strong>Branch:</strong> Adambakkam</p>
                        <p><strong>IFSC:</strong> HDFC0001858</p>
                    </td>
                    <td width='30%' align='right' style='border: none;'>
                    <p>Click here to <br/> Pay Online <br/>
                        <a href='{$razorpayLink}' 
                           style='display: block;  color: blue; text-align: center;  text-decoration: none; font-weight: bold;'>
                          <img src='images/Razorpayimage.png' width='80' height='60' />  
                        </a>
                        </p>
                    </td>
                </tr>
            </table>
        </div>";


        // Footer Image
        // $html .= '<img src="images/Footer.jpg" width="100%" style="margin-top:20px;" />';

        // Generate PDF
        $mpdf->WriteHTML($html);
        // $mpdf->Output($row['tour_name'] . "pdf", "D");
        $mpdf->Output($row['username'] . ($row['tour_name']) . ".pdf", "D");


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid Tour ID.";
}

$conn->close();
