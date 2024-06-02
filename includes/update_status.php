<?php
include '../includes/config.php';

// Check if order_id and status are provided via POST
if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Establish database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare UPDATE query
    $sql = "UPDATE `order` SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Bind parameters and execute the query
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();

        // Check if update was successful
        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "failed";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "failed";
    }

    // Close database connection
    $conn->close();
} else {
    echo "failed";
}
?>
