<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travels2020 Customer Details</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <!-- Tour Booking details -->
        <div class="card w-100 w-md-75 mx-auto">
            <div class="card-body">
                <h5 class="card-title text-center">Customer Details</h5>
                <form method="POST" id="customerForm">
                    <div class="row">

                        <!-- Customer Name -->
                        <div class="col-md-6 mb-3">
                        <label for="customerName" class="form-label">Name</label>
                        <input type="text" class="form-control required" id="customerName" name="customerName" placeholder="Enter name">
                        <span class="error-message text-danger" style="display: none;"></span> <!-- Error message -->
                            <!-- <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customerName" name="customerName" 
                                placeholder="Enter Customer Name" required>
                                <span class="error-message text-danger" style="display: none;"></span>  -->
                        </div>

                       <!-- Mobile Number -->
                        <div class="col-md-6 mb-3">
                            <label for="mobileNumber" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control required" id="mobileNumber" name="mobileNumber" 
                                placeholder="Enter Mobile Number" maxlength="10" pattern="[0-9]{10}" required>
                            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                            <span class="error-message text-danger" style="display: none;"></span>
                        </div>


                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control required" id="email" name="email" placeholder="Enter email">
                        <span class="error-message text-danger" style="display: none;"></span>
                            <!-- <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                placeholder="Enter Email Address" required>
                                <span class="error-message text-danger" style="display: none;"></span> -->
                        </div>
                        <!-- Date of Birth (DOB) -->
                        <div class="col-md-6 mb-3">
                            <label for="dob" class="form-label">Date of Birth (DOB)</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>

                        <!-- Anniversary Day -->
                        <div class="col-md-6 mb-3">
                            <label for="anniversary" class="form-label">Anniversary Day</label>
                            <input type="date" class="form-control" id="anniversary" name="anniversary">
                        </div>

                        <!-- Destination Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="tourName" class="form-label">Tour Name /Destination</label>
                            <input type="text" class="form-control mb-2" id="tourName" name="tourName" 
                                placeholder="Enter Destination Name">
                            <select class="form-select" id="destinationSelect" name="destination">
                                <option selected disabled>Select Destination</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Dubai">Dubai</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Bali">Bali</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Vietnam">Vietnam</option>
                            </select>
                        </div>

                        <!-- Number of Passengers -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Passengers</label>
                            <div class="input-group">
                                <label class="input-group-text" for="numAdults">Adults</label>
                                <input type="number" class="form-control" id="numAdults" name="numAdults" value="1" 
                                    min="1" max="100" required>
                                <label class="input-group-text" for="numChildren">Children</label>
                                <input type="number" class="form-control" id="numChildren" name="numChildren" value="0" 
                                    min="0" max="100">
                            </div>
                            <small class="text-muted">* Enter the number of adults and children.</small>
                        </div>

                        <!-- Travel Dates -->
                        <div class="col-md-6 mb-3">
                            <label for="travelFrom" class="form-label">Traveling Dates</label>
                            <div class="input-group">
                                <span class="input-group-text">From</span>
                                <input type="date" class="form-control" id="travelFrom" name="travelFrom" required>
                                <span class="input-group-text">To</span>
                                <input type="date" class="form-control" id="travelTo" name="travelTo" required>
                            </div>
                            <small class="text-muted">* Select the start and end dates of your trip.</small>
                        </div>

                        <!-- Pan Number -->
                        <div class="col-md-6 mb-3">
                            <label for="panNumber" class="form-label">Pan Number</label>
                            <input type="text" class="form-control" id="panNumber" name="panNumber" 
                                placeholder="Enter Pan Number" required>
                        </div>

                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <!-- <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                    </div>
                                </div> -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                       Enter Passport Details
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <!-- Passport Number -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="passportNumber" class="form-label">Passport Number</label>
                                                    <input type="text" class="form-control" id="passportNumber" name="passportNumber" 
                                                        placeholder="Enter Passport Number" required>
                                                </div>

                                                <!-- Passport Issue City -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="passportIssueCity" class="form-label">Passport Issue City</label>
                                                    <input type="text" class="form-control" id="passportIssueCity" name="passportIssueCity" 
                                                        placeholder="Enter Passport Issue City" required>
                                                </div>

                                                <!-- Passport Issue Country -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="passportIssueCountry" class="form-label">Passport Issue Country</label>
                                                        <select class="form-select" id="passportIssueCountry" name="passportIssueCountry" required>
                                                            <option value="" selected disabled>Select Country</option>
                                                            <option value="India">India</option>
                                                            <option value="United States">United States</option>
                                                            <option value="United Kingdom">United Kingdom</option>
                                                            <option value="Canada">Canada</option>
                                                            <option value="Australia">Australia</option>
                                                            <option value="Germany">Germany</option>
                                                            <option value="France">France</option>
                                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                                            <option value="Singapore">Singapore</option>
                                                            <option value="Japan">Japan</option>
                                                            <option value="China">China</option>
                                                            <option value="Brazil">Brazil</option>
                                                            <option value="South Africa">South Africa</option>
                                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                                            <option value="Malaysia">Malaysia</option>
                                                        </select>
                                                    </div>

                                                    <!-- Passport Issue Date -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="passportIssueDate" class="form-label">Passport Issue Date</label>
                                                        <input type="date" class="form-control" id="passportIssueDate" name="passportIssueDate" required>
                                                    </div>

                                                    <!-- Passport Expiry Date -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="passportExpiryDate" class="form-label">Passport Expiry Date</label>
                                                        <input type="date" class="form-control" id="passportExpiryDate" name="passportExpiryDate" required>
                                                    </div>


                                                <!-- Passport Front Image -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="passportFront" class="form-label">Passport Front</label>
                                                    <input type="file" class="form-control" id="passportFront" name="passportFront" 
                                                        accept="image/*" required>
                                                </div>

                                                <!-- Passport Back Image -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="passportBack" class="form-label">Passport Back</label>
                                                    <input type="file" class="form-control" id="passportBack" name="passportBack" 
                                                        accept="image/*" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center mt-3">
            <a href="customerlist.php" class="btn btn-secondary">Back</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/customer.js"></script>
</body>

</html>
