<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Check if worker ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: error.php");
    exit;
}

// Get the worker ID from the URL
$worker_id = $_GET['id'];

// Define variables for worker data
$name = $father_name = $address = $phone = $expertise = "";
$name_err = $father_name_err = $address_err = $phone_err = $expertise_err = "";

// Fetch worker data from the database
$sql_worker = "SELECT * FROM person WHERE person_id = ?";
if ($stmt_worker = $conn->prepare($sql_worker)) {
    // Bind worker ID to the prepared statement as parameter
    $stmt_worker->bind_param("i", $worker_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_worker->execute()) {
        // Get result
        $result_worker = $stmt_worker->get_result();
        
        // Check if worker exists
        if ($result_worker->num_rows == 1) {
            // Fetch worker details
            $row_worker = $result_worker->fetch_assoc();
            $name = $row_worker['name'];
            $father_name = $row_worker['father_name'];
            $address = $row_worker['address'];
            $phone = $row_worker['phone'];
            $expertise = $row_worker['expertise'];
        } else {
            // Redirect to error page if worker doesn't exist
            header("Location: error.php");
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_worker->close();
}

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
    
    // Check input errors before updating into database
    if (empty($name_err) && empty($father_name_err) && empty($address_err) && empty($phone_err) && empty($expertise_err)) {
        // Prepare an update statement
        $sql_update_worker = "UPDATE person SET name=?, father_name=?, address=?, phone=?, expertise=? WHERE person_id=?";
        
        if ($stmt_update_worker = $conn->prepare($sql_update_worker)) {
            // Bind variables to the prepared statement as parameters
            $stmt_update_worker->bind_param("sssssi", $param_name, $param_father_name, $param_address, $param_phone, $param_expertise, $param_worker_id);
            
            // Set parameters
            $param_name = $name;
            $param_father_name = $father_name;
            $param_address = $address;
            $param_phone = $phone;
            $param_expertise = $expertise;
            $param_worker_id = $worker_id;
            
            // Attempt to execute the prepared statement
            if ($stmt_update_worker->execute()) {
                // Redirect to view_workers.php after successful update
                header("Location: view_workers.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_update_worker->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Worker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Worker</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $worker_id; ?>" method="post">
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
            <input type="submit" class="btn btn-primary" value="Update">
            <a href="view_workers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
