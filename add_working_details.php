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
$person_id = $work_date = $work_time = $work_details = $paid_status = $amount = "";
$work_date_err = $work_time_err = $work_details_err = $paid_status_err = $amount_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate work date
    if (empty(trim($_POST["work_date"]))) {
        $work_date_err = "Please enter the work date.";
    } else {
        $work_date = trim($_POST["work_date"]);
    }
    
    // Validate work time
    if (empty(trim($_POST["work_time"]))) {
        $work_time_err = "Please enter the work time.";
    } else {
        $work_time = trim($_POST["work_time"]);
    }
    
    // Validate work details
    if (empty(trim($_POST["work_details"]))) {
        $work_details_err = "Please enter the work details.";
    } else {
        $work_details = trim($_POST["work_details"]);
    }
    
    // Validate paid status
    if (empty(trim($_POST["paid_status"]))) {
        $paid_status_err = "Please select the paid status.";
    } else {
        $paid_status = trim($_POST["paid_status"]);
    }
    
    // Validate amount
    if (empty(trim($_POST["amount"]))) {
        $amount_err = "Please enter the amount.";
    } else {
        $amount = trim($_POST["amount"]);
    }
    
    // Check input errors before inserting into database
    if (empty($work_date_err) && empty($work_time_err) && empty($work_details_err) && empty($paid_status_err) && empty($amount_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO work_log (site_id, person_id, work_date, work_time, work_details, paid_status, amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iissssi", $param_site_id, $param_person_id, $param_work_date, $param_work_time, $param_work_details, $param_paid_status, $param_amount);
            
            // Set parameters
            $param_site_id = $site_id;
            $param_person_id = $_GET['id']; // Person ID obtained from URL parameter
            $param_work_date = $work_date;
            $param_work_time = $work_time;
            $param_work_details = $work_details;
            $param_paid_status = $paid_status;
            $param_amount = $amount;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to view_workers.php after successful insertion
                header("Location: view_workers.php");
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
    <title>Add Working Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Working Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $_GET['id']; ?>" method="post">
        <div class="form-group">
            <label>Work Date:</label>
            <input type="date" name="work_date" class="form-control <?php echo (!empty($work_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $work_date; ?>">
            <span class="invalid-feedback"><?php echo $work_date_err; ?></span>
        </div>
        <div class="form-group">
            <label>Work Time:</label>
            <input type="text" name="work_time" class="form-control <?php echo (!empty($work_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $work_time; ?>">
            <span class="invalid-feedback"><?php echo $work_time_err; ?></span>
        </div>
        <div class="form-group">
            <label>Work Details:</label>
            <textarea name="work_details" class="form-control <?php echo (!empty($work_details_err)) ? 'is-invalid' : ''; ?>"><?php echo $work_details; ?></textarea>
            <span class="invalid-feedback"><?php echo $work_details_err; ?></span>
        </div>
        <div class="form-group">
            <label>Paid Status:</label>
            <select name="paid_status" class="form-control <?php echo (!empty($paid_status_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select</option>
                <option value="paid" <?php if ($paid_status === "paid") echo "selected"; ?>>Paid</option>
                <option value="not_paid" <?php if ($paid_status === "not_paid") echo "selected"; ?>>Not Paid</option>
            </select>
            <span class="invalid-feedback"><?php echo $paid_status_err; ?></span>
        </div>
        <div class="form-group">
            <label>Amount:</label>
            <input type="text" name="amount" class="form-control <?php echo (!empty($amount_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $amount; ?>">
            <span class="invalid-feedback"><?php echo $amount_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_workers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
