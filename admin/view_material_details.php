<?php
session_start();
include('config.php');

// Check if the user is logged in as admin
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

// Fetch material details for the site from the database
$sql = "SELECT * FROM material_details WHERE site_id = ?";
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
    <title>Material Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Material Details</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Floor Tile</th>
                <th>Bath Tile</th>
                <th>Front Elevation Tile</th>
                <th>Cement Type</th>
                <th>Gate</th>
                <th>Water Pump Tank</th>
                <th>Base Beams</th>
                <th>Roof Slab Type</th>
                <th>Paint</th>
                <th>Steel</th>
                <th>Wood Window Door</th>
                <th>Bricks Sand Concrete Stone</th>
                <th>Electricity Wire Switches</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['floor_tile'] . "</td>";
                    echo "<td>" . $row['bath_tile'] . "</td>";
                    echo "<td>" . $row['front_elevation_tile'] . "</td>";
                    echo "<td>" . $row['cement_type'] . "</td>";
                    echo "<td>" . $row['gate'] . "</td>";
                    echo "<td>" . $row['water_pump_tank'] . "</td>";
                    echo "<td>" . $row['base_beams'] . "</td>";
                    echo "<td>" . $row['roof_slab_type'] . "</td>";
                    echo "<td>" . $row['paint'] . "</td>";
                    echo "<td>" . $row['steel'] . "</td>";
                    echo "<td>" . $row['wood_window_door'] . "</td>";
                    echo "<td>" . $row['bricks_sand_concrete_stone'] . "</td>";
                    echo "<td>" . $row['electricity_wire_switches'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='14'>No material details found</td></tr>";
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
