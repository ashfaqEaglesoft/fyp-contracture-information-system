<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables to store form data
$name = $contact_number = $address = $email = "";
$name_err = $contact_number_err = $address_err = $email_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter owner's name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Validate contact number
    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter owner's contact number.";
    } else {
        $contact_number = trim($_POST["contact_number"]);
    }

    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter owner's address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter owner's email.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Check input errors before inserting into database
    if (empty($name_err) && empty($contact_number_err) && empty($address_err) && empty($email_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO owner (name, contact_number, address, email) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_contact_number, $param_address, $param_email);
            
            // Set parameters
            $param_name = $name;
            $param_contact_number = $contact_number;
            $param_address = $address;
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to admin dashboard after successful addition
                header("Location: view_owners.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Owner</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Add Owner</h2>
    <p>Please fill in the owner's information:</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
            <span class="invalid-feedback"><?php echo $address_err; ?></span>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_owners.php" class="btn btn-secondary ml-2">View Owners</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
