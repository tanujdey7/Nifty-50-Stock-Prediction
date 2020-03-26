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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        NSE-Companies
    </title>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="resources/img/apple-icon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">

    <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" /> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="resources/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- <link href="resources/css/paper-kit.css?v=2.2.0" rel="stylesheet" /> -->

    <style>
        .card {
            width: 60% !important;
            margin-left: 20%;
        }

        svg {
            fill: aliceblue;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 30px 10%;
            background-color: #24252a;
        }

        .logo {
            margin-right: auto;
        }

        .nav__links {
            list-style: none;
            display: flex;
        }

        .nav__links a,
        .cta,
        .overlay__content a {
            font-family: "Montserrat", sans-serif;
            font-weight: 500;
            color: #edf0f1;
            text-decoration: none;
        }

        .nav__links li {
            padding: 0px 20px;
        }

        .nav__links li a {
            transition: all 0.3s ease 0s;
        }

        .nav__links li a:hover {
            color: #0088a9;
        }

        .cta {
            padding: 9px 25px;
            background-color: rgba(0, 136, 169, 1);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease 0s;
        }

        .cta:hover {
            background-color: rgba(0, 136, 169, 0.8);
        }

        /* Mobile Nav */

        .menu {
            display: none;
        }

        .overlay {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            background-color: #24252a;
            overflow-x: hidden;
            transition: all 0.5s ease 0s;
        }

        .overlay__content {
            display: flex;
            height: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .overlay a {
            padding: 15px;
            font-size: 36px;
            display: block;
            transition: all 0.3s ease 0s;
        }

        .overlay a:hover,
        .overlay a:focus {
            color: #0088a9;
        }

        .overlay .close {
            position: absolute;
            top: 20px;
            right: 45px;
            font-size: 60px;
            color: #edf0f1;
            cursor: pointer;
        }

        @media screen and (max-height: 450px) {
            .overlay a {
                font-size: 20px;
            }

            .overlay .close {
                font-size: 40px;
                top: 15px;
                right: 35px;
            }
        }

        @media only screen and (max-width: 800px) {

            .nav__links,
            .cta {
                display: none;
            }

            .menu {
                display: initial;
            }
        }
    </style>
</head>

<body class="">
    <header>
        <a class="logo" href="/">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" />
            </svg>
        </a>
        <nav>
            <ul class="nav__links">
                <li><a href="index.php">Home</a></li>
                <li><a href="news.html">News</a></li>
                <li><a class="cta" href="nifty-companies.php">NSE Details</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <p onclick="openNav()" class="menu cta">Menu</p>
    </header>
    <div id="mobile__menu" class="overlay">
        <a class="close" onclick="closeNav()">&times;</a>
        <div class="overlay__content">
            <a href="index.php">Home</a>
            <a href="news.html">News</a>
            <a href="nifty-companies.php">NSE Details</a>
            <a href="dashboard.php">Dasboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <br>
    <div class="content">
        <div class="container-fluid">
            <div class="col">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Nifty 50</h4>
                            <p class="card-category"> Top 50 companies of Nifty 50 </p>
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
                                    </cen ter>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="resources/js/core/jquery.min.js"></script>
                <script src="resources/js/core/popper.min.js"></script>
                <script src="resources/js/core/bootstrap-material-design.min.js"></script>
                <script src="resources/js/plugins/perfect-scrollbar.jquery.min.js"></script>
                <script src="resources/js/plugins/moment.min.js"></script>
                <script src="resources/js/plugins/sweetalert2.js"></script>
                <script src="resources/js/plugins/jquery.validate.min.js"></script>
                <script src="resources/js/plugins/jquery.bootstrap-wizard.js"></script>
                <script src="resources/js/plugins/bootstrap-selectpicker.js"></script>
                <script src="resources/js/plugins/bootstrap-datetimepicker.min.js"></script>
                <script src="resources/js/plugins/jquery.dataTables.min.js"></script>
                <script src="resources/js/plugins/bootstrap-tagsinput.js"></script>
                <script src="resources/js/plugins/jasny-bootstrap.min.js"></script>
                <script src="resources/js/plugins/fullcalendar.min.js"></script>
                <script src="resources/js/plugins/jquery-jvectormap.js"></script>
                <script src="resources/js/plugins/nouislider.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
                <script src="resources/js/plugins/arrive.min.js"></script>
                <script src="resources/js/plugins/chartist.min.js"></script>
                <script src="resources/js/plugins/bootstrap-notify.js"></script>
                <script src="resources/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
                <script src="resources/demo/demo.js"></script>
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
                <script src="mobile.js"></script>
</body>

</html>