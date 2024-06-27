<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id); // Sanitize the input

    $query = "DELETE FROM komponen WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Tampilkan konfirmasi di sini
        echo "
        <script>
            var confirmed = window.confirm('Data has been successfully deleted. Do you want to go back to the main page?');
            if (confirmed) {
                window.location.href = 'index.php';
            } else {
                // Redirect or handle as needed
            }
        </script>";
    } else {
        // Penanganan kesalahan
        echo "Deletion failed: " . $stmt->error;
    }

    // Menutup pernyataan dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
