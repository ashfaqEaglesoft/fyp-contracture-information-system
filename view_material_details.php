<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Check if site_id is provided in the URL
if (!isset($_GET['site_id'])) {
    header("Location: error.php");
    exit;
}

// Get site_id from the URL
$site_id = $_GET['site_id'];

// Get material details for the specified site from the database
$sql = "SELECT * FROM material_details WHERE site_id = ?";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $site_id);
    
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result
        $result = $stmt->get_result();
        
        // Check if material details exist for the specified site
        if ($result->num_rows > 0) {
            // Fetch all rows from the result set
            $material_details = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Redirect to error page if material details are not found
            echo "No Site Material Found";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Material Details</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Floor Tile</th>
                    <th>Bath Tile</th>
                    <th>Front Elevation Tile</th>
                    <th>Cement Type</th>
                    <th>Gate</th>
                    <th>Water Pump and Tank</th>
                    <th>Base, Beams</th>
                    <th>Roof Slab Type</th>
                    <th>Paint</th>
                    <th>Steel</th>
                    <th>Wood, Window, Door</th>
                    <th>Bricks, Sand, Concrete, Stone</th>
                    <th>Electricity Wire and Switches</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($material_details as $row) : ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['floor_tile']; ?></td>
                    <td><?php echo $row['bath_tile']; ?></td>
                    <td><?php echo $row['front_elevation_tile']; ?></td>
                    <td><?php echo $row['cement_type']; ?></td>
                    <td><?php echo $row['gate']; ?></td>
                    <td><?php echo $row['water_pump_tank']; ?></td>
                    <td><?php echo $row['base_beams']; ?></td>
                    <td><?php echo $row['roof_slab_type']; ?></td>
                    <td><?php echo $row['paint']; ?></td>
                    <td><?php echo $row['steel']; ?></td>
                    <td><?php echo $row['wood_window_door']; ?></td>
                    <td><?php echo $row['bricks_sand_concrete_stone']; ?></td>
                    <td><?php echo $row['electricity_wire_switches']; ?></td>
                    <td>
    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="mt-2 btn btn-danger">Delete</button>
</td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Are you sure you want to delete this material detail entry?')) {
            window.location.href = 'delete_material_details.php?id=' + id;
        }
    }
</script>


</body>
</html>
