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
            <i><style>.logo{fill:aliceblue;}</style>
                <svg class="logo" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z"/></svg>
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
    <center>
        <table class="content-table">
            <style>.content-table {
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 0.9em;
                min-width: 400px;
                border-radius: 5px 5px 0 0;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            
            .content-table thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: left;
                font-weight: bold;
            }
            
            .content-table th,
            .content-table td {
                padding: 12px 15px;
            }
            
            .content-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }
            
            .content-table tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }
            
            .content-table tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }
            
            .content-table tbody tr.active-row {
                font-weight: bold;
                color: #009879;
            }
            </style>
        <thead>
            
            <tr>
                <th>Serial Number</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Open</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
        </thead>
        <tbody>
            <?php
                        $st1 = "select s_c_details.Comp_Name,stock_details.Symbol,stock_details.Open,stock_details.Close,stock_details.Volume
                        from s_c_details JOIN stock_details ON s_c_details.Comp_ID=stock_details.Comp_ID;";
                        $res1 = $con->query($st1);
                        echo $con->error;
                        $i = 1;
                        if($res1->num_rows > 0)
                        {
                            while($row = mysqli_fetch_row($res1))
                            {?>
                                <?php
                                    if($i % 2 == 0) {
                                        ?>
                                        <tr class="active-row">
                                            <style>.link{color:#009879;}</style>
                                            <td> <?php echo $i?></td>
                                            <td><a href="stock_data/graph.php?id=<?php echo $row[1];?>" class="link"> <?php echo $row[0] ?></a></td>
                                            <?php
                                    }
                                    else {
                                        ?>
                                        <tr>
                                            <style>.link1{color:black;}</style>
                                            <td> <?php echo $i?></td>
                                            <td><a href="stock_data/graph.php?id=<?php echo $row[1];?>" class="link1"> <?php echo $row[0] ?></a></td>
                                            <?php
                                    }
                                    ?>
                                    <td> <?php echo $row[1] ?></td>
                                    <td> <?php echo $row[2] ?></td>
                                    <td> <?php echo $row[3] ?></td>
                                    <td> <?php echo $row[4] ?></td>
                                </tr>
                                <?php $i+=1;
                            }
                        }
                        
                        ?>
            </tbody>
        </table>
    </center>
        <script src="resources/css/nse.js"></script>
    </body>
    </html>