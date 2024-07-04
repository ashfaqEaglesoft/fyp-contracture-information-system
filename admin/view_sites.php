<?php
session_start();
include('config.php');

// Check if the user is logged in as  admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete site if site_id is provided in  URL
if(isset($_GET["delete"]) && !empty($_GET["delete"])) {
    $site_id = $_GET["delete"];
    $sql = "DELETE FROM site WHERE site_id = ?";
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $site_id);
        if($stmt->execute()) {
            echo "<script>alert('Site deleted successfully');</script>";
        } else {
            echo "<script>alert('Failed to delete site');</script>";
        }
        $stmt->close();
    }
}

// Fetch all sites with owner information
$sql = "SELECT s.site_id, s.site_name, s.site_address, s.covered_area, o.name AS owner_name 
        FROM site s
        LEFT JOIN owner o ON s.owner_id = o.owner_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sites</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>View Sites</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Site Name</th>
                <th>Site Address</th>
                <th>Covered Area</th>
                <th>Owner</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['site_name'] . "</td>";
                    echo "<td>" . $row['site_address'] . "</td>";
                    echo "<td>" . $row['covered_area'] . "</td>";
                    echo "<td>" . $row['owner_name'] . "</td>";
                    echo "<td><a href='edit_site.php?id=" . $row['site_id'] . "' class='btn btn-primary btn-sm'>Edit</a> <a href='view_sites.php?delete=" . $row['site_id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this site?')\">Delete</a>";
                    echo "<a href='view_material_details.php?site_id=" . $row['site_id'] . "' class='btn btn-info btn-sm'>View Material Details</a> ";
                    echo "<a href='view_worker_details.php?site_id=" . $row['site_id'] . "' class='btn btn-success btn-sm'>View Worker Details</a> ";
                    echo "<a href='view_expense_details.php?site_id=" . $row['site_id'] . "' class='btn btn-warning btn-sm'>View Expense Details</a></td> ";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No sites found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
