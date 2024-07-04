<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables to store form data
$site_name = $site_address = $covered_area = $owner_id = "";
$site_name_err = $site_address_err = $covered_area_err = $owner_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate site name
    if (empty(trim($_POST["site_name"]))) {
        $site_name_err = "Please enter site name.";
    } else {
        $site_name = trim($_POST["site_name"]);
    }
    
    // Validate site address
    if (empty(trim($_POST["site_address"]))) {
        $site_address_err = "Please enter site address.";
    } else {
        $site_address = trim($_POST["site_address"]);
    }

    // Validate covered area
    if (empty(trim($_POST["covered_area"]))) {
        $covered_area_err = "Please enter covered area.";
    } else {
        $covered_area = trim($_POST["covered_area"]);
    }

    // Validate owner id
    if (empty(trim($_POST["owner_id"]))) {
        $owner_id_err = "Please select owner.";
    } else {
        $owner_id = trim($_POST["owner_id"]);
    }
    
    // Check input errors before inserting into database
    if (empty($site_name_err) && empty($site_address_err) && empty($covered_area_err) && empty($owner_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO site (site_name, site_address, covered_area, owner_id) VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_site_name, $param_site_address, $param_covered_area, $param_owner_id);
            
            // Set parameters
            $param_site_name = $site_name;
            $param_site_address = $site_address;
            $param_covered_area = $covered_area;
            $param_owner_id = $owner_id;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to admin dashboard after successful addition
                header("Location: view_sites.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Fetch owners for dropdown
$owners = [];
$sql_owners = "SELECT owner_id, name FROM owner";
$result_owners = $conn->query($sql_owners);
if ($result_owners->num_rows > 0) {
    while ($row = $result_owners->fetch_assoc()) {
        $owners[$row['owner_id']] = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Site</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Add Site</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Site Name:</label>
            <input type="text" name="site_name" class="form-control <?php echo (!empty($site_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $site_name; ?>">
            <span class="invalid-feedback"><?php echo $site_name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Site Address:</label>
            <input type="text" name="site_address" class="form-control <?php echo (!empty($site_address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $site_address; ?>">
            <span class="invalid-feedback"><?php echo $site_address_err; ?></span>
        </div>
        <div class="form-group">
            <label>Covered Area:</label>
            <input type="text" name="covered_area" class="form-control <?php echo (!empty($covered_area_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $covered_area; ?>">
            <span class="invalid-feedback"><?php echo $covered_area_err; ?></span>
        </div>
        <div class="form-group">
            <label>Owner:</label>
            <select name="owner_id" class="form-control <?php echo (!empty($owner_id_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select Owner</option>
                <?php
                foreach ($owners as $owner_id => $owner_name) {
                    echo "<option value='$owner_id'>$owner_name</option>";
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php echo $owner_id_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_sites.php" class="btn btn-secondary ml-2">View Sites</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
