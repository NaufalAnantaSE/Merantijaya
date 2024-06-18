<?php
include 'components/connect.php';

if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);

    // Redirect back to placed_orders.php after deletion
    header('location: ?mod=pesanan');
    exit;
} else {
    // Handle error if delete parameter is not provided
    header('location: error_page.php');
    exit;
}
?>
