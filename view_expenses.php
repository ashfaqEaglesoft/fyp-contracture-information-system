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

// Fetch expenses data for the contractor's site from the database
$sql_expenses = "SELECT * FROM Expense WHERE site_id = ?";
if ($stmt_expenses = $conn->prepare($sql_expenses)) {
    // Bind site_id to the prepared statement as parameter
    $stmt_expenses->bind_param("i", $site_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_expenses->execute()) {
        // Get result
        $result_expenses = $stmt_expenses->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_expenses->close();
}

// Processing form data when form is submitted (for delete action)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_expense_id'])) {
        $expense_id = $_POST['delete_expense_id'];
        
        // Delete expense from database
        $sql_delete_expense = "DELETE FROM Expense WHERE expense_id = ?";
        if ($stmt_delete_expense = $conn->prepare($sql_delete_expense)) {
            // Bind expense_id to the prepared statement as parameter
            $stmt_delete_expense->bind_param("i", $expense_id);
            
            // Attempt to execute the prepared statement
            if ($stmt_delete_expense->execute()) {
                // Redirect to view_expenses.php after successful deletion
                header("Location: view_expenses.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_delete_expense->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>View Expenses</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Material</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Other Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_expenses->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['material']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['cost']; ?></td>
                    <td><?php echo $row['other_details']; ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?php echo $row['expense_id']; ?>" class="btn btn-primary">Edit</a>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline-block;">
                            <input type="hidden" name="delete_expense_id" value="<?php echo $row['expense_id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
