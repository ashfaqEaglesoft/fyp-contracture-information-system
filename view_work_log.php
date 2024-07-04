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

// Check if person_id is provided in the URL
if (!isset($_GET["id"])) {
    header("Location: error.php");
    exit;
}

$person_id = $_GET["id"];

// Fetch work log of the person from the database
$sql_work_log = "SELECT * FROM work_log WHERE person_id = ?";
if ($stmt_work_log = $conn->prepare($sql_work_log)) {
    // Bind person_id to the prepared statement as parameter
    $stmt_work_log->bind_param("i", $person_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_work_log->execute()) {
        // Get result
        $result_work_log = $stmt_work_log->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_work_log->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Work Log</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Work Log</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Work Date</th>
                <th>Work Time</th>
                <th>Work Details</th>
                <th>Paid Status</th>
                <th>Amount</th>
                <th>Action</th> <!-- New column for delete button -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_work_log->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['work_date']; ?></td>
                    <td><?php echo $row['work_time']; ?></td>
                    <td><?php echo $row['work_details']; ?></td>
                    <td><?php echo $row['paid_status']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td>
                    <form action="delete_log.php" method="post">
    <input type="hidden" name="log_id" value="<?php echo $row['log_id']; ?>">
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</button>
</form>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
