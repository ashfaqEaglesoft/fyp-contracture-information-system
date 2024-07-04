<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Define variables
$contractors = [];

// Fetch contractors data with corresponding site names
$sql = "SELECT c.*, s.site_name FROM contractor c JOIN site s ON c.site_id = s.site_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contractors[] = $row;
    }
}

// Handling contractor deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_contractor'])) {
        $contractor_id = $_POST['contractor_id'];

        // Delete contractor from database
        $sql_delete = "DELETE FROM contractor WHERE contractor_id = ?";
        if ($stmt = $conn->prepare($sql_delete)) {
            $stmt->bind_param("i", $contractor_id);
            if ($stmt->execute()) {
                header("Location: view_contractors.php");
                exit;
            } else {
                echo "Error deleting contractor.";
            }
            $stmt->close();
        } else {
            echo "Error preparing delete statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Contractors</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h2>View Contractors</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Email</th>
                <th>Site Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contractors as $contractor) : ?>
                <tr>
                    <td><?php echo $contractor['contractor_id']; ?></td>
                    <td><?php echo $contractor['name']; ?></td>
                    <td><?php echo $contractor['contact_number']; ?></td>
                    <td><?php echo $contractor['address']; ?></td>
                    <td><?php echo $contractor['email']; ?></td>
                    <td><?php echo $contractor['site_name']; ?></td>
                    <td>
                        <a href="edit_contractor.php?id=<?php echo $contractor['contractor_id']; ?>" class="btn btn-primary">Edit</a>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="contractor_id" value="<?php echo $contractor['contractor_id']; ?>">
                            <button type="submit" name="delete_contractor" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this contractor?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
