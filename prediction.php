<?php
$id = 'ADANIPORTS';
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
a: while (!feof($file)) {
    $row5 = fgetcsv($file);
    if ($row5[0] == $newtime) {
        $row6[$c] = $row5;
        $c++;
        while (!feof($file)) {
            $row6[$c] = fgetcsv($file);
            $c++;
        }
    }
}
if (empty($row6)) {
    $newtime = $time->modify('-1 days')->format('Y-m-d');
    fseek($file, 0);
    goto a;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graph</title>
    <!-- Load plotly.js into the DOM -->
    <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />   
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
                },
                yaxis: {
                    autorange: true,
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
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400&display=swap" rel="stylesheet">
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
            list-style: none;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Raleway', Arial, Helvetica, sans-serif;
        }
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #c7d0d8;
            height: 100%;
            width: 100%;
            text-align: center;
            font-weight: 300;
            font-size: 20px;
            text-rendering: optimizeLegibility;
            font-family: 'Raleway', 'Arial', sans-serif;
        }

        /* Tabbed Styles */
        .tabbed {
            width: 80%;
            min-width: 400px;
            margin: 0 auto;
            margin-bottom: 68px;
            border-bottom: 4px solid #000;
            overflow: hidden;
            transition: border 250ms ease;
        }

        .tabbed ul {
            margin: 0px;
            padding: 0px;
            overflow: hidden;
            float: left;
            padding-left: 48px;
            list-style-type: none;
        }

        .tabbed ul * {
            margin: 0px;
            padding: 0px;
        }

        .tabbed ul li {
            display: block;
            float: right;
            padding: 10px 24px 8px;
            background-color: #fff;
            margin-right: 46px;
            z-index: 2;
            position: relative;
            cursor: pointer;
            color: #777;

            text-transform: uppercase;
            font: 600 13px/20px roboto, "Open Sans", Helvetica, sans-serif;

            transition: all 250ms ease;
        }

        .tabbed ul li:before,
        .tabbed ul li:after {
            display: block;
            content: " ";
            position: absolute;
            top: 0;
            height: 100%;
            width: 44px;
            background-color: #fff;
            transition: all 250ms ease;
        }

        .tabbed ul li:before {
            right: -24px;
            transform: skew(30deg, 0deg);
            box-shadow: rgba(0, 0, 0, 0.1) 3px 2px 5px,
                inset rgba(255, 255, 255, 0.09) -1px 0;
        }

        .tabbed ul li:after {
            left: -24px;
            transform: skew(-30deg, 0deg);
            box-shadow: rgba(0, 0, 0, 0.1) -3px 2px 5px,
                inset rgba(255, 255, 255, 0.09) 1px 0;
        }

        .tabbed ul li:hover,
        .tabbed ul li:hover:before,
        .tabbed ul li:hover:after {
            background-color: #f4f7f9;
            color: #444;
        }

        .tabbed ul li.active1 {
            z-index: 3;
        }

        .tabbed ul li.active1,
        .tabbed ul li.active1:before,
        .tabbed ul li.active1:after {
            background-color: #000;
            color: #fff;
        }

        /* Round Tabs */
        .tabbed.round ul li {
            border-radius: 8px 8px 0 0;
        }

        .tabbed.round ul li:before {
            border-radius: 0 8px 0 0;
        }

        .tabbed.round ul li:after {
            border-radius: 8px 0 0 0;
        }

        /* Skins */
        .tabbed[class*="skin-graphite"] ul li {
            color: #fff;
            text-shadow: rgba(0, 0, 0, 0.1) 0 1px;
        }

        .tabbed.skin-graphite {
            border-bottom-color: #454545;
        }

        .tabbed.skin-graphite ul li,
        .tabbed.skin-graphite ul li:before,
        .tabbed.skin-graphite ul li:after {
            background-color: #5f5f5f;
        }

        .tabbed.skin-graphite ul li:hover,
        .tabbed.skin-graphite ul li:hover:before,
        .tabbed.skin-graphite ul li:hover:after {
            background-color: #6b6b6b;
        }

        .tabbed.skin-graphite ul li.active1,
        .tabbed.skin-graphite ul li.active1:before,
        .tabbed.skin-graphite ul li.active1:after {
            background-color: #454545;
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

        
        .hist {
            width: 80%;
            text-align: center;
            border-collapse: collapse;
        }

        .hist .tl {
            border-bottom: 1px solid #ccc;
            padding: 10px;
        }

        .key td {
            min-width: 200px;
        }

        svg {
            fill: aliceblue;
        }
        header {
            position: sticky;
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
            display: flex;
            text-transform: uppercase;
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

        main {
            /* make sure to cover the screen */
            min-height: 100vh;

            /* need a solid bg to hide the footer */
            background: white;

            /* put on top */
            position: relative;
            z-index: 1;

            font: 16px/1.4 system-ui, sans-serif;
            padding: 2rem;
        }

        footer {
            /* place on the bottom */
            position: sticky;
            bottom: 0;
            left: 0;
            width: 100%;

            background: #24252a;
            display: block;
            overflow: hidden;
            /* place-items: center; */
            box-sizing: border-box;
            padding: 50px;
        }

        .inner-footer {
            display: block;
            margin: 0 auto;
            width: 1100px;
            height: 100%;

        }

        .inner-footer .logo1 {
            margin-top: 4   0px;
            width: 35%;
            float: left;
            height: 100%;
            display: block;
        }

        .inner-footer .logo1 svg {
            width: 65%;
            /* height: auto; */
        }

        .footer-third {
            width: calc(21.666666666667% - 20px);
            margin-right: 10px;
            float: left;
            height: 100%;
        }

        .footer-third:last-child {
            margin-right: 0;
        }

        .footer-third h1 {
            font-size: 22px;
            color: white;
            display: block;
            width: 100%;
            margin-bottom: 20px;
        }

        .inner-footer .footer-third a {
            font-size: 16px;
            color: #8ae8ff;
            display: block;
            width: 100%;
            font-weight: 200;
            /* margin-bottom: 5px; */
            padding-bottom: 5px;
            text-decoration: none;
        }

        .inner-footer .footer-third span {
            font-size: 12px;
            color: white;
            display: block;
            width: 100%;
            font-weight: 200;
            /* margin-bottom: 20px; */
            /* padding-bottom: 5px; */
            padding-top: 20px;
        }

        p {
            max-width: 600px;
            margin: 0 auto 1rem;
        }

        @media(max-width: 700px) {
            footer .inner-footer {
                width: 90%;
            }

            .inner-footer .footer-third {
                width: 100%;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body onload="openTabs(event, 'Portfolio')">
    <header>
        <a class="logo" href="/">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" />
            </svg>
        </a>
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="news.php">News</a></li>
            <li><a class="cta" href="nifty-companies.php">NSE Details</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="dashboard.php">Logout</a></li>
        </ul>
        <p onclick="openNav()" class="menu cta">Menu</p>
    </header>
    <div id="mobile__menu" class="overlay">
        <a class="close" onclick="closeNav()">&times;</a>
        <div class="overlay__content">
            <a href="index.php">Home</a>
            <a href="news.php">News</a>
            <a href="nifty-companies.php">NSE Details</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>

        </div>
    </div>
    <main>

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
                                    <a href="" style="fill: red;">
                                        <i class="fa fa-bookmark" style="fill:red;"></i>
                                    </a>
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
                                    echo "<h6>At close: " . $row6[$c - 2][0] . "</h6>";
                                    ?>
                                </h2>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="tabbed skin-graphite round" id="skinable">
                    <ul>
                        <li onclick="openTab(event, 'Historical')">Historical Data</li>
                        <li onclick="openTab(event, 'Prediction')">Prediction</li>
                        <li onclick="openTab(event, 'Chart')">Chart</li>
                        <li class="active1" id="port" onclick="openTab(event, 'Portfolio')">Profile</li>
                    </ul>
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
                            $file = fopen("resources/csv/profile.csv", "r");
                            $s9 = "SELECT Comp_ID from s_c_details WHERE Symbol='" . $id . "';";
                            $res6 = $con->query($s9);
                            if ($res6->num_rows == 1)
                                $row7 = mysqli_fetch_row($res6);
                            //print_r($row7[0]);
                            for ($q = 0; $q <= intval($row7[0]); $q++) {
                                $p = fgetcsv($file);
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
                                <div style="text-align:left;float:left;padding:5px;">Sector</div>
                                <div style="text-align:right;padding:5px;"><?php echo $p[6] ?></div>
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
                                <div style="text-align:right;padding:5px;"><?php echo $p[7] ?></div>
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
                        <tr>
                            <td class="tb">
                                <div style="text-align:left;float:left;padding:5px;">Symbol</div>
                                <div style="text-align:right;padding:5px;"><?php echo $id ?></div>
                            </td>
                            <td style="width:50px;"></td>
                            <td class="tb">
                                <div style="text-align:left;float:left;padding:5px;">No. of Employees</div>
                                <div style="text-align:right;padding:5px;"><?php echo $p[8] ?></div>
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
                            /*$s6 = "SELECT Symbol,Volume from stock_details";
                        $res3 = $con->query($s6);
                        if($res3->num_rows > 0)
                        {
                            while($row2 = mysqli_fetch_row($res3))
                            {
                                $z = strval($row2[0]);
                                $a2[$z] = intval($row2[1]);        
                            }
                        }
                        arsort($a2);*/
                            //$a3 = array_slice($a2,0,5);
                            $file2 = fopen("resources/csv/mar_cap.csv", "r");
                            $i = 0;
                            fgetcsv($file2);
                            fgetcsv($file2);
                            fgetcsv($file2);
                            while (!feof($file2)) {
                                $f1 = fgetcsv($file2);
                                $j = $f1[1];
                                $a2[$j] = $f1[5];
                                $i++;
                                if ($i == 50)
                                    break;
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
                    <div>
                        <h4>Key executives</h4>
                        <?php
                        if (($p[9] == $p[10]) && ($p[9] == $p[11])) {
                            echo "<p><b>CEO, MD & Director - </b>" . $p[9] . "</p>";
                        }
                        if (($p[9] == $p[10]) && ($p[9] != $p[11])) {
                            echo "<p><b>CEO & MD - </b>" . $p[9] . "</p>";
                            echo "<p><b>Director - </b>" . $p[11] . "</p>";
                        }
                        if (($p[9] == $p[11]) && ($p[9] != $p[10])) {
                            echo "<p><b>CEO & Director - </b>" . $p[9] . "</p>";
                            echo "<p><b>MD - </b>" . $p[10] . "</p>";
                        }
                        if (($p[10] == $p[11]) && ($p[9] != $p[10])) {
                            echo "<p><b>CEO - </b>" . $p[9] . "</p>";
                            echo "<p><b>MD & Director - </b>" . $p[10] . "</p>";
                        }
                        if (($p[9] != $p[11]) && ($p[9] != $p[10]) && ($p[10] != $p[11])) {
                            echo "<p><b>CEO - </b>" . $p[9] . "</p>";
                            echo "<p><b>MD - </b>" . $p[10] . "</p>";
                            echo "<p><b>Director - </b>" . $p[11] . "</p>";
                        }
                        echo "<br><br><br>";
                        ?>
                    </div>
                    <div>
                        <h4>Address:</h4>
                        <?php
                        // echo $p[5];
                        echo "<p>" . $p[0] . ",</p>";
                        echo "<p>" . $p[1] . "-" . $p[2] . "," . $p[3] . ".</p>";
                        echo "<p>Contact No:- +" . $p[4] . "</p>";
                        ?>
                    </div>
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
    </main>
    <footer>
        <div class="inner-footer">
            <div class="logo1">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                    <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" />
                </svg>

            </div>
            <div class="footer-third">
                <h1>Need Help?</h1>
                <a href="#">Terms &amp; Conditions</a>
                <a href="https://raw.githubusercontent.com/tanujdey7/Project/master/LICENSE">Privacy Policy</a>
            </div>
            <div class="footer-third">
                <h1>Pages</h1>
                <a href="index.php">Home</a>
                <a href="nifty-companies.php">NSE Companies</a>
                <a href="dashboard.php">Dashboard</a>
            </div>
            <div class="footer-third">
                <h1>Developed By</h1>
                <a href="https://github.com/tanujdey7"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg>&nbsp;&nbsp; Tanuj Dey </a>
                <a href="https://github.com/maruf212000"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg> &nbsp;&nbsp;Maruf Memon</a>
                <span>
                    Stock Predictors &copy; 2020<br>
                    GLS University <br>
                    Faculty of Computer Applications &amp; IT <br>
                </span>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tabs = document.querySelectorAll(".tabbed li");
            for (var i = 0, len = tabs.length; i < len; i++) {
                tabs[i].addEventListener("click", function() {
                    if (this.classList.contains("active1")) return;

                    var parent = this.parentNode,
                        innerTabs = parent.querySelectorAll("li");

                    for (
                        var index = 0, iLen = innerTabs.length; index < iLen; index++
                    ) {
                        innerTabs[index].classList.remove("active1");
                    }

                    this.classList.add("active1");
                });
            }

            for (var i = 0, len = switchers.length; i < len; i++) {
                switchers[i].addEventListener("click", function() {
                    if (this.classList.contains("active1")) return;

                    var parent = this.parentNode,
                        innerSwitchers = parent.querySelectorAll("a"),
                        skinName = this.getAttribute("skin");

                    for (
                        var index = 0, iLen = innerSwitchers.length; index < iLen; index++
                    ) {
                        innerSwitchers[index].classList.remove("active1");
                    }

                    this.classList.add("active1");
                    skinable.className = "tabbed round " + skinName;
                });
            }
        });
    </script>
</body>

</html>