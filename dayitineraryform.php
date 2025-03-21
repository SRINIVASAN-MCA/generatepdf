<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travels2020</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 5 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="css/itinerary.css"> -->


</head>

<body>
    <div class="container mt-4">

        <!-- Tour Booking details -->
        <div class="card w-100 w-md-75 mx-auto">
            <div class="card-body">
                <h5 class="card-title text-center">Tour Booking Form</h5>
                <form method="POST" id="tourForm">
                    <div class="row">
                        <input type="hidden" class="form-control" disabled id="id" name="id">
                        <!-- Trip ID -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="tripId" class="form-label">Trip ID</label>
                            <!-- <input type="hidden" id="tripId" name="tripId"> -->
                            <input type="text" class="form-control" disabled id="tripId" name="tripId"
                                placeholder="Enter Trip ID">
                        </div>

                        <!-- User Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="userName" class="form-label"> UserName </label>
                            <input type="text" class="form-control" id="userName" name="userName"
                                placeholder="Enter User Name">
                        </div>


                        <!-- Tour Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="tourName" class="form-label">Tour Name / Place</label>
                            <input type="text" class="form-control" id="tourName" name="tourName"
                                placeholder="Enter Tour Name or Place">
                        </div>
                        <!-- Image Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Image (Max: 300px * 200px )</label>
                            <input type="file" class="form-control image-upload" id="timages" name="timages"
                                accept="image/*">
                            <div id="imagehide">
                                <img id="tourImagePreview" src="" alt="Tour Image" style="width: 80px; height: 80px;">
                            </div>
                        </div>


                        <!-- Check-in Date -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="checkIn" class="form-label">Check-in Date</label>
                            <input type="date" class="form-control" id="checkIn" name="checkIn">
                        </div>

                        <!-- Check-out Date -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="checkOut" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="checkOut" name="checkOut">
                        </div>

                        <!-- Number of Passengers -->
                        <div class="col-12 mt-3">
                            <h5>Number of Passengers</h5>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Adults:</span>
                                <button class="btn btn-outline-secondary" type="button" id="decreaseAdults">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control" id="numAdults" name="numAdults" value="1"
                                    min="1">
                                <button class="btn btn-outline-secondary" type="button" id="increaseAdults">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Children:</span>
                                <button class="btn btn-outline-secondary" type="button" id="decreaseChildren">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control" id="numChildren" name="numChildren" value="0"
                                    min="0">
                                <button class="btn btn-outline-secondary" type="button" id="increaseChildren">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- cost -->
                        <!-- <div class="col-12 col-md-6 mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <input type="number" class="form-control" id="cost" name="cost" value="0"
                                placeholder="Enter cost">
                        </div> -->

                        <!-- cost Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="cost" class="form-label">Landpackage Cost </label>
                            <textarea class="form-control" id="cost" name="cost" placeholder="Enter LandPackage cost"
                                style="height: 100px"></textarea>
                        </div>

                        <!-- Landpackage Cost -->
                        <!-- <div class="col-12 col-md-6 mb-3">
                            <label for="packagecost" class="form-label">Landpackage Cost</label>
                            <textarea class="form-control" id="packagecost" name="packagecost"
                                placeholder="Enter important packagecost" style="height: 100px"></textarea>
                        </div> -->

                        <div class="col-12 col-md-6 mb-3">
                            <label for="inclusion" class="form-label">Inclusion </label>
                            <textarea class="form-control" id="inclusion" name="inclusion" placeholder="Enter Inlusion"
                                style="height: 100px"></textarea>
                        </div>

                        <!-- Exclusion Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="exclusion" class="form-label">Exclusion</label>
                            <textarea class="form-control" id="exclusion" name="exclusion" placeholder="Enter Exclusion"
                                style="height: 100px"></textarea>

                        </div>


                        <!-- Exclusion Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="notes" class="form-label">Important Notes(Options)</label>
                            <textarea class="form-control" id="notes" name="notes" placeholder="Enter important  notes"
                                style="height: 100px"></textarea>
                        </div>

                        <!-- Hotel Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="hotel" class="form-label">Hotel</label>
                            <textarea class="form-control" id="hotel" name="hotel" placeholder="Enter hotel Details"
                                style="height: 100px"></textarea>
                        </div>
                        <!-- Exclusion Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="flight" class="form-label">flight</label>
                            <textarea class="form-control" id="flight" name="flight" placeholder="Enter flight Details"
                                style="height: 100px"></textarea>
                        </div>

                        <!-- Image Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Flight Image (Max: 300px * 200px )</label>
                            <input type="file" class="form-control image-upload" id="flightimages" name="flightimages"
                                accept="image/*">
                            <div id="imagehideflight" class="imagehideflight">
                                <img src="" class="img-preview mt-2" id="tourImagePreviewflight"
                                    style="width: 80px; height: 80px">
                            </div>

                        </div>

                        <!-- Image Name -->
                        <!-- <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">officer Image (Max: 300px * 200px )</label>
                            <input type="file" class="form-control image-upload" id="officerimages" name="officerimages"
                                accept="image/*">
                            <img src="" class="img-preview mt-2" id="tourImagePreviewofficer" style="width: 200px; height: 200px" >
                        </div> -->
                    </div>
                </form>
            </div>
        </div>

        <!-- Vacation Summary -->
        <div class="card w-100 w-md-75 mx-auto mt-4 mb-3">
            <div class="card-body">
                <h5 class="card-title text-center">Vacation Summary</h5>
                <form method="post" id="vacationForm" enctype="multipart/form-data">
                    <div id="daysContainer">
                        <!-- Day 1 Form -->
                        <div class="day-form border rounded p-3 mb-3" id="day1">
                            <h6 class="mb-3">Day 1</h6>
                            <div class="row">
                                <!-- Stay -->
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay">
                                        <label>Stay</label>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="date[]">
                                        <label>Date</label>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Day 1 Image (Max: 300px * 200px )</label>
                                    <input type="file" class="form-control image-upload" name="images[]"
                                        accept="image/*">
                                    <!-- <img src="" class="img-preview mt-2" style="display:none;"> -->
                                </div>

                                <!-- Itinerary Content -->
                                <!-- <div class="col-12 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="itinerary[]"
                                            placeholder="Enter itinerary details" style="height: 100px"></textarea>
                                        <label>Itinerary Content</label>
                                    </div>
                                </div> -->
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <textarea id="itinerary" class="form-control" name="itinerary[]"
                                            placeholder="Enter itinerary details" style="height: 100px"
                                            required></textarea>
                                        <!-- <label for="itinerary">Itinerary Content</label> -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label>Select PDF Type:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pdfType" id="singlePdf"
                                value="Single Pdf" checked>
                            <label class="form-check-label" for="singlePdf">Single PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pdfType" id="multiplePdf"
                                value="Multiple Pdf">
                            <label class="form-check-label" for="multiplePdf">Multiple PDF</label>
                        </div>
                    </div>
                    <div class="text-center">
                        <!-- Main action buttons -->
                           <a href="index.php" class="btn btn-secondary">Back</a>

                        <button type="button" id="submitButton" class="btn btn-primary">Save</button>
                        <button type="button" id="updateButton" class="btn btn-primary">Update</button>
                      
                        <!-- PDF generation buttons -->
                        <!-- <div class="mt-3">
                            <button type="button" id="submitButtonsingle" class="btn btn-danger">Single PDF</button>
                            <button type="button" id="submitButtonmultiple" class="btn btn-primary">Multiple
                                PDF</button>
                        </div> -->
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/decoupled-document/ckeditor.js"></script> -->
    <!-- <script>
        ClassicEditor.create(document.querySelector('#hotel'))
            .catch(error => console.error(error));
    </script> -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- <script src="js/itinerary.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="js/index.js"></script>

    <script src="js/itineraryform.js"></script>
</body>

</html>