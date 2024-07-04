<?php
session_start();
include('config.php');

// Check if the user is logged in as  admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if site_id is provided in the URL
if (!isset($_GET["site_id"])) {
    header("Location: error.php");
    exit;
}

$site_id = $_GET["site_id"];

// Fetch workers of the site from the database
$sql = "SELECT * FROM person WHERE site_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $site_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Worker Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Worker Details</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Father's Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Expertise</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['father_name'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['expertise'] . "</td>";
                    echo "<td><a href='view_work_logs.php?person_id=" . $row['person_id'] . "' class='btn btn-info btn-sm'>View Work Logs</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No workers found</td></tr>";
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
