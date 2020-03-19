<?php
    include 'database.php';
    if(isset($_SESSION["username"]))
    {
        $s1 = "SELECT * FROM login WHERE Username = '" . $_SESSION["username"] . "' AND Password='" . $_SESSION["password"] . "';";
        $result = $con->query($s1);
        $num_rows = mysqli_num_rows($result);
        if($num_rows != 1)
        {
            header("Location: login.php");
        }
    }
    else
    {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSE</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/css/style-nse-companies.css">
</head>
<body>
    <nav>
        <div class="logo">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z"/></svg>
            </i>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>
    <script src="resources/css/nse.js"></script>
    <table class="nifty">
        <tr>
            <th>Serial Number</th>
            <th>Name</th>
            <th>Symbol</th>
            <th>Open</th>
            <th>Close</th>
            <th>Volume</th>
        </tr>
        <?php
            $st1 = "select s_c_details.Comp_Name,stock_details.Symbol,stock_details.Open,stock_details.Close,stock_details.Volume
                        from s_c_details JOIN stock_details ON s_c_details.Comp_ID=stock_details.Comp_ID;";
            $res1 = $con->query($st1);
            echo $con->error;
            $i = 1;
            if($res1->num_rows > 0)
            {
                while($row = mysqli_fetch_row($res1))
                {
                    echo "<tr>";
                    echo "<td>" . $i ."</td>";
                    echo "<td>" . $row[0] . "</td>";
                    echo "<td>" . $row[1] . "</td>";
                    echo "<td>" . $row[2] . "</td>";
                    echo "<td>" . $row[3] . "</td>";
                    echo "<td>" . $row[4] . "</td>";
                    echo "</tr>";
                    $i+=1;
                }
            }

        ?>
</body>
</html>