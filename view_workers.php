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

// Define variables for worker data
$worker_id = $name = $father_name = $address = $phone = $expertise = "";

// Fetch workers data for the contractor's site from the database
$sql_workers = "SELECT * FROM person WHERE site_id = ?";
if ($stmt_workers = $conn->prepare($sql_workers)) {
    // Bind site_id to the prepared statement as parameter
    $stmt_workers->bind_param("i", $site_id);
    
    // Attempt to execute the prepared statement
    if ($stmt_workers->execute()) {
        // Get result
        $result_workers = $stmt_workers->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_workers->close();
}

// Processing form data when form is submitted (for delete action)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_worker_id'])) {
        $worker_id = $_POST['delete_worker_id'];
        
        // Delete worker from database
        $sql_delete_worker = "DELETE FROM person WHERE person_id = ?";
        if ($stmt_delete_worker = $conn->prepare($sql_delete_worker)) {
            // Bind worker_id to the prepared statement as parameter
            $stmt_delete_worker->bind_param("i", $worker_id);
            
            // Attempt to execute the prepared statement
            if ($stmt_delete_worker->execute()) {
                // Redirect to view_workers.php after successful deletion
                header("Location: view_workers.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_delete_worker->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Workers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>View Workers</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Father's Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Expertise</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_workers->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['father_name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['expertise']; ?></td>
                    <td>
                        <a href="edit_worker.php?id=<?php echo $row['person_id']; ?>" class="btn btn-primary">Edit</a>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline-block;">
                            <input type="hidden" name="delete_worker_id" value="<?php echo $row['person_id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this worker?')">Delete</button>
                        </form>
                        <a href="add_working_details.php?id=<?php echo $row['person_id']; ?>" class="btn btn-success">Add Working Details</a>
                        <a href="view_work_log.php?id=<?php echo $row['person_id']; ?>" class="btn btn-info">View Work Log</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
