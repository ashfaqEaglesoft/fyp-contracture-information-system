<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete owner if owner_id is provided in the URL
if(isset($_GET["delete"]) && !empty($_GET["delete"])) {
    $owner_id = $_GET["delete"];
    $sql = "DELETE FROM owner WHERE owner_id = ?";
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $owner_id);
        if($stmt->execute()) {
            echo "<script>alert('Owner deleted successfully');</script>";
        } else {
            echo "<script>alert('Failed to delete owner');</script>";
        }
        $stmt->close();
    }
}

// Fetch all owners
$sql = "SELECT * FROM owner";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Owners</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>View Owners</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['contact_number'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td><a href='edit_owner.php?id=" . $row['owner_id'] . "' class='btn btn-primary btn-sm'>Edit</a> <a href='view_owners.php?delete=" . $row['owner_id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this owner?')\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No owners found</td></tr>";
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
