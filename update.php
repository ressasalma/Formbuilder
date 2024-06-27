<?php
// Pastikan Anda memiliki koneksi ke database di dalam file db.php
include 'db.php';

// Memastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan variabel yang diterima tidak kosong
    if (isset($_POST['id'], $_POST['tittle'], $_POST['code'])) {
        $id = $_POST['id'];
        $nama = $_POST['tittle'];
        $kode = $_POST['code'];
        // Anda dapat menambahkan validasi atau pembersihan data di sini

        // Perintah SQL untuk melakukan pembaruan data
        $query = "UPDATE komponen SET tittle=?, code=? WHERE id=?";

        // Mempersiapkan pernyataan
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nama, $kode, $id);

        // Menjalankan pernyataan
        if ($stmt->execute()) {
            // Tampilkan konfirmasi di sini
            echo "
            <script>
                var confirmed = window.confirm('Data has been successfully updated. Do you want to go back to the main page?');
                if (confirmed) {
                    window.location.href = 'index.php';
                } else {
                    // Redirect or handle as needed
                }
            </script>";
        } else {
            // Penanganan kesalahan
            echo "Update failed: " . $stmt->error;
        }

        // Menutup pernyataan dan koneksi
        $stmt->close();
        $conn->close();
    } else {
        echo "One or more fields are missing.";
    }
} else {
    // Redirect atau tampilkan pesan kesalahan
    echo "Invalid request.";
}
?>
