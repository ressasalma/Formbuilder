<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
require 'db.php';

// Mengambil data dari tabel komponen
$query = "SELECT tittle, code FROM komponen";
$result = mysqli_query($conn, $query);

// Menyimpan data dalam array
$komponenData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $komponenData[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tittle'])) {
        $namaKomponen = $_POST['tittle'];
        $kodeKomponen = $_POST['code'];

        // Prepare the query using a prepared statement
        $query = "INSERT INTO komponen (tittle, code) VALUES (?,?)";
        $stmt = mysqli_prepare($conn, $query);

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "ss", $namaKomponen, $kodeKomponen);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
    $successMessage = "Data inserted successfully!";
    echo "
    <script>
        alert('Data inserted successfully.');
        window.location.href = 'index.php'; // Ganti dengan URL halaman utama Anda
    </script>";
} else {
    $errorMessage = "Error inserting data: " . mysqli_stmt_error($stmt);
}

}
}

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Drag & Drop Bootstrap Form Builder</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/tether.min.css"/>
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/form_builder.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.11.0/beautify-html.min.js"></script>

    <style>
        body {
            background-color: #f7f7f7;
        }

        .navbar {
            background-color: #f8f9fa;
        }
        .form_builder {
    padding: 0; /* Remove padding */
    box-shadow: none; /* Remove box shadow */
    /* Remove other styling properties */
}


.nav-sidebar {
    background-color: white;
    color: blue;
    border-radius: 5px; /* Tambahkan border-radius kembali di sini */
    padding: 20px;
     box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Tambahkan padding kembali di sini */
}

        .nav-sidebar a {
            color: aqua;
        }
        .nav-sidebar a:hover {
            
            color: salmon;
        }
        .form-actions {
            text-align: right;
            margin-top: 10px;
        }
        .arrow {
  border: solid black;
  border-width: 0 3px 3px 0;
  display: inline-block;
  padding: 3px;

}
.down {
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
}
.nav-flex-column-ml-3{
border: 1px  padding: 5px 10px; border-radius: 4px; background-color: #f7f7f7;
}

    </style>

</head>
<!-- Add this after the form submission check -->


<body style="background-color: #eee;">
    <div class="container">
        <!-- Add this after the form submission check -->
<?php if (isset($successMessage)) { ?>
    <script>
        window.onload = function() {
            alert("<?php echo $successMessage; ?>");
        };
    </script>
<?php } elseif (isset($errorMessage)) { ?>
    <script>
        window.onload = function() {
            alert("<?php echo $errorMessage; ?>");
        };
    </script>
<?php } ?>

        <nav class="navbar navbar-light bg-faded fixed-top" style="background-color: white; padding-bottom: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); border-bottom-left-radius: 36px; border-bottom-right-radius: 36px;">
            <div class="clearfix" >
                <div class="container" >
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <h3 class="mr-auto" style="text-align: center; font-family: bakso sapi;">Drag & Drop Form Builder</h3>
                        <button style="cursor: pointer; display: none; border-radius: 36px;" class="btn btn-info export_html mt-2 ml-2">Export HTML</button>
                        <button style="cursor: pointer; display: none; border-radius: 36px;" class="btn btn-primary download_html mt-2 ml-2">Download HTML</button>&nbsp;
                        <button style="cursor: pointer; border-radius: 36px;" class="btn btn-danger add_komponen mt-2 ml-2" data-toggle="modal" data-target="#tambahKomponenModal">Tambah Komponen</button>
                        <a  style="cursor: pointer; border-radius: 50px;" href="manage_component.php" class="btn btn-info mt-2 ml-2">
                            <i class="fa fa-cog"></i>
                        </a>
                        <div class="nav-item dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
                                <span>
                                    <div class="d-flex badge-pill">
                                        <span class="fa fa-user mr-2"><b> <?php echo $_SESSION['username']; ?></b></span>
                                        <span class="fa fa-angle-down ml-2"></span>
                                    </div>
                                </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="account_settings" style="">
                                <a class="dropdown-item" data-toggle="modal" data-target="#userModal"><i
                                        class="fa fa-cog" ></i> Manage Account</a>
                                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <br/><br><br><br>
       <div class="clearfix"></div>
        <div class="form_builder" style="margin-top: 25px">
            <div class="row">
                    <!-- Modal Tambah Komponen -->
                     <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="update_user.php" method="post">
                                    <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                                    <div class="form-group">
                                        <label for="newUsername">New Username:</label>
                                        <input type="text" class="form-control" id="newUsername" name="newUsername" value="<?php echo $_SESSION['username']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password:</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                                    </div>
                                    <!-- Add other form fields for additional attributes -->
<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
                                  
    <div class="modal fade" id="tambahKomponenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Komponen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="namaKomponen">Nama Komponen</label>
                            <input type="text" class="form-control" id="namaKomponen" name="tittle" placeholder="Masukkan Nama Komponen">
                        </div>
                        <div class="form-group">
                            <label for="namaKomponen">Kode Komponen</label>
                            <textarea class="form-control" class="form-control" id="namaKomponen" name="code" placeholder="Masukkan Kode Komponen"></textarea>
                        </div>
                        <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
                <div class="col-sm-3">
                    <nav class="nav-sidebar">
                        <ul class="nav">
                             <li>
                                <a data-toggle="collapse" href="#group1" role="button" aria-expanded="false" aria-controls="group1" >
                                    Input <i class="arrow down"></i>
                                </a>
                                <div class="collapse" id="group1">
                                    <ul class="nav flex-column ml-3">
                                        <li>
                                            <a data-toggle="collapse" href="#subsection1" role="button" aria-expanded="false" aria-controls="subsection1">
                                                Basic Input <i class="arrow down"></i>
                                            </a>
                                            <div class="collapse" id="subsection1">
                                                <ul class="nav-flex-column-ml-3" style="">
                                                    <li class="form_bal_header">
                                                        <a href="javascript:;">Header <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_search">
                                                        <a href="javascript:;">Search <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_hidden">
                                                        <a href="javascript:;">Hidden <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_fileuploader">
                                                        <a href="javascript:;">File Uploader <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                     <li class="form_bal_email">
                                                        <a href="javascript:;">Email <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_telepon">
                                                        <a href="javascript:;">Telepon <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_number">
                                                        <a href="javascript:;">Number <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_password">
                                                        <a href="javascript:;">Password <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_textfield">
                                                        <a href="javascript:;">Text Field <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_textarea">
                                                        <a href="javascript:;">Text Area <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_image">
                                                        <a href="javascript:;">Image <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <a data-toggle="collapse" href="#subsection2" role="button" aria-expanded="false" aria-controls="subsection2">
                                                Advanced Input <i class="arrow down"></i>
                                            </a>
                                            <div class="collapse" id="subsection2">
                                                <ul class="nav-flex-column-ml-3">
                                                    <!-- <li class="form_bal_range">
                                                        <a href="javascript:;">Range <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li> -->
                                                    <li class="form_bal_showimage">
                                                        <a href="javascript:;">Show Image <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_color">
                                                        <a href="javascript:;">Color <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                    <li class="form_bal_url">
                                                        <a href="javascript:;">Url <i class="fa fa-plus-circle pull-right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                             <li>
                                <a data-toggle="collapse" href="#group2" role="button" aria-expanded="false" aria-controls="group2">
                                    Date <i class="arrow down"></i>
                                </a>
                                <div class="collapse" id="group2">
                                    <ul class="nav-flex-column-ml-3">
                                        <li class="form_bal_week">
                                            <a href="javascript:;">Week <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_date">
                                            <a href="javascript:;">Date <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_datetime">
                                            <a href="javascript:;">Date Time <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_time">
                                            <a href="javascript:;">Time <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_month">
                                            <a href="javascript:;">Month <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                             <li>
                                <a data-toggle="collapse" href="#group3" role="button" aria-expanded="false" aria-controls="group3">
                                    Checkbox <i class="arrow down"></i>
                                </a>
                                <div class="collapse" id="group3">
                                    <ul class="nav-flex-column-ml-3">
                                        <li class="form_bal_select">
                                            <a href="javascript:;">Select <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        
                                        <li class="form_bal_radio">
                                            <a href="javascript:;">Radio Button <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_radioinline">
                                            <a href="javascript:;">Radio Button Inline<i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_checkbox">
                                            <a href="javascript:;">Checkbox <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_checkboxinline">
                                            <a href="javascript:;">Checkbox Inline <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a data-toggle="collapse" href="#group4" role="button" aria-expanded="false" aria-controls="group4">
                                    Button <i class="arrow down"></i>
                                </a>
                                <div class="collapse" id="group4">
                                    <ul class="nav-flex-column-ml-3">
                                        <li class="form_bal_button">
                                            <a href="javascript:;">Button <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                        <li class="form_bal_reset">
                                        <a href="javascript:;">Reset <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                            <a data-toggle="collapse" href="#group5" role="button" aria-expanded="false" aria-controls="group5">
                                Milik Kamu <i class="arrow down"></i>
                            </a>
                            <div class="collapse" id="group5">
                                <ul class="nav flex-column ml-3">
                                    <?php foreach ($komponenData as $komponen) { ?>
                                        <li class="form_bal_<?php echo $komponen['tittle']; ?>">
                                            <a href="javascript:;"><?php echo $komponen['tittle']; ?> <i class="fa fa-plus-circle pull-right"></i></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </li>
   
                        </ul>
                    </nav>
                </div>
                <div class="col-md-4 bal_builder">
                    <div class="form_builder_area"></div>
                </div>
                <div class="col-md-5">
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="preview"></div>
                            <div style="display: none" class="form-group plain_html"><textarea rows="50" class="form-control"></textarea></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  <!--   <script src="js/form_builder.js"></script> -->
  <script type="text/javascript">
      $(document).ready(function () {
     <?php foreach ($komponenData as $komponen) { ?>
    $(".form_bal_<?php echo $komponen['tittle']; ?>").draggable({
        helper: function () {
            return get<?php echo $komponen['tittle']; ?>FieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    <?php } ?>

    $(".form_bal_textfield").draggable({
        helper: function () {
            return getTextFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_search").draggable({
        helper: function () {
            return getSearchFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    // $(".form_bal_range").draggable({
    //     helper: function () {
    //         return getRangeFieldHTML();
    //     },
    //     connectToSortable: ".form_builder_area"
    // });
    $(".form_bal_color").draggable({
        helper: function () {
            return getColorFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });

    $(".form_bal_month").draggable({
        helper: function () {
            return getMonthFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_hidden").draggable({
        helper: function () {
            return getHiddenFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_telepon").draggable({
        helper: function () {
            return getTeleponFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_image").draggable({
        helper: function () {
            return getImageFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_showimage").draggable({
        helper: function () {
            return getShowImageFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_url").draggable({
        helper: function () {
            return getUrlFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_reset").draggable({
        helper: function () {
            return getResetFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_header").draggable({
        helper: function () {
            return getHeaderFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_textarea").draggable({
        helper: function () {
            return getTextAreaFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_fileuploader").draggable({
        helper: function () {
            return getFileUploaderFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_number").draggable({
        helper: function () {
            return getNumberFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_email").draggable({
        helper: function () {
            return getEmailFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_password").draggable({
        helper: function () {
            return getPasswordFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_date").draggable({
        helper: function () {
            return getDateFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_datetime").draggable({
        helper: function () {
            return getDateTimeFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_time").draggable({
        helper: function () {
            return getTimeFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_button").draggable({
        helper: function () {
            return getButtonFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_select").draggable({
        helper: function () {
            return getSelectFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    
    $(".form_bal_radio").draggable({
        helper: function () {
            return getRadioFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_radioinline").draggable({
        helper: function () {
            return getRadioInlineFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_week").draggable({
        helper: function () {
            return getWeekFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_checkbox").draggable({
        helper: function () {
            return getCheckboxFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    $(".form_bal_checkboxinline").draggable({
        helper: function () {
            return getCheckboxInlineFieldHTML();
        },
        connectToSortable: ".form_builder_area"
    });
    <?php

    ?>

    $(".form_builder_area").sortable({
        cursor: 'move',
        placeholder: 'placeholder',
        start: function (e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        },
        stop: function (ev, ui) {
            getPreview();
        }
    });
    $(".form_builder_area").disableSelection();

    <?php foreach ($komponenData as $komponen) { ?>
        function get<?php echo $komponen['tittle']; ?>FieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="<?php echo $komponen['tittle']; ?>" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div></label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
       <?php } ?>
    function getButtonFieldHTML() {
    var field = generateField();
    
    var classOptions = [
        "btn btn-primary",
        "btn btn-secondary",
        "btn btn-success",
        "btn btn-danger",
        "btn btn-warning",
        "btn btn-info",
        "btn btn-light",
        "btn btn-dark"
        // Add more class options as needed
    ];
    
    var classSelectOptions = '';
    for (var i = 0; i < classOptions.length; i++) {
        classSelectOptions += '<option value="' + classOptions[i] + '">' + classOptions[i] + '</option>';
    }
    
    var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="button" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><select name="class_' + field + '" class="form-control form_input_button_class" data-field="' + field + '">' + classSelectOptions + '</select></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="value_' + field + '" data-field="' + field + '" class="form-control form_input_button_value" value="Submit" placeholder="Value"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div></div>';
    
    return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
}
    function getTextFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="text" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getSearchFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="search" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getHiddenFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="hidden" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }

    function getColorFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="color" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getMonthFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="month" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getWeekFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="week" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getTeleponFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="telepon" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    // function getRangeFieldHTML() {
    //     var field = generateField();
    //     var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="range" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
    //     return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    // }
    function getUrlFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="url" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
  function getImageFieldHTML() {
    var field = generateField();
    var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="image" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div><br><div class="col-md-12"><h6>Pilih salah satu:</h6><div class="form-group"><input type="text" name="src_' + field + '" class="form-control form_input_src" placeholder="Image Source (URL)"/></div></div></div>';
    return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
}
function getShowImageFieldHTML() {
    var field = generateField();
    var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="showimage" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div><br><div class="col-md-12"><h6>Pilih salah satu:</h6><div class="form-group"><input type="text" name="src_' + field + '" class="form-control form_input_src" placeholder="Image Source (URL)"/></div></div><div class="col-md-12"><div class="form-group"><input type="number" name="width_' + field + '" class="form-control form_input_width" placeholder="Image Width"/></div></div><div class="col-md-12"><div class="form-group"><input type="number" name="height_' + field + '" class="form-control form_input_height" placeholder="Image Height"/></div></div></div>';
    return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
}


    function getHeaderFieldHTML() {
    var field = generateField();
    var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="header" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-group"><label>Select Heading Level:</label><select class="form-control form_input_heading" name="heading_' + field + '"><option value="h1">H1</option><option value="h2">H2</option><option value="h3">H3</option><option value="h4">H4</option><option value="h5">H5</option><option value="h6">H6</option></select></div></div></div>';
    return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
}
    function getResetFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="reset" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="class_' + field + '" class="form-control form_input_button_class" placeholder="Class" value="btn btn-danger" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="value_' + field + '" data-field="' + field + '" class="form-control form_input_button_value" value="Reset" placeholder="Value"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getFileUploaderFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="file" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getNumberFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="number" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getEmailFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="email" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getPasswordFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="password" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getDateFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="date" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getDateTimeFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="datetime" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getTimeFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="time" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getTextAreaFieldHTML() {
        var field = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div></div><hr/><div class="row li_row form_output" data-type="textarea" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="placeholder_' + field + '" data-field="' + field + '" class="form-control form_input_placeholder" placeholder="Placeholder"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-check"><label class="form-check-label"><input data-field="' + field + '" type="checkbox" class="form-check-input form_input_req">Required</label></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getSelectFieldHTML() {
        var field = generateField();
        var opt1 = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div><hr/><div class="row li_row form_output" data-type="select" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-group"><select name="select_' + field + '" class="form-control"><option data-opt="' + opt1 + '" value="Value">Option</option></select></div></div></div><div class="row li_row"><div class="col-md-12"><div class="field_extra_info_' + field + '"><div data-field="' + field + '" class="row select_row_' + field + '" data-opt="' + opt1 + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="s_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="s_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_select" data-field="' + field + '"></i></div></div></div></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getRadioFieldHTML() {
        var field = generateField();
        var opt1 = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div><hr/><div class="row li_row form_output" data-type="radio" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name (Harus diisi)"/></div></div><div class="col-md-12"><div class="form-group"><div class="mt-radio-list radio_list_' + field + '"><label class="mt-radio mt-radio-outline"><input data-opt="' + opt1 + '" type="radio" name="radio_' + field + '" value="Value"> <p class="r_opt_name_' + opt1 + '">Option</p><span></span></label></div></div></div></div><div class="row li_row"><div class="col-md-12"><div class="field_extra_info_' + field + '"><div data-field="' + field + '" class="row radio_row_' + field + '" data-opt="' + opt1 + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="r_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="r_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_radio" data-field="' + field + '"></i></div></div></div></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getRadioInlineFieldHTML() {
        var field = generateField();
        var opt1 = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div><hr/><div class="row li_row form_output" data-type="radioinline" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name (Harus diisi)"/></div></div><div class="col-md-12"><div class="form-group"><div class="mt-radio-list radio_list_' + field + '"><label class="mt-radio mt-radio-outline"><input data-opt="' + opt1 + '" type="radio" name="radio_' + field + '" value="Value"> <p class="r_opt_name_' + opt1 + '">Option</p><span></span></label></div></div></div></div><div class="row li_row"><div class="col-md-12"><div class="field_extra_info_' + field + '"><div data-field="' + field + '" class="row radio_row_' + field + '" data-opt="' + opt1 + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="r_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="r_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_radio" data-field="' + field + '"></i></div></div></div></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getCheckboxFieldHTML() {
        var field = generateField();
        var opt1 = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div><hr/><div class="row li_row form_output" data-type="checkbox" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-group"><div class="mt-checkbox-list checkbox_list_' + field + '"><label class="mt-checkbox mt-checkbox-outline"><input data-opt="' + opt1 + '" type="checkbox" name="checkbox_' + field + '" value="Value"> <p class="c_opt_name_' + opt1 + '">Option</p><span></span></label></div></div></div></div><div class="row li_row"><div class="col-md-12"><div class="field_extra_info_' + field + '"><div data-field="' + field + '" class="row checkbox_row_' + field + '" data-opt="' + opt1 + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="c_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="c_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_checkbox" data-field="' + field + '"></i></div></div></div></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }
    function getCheckboxInlineFieldHTML() {
        var field = generateField();
        var opt1 = generateField();
        var html = '<div class="all_div"><div class="row li_row"><div class="col-md-12"><button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right" data-field="' + field + '"><i class="fa fa-times"></i></button></div></div><hr/><div class="row li_row form_output" data-type="checkboxinline" data-field="' + field + '"><div class="col-md-12"><div class="form-group"><input type="text" name="label_' + field + '" class="form-control form_input_label" value="Label" data-field="' + field + '"/></div></div><div class="col-md-12"><div class="form-group"><input type="text" name="text_' + field + '" class="form-control form_input_name" placeholder="Name"/></div></div><div class="col-md-12"><div class="form-group"><div class="mt-checkbox-list checkbox_list_' + field + '"><label class="mt-checkbox mt-checkbox-outline"><input data-opt="' + opt1 + '" type="checkbox" name="checkbox_' + field + '" value="Value"> <p class="c_opt_name_' + opt1 + '">Option</p><span></span></label></div></div></div></div><div class="row li_row"><div class="col-md-12"><div class="field_extra_info_' + field + '"><div data-field="' + field + '" class="row checkbox_row_' + field + '" data-opt="' + opt1 + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="c_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="c_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_checkbox" data-field="' + field + '"></i></div></div></div></div></div></div>';
        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html);
    }

    $(document).on('click', '.add_more_select', function () {
        $(this).closest('.form_builder_field').css('height', 'auto');
        var field = $(this).attr('data-field');
        var option = generateField();
        $('.field_extra_info_' + field).append('<div data-field="' + field + '" class="row select_row_' + field + '" data-opt="' + option + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="s_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="s_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_select" data-field="' + field + '"></i><i class="margin-top-5 margin-left-5 fa fa-times-circle default_red fa-2x remove_more_select" data-field="' + field + '"></i></div></div>');
        var options = '';
        $('.select_row_' + field).each(function () {
            var opt = $(this).find('.s_opt').val();
            var val = $(this).find('.s_val').val();
            var s_opt = $(this).attr('data-opt');
            options += '<option data-opt="' + s_opt + '" value="' + val + '">' + opt + '</option>';
        });
        $('select[name=select_' + field + ']').html(options);
        getPreview();
    });
    $(document).on('click', '.add_more_radio', function () {
        $(this).closest('.form_builder_field').css('height', 'auto');
        var field = $(this).attr('data-field');
        var option = generateField();
        $('.field_extra_info_' + field).append('<div data-opt="' + option + '" data-field="' + field + '" class="row radio_row_' + field + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="r_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="r_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_radio" data-field="' + field + '"></i><i class="margin-top-5 margin-left-5 fa fa-times-circle default_red fa-2x remove_more_radio" data-field="' + field + '"></i></div></div>');
        var options = '';
        $('.radio_row_' + field).each(function () {
            var opt = $(this).find('.r_opt').val();
            var val = $(this).find('.r_val').val();
            var s_opt = $(this).attr('data-opt');
            options += '<label class="mt-radio mt-radio-outline"><input data-opt="' + s_opt + '" type="radio" name="radio_' + field + '" value="' + val + '"> <p class="r_opt_name_' + s_opt + '">' + opt + '</p><span></span></label>';
        });
        $('.radio_list_' + field).html(options);
        getPreview();
    });
    $(document).on('click', '.add_more_checkbox', function () {
        $(this).closest('.form_builder_field').css('height', 'auto');
        var field = $(this).attr('data-field');
        var option = generateField();
        $('.field_extra_info_' + field).append('<div data-opt="' + option + '" data-field="' + field + '" class="row checkbox_row_' + field + '"><div class="col-md-4"><div class="form-group"><input type="text" value="Option" class="c_opt form-control"/></div></div><div class="col-md-4"><div class="form-group"><input type="text" value="Value" class="c_val form-control"/></div></div><div class="col-md-4"><i class="margin-top-5 fa fa-plus-circle fa-2x default_blue add_more_checkbox" data-field="' + field + '"></i><i class="margin-top-5 margin-left-5 fa fa-times-circle default_red fa-2x remove_more_checkbox" data-field="' + field + '"></i></div></div>');
        var options = '';
        $('.checkbox_row_' + field).each(function () {
            var opt = $(this).find('.c_opt').val();
            var val = $(this).find('.c_val').val();
            var s_opt = $(this).attr('data-opt');
            options += '<label class="mt-checkbox mt-checkbox-outline"><input data-opt="' + s_opt + '" name="checkbox_' + field + '" type="checkbox" value="' + val + '"> <p class="c_opt_name_' + s_opt + '">' + opt + '</p><span></span></label>';
        });
        $('.checkbox_list_' + field).html(options);
        getPreview();
    });
    $(document).on('keyup', '.s_opt', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('select[name=select_' + field + ']').find('option[data-opt=' + option + ']').html(op_val);
        getPreview();
    });
    $(document).on('keyup', '.s_val', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('select[name=select_' + field + ']').find('option[data-opt=' + option + ']').val(op_val);
        getPreview();
    });
    $(document).on('keyup', '.r_opt', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('.radio_list_' + field).find('.r_opt_name_' + option).html(op_val);
        getPreview();
    });
    $(document).on('keyup', '.r_val', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('.radio_list_' + field).find('input[data-opt=' + option + ']').val(op_val);
        getPreview();
    });
    $(document).on('keyup', '.c_opt', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('.checkbox_list_' + field).find('.c_opt_name_' + option).html(op_val);
        getPreview();
    });
    $(document).on('keyup', '.c_val', function () {
        var op_val = $(this).val();
        var field = $(this).closest('.row').attr('data-field');
        var option = $(this).closest('.row').attr('data-opt');
        $('.checkbox_list_' + field).find('input[data-opt=' + option + ']').val(op_val);
        getPreview();
    });
    $(document).on('click', '.edit_bal_textfield', function () {
        var field = $(this).attr('data-field');
        var el = $('.field_extra_info_' + field);
        el.html('<div class="form-group"><input type="text" name="label_' + field + '" class="form-control" placeholder="Enter Text Field Label"/></div><div class="mt-checkbox-list"><label class="mt-checkbox mt-checkbox-outline"><input name="req_' + field + '" type="checkbox" value="1"> Required<span></span></label></div>');
        getPreview();
    });
    $(document).on('click', '.remove_bal_field', function (e) {
        e.preventDefault();
        var field = $(this).attr('data-field');
        $(this).closest('.li_' + field).hide('400', function () {
            $(this).remove();
            getPreview();
        });
    });
    $(document).on('click', '.remove_more_select', function () {
        var field = $(this).attr('data-field');
        $(this).closest('.select_row_' + field).hide('400', function () {
            $(this).remove();
            var options = '';
            $('.select_row_' + field).each(function () {
                var opt = $(this).find('.s_opt').val();
                var val = $(this).find('.s_val').val();
                var s_opt = $(this).attr('data-opt');
                options += '<option data-opt="' + s_opt + '" value="' + val + '">' + opt + '</option>';
            });
            $('select[name=select_' + field + ']').html(options);
            getPreview();
        });
    });
    $(document).on('click', '.remove_more_radio', function () {
        var field = $(this).attr('data-field');
        $(this).closest('.radio_row_' + field).hide('400', function () {
            $(this).remove();
            var options = '';
            $('.radio_row_' + field).each(function () {
                var opt = $(this).find('.r_opt').val();
                var val = $(this).find('.r_val').val();
                var s_opt = $(this).attr('data-opt');
                options += '<label class="mt-radio mt-radio-outline"><input data-opt="' + s_opt + '" type="radio" name="radio_' + field + '" value="' + val + '"> <p class="r_opt_name_' + s_opt + '">' + opt + '</p><span></span></label>';
            });
            $('.radio_list_' + field).html(options);
            getPreview();
        });
    });
    $(document).on('click', '.remove_more_checkbox', function () {
        var field = $(this).attr('data-field');
        $(this).closest('.checkbox_row_' + field).hide('400', function () {
            $(this).remove();
            var options = '';
            $('.checkbox_row_' + field).each(function () {
                var opt = $(this).find('.c_opt').val();
                var val = $(this).find('.c_val').val();
                var s_opt = $(this).attr('data-opt');
                options += '<label class="mt-checkbox mt-checkbox-outline"><input data-opt="' + s_opt + '" name="checkbox_' + field + '" type="checkbox" value="' + val + '"> <p class="r_opt_name_' + s_opt + '">' + opt + '</p><span></span></label>';
            });
            $('.checkbox_list_' + field).html(options);
            getPreview();
        });
    });

    $(document).on('keyup', '.form_input_button_class', function () {
        getPreview();
    });
    $(document).on('keyup', '.form_input_button_value', function () {
        getPreview();
    });
    $(document).on('change', '.form_input_req', function () {
        getPreview();
    });
    $(document).on('keyup', '.form_input_placeholder', function () {
        getPreview();
    });
    $(document).on('keyup', '.form_input_label', function () {
        getPreview();
    });
    $(document).on('keyup', '.form_input_name', function () {
        getPreview();
    });
    function generateField() {
        return Math.floor(Math.random() * (100000 - 1 + 1) + 57);
    }
    function getPreview(plain_html = '') {
        var el = $('.form_builder_area .form_output');
        var html = '';
        el.each(function () {
            var data_type = $(this).attr('data-type');
            //var field = $(this).attr('data-field');
            var label = $(this).find('.form_input_label').val();
            var name = $(this).find('.form_input_name').val();
            if (data_type === 'text') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="text" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
           if (data_type === 'header') {
    var placeholder = $(this).find('.form_input_placeholder').val();
    var checkbox = $(this).find('.form-check-input');
    var required = '';

    if (checkbox.is(':checked')) {
        required = 'required';
    }

    var headingLevel = $(this).find('.form_input_heading').val();
    html += '<div class="form-group"><' + headingLevel + ' class="control-label">' + label + '</' + headingLevel + '></div>';
}
            <?php foreach ($komponenData as $komponen) { ?>
                if (data_type === '<?php echo $komponen['tittle'] ?>') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<?php echo $komponen['code'] ?></div>';
            }
        <?php } ?>
            if (data_type === 'file') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="file" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'reset') {
                var btn_class = $(this).find('.form_input_button_class').val();
                var btn_value = $(this).find('.form_input_button_value').val();
                html += '<button name="' + name + '" type="reset" class="' + btn_class + '">' + btn_value + '</button>';
            }
            if (data_type === 'color') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="color" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            if (data_type === 'checkboxinline') {  // Add this part for checkboxes
    var option_html = '';
    $(this).find('.mt-checkbox').each(function () {
        var option = $(this).find('p').html();
        var value = $(this).find('input[type=checkbox]').val();
        option_html += '<div class="form-check form-check-inline"><label class="form-check-label">&nbsp;<input type="checkbox" class="form-check-input" name="' + name + '" value="' + value + '">' + option + '</label></div>';
    });
    html += '<div class="form-group"><label class="control-label">' + label + '</label>' + option_html + '</div>';
}
            if (data_type === 'month') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="month" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            // if (data_type === 'range') {
            //     var placeholder = $(this).find('.form_input_placeholder').val();
            //     var checkbox = $(this).find('.form-check-input');
            //     var required = '';
            //     if (checkbox.is(':checked')) {
            //         required = 'required';
            //     }
            //     html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="range" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            // }
            if (data_type === 'url') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="url" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            if (data_type === 'week') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="week" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            if (data_type === 'telepon') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="tel" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            if (data_type === 'hidden') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><input type="hidden" name="'+name+'"placeholder="'+placeholder+'"class=form-control"'+required+'/></div>';
            }
            if (data_type === 'number') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="number" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
           if (data_type === 'showimage') {
    var placeholder = $(this).find('.form_input_placeholder').val();
    var checkbox = $(this).find('.form-check-input');
    var required = '';
    if (checkbox.is(':checked')) {
        required = 'required';
    }

    var srcInput = $(this).find('.form_input_src');
    var src = srcInput.val(); // Get the user-input src

    var widthInput = $(this).find('.form_input_width');
    var width = widthInput.val(); // Get the user-input width

    var heightInput = $(this).find('.form_input_height');
    var height = heightInput.val(); // Get the user-input height

    var imgTag = '<img src="' + src + '" alt="' + label + '" class="form-control" ' + required;
    if (width) {
        imgTag += ' width="' + width + 'px"';
    }
    if (height) {
        imgTag += ' height="' + height + 'px"';
    }
    imgTag += '/>';

    html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;' + imgTag + '</div>';
}
if (data_type === 'image') {
    var placeholder = $(this).find('.form_input_placeholder').val();
    var checkbox = $(this).find('.form-check-input');
    var required = '';
    if (checkbox.is(':checked')) {
        required = 'required';
    }

    var srcInput = $(this).find('.form_input_src');
    var src = srcInput.val(); // Get the user-input src

    html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="image" src="' + src + '"  class="form-control" ' + required + '/></div>';
}

            if (data_type === 'search') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="search" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'email') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="email" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'password') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="password" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'textarea') {
                var placeholder = $(this).find('.form_input_placeholder').val();
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<textarea rows="5" name="' + name + '" placeholder="' + placeholder + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'date') {
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="date" name="' + name + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'datetime') {
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="datetime-local" name="' + name + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'time') {
                var checkbox = $(this).find('.form-check-input');
                var required = '';
                if (checkbox.is(':checked')) {
                    required = 'required';
                }
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<input type="time" name="' + name + '" class="form-control" ' + required + '/></div>';
            }
            if (data_type === 'button') {
                var btn_class = $(this).find('.form_input_button_class').val();
                var btn_value = $(this).find('.form_input_button_value').val();
                html += '<button name="' + name + '" type="submit" class="' + btn_class + '">' + btn_value + '</button>';
            }
            if (data_type === 'select') {
                var option_html = '';
                $(this).find('select option').each(function () {
                    var option = $(this).html();
                    var value = $(this).val();
                    option_html += '<option value="' + value + '">' + option + '</option>';
                });
                html += '<div class="form-group"><label class="control-label">' + label + '</label>&nbsp;<select class="form-control" name="' + name + '">' + option_html + '</select></div>';
            }
            if (data_type === 'radio') {
                var option_html = '';
                $(this).find('.mt-radio').each(function () {
                    var option = $(this).find('p').html();
                    var value = $(this).find('input[type=radio]').val();
                    option_html += '<div class="form-check"><label class="form-check-label">&nbsp;<input type="radio" class="form-check-input" name="' + name + '" value="' + value + '">' + option + '</label></div>';
                });
                html += '<div class="form-group"><label class="control-label">' + label + '</label>' + option_html + '</div>';
            }
            if (data_type === 'radioinline') {
    var option_html = '';
    $(this).find('.mt-radio').each(function () {
        var option = $(this).find('p').html();
        var value = $(this).find('input[type=radio]').val();
        option_html += '<div class="form-check form-check-inline"><label class="form-check-label">&nbsp;<input type="radio" class="form-check-input" name="' + name + '" value="' + value + '">' + option + '</label></div>';
    });
    html += '<div class="form-group"><label class="control-label">' + label + '</label>' + option_html + '</div>';
}

            if (data_type === 'checkbox') {
                var option_html = '';
                $(this).find('.mt-checkbox').each(function () {
                    var option = $(this).find('p').html();
                    var value = $(this).find('input[type=checkbox]').val();
                    option_html += '<div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input" name="' + name + '[]" value="' + value + '">' + option + '</label></div>';
                });
                html += '<div class="form-group"><label class="control-label">' + label + '</label>' + option_html + '</div>';
            }
        });
        if (html.length) {
      $(".export_html").show();
      // $(".download_html").show();
    } else {
      $(".export_html").hide();
      $(".download_html").hide();
    }
    if (plain_html === "html") {
      $(".preview").hide();
      $(".plain_html").show().find("textarea").val(html);
    } else {
      $(".plain_html").hide();
      $(".preview").html(html).show();
    }
  }
  // $(document).on("click", ".export_html", function () {
  //   getPreview("html");
  //   $('.download_html').show();
  // });

function downloadHtmlFile(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

$(document).on("click", ".export_html", function () {
    getPreview("html");
    $('.download_html').show();
});

$(document).on("click", ".export_html", function () {
    getPreview("html");
    $('.download_html').show();
});

$(document).on("click", ".download_html", function () {
    var formAction = prompt("Masukkan action form:", "");
    
    var methodOptions = ["post", "get"];
    var selectedMethodIndex = prompt("Pilih method form:\n1. POST\n2. GET", "1");
    var formMethod = methodOptions[parseInt(selectedMethodIndex) - 1];

    var formEnctype = prompt("Masukkan enctype form:", "application/x-www-form-urlencoded");

    var previewHtml =`<!DOCTYPE html>
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
                            <form action="${formAction}" method="${formMethod}" enctype="${formEnctype}">
                                ${$(".preview").html()}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>`;

  var customFileName = prompt("Masukkan nama file:", "form.html");
    if (customFileName) {
        downloadHtmlFile(customFileName, previewHtml, formAction, formMethod, formEnctype);
    }
});

function downloadHtmlFile(filename, content, action, method, enctype) {
    if (action) {
        content = content.replace('<form', `<form action="${action}"`);
    }
    if (method) {
        content = content.replace('<form', `<form method="${method}"`);
    }
    if (enctype) {
        content = content.replace('<form', `<form enctype="${enctype}"`);
    }

    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/html;charset=utf-8,' + encodeURIComponent(content));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}


});
  </script>
</body>
</html>
