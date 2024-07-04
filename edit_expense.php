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

// Check if expense_id is provided in the URL
if (!isset($_GET["id"])) {
    header("Location: error.php");
    exit;
}

$expense_id = $_GET["id"];

// Fetch expense data from the database
$sql_expense = "SELECT * FROM Expense WHERE expense_id = ?";
if ($stmt_expense = $conn->prepare($sql_expense)) {
    // Bind expense_id to the prepared statement as parameter
    $stmt_expense->bind_param("i", $expense_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_expense->execute()) {
        // Get result
        $result_expense = $stmt_expense->get_result();
        
        if ($result_expense->num_rows == 1) {
            // Fetch expense data
            $expense_data = $result_expense->fetch_assoc();
        } else {
            // Redirect to error page if expense_id doesn't exist
            header("Location: error.php");
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_expense->close();
}

// Processing form data when form is submitted (for update action)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $material = $_POST["material"];
    $quantity = $_POST["quantity"];
    $cost = $_POST["cost"];
    $other_details = $_POST["other_details"];

    // Update expense in the database
    $sql_update_expense = "UPDATE Expense SET material = ?, quantity = ?, cost = ?, other_details = ? WHERE expense_id = ?";
    if ($stmt_update_expense = $conn->prepare($sql_update_expense)) {
        // Bind parameters to the prepared statement
        $stmt_update_expense->bind_param("sidsi", $material, $quantity, $cost, $other_details, $expense_id);
        
        // Attempt to execute the prepared statement
        if ($stmt_update_expense->execute()) {
            // Redirect to view_expenses.php after successful update
            header("Location: view_expenses.php");
            exit;
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt_update_expense->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Expense</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $expense_id; ?>" method="post">
        <div class="form-group">
            <label for="material">Material:</label>
            <input type="text" class="form-control" id="material" name="material" value="<?php echo $expense_data['material']; ?>">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $expense_data['quantity']; ?>">
        </div>
        <div class="form-group">
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" value="<?php echo $expense_data['cost']; ?>">
        </div>
        <div class="form-group">
            <label for="other_details">Other Details:</label>
            <textarea class="form-control" id="other_details" name="other_details" rows="3"><?php echo $expense_data['other_details']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

</body>
</html>
