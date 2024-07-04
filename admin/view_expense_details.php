<?php
session_start();
include('config.php');

// Check if the user is logged in as  admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if site_id is in  URL
if (!isset($_GET["site_id"])) {
    header("Location: error.php");
    exit;
}

$site_id = $_GET["site_id"];

// Fetch expense details for the site from the database
$sql_expenses = "SELECT * FROM expense WHERE site_id = ?";
if ($stmt_expenses = $conn->prepare($sql_expenses)) {
    $stmt_expenses->bind_param("i", $site_id);
    if ($stmt_expenses->execute()) {
        $result_expenses = $stmt_expenses->get_result();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt_expenses->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>Expense Details</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Expense ID</th>
                <th>Material</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Other Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_expenses->num_rows > 0) {
                while ($row = $result_expenses->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['expense_id'] . "</td>";
                    echo "<td>" . $row['material'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['cost'] . "</td>";
                    echo "<td>" . $row['other_details'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No expenses found for this site</td></tr>";
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
