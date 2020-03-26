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
$s1 = "SELECT * FROM s_c_details WHERE Symbol='" . $id . "';";
$result = $con->query($s1);
if ($result->num_rows == 1) {
    $row = mysqli_fetch_row($result);
    $comp_name = $row[1];
    $comp_img = $row[9];
}
$s2 = "SELECT * from stock_details WHERE Symbol = '" . $id . "';";
$res1 = $con->query($s2);
if ($res1->num_rows == 1)
    $row1 = mysqli_fetch_row($res1);


$open = "stock_data/" . $id . ".csv";
$file = fopen($open, "r");
$time = new DateTime();
$newtime = $time->modify('-1 year')->format('Y-m-d');
//echo $newtime;
$c = 0;
//$row6 = array();
while (!feof($file)) {
    $row5 = fgetcsv($file);
    if ($row5[0] == $newtime) {
        $row6[$c] = $row5;
        $c++;
        while (!feof($file)) {
            $row6[$c] = fgetcsv($file);
            $c++;
        }
        //print_r($row6[$c-2]);
        //echo $row6[$c-2][0];
    }
    //print_r($row5);
}
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
                        width: 1000,
                        height: 500,
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
                        };

                        Plotly.newPlot('myDiv', data, layout);
                    });

                function openTabs(evt, tabName) {
                    document.getElementById(tabName).style.display = "block";
                    document.getElementById("port").className += " active";
                }

                function openTab(evt, tabName) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.className += " active";
                }
    </script>
    <style type="text/css">
        .modebar {
            display: none !important;
        }

        .graph {
            display: grid;
            grid-template-columns: 30%;
            float: left;
            margin-right: 30px;
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

        .comp {
            width: 70%;
            text-align: left;
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
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            background-color: #f1f1f1;
            width: 70%;
        }

        .tablinks {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        .tablinks:hover {
            color: #FF586D;
        }

        .tb {
            border-bottom: 1px solid #ccc;
            font-size: 15px;
            width: 300px;
            padding: 10px 0px;
        }

        .tb a {
            color: black;
            text-decoration: none;
        }

        .tb a:hover {
            color: #1abc9c;
        }

        .tabcontent {
            width: 70%;
            text-align: left;
            margin: 20px 0px 0px 30px;
            display: none;
        }

        .tabcotent.active {
            display: block;
        }

        .trend {
            background-color: #135690;
            color: white;
            margin-left: 40px;
        }

        .tablinks.active {
            border-bottom: 5px solid #FF586D;
        }

        .hist {
            width: 80%;
            text-align: center;
            border-collapse: collapse;
        }

        .hist .tl {
            border-bottom: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body onload="openTabs(event, 'Portfolio')">
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
    <center>
        <div>
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
                </table>
            </div>
            <div class="tab">
                <button class="tablinks" id="port" onclick="openTab(event, 'Portfolio')">Portfolio</button>
                <button class="tablinks" onclick="openTab(event, 'Chart')">Chart</button>
                <button class="tablinks" onclick="openTab(event, 'Historical')">Historical Data</button>
            </div>
            <div id="Chart" class="tabcontent">
                <div class="graph">
                    <div id='myDiv'>
                        <!-- Plotly chart will be drawn inside this DIV -->
                    </div>
                </div>
                <table class="trend">
                    <tr>
                        <td style="text-align:center;font-weight:bold;padding:10px;">
                            TOP TRENDING STOCKS
                            <center>
                                <hr style="width:70px;border:1px solid yellow;margin-top:10px;">
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <th class="tb" style="border:none;">
                            <div style="text-align:left;float:left;padding:5px;">Stock Name</div>
                            <div style="text-align:right;padding:5px;">%change</div>
                        </th>
                    <tr>
                        <?php
                        $a2 = array();
                        $s6 = "SELECT Symbol,Volume from stock_details";
                        $res3 = $con->query($s6);
                        if ($res3->num_rows > 0) {
                            while ($row2 = mysqli_fetch_row($res3)) {
                                $z = strval($row2[0]);
                                $a2[$z] = intval($row2[1]);
                            }
                        }
                        arsort($a2);
                        //print_r($a2);
                        $a3 = array_slice($a2, 0, 5);
                        foreach ($a3 as $x => $x_val) {
                            $s7 = "SELECT Comp_Name from s_c_details WHERE Symbol='" . $x . "';";
                            $res4 = $con->query($s7);
                            if ($res4->num_rows == 1) {
                                $row3 = mysqli_fetch_row($res4);
                            }
                            $s8 = "SELECT Close,prev_close from stock_details WHERE Symbol='" . $x . "';";
                            $res5 = $con->query($s8);
                            if ($res5->num_rows == 1) {
                                $row4 = mysqli_fetch_row($res5);
                            }
                            $b1 = floatval($row4[0]);
                            $b2 = floatval($row4[1]);
                            $b4 = round($b1 - $b2, 3);
                            $b3 = round(($b4 * 100) / $b2, 2);
                            echo "<tr>
                                    <td class='tb'style='background-color:#2869A1;'>
                                    <div style='text-align:left;float:left;padding:5px;'>" . $row3[0] . "</div>";
                            if ($b3 > 0)
                                echo "<div style='text-align:right;padding:5px;color:#23D537;'>+" . $b3 . "</div>";
                            else
                                echo "<div style='text-align:right;padding:5px;color:#FF586D;'>" . $b3 . "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                </table>
            </div>
            <div id="Historical" class="tabcontent">
                <center>
                    <table class="hist">
                        <tr>
                            <th class="tl" style="text-align:left;">Date</th>
                            <th class="tl">High</th>
                            <th class="tl">Low</th>
                            <th class="tl">Open</th>
                            <th class="tl">Close</th>
                            <th class="tl" style="text-align:right;">Volume</th>
                        </tr>
                        <?php
                        for ($d = $c - 2; $d >= 0; $d--) {
                            echo "<tr>";
                            echo "<td class='tl' style='text-align:left;'>" . $row6[$d][0] . "</td>";
                            printf("<td class='tl'>%.2f</td>", $row6[$d][1]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][2]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][3]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][4]);
                            printf("<td class='tl' style='text-align:right;'>%.0f</td>", $row6[$d][5]);
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </center>
            </div>
            <div id="Portfolio" class="tabcontent">
                <table style="float:left;margin-right:200px;">
                    <tr>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Website</div>
                            <div style="text-align:right;padding:5px;"><a href="<?php echo "http://" . $row[2] ?>"><?php echo $row[2] ?></a></div>
                        </td>
                        <td style="width:50px;"></td>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Open</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row1[2] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Headquaters</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row[3] ?></div>
                        </td>
                        <td style="width:50px;"></td>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Close</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row1[3] ?></div>
                        </td>
                    <tr>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Founded</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row[4] ?></div>
                        </td>
                        <td style="width:50px;"></td>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Volume</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row1[4] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Industry</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row[5] ?></div>
                        </td>
                        <td style="width:50px;"></td>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Previous Close</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row1[5] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">Series</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row[7] ?></div>
                        </td>
                        <td style="width:50px;"></td>
                        <td class="tb">
                            <div style="text-align:left;float:left;padding:5px;">ISIN Code</div>
                            <div style="text-align:right;padding:5px;"><?php echo $row[8] ?></div>
                        </td>
                    </tr>
                </table>
                <table class="trend">
                    <tr>
                        <td style="text-align:center;font-weight:bold;padding:10px;">
                            TOP TRENDING STOCKS
                            <center>
                                <hr style="width:70px;border:1px solid yellow;margin-top:10px;">
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <th class="tb" style="border:none;">
                            <div style="text-align:left;float:left;padding:5px;">Stock Name</div>
                            <div style="text-align:right;padding:5px;">%change</div>
                        </th>
                    <tr>
                        <?php
                        $a2 = array();
                        $s6 = "SELECT Symbol,Volume from stock_details";
                        $res3 = $con->query($s6);
                        if ($res3->num_rows > 0) {
                            while ($row2 = mysqli_fetch_row($res3)) {
                                $z = strval($row2[0]);
                                $a2[$z] = intval($row2[1]);
                            }
                        }
                        arsort($a2);
                        //print_r($a2);
                        $a3 = array_slice($a2, 0, 5);
                        foreach ($a3 as $x => $x_val) {
                            $s7 = "SELECT Comp_Name from s_c_details WHERE Symbol='" . $x . "';";
                            $res4 = $con->query($s7);
                            if ($res4->num_rows == 1) {
                                $row3 = mysqli_fetch_row($res4);
                            }
                            $s8 = "SELECT Close,prev_close from stock_details WHERE Symbol='" . $x . "';";
                            $res5 = $con->query($s8);
                            if ($res5->num_rows == 1) {
                                $row4 = mysqli_fetch_row($res5);
                            }
                            $b1 = floatval($row4[0]);
                            $b2 = floatval($row4[1]);
                            $b4 = round($b1 - $b2, 3);
                            $b3 = round(($b4 * 100) / $b2, 2);
                            echo "<tr>
                                    <td class='tb'style='background-color:#2869A1;'>
                                    <div style='text-align:left;float:left;padding:5px;'>" . $row3[0] . "</div>";
                            if ($b3 > 0)
                                echo "<div style='text-align:right;padding:5px;color:#23D537;'>+" . $b3 . "</div>";
                            else
                                echo "<div style='text-align:right;padding:5px;color:#FF586D;'>" . $b3 . "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                </table>
            </div>

            <!--<tr style="height:30px;"></tr>
            <tr>
                <td></td>
                <td><h2>Summary</h2></td>
            </tr>
            <tr style="height:15px;"></tr>
            <tr>
                <td></td>
                <td class="show">
                    
                </td>
            </tr>
            <tr>
                <td></td>
                <td><h2>Prediction</h2></td>
            </tr>    
        </table>
    </div> -->
            <!-- <div class="tab">
            <div class="tablinks">Summary</div>
            <div class="tablinks">-->
        </div>
    </center>
</body>