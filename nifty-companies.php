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
    <style>
        *{
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}
html {
    font-weight: 300;
    font-size: 20px;
    text-rendering: optimizeLegibility;
    font-family: 'Raleway','Arial',sans-serif;
}
nav {
    display: flex;
    justify-content: space-around;
    align-items: center;
    min-height: 8vh;
    background-color: #26138e;
}

.logo {
    fill: aliceblue;
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
.burger div{
    width: 25px;
    height: 3px;
    background-color: rgb(226,226,226);
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
        background-color:  #1abc9c;
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
.nav-active{
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
    transform: rotate(-45deg) translate(-5px,6px);
}
.toggle .line2 {
    opacity: 0;
}
.toggle .line3 {
    transform: rotate(45deg) translate(-5px,-6px);
}
.nav-links li a:hover,
.nav-links li a:active{
    border-bottom: 2px solid #169e83;
    color: #26138e;
}
    </style>
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
    <style>.parallax {
    /* background-image:  */
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)),url("resources/css/img/nse-join2.jpg");
    /* min-height: 500px; */
    background-attachment:fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}</style>
    <div class="parallax">
    <center>
        <br>
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
                background-color: #1a0d60;
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
                /* background-color: #f3f3f3; */
            }            
            .content-table tbody tr{
                background-color: rgba(226,226,240,0.9);
                /* background-color: #f3f3f3; */
            }            
            .content-table tbody tr:last-of-type {
                border-bottom: 2px solid #1a0d60;
            }
            
            .content-table tbody tr:last-of-type {
                border-bottom: 2px solid #1a0d60;
            }
            .content-table tbody tr.active-row {
                font-weight: bold;
                color: #1a0d60;
            }

            </style>
        <thead>
            
        </style>
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Percentage Change</th>
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
                        $file = fopen("extra/percentage.csv","r");
                        $i = 1;
                        if($res1->num_rows > 0)
                        {
                            while($row = mysqli_fetch_row($res1))
                            {?>
                                <?php
                                    if($i % 2 == 0) {
                                        ?>
                                        <tr class="active-row">
                                            <style>.link{color:#1a0d60;}</style>
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
                                    $a1 = fgetcsv($file);
                                    $a2 = fgetcsv($file);
                                    $a4 = round($a1[4],3);
                                    $a3 = round((round($a2[4] - $a1[4],3)*100)/$a4,2);
                                    ?>
                                    <td> <?php echo $row[1] ?></td>
                                    <?php 
                                        if($a3 < 0)
                                        {
                                            echo "<td style='color:red;'>" . $a3 . "</td>";
                                        }
                                        else{
                                            echo "<td style='color:green;'>" . $a3 . "</td>";
                                        }
                                    ?>
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
    </div>
        <script src="resources/css/nse.js"></script>
    </body>
    </html>