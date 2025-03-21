document.addEventListener("DOMContentLoaded", function () {
    fetch("controller/listingController.php") // Ensure the path is correct
        .then(response => response.json())
        .then(trips => {
            let tableBody = document.getElementById("tripTableBody");
            let table = document.querySelector("listTable"); // Select the table

            tableBody.innerHTML = ""; // Clear previous content

            trips.forEach((trip, index) => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td> 
                    <td>${trip.trip_id}</td> 
                    <td>${trip.username}</td>
                    <td>${trip.tour_name}</td>
                    <td>${trip.check_in}</td>
                    <td>${trip.check_out}</td>
                    <td>${trip.adults}</td>
                    <td>${trip.children}</td>
                   
                   <td>
                       <a href='dayitineraryform.php?id=${trip.id}'> <button class="btn btn-warning btn-sm" >Edit</button></a>
                        <button class="btn btn-danger btn-sm" onclick="deleteTour(${trip.id}, this)">Delete</button>
                    </td>

                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTables after data is loaded
            new DataTable(table);

        })
    // .catch(error => console.error("Error fetching data:", error));
});

function deleteTour(index, btn) {
    if (confirm("Are you sure you want to delete this trip?")) {
        fetch("controller/deleteController.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ trip_id: index }) // Safer way to encode data
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            })

    }
}
