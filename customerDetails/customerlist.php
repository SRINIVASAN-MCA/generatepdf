<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Customer List</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Correct DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">



</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Tour Listing -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Tour List</h2>
            <a href="customerform.php">
                <button class="btn btn-primary fw-bold">
                    Add New
                </button>
            </a>
        </div>

        <div class="table-responsive">
            <table id="listTable" class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Username</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Tour Name</th>
                        <th colspan="2">Traveling Dates</th>
                        <th colspan="2">No of Passengers</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tripTableBody">
                </tbody>
            </table>
        </div>

    </div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Correct jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="js/listcustomer.js"></script>

</body>

</html>