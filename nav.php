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
        } else {
            $target_path = $row[7];
        }
    }
    if ($target_path == "resources/img/profile/") {
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
    <title>
        Dashboard
    </title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="./resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./resources/css/paper-kit.css" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="./resources/demo/demo.css" rel="stylesheet" />
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
                        <a href="./news.html" class="nav-link"><i class="nc-icon nc-paper"></i> News</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link"><i class="nc-icon nc-book-bookmark"></i> Sign Out</a>
                    </li>

                </ul>
                </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="page-header page-header-xs" data-parallax="true" style="background-image: url('https://source.unsplash.com/970x1080/?background');">
        <div class="filter"></div>
    </div>
    <div class="col-md-8 ml-auto mr-auto text-center">
    <div class="content">
        <div class="container-fluid">
            <div class="col">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h2 class="card-title ">Nifty 50</h4>
                            <h6 class="card-category"> Top 50 companies of Nifty 50 </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <center>
                                    <table class="table table-hover text-center">
                                        <thead class=" text-primary">
                                            <th>
                                                Serial Number
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Symbol
                                            </th>
                                            <th>
                                                Change
                                            </th>
                                            <th>
                                                Percentage Change
                                            </th>
                                            <th>
                                                Open
                                            </th>
                                            <th>
                                                Close
                                            </th>
                                            <th>
                                                Volume
                                            </th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $st1 = "select s_c_details.Comp_Name,stock_details.Symbol,stock_details.Open,stock_details.Close,stock_details.Volume,stock_details.prev_close
                                        from s_c_details JOIN stock_details ON s_c_details.Comp_ID=stock_details.Comp_ID;";
                                            $res1 = $con->query($st1);
                                            echo $con->error;
                                            $i = 1;
                                            if ($res1->num_rows > 0) {
                                                while ($row = mysqli_fetch_row($res1)) { ?>
                                                    <tr>
                                                        <td style="text-align:center;"> <?php echo $i ?></td>
                                                        <td><a href="graph.php?id=<?php echo $row[1]; ?>" class="link1"> <?php echo $row[0] ?></a></td>
                                                        <td><?php echo $row[1] ?></td>
                                                        <?php
                                                        $a1 = floatval($row[3]);
                                                        $a2 = floatval($row[5]);
                                                        $a4 = round($a1 - $a2, 3);
                                                        $a3 = round(($a4 * 100) / $a2, 2);
                                                        ?>
                                                        <?php
                                                        if ($a3 < 0) {
                                                            echo "<td style='color:red;text-align:center;'>" . $a4 . "</td>";
                                                            echo "<td style='color:red;text-align:center;'>" . $a3 . '%' . "</td>";
                                                        } else {
                                                            echo "<td style='color:green;text-align:center;'>" . "+" . $a4 . "</td>";
                                                            echo "<td style='color:green;text-align:center;'>" . '+' . $a3 . '%' . "</td>";
                                                        }
                                                        ?>
                                                        <td><?php echo $row[2] ?></td>
                                                        <td><?php echo $row[3] ?></td>
                                                        <td><?php echo $row[4] ?></td>

                                                    </tr>
                                            <?php $i += 1;
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                    </center>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $().ready(function() {
                            $sidebar = $('.sidebar');

                            $sidebar_img_container = $sidebar.find('.sidebar-background');
                            
                            $full_page = $('.full-page');
                            
                            $sidebar_responsive = $('body > .navbar-collapse');
                            
                            window_width = $(window).width();
                            
                            fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
                            
                            if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
                                if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                                    $('.fixed-plugin .dropdown').addClass('open');
                                }

                            }

                            $('.fixed-plugin a').click(function(event) {
                                // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
                                if ($(this).hasClass('switch-trigger')) {
                                    if (event.stopPropagation) {
                                        event.stopPropagation();
                                    } else if (window.event) {
                                        window.event.cancelBubble = true;
                                    }
                                }
                            });
                            
                            $('.fixed-plugin .active-color span').click(function() {
                                $full_page_background = $('.full-page-background');
                                
                                $(this).siblings().removeClass('active');
                                $(this).addClass('active');
                                
                                var new_color = $(this).data('color');
                                
                                if ($sidebar.length != 0) {
                                    $sidebar.attr('data-color', new_color);
                                }
                                
                                if ($full_page.length != 0) {
                                    $full_page.attr('filter-color', new_color);
                                }

                                if ($sidebar_responsive.length != 0) {
                                    $sidebar_responsive.attr('data-color', new_color);
                                }
                            });
                            
                            $('.fixed-plugin .background-color .badge').click(function() {
                                $(this).siblings().removeClass('active');
                                $(this).addClass('active');
                                
                                var new_color = $(this).data('background-color');
                                
                                if ($sidebar.length != 0) {
                                    $sidebar.attr('data-background-color', new_color);
                                }
                            });
                            
                            $('.fixed-plugin .img-holder').click(function() {
                                $full_page_background = $('.full-page-background');
                                
                                $(this).parent('li').siblings().removeClass('active');
                                $(this).parent('li').addClass('active');
                                
                                
                                var new_image = $(this).find("img").attr('src');
                                
                                if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                                    $sidebar_img_container.fadeOut('fast', function() {
                                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                                        $sidebar_img_container.fadeIn('fast');
                                    });
                                }
                                
                                if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                                    var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                                    $full_page_background.fadeOut('fast', function() {
                                        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                                        $full_page_background.fadeIn('fast');
                                    });
                                }
                                
                                if ($('.switch-sidebar-image input:checked').length == 0) {
                                    var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
                                    var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                                    $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                                    $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                                }
                                
                                if ($sidebar_responsive.length != 0) {
                                    $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
                                }
                            });
                            
                            $('.switch-sidebar-image input').change(function() {
                                $full_page_background = $('.full-page-background');
                                
                                $input = $(this);
                                
                                if ($input.is(':checked')) {
                                    if ($sidebar_img_container.length != 0) {
                                        $sidebar_img_container.fadeIn('fast');
                                        $sidebar.attr('data-image', '#');
                                    }
                                    
                                    if ($full_page_background.length != 0) {
                                        $full_page_background.fadeIn('fast');
                                        $full_page.attr('data-image', '#');
                                    }

                                    background_image = true;
                                } else {
                                    if ($sidebar_img_container.length != 0) {
                                        $sidebar.removeAttr('data-image');
                                        $sidebar_img_container.fadeOut('fast');
                                    }
                                    
                                    if ($full_page_background.length != 0) {
                                        $full_page.removeAttr('data-image', '#');
                                        $full_page_background.fadeOut('fast');
                                    }
                                    
                                    background_image = false;
                                }
                            });
                            
                            $('.switch-sidebar-mini input').change(function() {
                                $body = $('body');
                                
                                $input = $(this);
                                
                                if (md.misc.sidebar_mini_active == true) {
                                    $('body').removeClass('sidebar-mini');
                                    md.misc.sidebar_mini_active = false;

                                    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

                                } else {
                                    
                                    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
                                    
                                    setTimeout(function() {
                                        $('body').addClass('sidebar-mini');
                                        
                                        md.misc.sidebar_mini_active = true;
                                    }, 300);
                                }

                                // we simulate the window Resize so the charts will get updated in realtime.
                                var simulateWindowResize = setInterval(function() {
                                    window.dispatchEvent(new Event('resize'));
                                }, 180);
                                
                                // we stop the simulation of Window Resize after the animations are completed
                                setTimeout(function() {
                                    clearInterval(simulateWindowResize);
                                }, 1000);
                                
                            });
                        });
                    });
                    </script>
    </div> <!-- cd-modal -->
    
</div>
</div>
<br />

</div>
</div>
</div>
<footer class="footer    ">
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
    <script src="./resources/js/core/popper.min.js" type="text/javascript"></script>
    <script src="./resources/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="./resources/js/plugins/bootstrap-switch.js"></script>
    <script src="./resources/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="./resources/js/plugins/moment.min.js"></script>
    <script src="./resources/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="./resources/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
    <script src="resources/js/jquery-2.1.1.js"></script>
    <script src="resources/js/velocity.min.js"></script>
    <script src="resources/js/main.js"></script>
    <script src="resources/js/core/bootstrap-material-design.min.js"></script>
    <script src="resources/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="resources/js/plugins/sweetalert2.js"></script>
    <script src="resources/js/plugins/jquery.validate.min.js"></script>
    <script src="resources/js/plugins/jquery.bootstrap-wizard.js"></script>
    <script src="resources/js/plugins/jquery.dataTables.min.js"></script>
    <script src="resources/js/plugins/bootstrap-tagsinput.js"></script>
    <script src="resources/js/plugins/jasny-bootstrap.min.js"></script>
    <script src="resources/js/plugins/fullcalendar.min.js"></script>
    <script src="resources/js/plugins/jquery-jvectormap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="resources/js/plugins/arrive.min.js"></script>
    <script src="resources/js/plugins/bootstrap-notify.js"></script>
    <script src="resources/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
    <script src="resources/demo/demo.js"></script>
    <script src="resources/js/cmain.js"></script>
</body>

</html>