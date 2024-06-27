<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<style>
    .custom-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .card-content {
        padding: 20px;
    }
    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
    }
</style>
<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id); // Sanitize the input

    $query = "SELECT * FROM komponen WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $komponen = mysqli_fetch_assoc($result);
    } else {
        echo "Failed to fetch komponen: " . mysqli_error($conn);
        exit;
    }
} else {
    // Redirect or show an error message
    echo "Gagal";
    exit;
}

// ... Your HTML and form code
?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card custom-card">
                <div class="card-content">
                    <a href="index.php" class="btn btn-primary mb-3">Back to Index</a>
                    <!-- Pre-fill the form fields with komponen for editing -->
                    <form action="update.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $komponen['id']; ?>">
                        <div class="form-group">
                            <label for="tittle">Nama Komponen:</label>
                            <input type="text" class="form-control" id="tittle" name="tittle" value="<?php echo $komponen['tittle']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="code">Kode:</label>
                            <input type="text" class="form-control" id="code" name="code" value="<?php echo $komponen['code']; ?>">
                        </div>
                        <!-- Add similar fields for other attributes -->

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
