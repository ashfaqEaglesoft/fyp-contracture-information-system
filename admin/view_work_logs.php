<?php
session_start();
include('config.php');

// Check if the user is logged in as admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if person_id is provided in the URL
if (!isset($_GET["person_id"])) {
    header("Location: error.php");
    exit;
}

$person_id = $_GET["person_id"];

// Fetch worker details from the database
$sql_worker = "SELECT p.name AS person_name, s.site_name 
               FROM person p
               INNER JOIN site s ON p.site_id = s.site_id
               WHERE p.person_id = ?";
if ($stmt_worker = $conn->prepare($sql_worker)) {
    $stmt_worker->bind_param("i", $person_id);
    if ($stmt_worker->execute()) {
        $result_worker = $stmt_worker->get_result();
        $worker = $result_worker->fetch_assoc();
        $site_name = $worker['site_name'];
        $person_name = $worker['person_name'];
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt_worker->close();
}

// Fetch total working days and total amount from the database
$sql_logs = "SELECT COUNT(DISTINCT work_date) AS total_working_days, SUM(amount) AS total_amount 
             FROM work_log 
             WHERE person_id = ?";
if ($stmt_logs = $conn->prepare($sql_logs)) {
    $stmt_logs->bind_param("i", $person_id);
    if ($stmt_logs->execute()) {
        $result_logs = $stmt_logs->get_result();
        $logs_data = $result_logs->fetch_assoc();
        $total_working_days = $logs_data['total_working_days'];
        $total_amount = $logs_data['total_amount'];
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt_logs->close();
}

// Fetch work logs of the worker from the database
$sql_logs = "SELECT * FROM work_log WHERE person_id = ?";
if ($stmt_logs = $conn->prepare($sql_logs)) {
    $stmt_logs->bind_param("i", $person_id);
    if ($stmt_logs->execute()) {
        $result_logs = $stmt_logs->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt_logs->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Work Logs</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Work Logs - <?php echo $person_name; ?> (<?php echo $site_name; ?>)</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Work Date</th>
                <th>Work Time</th>
                <th>Work Details</th>
                <th>Paid Status</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_logs->num_rows > 0) {
                while ($row = $result_logs->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['work_date'] . "</td>";
                    echo "<td>" . $row['work_time'] . "</td>";
                    echo "<td>" . $row['work_details'] . "</td>";
                    echo "<td>" . $row['paid_status'] . "</td>";
                    echo "<td>" . $row['amount'] . "</td>";
                    echo "</tr>";
                }
                // Display total working days and total amount
                echo "<tr>";
                echo "<td colspan='4'>Total Working Days</td>";
                echo "<td>" . $total_working_days . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='4'>Total Amount</td>";
                echo "<td>" . $total_amount . "</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='5'>No work logs found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
