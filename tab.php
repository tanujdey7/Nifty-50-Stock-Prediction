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
    <meta charset="UTF-8" />
    <title>CodePen - Modern Tabs Design with pure CSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Varela+Round" />
    <!-- <link rel="stylesheet" href="./style.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: #e8ecef;
            height: 100%;
            width: 100%;
            text-align: center;
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
    </style>
</head>

<body onload="openCity(event, 'Tokyo')">
    <br />
    
    <div class="tabbed skin-graphite round" id="skinable">
        <!-- <div class="tab"> -->
        <ul>
            <li onclick="openCity(event, 'London')">London</li>
            <li onclick="openCity(event, 'Paris')">Paris</li>
            <li class="active1" onclick="openCity(event, 'Tokyo')">Tokyo</li>
        </ul>
        
    </div>
        <div id="London" class="tabcontent">
            <h3>London</h3>
            <p>London is the capital city of England.</p>
        </div>
        
        <div id="Paris" class="tabcontent">
            <h3>Paris</h3>
            <p>Paris is the capital of France.</p>
        </div>
        
        <div id="Tokyo" class="tabcontent">
            <h3>Tokyo</h3>
            <p>Tokyo is the capital of Japan.</p>
        </div>
    <script>
        
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            document.getElementById(cityName).style.display = "block";
            // evt.currentTarget.className += " active1";
        }
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