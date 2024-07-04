<?php
session_start();
include('config.php');

// Check if the user is logged in as  admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables to store owner data
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
    
    // will Check input errors before updating into database
    if (empty($name_err) && empty($contact_number_err) && empty($address_err) && empty($email_err)) {
        // Prepare an update statement
        $sql = "UPDATE owner SET name=?, contact_number=?, address=?, email=? WHERE owner_id=?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi", $param_name, $param_contact_number, $param_address, $param_email, $param_owner_id);
            
            // Set parameters
            $param_name = $name;
            $param_contact_number = $contact_number;
            $param_address = $address;
            $param_email = $email;
            $param_owner_id = $_POST['owner_id'];
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect 
                header("Location: view_owners.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Get owner data from database
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Prepare a select statement
    $sql = "SELECT * FROM owner WHERE owner_id = ?";
    
    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_owner_id);
        
        // Set parameters
        $param_owner_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                // Fetch result row as  associative array
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $contact_number = $row["contact_number"];
                $address = $row["address"];
                $email = $row["email"];
            } else{
                // URL doesn't contain valid owner_id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else{
            echo "Oops! Something went wrong. Please try again .";
        }

        // Close statement
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Owner</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Edit Owner</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="owner_id" value="<?php echo $param_owner_id; ?>">
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
            <a href="view_owners.php" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
