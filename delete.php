<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id); // Sanitize the input

    // Using prepared statement to delete the data
    $query = "DELETE FROM komponen WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    // Display JavaScript confirmation before deleting
    echo '<script>
        var confirmDelete = confirm("Are you sure you want to delete this data?");
        if (confirmDelete) {
            window.location.href = "delete_confirm.php?id=' . $id . '";
        } else {
            window.location.href = "index.php"; // Redirect if canceled
        }
    </script>';
} else {
    // Redirect or show an error message
    echo "Failed";
}
?>
