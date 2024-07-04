<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Fetch the site_id of the logged-in contractor
$user_id = $_SESSION["user_id"];
$sql_site_id = "SELECT site_id FROM contractor WHERE contractor_id = ?";
if ($stmt_site_id = $conn->prepare($sql_site_id)) {
    // Bind variables to the prepared statement as parameters
    $stmt_site_id->bind_param("i", $user_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_site_id->execute()) {
        // Store result
        $stmt_site_id->store_result();
        
        // Check if site_id exists for the contractor
        if ($stmt_site_id->num_rows > 0) {
            // Bind the result variables
            $stmt_site_id->bind_result($site_id);
            
            // Fetch the site_id value
            $stmt_site_id->fetch();
        } else {
            // Redirect to error page if site_id doesn't exist
            header("Location: error.php");
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_site_id->close();
}

// Define variables and initialize with empty values
$name = $father_name = $address = $phone = $expertise = "";
$name_err = $father_name_err = $address_err = $phone_err = $expertise_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter worker's name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Validate father's name
    if (empty(trim($_POST["father_name"]))) {
        $father_name_err = "Please enter father's name.";
    } else {
        $father_name = trim($_POST["father_name"]);
    }
    
    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter worker's address.";
    } else {
        $address = trim($_POST["address"]);
    }
    
    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter worker's phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }
    
    // Validate expertise
    if (empty(trim($_POST["expertise"]))) {
        $expertise_err = "Please enter worker's expertise.";
    } else {
        $expertise = trim($_POST["expertise"]);
    }
    
    // Check input errors before inserting into database
    if (empty($name_err) && empty($father_name_err) && empty($address_err) && empty($phone_err) && empty($expertise_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO person (name, father_name, address, phone, expertise, site_id) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssi", $param_name, $param_father_name, $param_address, $param_phone, $param_expertise, $site_id);
            
            // Set parameters
            $param_name = $name;
            $param_father_name = $father_name;
            $param_address = $address;
            $param_phone = $phone;
            $param_expertise = $expertise;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to contractor_dashboard.php after successful insertion
                header("Location: view_workers.php?site_id=" . urlencode($site_id));
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Worker</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Father's Name:</label>
            <input type="text" name="father_name" class="form-control <?php echo (!empty($father_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $father_name; ?>">
            <span class="invalid-feedback"><?php echo $father_name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Address:</label>
            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
            <span class="invalid-feedback"><?php echo $address_err; ?></span>
        </div>
        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
            <span class="invalid-feedback"><?php echo $phone_err; ?></span>
        </div>
        <div class="form-group">
            <label>Expertise:</label>
            <input type="text" name="expertise" class="form-control <?php echo (!empty($expertise_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $expertise; ?>">
            <span class="invalid-feedback"><?php echo $expertise_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_workers.php?site_id=<?php echo urlencode($site_id); ?>" class="btn btn-secondary">View Workers</a>
        </div>
    </form>
</div>

</body>
</html>
