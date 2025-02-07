<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM brands WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Brand deleted successfully.";
    } else {
        echo "Error deleting brand.";
    }
}
?>
