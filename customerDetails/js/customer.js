$(document).ready(function () {
    $("#submitButton").click(function (event) {
        event.preventDefault(); // Prevent default form submission

        var form = $("#customerForm");
        if (form.length === 0) {
            alert("Form not found!");
            return;
        }

        // Validate required fields before submitting
        var isValid = true;

        $(".required").each(function () {
            if ($(this).val().trim() === "") {
                $(this).addClass("is-invalid"); // Highlight empty fields
                $(this).next(".error-message").text("This field is required").show(); // Show error message
                isValid = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).next(".error-message").hide(); // Hide error message when field is filled
            }
        });

        if (!isValid) {
            return; // Stop submission if validation fails
        }

        // Create FormData object for file uploads
        var formData = new FormData(form[0]);

        // Debugging: Log form data to console (remove before production)
        console.log("Submitting FormData:", formData);

        // AJAX request to send form data to customerController.php
        $.ajax({
            url: "controller/customerController.php", // Ensure correct path
            type: "POST",
            data: formData,
            contentType: false,  // Required for file upload
            processData: false,  // Prevents jQuery from converting FormData to query string
            dataType: "json",
            beforeSend: function () {
                $("#submitButton").prop("disabled", true).text("Saving...");
            },
            success: function (response) {
                console.log("Server Response:", response); // Debugging
                if (response.success) {
                    alert("Customer details saved successfully!");
                    form[0].reset(); // Reset form fields
                    $(".required").removeClass("is-invalid"); // Remove validation class
                    $(".error-message").hide(); // Hide error messages after successful save
                } else {
                    alert();
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("An unexpected error occurred. Please try again.");
            },
            complete: function () {
                $("#submitButton").prop("disabled", false).text("Save");
            }
        });
    });
});
