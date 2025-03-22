document.addEventListener("DOMContentLoaded", function () {
    fetch("controller/listcustomerController.php") // Ensure the path is correct
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(customer => {
            let tableBody = document.getElementById("tripTableBody");
            let table = document.getElementById("listTable"); // Corrected table selection

            tableBody.innerHTML = ""; // Clear previous content

            customer.forEach(({ id, customer_name, mobile_number, email, tour_name, travel_from, travel_to, adults, children }, index) => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td> 
                    <td>${customer_name}</td>
                    <td>${mobile_number}</td>
                    <td>${email}</td>
                    <td>${tour_name}</td>
                    <td>${travel_from}</td>
                    <td>${travel_to}</td>
                    <td>${adults}</td>
                    <td>${children}</td>
                    <td>
                       <a href='customerform.php?id=${id}'>
                           <button class="btn btn-warning btn-sm">Edit</button>
                       </a>
                       <button class="btn btn-danger btn-sm" onclick="deleteTour(${id}, this)">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTables after data is loaded
            new DataTable(table);
        })
        .catch(error => console.error("Error fetching data:", error));
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
                btn.closest("tr").remove(); // Remove row without reloading
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error deleting data:", error));
    }
}
