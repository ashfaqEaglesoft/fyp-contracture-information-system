<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Check if id is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: error.php");
    exit;
}

// Get id from the URL
$id = $_GET['id'];

// Delete the material detail entry with the specified id from the database
$sql = "DELETE FROM material_details WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $id);
    
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to view_material_details.php after successful deletion
        header("Location: contractor_dashboard.php");
        exit;
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}
?>
