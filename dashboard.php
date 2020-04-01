<?php
include 'database.php';
if (isset($_SESSION["username"])) {
    $s1 = "SELECT * FROM login WHERE (Username = '" . $_SESSION["username"] . "' OR Email='" . $_SESSION["username"] . "')" . " AND Password='" . $_SESSION["password"] . "';";
    $result = $con->query($s1);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows != 1) {
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}
$s2 = "SELECT * FROM user WHERE Username = '" . $_SESSION["username"] . "' OR Email = '" . $_SESSION["username"] . "';";
$result = $con->query($s2);
$row = mysqli_fetch_row($result);
if ($row[7] == "") {
    $img = "resources/img/profile_img.jpg";
} else {
    $img = $row[7];
}
$s4 = "SELECT * from s_c_details;";
$result_c = $con->query($s4);
if (isset($_POST["submit"])) {
    if (isset($_FILES['fileToUpload'])) {
        $target_path = "resources/img/profile/";
        $target_path = $target_path . basename($_FILES['fileToUpload']['name']);
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) {
            unlink($row[7]);
        }
    }
    $s3 = "UPDATE user SET Last_Name='" . $_POST["Last_Name"] . "', Age = ' " . $_POST["Age"] . " ', Username='" . $_POST["Username"] . "', img = '" . $target_path . "' WHERE Email = '" . $row[4] . "';";
    $con->query($s3);
    echo '<meta http-equiv="refresh" content="0">';
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./resources/img//apple-icon.png" />
    <link rel="icon" type="image/png" href="./resources/img//favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="resources/css/reset.css"> <!-- CSS reset -->
    <link rel="stylesheet" href="resources/css/creset.css"> <!-- CSS reset -->
    <link rel="stylesheet" href="resources/css/mstyle.css"> <!-- Resource style -->
    <link rel="stylesheet" href="resources/css/cstyle.css"> <!-- Resource style -->
    <script src="resources/js/modernizr.js"></script> <!-- Modernizr -->
    <script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" /> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> -->
    <script src="https://use.fontawesome.com/3e489ec802.js"></script>
    <title>
        Dashboard
    </title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <link href="./resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./resources/css/paper-kit.css?v=2.2.0" rel="stylesheet" />
    <link href="./resources/demo/demo.css" rel="stylesheet" />
    <style>
        .p {
            font-weight: bold;
        }

        .swal2-popup {
            font-size: 1.6rem !important;
        }
    </style>
</head>

<body class="profile-page sidebar-collapse">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-transparent " color-on-scroll="300">
        <div class="container">
            <div class="navbar-translate">
                <a class="navbar-brand" href="dashboard.php" rel="tooltip" data-placement="bottom">
                    <i class="logo">
                        <style>
                            .logo {
                                fill: aliceblue;
                            }
                        </style>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                            <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" /></svg>
                    </i>
                </a>
                <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="./index.php" class="nav-link"><i class="nc-icon nc-layout-11"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="./nifty-companies.php" class="nav-link"><i class=" nc-icon nc-sound-wave"></i> NSE Companies</a>
                    </li>
                    <li class="nav-item">
                        <a href="./news.php" class="nav-link"><i class="nc-icon nc-paper"></i> News</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link"><i class="nc-icon nc-book-bookmark"></i> Sign Out</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="page-header page-header-xs" data-parallax="true" style="background-image: url('resources/img/dash_bg.jpg');">
        <div class="filter"></div>
    </div>
    <div class="section profile-content">
        <div class="container">
            <div class="owner">
                <div class="avatar">
                    <img src="<?php echo $img ?>" alt="Circle Image" class="img-circle img-no-padding img-responsive" />
                </div>
                <div class="name">
                    <h4 class="title"><b>
                            <p class="p">Name: <?php echo $row[1] . " " . $row[2]; ?></p>
                            <p class="p">Username: <?php echo $row[5]; ?></p>
                            <p class="p">Age: <?php echo $row[3]; ?></p>
                            <br />
                    </h4>
                    <h6 class="description">Account </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto text-center">
                    <section class="cd-section">
                        <div class="cd-modal-action">
                            <style>
                                .btn1 {
                                    background-color: #34383c;
                                    border: none;
                                    color: white;
                                    padding: 15px 32px;
                                    text-align: center;
                                    text-decoration: none;
                                    display: inline-block;
                                    font-size: 12px;
                                    margin: 4px 2px;
                                    cursor: pointer;
                                    border-radius: 5em;
                                }

                                .btn1:hover {
                                    box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
                                    color: aliceblue;
                                }
                            </style>
                            <a href="#0" class="btn1" data-type="modal-trigger"><i class="fa fa-cog"></i> Update</a>
                            <span class="cd-modal-bg"></span>
                            <div class="cd-modal">
                                <div class="cd-modal-content">
                                    <form class="cd-form floating-labels" action="" method="POST" enctype="multipart/form-data">
                                        <fieldset>
                                            <legend>Update Information</legend>

                                            <div class="icon">
                                                <label class="cd-label p" for="cd-name">First Name</label>
                                                <input class="user p" type="text" name="First_Name" id="cd-name" value="<?php echo $row[1] ?>" required>
                                            </div>
                                            <div class="icon">
                                                <label class="cd-label p" for="cd-name">Last Name</label>
                                                <input class="user p" type="text" name="Last_Name" id="cd-name" value="<?php echo $row[2] ?>" required>
                                            </div>
                                            <div class="icon">
                                                <label class="cd-label p" for="cd-name">Age</label>
                                                <input class="age p" type="text" name="Age" id="cd-name" value="<?php echo $row[3] ?>" required>
                                            </div>
                                            <div class="icon">
                                                <label class="cd-label p" for="cd-email">Email</label>
                                                <input class="email p error" type="email" name="Email" id="cd-email" value="<?php echo $row[4] ?>" required>
                                            </div>
                                            <div class="icon">
                                                <label class="cd-label p" for="cd-name">Username</label>
                                                <input class="user p" type="text" name="Username" id="cd-name" value="<?php echo $row[5] ?>" required>
                                            </div>
                                            <div class="file-upload">
                                                <div class="file-select">
                                                    <div class="file-select-button" id="fileName"><b>Select Image</b></div>
                                                    <!-- <div class="file-select-name" id="noFile">No file chosen...</div>  -->
                                                    <input type="file" name="fileToUpload" id="chooseFile">
                                                </div>
                                            </div>
                                        </fieldset>
                                        <input type="submit" value="Update" name="submit">
                                    </form>
                                </div> <!-- cd-modal-content -->
                            </div> <!-- cd-modal-action -->
                            <button onclick="reset_nice()" class="btn1"><i class="fa fa-history"></i> Password Reset</button>
                            <button onclick="nice()" class="btn1"><i class="fa fa-trash"></i> Delete</button>

                            <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
                            <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
                            <script>
                                async function nice() {
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "You won't be able to revert this!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, delete it!'
                                    }).then((willDelete) => {
                                        if (willDelete.value) {
                                            async function f() {

                                                const {
                                                    value: password
                                                } = await Swal.fire({
                                                    title: 'Enter your password',
                                                    input: 'password',
                                                    inputPlaceholder: 'Enter your password',
                                                    inputAttributes: {
                                                        autocapitalize: 'off',
                                                        autocorrect: 'off'
                                                    }
                                                })
                                                if (password == '<?php echo $row[6]; ?>') {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "delacc.php",
                                                        data: {
                                                            'id': <?php echo $row[0]; ?>
                                                        },
                                                        cache: false,
                                                        success: function(response) {
                                                            Swal.fire(
                                                                "Sccess!",
                                                                "Poof! Your account has been deleted!",
                                                                "success"
                                                            ).then(function() {
                                                                window.location.assign('delacc.php');
                                                            })
                                                        },
                                                        failure: function(response) {
                                                            Swal.fire(
                                                                "Internal Error",
                                                                "Please try again", // had a missing comma
                                                                "error"
                                                            )
                                                        }
                                                    });
                                                }
                                            }
                                            f();
                                        } else if (willDelete.dismiss === Swal.DismissReason.cancel) {
                                            Swal.fire(
                                                'Cancelled',
                                                'Enjoy our service :)',
                                                'error'
                                            )
                                        }
                                    })
                                }

                                async function reset_nice() {
                                    Swal.mixin({
                                        input: 'password',
                                        confirmButtonText: 'Next &rarr;',
                                        showCancelButton: true,
                                        progressSteps: ['1', '2', '3']
                                    }).queue([{
                                            title: 'Reset Password',
                                            text: 'Enter your current Password'
                                        },
                                        {
                                            title: 'Reset Password',
                                            text: 'Enter new Password'
                                        },
                                        {
                                            title: 'Reset Password',
                                            text: 'Enter re-type Password'
                                        }
                                    ]).then((result) => {
                                        if (result.value) {
                                            if (result.value[0] == "<?php echo $row[6]; ?>") {
                                                if (result.value[1] == result.value[2]) {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "resetpass.php",
                                                        data: {
                                                            'new_pass': result.value[1],
                                                            'id': <?php echo $row[0] ?>
                                                        },
                                                        cache: false,
                                                        success: function(response) {
                                                            Swal.fire(
                                                                "Success!",
                                                                "Your Password has been reset!",
                                                                "success"
                                                            ).then(function() {
                                                                window.location.assign('resetpass.php');
                                                            })
                                                        },
                                                        failure: function(response) {
                                                            Swal.fire(
                                                                "Internal Error",
                                                                "Please try again", // had a missing comma
                                                                "error"
                                                            )
                                                        }
                                                    });
                                                }
                                                if (result.value[1] != result.value[2]) {
                                                    Swal.fire(
                                                        "Internal Error",
                                                        "Password doesn't match,please try again", // had a missing comma
                                                        "error"
                                                    )
                                                }
                                            }
                                        }
                                    })
                                }
                            </script>
                            <!-- <btn class="btn btn-outline-default btn-round"><i class="fa fa-cog"></i> Settings</btn> -->

                        </div> <!-- cd-modal -->
                        <a href="#0" class="cd-modal-close">Close</a>
                </div>
            </div>
            <br />
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active p" data-toggle="tab" href="#follows" role="tab">Recommended</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p" data-toggle="tab" href="#following" role="tab">Following</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content following">
                <div class="tab-pane active" id="follows" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 ml-auto mr-auto">
                            <ul class="list-unstyled follows">
                                <?php
                                for ($i = 1; $i < 3; $i++) {
                                    $row = mysqli_fetch_row($result_c);
                                    // echo "<div class='comp_info'>";
                                    // echo '<img class="img" src = "' . $row[9] . '">';
                                    // echo "<div style='padding:5px;'>" . $row[6] . "<br>" . $row[5] . "</div>";
                                    // echo "</div>";
                                    echo '<li>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-4 col-4 ml-auto mr-auto">
                                                <img src="' . $row[9] . '" alt="Circle Image" class="img-thumbnail img-no-padding img-responsive" />
                                            </div>
                                            <div class="col-lg-7 col-md-4 col-4  ml-auto mr-auto">
                                                <h6>
                                                    ' . $row[6] . '
                                                    <br />
                                                    <small>' . $row[5] . '</small>
                                                    </h6>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-4  ml-auto mr-auto"> </div>
                                                    </div>
                                                    </li>';
                                }
                                ?>
                                <hr />
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane text-center" id="following" role="tabpanel">
                    <h3 class="text-muted">Not following anyone yet :(</h3>
                    <a href="nifty-companies.php"><button class="btn btn-warning btn-round">Find Companies</button></a>
                </div>
            </div>

        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <nav class="footer-nav">
                    <ul>
                        <li>
                            <a href="index.php">Stock Predictor</a>
                        </li>

                    </ul>
                </nav>
                <div class=" credits ml-auto">
                    <span class="copyright">
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        , made with <i class="fa fa-heart heart"></i> by Tanuj Dey
                    </span>
                </div>
            </div>
        </div>
    </footer>
    <script src="./resources/js/core/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="./resources/js/core/popper.min.js" type="text/javascript"></script> -->
    <script src="./resources/js/core/bootstrap.min.js" type="text/javascript"></script>
    <!-- <script src="./resources/js/plugins/bootstrap-switch.js"></script>
    <script src="./resources/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="./resources/js/plugins/moment.min.js"></script>
    <script src="./resources/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script> -->
    <script src="./resources/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
    <script src="resources/js/jquery-2.1.1.js"></script>
    <script src="resources/js/velocity.min.js"></script>
    <script src="resources/js/main.js"></script>
    <script src="resources/js/cmain.js"></script>
</body>

</html>