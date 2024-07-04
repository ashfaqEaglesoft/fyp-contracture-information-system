<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['log_id'])) {
        $log_id = $_POST['log_id'];
        
        // Delete work log entry from the database
        $sql_delete_log = "DELETE FROM work_log WHERE log_id = ?";
        if ($stmt_delete_log = $conn->prepare($sql_delete_log)) {
            // Bind log_id to the prepared statement as parameter
            $stmt_delete_log->bind_param("i", $log_id);
            
            // Attempt to execute the prepared statement
            if ($stmt_delete_log->execute()) {
                // Redirect back to view_work_log.php after successful deletion
                header("Location: view_workers.php" );

                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_delete_log->close();
        }
    }
}
?>
