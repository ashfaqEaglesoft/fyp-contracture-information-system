<?php
session_start();
include('config.php');

// Check  user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$name = $contact_number = $address = $email = $password = $site_id = "";
$name_err = $contact_number_err = $address_err = $email_err = $password_err = $site_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter contractor's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate contact number
    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter contractor's contact number.";
    } else {
        $contact_number = trim($_POST["contact_number"]);
    }

    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter contractor's address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter contractor's email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate site id
    if (empty(trim($_POST["site_id"]))) {
        $site_id_err = "Please enter contractor's site id.";
    } else {
        $site_id = trim($_POST["site_id"]);
    }

    // Will Get contractor ID from URL
    $contractor_id = $_GET["id"];

    // Check input errors before updating the database
    if (empty($name_err) && empty($contact_number_err) && empty($address_err) && empty($email_err) && empty($site_id_err)) {
        // Prepare an update statement
        $sql = "UPDATE contractor SET name=?, contact_number=?, address=?, email=?, site_id=? WHERE contractor_id=?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssii", $param_name, $param_contact_number, $param_address, $param_email, $param_site_id, $param_contractor_id);
            
            // Set parameters
            $param_name = $name;
            $param_contact_number = $contact_number;
            $param_address = $address;
            $param_email = $email;
            $param_site_id = $site_id;
            $param_contractor_id = $contractor_id;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect 
                header("Location: view_contractors.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
} else {
    // Get contractor ID from URL
    $contractor_id = $_GET["id"];
    
    // Retrieve contractor's information from database
    $sql = "SELECT * FROM contractor WHERE contractor_id=?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_contractor_id);
        
        // Set parameters
        $param_contractor_id = $contractor_id;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if contractor exists, if yes then fetch its data
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($contractor_id, $name, $contact_number, $address, $email, $password, $site_id);
                $stmt->fetch();
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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Contractor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Edit Contractor</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $contractor_id; ?>" method="post">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Contact Number:</label>
            <input type="text" name="contact_number" class="form-control <?php echo (!empty($contact_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contact_number; ?>">
            <span class="invalid-feedback"><?php echo $contact_number_err; ?></span>
        </div>
        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
            <span class="invalid-feedback"><?php echo $address_err; ?></span>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Site ID:</label>
            <input type="text" name="site_id" class="form-control <?php echo (!empty($site_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $site_id; ?>">
            <span class="invalid-feedback"><?php echo $site_id_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_contractors.php" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
