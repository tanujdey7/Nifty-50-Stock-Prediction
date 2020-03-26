<?php
$id = $_GET['id'];
// echo $id;
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
$s1 = "SELECT Comp_Name,Img FROM s_c_details WHERE Symbol='" . $id . "';";
$result = $con->query($s1);
if ($result->num_rows == 1) {
    $row = mysqli_fetch_row($result);
    $comp_name = $row[0];
    $comp_img = $row[1];
}
$s2 = "SELECT * from stock_details WHERE Symbol = '" . $id . "';";
$res1 = $con->query($s2);
if ($res1->num_rows == 1)
    $row1 = mysqli_fetch_row($res1);
?>

<head>
    <!-- Load plotly.js into the DOM -->
    <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
    <script>
        Plotly.d3.csv('<?php echo "stock_data/" . $id . ".csv" ?>', function(err, rows) {
            function unpack(rows, key) {
                return rows.map(function(row) {
                    return row[key];
                });
            }

            var trace = {
                /* x: unpack(rows, 'Date'),
                 close: unpack(rows, 'Close'),
                 high: unpack(rows, 'High'),
                 low: unpack(rows, 'Low'),
                 open: unpack(rows, 'Open'),   

                 // cutomise colors
                 //   increasing: {line: {color: }},
                 increasing: {line: {color: '#17BECF'}}, 
                 decreasing: {line: {color: '#7F7F7F'}},
                 line: {color: 'rgba(31,119,180,1)'},  
                 //   decreasing: {line: {color: 'red'}},*/
                //type: "scatter",
                type: 'scatter',
                mode: "lines",
                name: 'AAPL High',
                x: unpack(rows, 'Date'),
                y: unpack(rows, 'Close'),
                line: {
                    color: '#17BECF'
                },
                xaxis: 'x',
                yaxis: 'y'
            };

            var data = [trace];

            var layout = {
                autosize: false,
                width: 600,
                height: 400,
                dragmode: 'zoom',
                margin: {
                    r: 10,
                    t: 25,
                    b: 60,
                    l: 60
                },
                showlegend: false,
                xaxis: {
                    autorange: true,
                    title: '<?php echo $id ?>',
                    rangeslider: true,
                    rangeselector: {
                        x: 0,
                        y: 1.2,
                        xanchor: 'left',
                        font: {
                            size: 12
                        },
                        buttons: [{
                            step: 'month',
                            stepmode: 'backward',
                            count: 1,
                            label: '1 month'
                        }, {
                            step: 'month',
                            stepmode: 'backward',
                            count: 6,
                            label: '6 months'
                        }, {
                            step: 'month',
                            stepmode: 'backward',
                            count: 12,
                            label: '1 Year'
                        }, {
                            step: 'all',
                        }]
                    }
                },
                yaxis: {
                    autorange: true,
                }
            };

            Plotly.newPlot('myDiv', data, layout);
        });
    </script>
    <style type="text/css">
        .modebar {
            display: none !important;
        }

        .graph {
            display: grid;
            grid-template-columns: 30%;
        }

        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        html {
            font-weight: 300;
            font-size: 20px;
            text-rendering: optimizeLegibility;
            font-family: 'Raleway', 'Arial', sans-serif;
        }

        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            min-height: 8vh;
            background-color: #1abc9c;
        }

        .logo {
            color: aliceblue;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-size: 20px;
        }

        .nav-links {
            display: flex;
            justify-content: space-around;
            width: 25%;
            list-style: none;
        }

        .nav-links a {
            color: aliceblue;
            text-decoration: none;
            letter-spacing: 3px;
            font-weight: bold;
            font-size: 14px;
        }

        .burger {
            display: none;
        }

        .burger div {
            width: 25px;
            height: 3px;
            background-color: rgb(226, 226, 226);
            margin: 5px;
            transition: all 0.3s ease;
        }

        @media screen and (max-width:1024px) {
            .nav-links {
                width: 40%;
            }
        }

        @media screen and (max-width:768px) {
            body {
                overflow: hidden;
                cursor: pointer;
            }

            .nav-links {
                position: absolute;
                right: 0px;
                height: 92vh;
                top: 8vh;
                background-color: #1abc9c;
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 50%;
                transform: translateX(100%);
                transition: transform 0.5s ease-in;
            }

            .nav-links li {
                opacity: 0;
            }

            .burger {
                display: block;
            }
        }

        .nav-active {
            transform: translateX(0%);
        }

        @keyframes navLinkFade {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0px);
            }
        }

        .toggle .line1 {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .toggle .line2 {
            opacity: 0;
        }

        .toggle .line3 {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        .nav-links li a:hover,
        .nav-links li a:active {
            border-bottom: 2px solid #169e83;
            color: #26138e;
        }

        .comp .row {
            width: 180px;
            height: 100px;
            margin: 20px;
            float: left;
            position: relative;
        }

        .comp .row .img {
            max-height: 100%;
            max-width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .comp table {
            font-weight: 300;
            font-size: 20px;
            text-rendering: optimizeLegibility;
            font-family: 'Raleway', 'Arial', sans-serif;
        }

        .tab {
            border-bottom: 1px solid #ccc;
        }

        .tb {
            border-bottom: 1px solid #ccc;
            font-size: 15px;
            width: 230px;
            padding: 10px 0px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                    <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" /></svg>
            </i>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>
    <div style="padding:0px 200px;">
        <div class="comp">
            <table>
                <tr>
                    <td>
                        <div class="row"><img src="<?php echo $comp_img; ?>" class="img" /></div>
                    </td>
                    <td>
                        <div>
                            <h1 style="padding-top:5px;"><?php echo $comp_name; ?>
                                <img src="resources/img/wishlist.svg" width="25px" /></h1>
                            <h3><?php echo $id; ?></h3>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <h2><?php echo $row1[3]; ?>
                            <?php
                            $a1 = floatval($row1[3]);
                            $a2 = floatval($row1[5]);
                            $a4 = round($a1 - $a2, 3);
                            $a3 = round(($a4 * 100) / $a2, 2);
                            if ($a3 < 0) {
                                echo "<span style='color:red;font-size:25px;'>" . $a4 . "  (" . $a3 . "%)" . "</span>";
                            } else {
                                echo "<span style='color:green;font-size:25px;'>+" . $a4 . "  (+" . $a3 . "%)" . "</span>";
                            }
                            ?>
                        </h2>
                    </td>
                </tr>
                <tr style="height:30px;"></tr>
                <tr>
                    <td></td>
                    <td>
                        <h2>Summary</h2>
                    </td>
                </tr>
                <tr style="height:15px;"></tr>
                <tr>
                    <td></td>
                    <td class="show">
                        <table style="float:left;">
                            <tr>
                                <td class="tb">
                                    <div style="text-align:left;float:left;padding:5px;">Open</div>
                                    <div style="text-align:right;padding:5px;"><?php echo $row1[2] ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tb">
                                    <div style="text-align:left;float:left;padding:5px;">Close</div>
                                    <div style="text-align:right;padding:5px;"><?php echo $row1[3] ?></div>
                                </td>
                            <tr>
                                <td class="tb">
                                    <div style="text-align:left;float:left;padding:5px;">Volume</div>
                                    <div style="text-align:right;padding:5px;"><?php echo $row1[4] ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tb">
                                    <div style="text-align:left;float:left;padding:5px;">Previous Close</div>
                                    <div style="text-align:right;padding:5px;"><?php echo $row1[5] ?></div>
                                </td>
                            </tr>
                        </table>
                        <div class="graph">
                            <style></style>
                            <div id='myDiv'>
                                <!-- Plotly chart will be drawn inside this DIV-->
                            </div>
                        </div>
        </div>
        </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <h2>Prediction</h2>
            </td>
        </tr>
        </table>
    </div>
    <!-- <div class="tab">
            <div class="tablinks">Summary</div>
            <div class="tablinks">
    </div> -->
    <!-- <script src="graph.js"></script> -->
</body>