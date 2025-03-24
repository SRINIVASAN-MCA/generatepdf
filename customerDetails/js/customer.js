$(document).ready(function () {
    function updatePassengerAccordion() {
        let numAdults = parseInt($('#numAdults').val()) || 0;
        let numChildren = parseInt($('#numChildren').val()) || 0;
        let passengerAccordion = $('#passengerAccordion');

        passengerAccordion.empty(); // Clear existing entries

        let adultCount = 1;
        let childCount = 1;

        for (let i = 1; i <= numAdults + numChildren; i++) {
            let passengerType = i <= numAdults ? 'Adult' : 'Child';
            let passengerIndex = i <= numAdults ? adultCount++ : childCount++;

            let passengerHtml = `
                <div class="accordion-item">
                  <form class="passenger-details">
                    <h2 class="accordion-header" id="heading${i}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${i}" aria-expanded="false" aria-controls="collapse${i}">
                            ${passengerType} ${passengerIndex} Details
                        </button>
                    </h2>
                    <div id="collapse${i}" class="accordion-collapse collapse" aria-labelledby="heading${i}" data-bs-parent="#passengerAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control required" name="passengerName" placeholder="Enter name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control required" name="mobileNumber" placeholder="Enter Mobile Number" maxlength="10" pattern="[0-9]{10}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control required" name="email" placeholder="Enter email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control required" name="dob">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Anniversary</label>
                                    <input type="date" class="form-control required" name="anniversary">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" name="panNumber" placeholder="Enter PAN Number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Number</label>
                                    <input type="text" class="form-control required" name="passportNumber" placeholder="Enter Passport Number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue City</label>
                                    <input type="text" class="form-control" name="passportIssueCity" placeholder="Enter Passport Issue City">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue Country</label>
                                    <select class="form-select" name="passportIssueCountry">
                                        <option value="" selected disabled>Select Country</option>
                                        <option value="India">India</option>
                                        <option value="United States">United States</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue Date</label>
                                    <input type="date" class="form-control" name="passportIssueDate">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Expiry Date</label>
                                    <input type="date" class="form-control" name="passportExpiryDate">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Front</label>
                                    <input type="file" class="form-control" name="passportFront" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Back</label>
                                    <input type="file" class="form-control" name="passportBack" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>`;
            passengerAccordion.append(passengerHtml);
        }
    }

    updatePassengerAccordion();

    $('#numAdults, #numChildren').on('input', updatePassengerAccordion);

    $("#submitButton").click(function (event) {
        event.preventDefault();
    
        let form = $("#customerForm")[0];
        let formData = new FormData(form);
        let passengers = $(".passenger-details");
    
        let passengerDataArray = [];
    
        passengers.each(function () {
            let passengerData = {};
    
            $(this).find("input, select").each(function () {
                let fieldName = $(this).attr("name");
                let fieldValue = $(this).val();
    
                if (!fieldName) return; // Skip elements without a name
                fieldName = fieldName.replace(/\[\]$/, ""); // Remove array brackets if any
    
                if ($(this).attr("type") === "file") {
                    if (this.files.length > 0) {
                        passengerData[fieldName] = this.files[0]; // Store file in the object
                        formData.append(`passengerFiles[]`, this.files[0]); // Append files separately
                    }
                } else {
                    passengerData[fieldName] = fieldValue ? fieldValue.trim() : "";
                }
            });
    
            passengerDataArray.push(passengerData);
        });
    
        console.log("Passenger Data Being Sent:", passengerDataArray);
    
        // Convert passenger array to JSON and append it
        formData.append("passengers", JSON.stringify(passengerDataArray));
    
        $.ajax({
            url: "controller/customerController.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $("#submitButton").prop("disabled", true).text("Saving...");
            },
            success: function (response) {
                console.log("Server Response:", response);
                alert(response.success ? "Customer details saved successfully!" : "Error: " + response.message);
                if (response.success) $("#customerForm")[0].reset();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
            },
            complete: function () {
                $("#submitButton").prop("disabled", false).text("Save");
            }
        });
    });
    
    });
  
