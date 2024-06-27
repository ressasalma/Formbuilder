<?php 
include 'db.php';
$query = "SELECT tittle , id FROM komponen";
$result = mysqli_query($conn, $query);

// Menyimpan data dalam array
$komponenData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $komponenData[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form</title>
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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card custom-card">
                    <div class="card-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Komponen</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($komponenData as $komponen) { ?>
                                    <tr>
                                        <td><?php echo $komponen['tittle']; ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $komponen['id']; ?>" class="btn btn-warning">Edit</a>
                                            <a href="delete.php?id=<?php echo $komponen['id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
