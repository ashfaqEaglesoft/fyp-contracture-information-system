<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Get contractor details from database
$user_id = $_SESSION["user_id"];
$sql = "SELECT c.name, c.contact_number, c.address, c.email, c.password, c.site_id, s.site_name, s.site_address, s.covered_area, s.owner_id, o.name, o.contact_number AS owner_contact_number, o.address AS owner_address FROM contractor c
        LEFT JOIN site s ON c.site_id = s.site_id
        LEFT JOIN owner o ON s.owner_id = o.owner_id
        WHERE c.contractor_id = ?";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $user_id);
    
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result
        $result = $stmt->get_result();
        
        // Check if contractor exists
        if ($result->num_rows > 0) {
            $contractor_data = $result->fetch_assoc();
        } else {
            // Redirect to error page if contractor ID doesn't exist
            header("Location: error.php");
            exit;
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
    <title>Contractor Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Welcome back, <?php echo $contractor_data['name']; ?>!</h2>
    <h3>Your Sites:</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Site Name</th>
                    <th>Site Address</th>
                    <th>Covered Area</th>
                    <th>Owner Name</th>
                    <th>Owner Contact Number</th>
                    <th>Owner Address</th>
                    <th>Material </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $contractor_data['site_name']; ?></td>
                    <td><?php echo $contractor_data['site_address']; ?></td>
                    <td><?php echo $contractor_data['covered_area']; ?></td>
                    <td><?php echo $contractor_data['name']; ?></td>
                    <td><?php echo $contractor_data['owner_contact_number']; ?></td>
                    <td><?php echo $contractor_data['owner_address']; ?></td>
                    <td><a href="add_material_details.php?site_id=<?php echo $contractor_data['site_id']; ?>" class="btn btn-primary">Add Material Details</a>
                    <a href="view_material_details.php?site_id=<?php echo $contractor_data['site_id']; ?>" class="mt-2 btn btn-dark">View Material Details</a>
</td>
                    
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
