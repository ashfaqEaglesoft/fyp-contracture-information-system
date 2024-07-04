<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Get contractor's site_id
$user_id = $_SESSION["user_id"];
$sql_site_id = "SELECT site_id FROM contractor WHERE contractor_id = ?";
if ($stmt_site_id = $conn->prepare($sql_site_id)) {
    // Bind variables to the prepared statement as parameters
    $stmt_site_id->bind_param("i", $user_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_site_id->execute()) {
        // Store result
        $stmt_site_id->store_result();
        
        // Check if site_id exists
        if ($stmt_site_id->num_rows == 1) {
            // Bind result variables
            $stmt_site_id->bind_result($site_id);
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
$material = $quantity = $cost = $other_details = "";
$material_err = $quantity_err = $cost_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate material
    if (empty(trim($_POST["material"]))) {
        $material_err = "Please enter material name.";
    } else {
        $material = trim($_POST["material"]);
    }
    
    // Validate quantity
    if (empty(trim($_POST["quantity"]))) {
        $quantity_err = "Please enter quantity.";
    } else {
        $quantity = trim($_POST["quantity"]);
    }
    
    // Validate cost
    if (empty(trim($_POST["cost"]))) {
        $cost_err = "Please enter cost.";
    } else {
        $cost = trim($_POST["cost"]);
    }

    // Validate other details
    $other_details = trim($_POST["other_details"]);

    // Check input errors before inserting into database
    if (empty($material_err) && empty($quantity_err) && empty($cost_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO expense (site_id, material, quantity, cost, other_details) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("isiss", $param_site_id, $param_material, $param_quantity, $param_cost, $param_other_details);
            
            // Set parameters
            $param_site_id = $site_id; // Use the retrieved site_id
            $param_material = $material;
            $param_quantity = $quantity;
            $param_cost = $cost;
            $param_other_details = $other_details;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to expense list page after successful insertion
                header("Location: view_expenses.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Expense</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Material:</label>
            <input type="text" name="material" class="form-control <?php echo (!empty($material_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $material; ?>">
            <span class="invalid-feedback"><?php echo $material_err; ?></span>
        </div>
        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
            <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
        </div>
        <div class="form-group">
            <label>Cost:</label>
            <input type="number" name="cost" class="form-control <?php echo (!empty($cost_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cost; ?>">
            <span class="invalid-feedback"><?php echo $cost_err; ?></span>
        </div>
        <div class="form-group">
            <label>Other Details:</label>
            <textarea name="other_details" class="form-control"><?php echo $other_details; ?></textarea>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_expenses.php" class="btn btn-secondary">View Expenses</a>
        </div>
    </form>
</div>

</body>
</html>
