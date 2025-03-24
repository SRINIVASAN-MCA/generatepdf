document.addEventListener("DOMContentLoaded", async function () {
    try {
        let response = await fetch("controller/listcustomerController.php"); // Ensure correct API path
        if (!response.ok) throw new Error("Network response was not ok");

        let text = await response.text(); // Get raw response for debugging
        console.log("Raw response:", text); // Debugging

        let data;
        try {
            data = JSON.parse(text);
        } catch (error) {
            throw new Error("Invalid JSON format: " + text);
        }

        if (!data.success || !Array.isArray(data.data)) {
            throw new Error("Invalid data format received");
        }

        let customers = data.data;
        let tableBody = document.getElementById("tripTableBody");
        let table = document.getElementById("listTable");

        if (!tableBody || !table) {
            console.error("Table or table body not found.");
            return;
        }

        tableBody.innerHTML = ""; // Clear previous content

        customers.forEach(({ id, tour_name, travel_from, travel_to, adults, children }, index) => {
            let row = document.createElement("tr");
            row.setAttribute("data-id", id); // Store ID for reference
            row.innerHTML = `
                <td>${index + 1}</td> 
                <td>${tour_name}</td>
                <td>${travel_from}</td>
                <td>${travel_to}</td>
                <td>${adults}</td>
                <td>${children}</td>
                <td>
                   <a href="customerform.php?id=${id}">
                       <button class="btn btn-warning btn-sm">Edit</button>
                   </a>
                   <button class="btn btn-danger btn-sm" onclick="deleteTour(${id}, this)">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Initialize DataTables after data is loaded (Ensure it initializes only once)
        if (!$.fn.DataTable.isDataTable(table)) {
            new DataTable(table);
        }

    } catch (error) {
        console.error("Error fetching data:", error.message);
    }
});

async function deleteTour(id, btn) {
    if (!confirm("Are you sure you want to delete this customer?")) return;

    try {
        let response = await fetch("controller/deleteController.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ id: id }) // Ensure correct key matches PHP
        });

        let text = await response.text(); // Read raw response first
        console.log("Raw response:", text); // Debugging

        let data;
        try {
            data = JSON.parse(text); // Attempt to parse JSON
        } catch (error) {
            throw new Error("Invalid JSON response: " + text);
        }

        if (data.success) {
            alert(data.message);
            let row = btn.closest("tr");
            if (row) row.remove(); // Remove row from table
        } else {
            alert("Error: " + data.message);
        }
    } catch (error) {
        console.error("Error deleting data:", error.message);
        alert("An error occurred while deleting the customer.");
    }
}
