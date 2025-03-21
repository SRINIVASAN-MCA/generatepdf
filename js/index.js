var hotelvalue;
var flightvalue;
var inclusionvalue;
var exclusionvalue;
var notevalue;
var costvalue;
// var packagecostvalue;
var itineraryEditor;

document.addEventListener("DOMContentLoaded", function () {
    function initializeEditor(selector, callback) {
        const element = document.querySelector(selector);
        if (element) {
            ClassicEditor.create(element)
                .then(editor => {
                    callback(editor);
                    editor.model.document.on('change:data', () => {
                        console.log(`${selector} content changed:`, editor.getData());
                    });
                    console.log(`${selector} editor initialized`);
                })
                .catch(error => console.error(`Error initializing ${selector}:`, error));
        } else {
            console.error(`Element ${selector} not found in the DOM.`);
        }
    }

    initializeEditor('#itinerary', editor => (itineraryEditor = editor));
});


document.addEventListener("DOMContentLoaded", function () {
    function initializeEditor(selector, callback) {
        const element = document.querySelector(selector);
        if (element) {
            ClassicEditor.create(element)
                .then(editor => {
                    callback(editor);
                    editor.model.document.on('change:data', () => {
                        handleEditorChange(editor.getData());
                    });
                    console.log(`${selector} editor initialized`);
                })
                .catch(error => console.error(`Error initializing ${selector}:`, error));
        } else {
            console.error(`Element ${selector} not found in the DOM.`);
        }
    }

    initializeEditor('#hotel', editor => (hotelvalue = editor));
    initializeEditor('#flight', editor => (flightvalue = editor));
    initializeEditor('#inclusion', editor => (inclusionvalue = editor));
    initializeEditor('#exclusion', editor => (exclusionvalue = editor));
    initializeEditor('#notes', editor => (notevalue = editor));
    initializeEditor('#cost', editor => (costvalue = editor));
    // initializeEditor('#packagecost', editor => (packagecostvalue = editor));

    document.getElementById("submitButton")?.addEventListener("click", function () {
        handleSubmit(this);
    });
});

// Function to handle editor change
function handleEditorChange(data) {
    console.log("Editor content changed:", data);
}


function handleSubmit(button) {
    button.innerHTML = `
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;

    let daysContainer = document.getElementById("daysContainer");
    let dayForms = daysContainer.getElementsByClassName("day-form");
    let errorMessages = [];
    let formData = new FormData();

    // Get selected PDF type from radio buttons
    let pdfType = document.querySelector("input[name='pdfType']:checked").value;

    for (let i = 0; i < dayForms.length; i++) {
        let dayForm = dayForms[i];
        let stay = dayForm.querySelector("input[name='stay[]']").value.trim();
        let date = dayForm.querySelector("input[name='date[]']").value.trim();
        let itinerary = dayForm.querySelector("textarea[name='itinerary[]']").value.trim();
        let vsImageInput = dayForm.querySelector("input[name='images[]']");
        let vsImages = vsImageInput.files.length > 0 ? vsImageInput.files[0] : null;

        if (!stay || !date || !itinerary) {
            errorMessages.push(`Please fill all fields for Day ${i + 1}`);
        } else {
            formData.append(`days[${i}][stay]`, stay);
            formData.append(`days[${i}][date]`, date);
            formData.append(`days[${i}][itinerary]`, itinerary);
            if (vsImages) {
                formData.append(`days[${i}][vsImages]`, vsImages);
            }
        }
    }

    let tripId = document.getElementById("tripId").value.trim();
    let userName = document.getElementById("userName").value.trim();
    let tourName = document.getElementById("tourName").value.trim();
    let checkIn = document.getElementById("checkIn").value.trim();
    let checkOut = document.getElementById("checkOut").value.trim();
    let numAdults = document.getElementById("numAdults").value.trim();
    let numChildren = document.getElementById("numChildren").value.trim();
    // let inclusion = document.getElementById("inclusion").value.trim();
    // let exclusion = document.getElementById("exclusion").value.trim();
    // let notes = document.getElementById("notes").value.trim();
    // let perCost = document.getElementById("cost").value.trim();
    // let hotel = document.getElementById("hotel").value.trim();
    // let flight = document.getElementById("flight").value.trim();

    let tourImages = document.getElementById("timages").files[0] || null;
    let flightImages = document.getElementById("flightimages").files[0] || null;

    if (tourImages) formData.append("tourImages", tourImages);
    if (flightImages) formData.append("flightimages", flightImages);

    if (!tripId || !tourName || !checkIn || !checkOut || !numAdults || !numChildren) {
        errorMessages.push("Please fill in all required trip details.");
    }

    if (errorMessages.length > 0) {
        button.innerHTML = `Save`;
        alert(errorMessages.join("\n"));
        return;
    }

    // Append general form data
    formData.append("tripId", tripId);
    formData.append("userName", userName);
    formData.append("tourName", tourName);
    formData.append("checkIn", checkIn);
    formData.append("checkOut", checkOut);
    formData.append("numAdults", numAdults);
    formData.append("numChildren", numChildren);
    formData.append("inclusion", inclusionvalue.getData());
    formData.append("exclusion", exclusionvalue.getData());
    formData.append("notes", notevalue.getData());
    formData.append("cost", costvalue.getData());
    formData.append("hotel", hotelvalue.getData());
    formData.append("flight", flightvalue.getData());

    // formData.append("inclusion", inclusion);
    // formData.append("exclusion", exclusion);
    // formData.append("notes", notes);
    // formData.append("perCost", perCost);
    // formData.append("flight", flight);

    fetch("controller/itineraryController.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            if (data) {
                if (pdfType === 'Single Pdf') {
                    window.location.href = 'singlepdf.php?id=' + data;
                } else if (pdfType === 'Multiple Pdf') {
                    window.location.href = 'generatepdf.php?id=' + data;
                } else {
                    alert("Data saved successfully!");
                }
            } else {
                alert("Error processing request. Please try again.");
            }
            button.innerHTML = `Save`;
        })
        .catch(error => {
            button.innerHTML = `Save`;
            console.error("Error:", error);
            alert("An error occurred while submitting the form.");
        });
}



$(document).ready(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('id');
    // alert(myParam
    if (myParam) {
        editTour(myParam);
        $('#submitButton').hide();
        $('#imagehide').show();
        $('#imagehideflight').show();

    } else {
        $('#submitButton').show();
        $('#updateButton').hide();
        $('#imagehide').hide();
        $('#imagehideflight').hide();
    }

    let $daysbetween = [];

    document.getElementById("checkIn").addEventListener("change", calculateDays);
    document.getElementById("checkOut").addEventListener("change", calculateDays);

    let formData = new FormData();
    formData.append("autoTripId", 0);

    fetch("controller/tripIdAutoGenerateController.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            // console.log("-----", data);
            $('#tripId').val(data);
        })
        .catch(error => {
            console.error("Error:", error);
        });

    // Increment Adult Count
    document.getElementById("increaseAdults").addEventListener("click", function () {
        let numAdults = document.getElementById("numAdults");
        numAdults.value = parseInt(numAdults.value) + 1;
    });

    // Decrement Adult Count
    document.getElementById("decreaseAdults").addEventListener("click", function () {
        let numAdults = document.getElementById("numAdults");
        if (parseInt(numAdults.value) > 1) {
            numAdults.value = parseInt(numAdults.value) - 1;
        }
    });

    // Increment Children Count
    document.getElementById("increaseChildren").addEventListener("click", function () {
        let numChildren = document.getElementById("numChildren");
        numChildren.value = parseInt(numChildren.value) + 1;
    });

    // Decrement Children Count
    document.getElementById("decreaseChildren").addEventListener("click", function () {
        let numChildren = document.getElementById("numChildren");
        if (parseInt(numChildren.value) > 0) {
            numChildren.value = parseInt(numChildren.value) - 1;
        }
    });

});



// Edit Tour Function
function editTour(tripId) {
    // console.log("Fetching details for Trip ID:", tripId);

    // Show the form
    document.getElementById("tourForm");

    fetch(`controller/getTripDetails.php?id=${tripId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const trip = data.trip;
                // console.log("hotel", trip.hotel);


                let daysContainer = document.getElementById("daysContainer");

                // Clear previous day forms
                daysContainer.innerHTML = "";

                // Populate form fields
                document.getElementById("id").value = trip.id; // Assuming trip_id is disabled
                document.getElementById("tripId").value = trip.trip_id; // Assuming trip_id is disabled
                document.getElementById("userName").value = trip.username;
                document.getElementById("tourName").value = trip.tour_name;
                document.getElementById("checkIn").value = trip.check_in;
                document.getElementById("checkOut").value = trip.check_out;
                document.getElementById("numAdults").value = trip.adults;
                document.getElementById("numChildren").value = trip.children;
                inclusionvalue.setData(trip.inclusion);
                exclusionvalue.setData(trip.exclusion);
                notevalue.setData(trip.notes);
                costvalue.setData(trip.cost);
                hotelvalue.setData(trip.hotel);
                flightvalue.setData(trip.flight);

                // document.getElementById("cost").value = trip.cost;
                // document.getElementById("inclusion").value = trip.inclusion;
                // document.getElementById("exclusion").value = trip.exclusion;
                // document.getElementById("notes").value = trip.notes;
                // document.getElementById("hotel").value = trip.hotel;
                // document.getElementById("flight").value = trip.flight;

                // Show image preview if available
                if (trip.tour_image) {
                    document.getElementById("tourImagePreview").src = trip.tour_image;
                    document.getElementById("tourImagePreview");
                }
                if (trip.tour_image) {
                    document.getElementById("tourImagePreviewflight").src = trip.ftimage;
                    document.getElementById("tourImagePreviewflight");
                }
                // if (trip.tour_image) {
                //     document.getElementById("tourImagePreview").src = trip.tour_image;
                //     document.getElementById("tourImagePreview");
                // }

                // Store trip ID for updating
                // document.getElementById("updateTripBtn").setAttribute("data-trip-id", tripId);

                if (data && data.vacation_summary && data.vacation_summary.length > 0) {
                    var vsummary = data.vacation_summary;

                    for (let i = 0; i < vsummary.length; i++) {
                        addDay(vsummary[i].stay, vsummary[i].date, vsummary[i].image, vsummary[i].itinerary_content, i + 1);
                    }
                }
            } else {
                alert("Error fetching trip details: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error fetching trip details:", error);
        });
}

// Update Trip Function
$("#updateButton").on("click", function () {
    let daysContainer = document.getElementById("daysContainer");
    let dayForms = daysContainer.getElementsByClassName("update-day-form");
    let errorMessages = [];
    let formData = new FormData();

    // Get selected PDF type from radio buttons
    let pdfType = document.querySelector("input[name='pdfType']:checked").value;

    for (let i = 0; i < dayForms.length; i++) {
        let dayForm = dayForms[i];
        let stay = dayForm.querySelector("input[name='stay[]']").value.trim();
        let date = dayForm.querySelector("input[name='date[]']").value.trim();
        let itinerary = dayForm.querySelector("textarea[name='itinerary[]']").value.trim();
        let vsImageInput = dayForm.querySelector("input[name='images[]']");
        let vsImages = vsImageInput.files.length > 0 ? vsImageInput.files[0] : null;

        if (!stay || !date || !itinerary) {
            errorMessages.push(`Please fill all fields for Day ${i + 1}`);
        } else {
            formData.append(`days[${i}][stay]`, stay);
            formData.append(`days[${i}][date]`, date);
            formData.append(`days[${i}][itinerary]`, itinerary);
            if (vsImages) {
                formData.append(`days[${i}][vsImages]`, vsImages);
            }
        }
    }

    let id = document.getElementById("id").value.trim();
    let tripId = document.getElementById("tripId").value.trim();
    let userName = document.getElementById("userName").value.trim();
    let tourName = document.getElementById("tourName").value.trim();
    let checkIn = document.getElementById("checkIn").value.trim();
    let checkOut = document.getElementById("checkOut").value.trim();
    let numAdults = document.getElementById("numAdults").value.trim();
    let numChildren = document.getElementById("numChildren").value.trim();
    let inclusion = inclusionvalue.getData();
    let exclusion = exclusionvalue.getData();
    let notes = notevalue.getData();
    let cost = costvalue.getData();

    // let inclusion = document.getElementById("inclusion").value.trim();
    // let exclusion = document.getElementById("exclusion").value.trim();
    // let notes = document.getElementById("notes").value.trim();
    // let perCost = document.getElementById("cost").value.trim();
    let hotel = hotelvalue.getData();
    let flight = flightvalue.getData();
    // let flight = document.getElementById("flight").value.trim();

    let tourImages = document.getElementById("timages").files[0] || null;
    let flightImages = document.getElementById("flightimages").files[0] || null;

    if (tourImages) formData.append("tourImages", tourImages);
    if (flightImages) formData.append("flightimages", flightImages);

    if (!tripId || !tourName || !checkIn || !checkOut || !numAdults || !numChildren) {
        errorMessages.push("Please fill in all required trip details.");
    }

    if (errorMessages.length > 0) {
        button.innerHTML = `Save`;
        alert(errorMessages.join("\n"));
        return;
    }

    // Append general form data
    formData.append("tripId", tripId);
    formData.append("userName", userName);
    formData.append("tourName", tourName);
    formData.append("checkIn", checkIn);
    formData.append("checkOut", checkOut);
    formData.append("numAdults", numAdults);
    formData.append("numChildren", numChildren);
    formData.append("inclusion", inclusion);
    formData.append("exclusion", exclusion);
    formData.append("notes", notes);
    formData.append("cost", cost);
    formData.append("hotel", hotel);
    formData.append("flight", flight);

    fetch(`controller/updateController.php?id=${id}`, {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            // console.log("data", data);
            if (pdfType === 'Single Pdf') {
                window.location.href = 'singlepdf.php?id=' + id;
            } else if (pdfType === 'Multiple Pdf') {
                window.location.href = 'generatepdf.php?id=' + id;
            } else {
                alert("Data saved successfully!");
            }

            // if (data.success) {
            //     alert("Trip updated successfully!");
            //     location.reload(); // Reload the page to reflect changes
            // } else {
            //     console.error("Error updating trip: " + data ? data.message : '');
            // }
        })
        .catch(error => {
            console.error("Error updating trip:", error);
            // alert("An error occurred while updating the trip.");
        });
});

function calculateDays() {
    let checkIn = document.getElementById("checkIn").value;
    let checkOut = document.getElementById("checkOut").value;
    let daysContainer = document.getElementById("daysContainer");

    daysContainer.innerHTML = "";

    if (!checkIn || !checkOut) return;

    let startDate = new Date(checkIn);
    let endDate = new Date(checkOut);

    if (startDate >= endDate) {
        alert("Check-out date must be after check-in date!");
        return;
    }

    $daysbetween = []; // Reset the global array

    while (startDate <= endDate) {
        let formattedDate = new Date(startDate).toISOString().split("T")[0]; // Format YYYY-MM-DD
        $daysbetween.push(formattedDate);
        startDate.setDate(startDate.getDate() + 1);
    }

    // Generate and add day forms
    $daysbetween.forEach((date, index) => {
        let dayCount = index + 1;
        let newDay = document.createElement("div");
        newDay.classList.add("day-form", "border", "rounded", "p-3", "mb-3");
        newDay.id = `day${dayCount}`;
        newDay.innerHTML = `
            <h6 class="mb-3">Day ${dayCount}</h6>
            <div class="row">
                <!-- Stay -->
                <div class="col-12 col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay" required>
                        <label>Stay</label>
                    </div>
                </div>

                <!-- Date -->
                <div class="col-12 col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" name="date[]" value="${date}" required>
                        <label>Date</label>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="col-12 col-md-6 mb-3">
                    <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
                    <input type="file" class="form-control image-upload" name="images[]" accept="image/*" required>
                </div>

                <!-- Itinerary Content -->
                <div class="col-12 col-md-6 mb-3">
                    <div class="form-floating">
                        <textarea id="itinerary" class="form-control" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required></textarea>
                       
                    </div>
                </div>
            </div>
        `;

        daysContainer.appendChild(newDay);
    });
}

// function addDay(stay, date, image, itinerary_content, dayCount) {

//     let newDay = document.createElement("div");
//     newDay.classList.add("day-container", "update-day-form"); 

//     newDay.innerHTML = `
//         <h6 class="mb-3">Day ${dayCount}</h6>
//         <div class="row">
//             <!-- Stay -->
//             <div class="col-12 col-md-6 mb-3">
//                 <div class="form-floating">
//                     <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay" value="${stay}" required>
//                     <label>Stay</label>
//                 </div>
//             </div>

//             <!-- Date -->
//             <div class="col-12 col-md-6 mb-3">
//                 <div class="form-floating">
//                     <input type="date" class="form-control" name="date[]" value="${date}" required>
//                     <label>Date</label>
//                 </div>
//             </div>

//             <!-- Image Upload -->
//             <div class="col-12 col-md-6 mb-3">
//                 <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
//                 <input type="file" class="form-control image-upload" name="images[]" accept="image/*" required>
//                  <img src="${image}" class="img-preview mt-2" id=""
//                                 style="width: 80px; height: 80px">
//             </div>

//             <!-- Itinerary Content -->
//             <div class="col-12 col-md-6 mb-3">
//                 <div class="form-floating">
//                     <textarea id="itinerary" class="form-control" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required>${itinerary_content}</textarea>

//                 </div>
//             </div>
//         </div>
//     `;


//     document.getElementById("daysContainer").appendChild(newDay);
// }

function addDay(stay, date, image, itinerary_content, dayCount) {
    let newDay = document.createElement("div");
    newDay.classList.add("day-container", "update-day-form");

    let uniqueId = `itinerary-${dayCount}`; // Generate a unique ID for each itinerary field

    newDay.innerHTML = `
        <h6 class="mb-3">Day ${dayCount}</h6>
        <div class="row">
            <!-- Stay -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay" value="${stay}" required>
                    <label>Stay</label>
                </div>
            </div>

            <!-- Date -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <input type="date" class="form-control" name="date[]" value="${date}" required>
                    <label>Date</label>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
                <input type="file" class="form-control image-upload" name="images[]" accept="image/*" required>
                <img src="${image}" class="img-preview mt-2" style="width: 80px; height: 80px">
            </div>

            <!-- Itinerary Content -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <textarea id="${uniqueId}" class="form-control" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required>${itinerary_content}</textarea>
                </div>
            </div>
        </div>
    `;

    document.getElementById("daysContainer").appendChild(newDay);

    // Initialize CKEditor for the new textarea
    ClassicEditor.create(document.getElementById(uniqueId))
        .then(editor => {
            console.log(`Editor initialized for: ${uniqueId}`);
        })
        .catch(error => console.error(`Error initializing CKEditor for ${uniqueId}:`, error));
}
