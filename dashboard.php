<?php
    session_start();
    //echo $_SESSION["username"];
    //print_r($_SESSION);
    $con = mysqli_connect("localhost","root","","predictor");
    if(!$con)
         die("Connection error:- " + mysqli_connect_error());
    $flag = true;
    if(isset($_SESSION["username"]))
    {
        $s1 = "SELECT * FROM login";
        $result = $con->query($s1);
        if($result->num_rows > 0)
        {
            while($row = mysqli_fetch_row($result))
            {
                if(($row[1] == $_SESSION["username"] || $row[2] == $_SESSION["username"]) && $row[3] == $_SESSION["password"])
                {
                    $flag = true;
                }
            }
        }
    }    
    if($flag == false)
    {
        header("Location: login.php");
    }
    $s2 = "SELECT * FROM user WHERE Username = '" . $_SESSION["username"] . "' OR Email = '" . $_SESSION["username"] . "';";
    $result = $con->query($s2);
    $row = mysqli_fetch_row($result);
    if(isset($_POST["submit"]))
    {
        $s3 = "UPDATE user SET Last_Name='" . $_POST["Last_Name"] . "', Age = ' " . $_POST["Age"] . " ', Username='" . $_POST["Username"] . "' WHERE Email = '" . $row[4] . "';";
        $con->query($s3);
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title> Dashboard </title>
    <link rel="stylesheet" type="text/css" href="vendors/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="resources/css/styledash.css"> 
    <link rel="stylesheet" type="text/css" href="vendors/css/grid.css" >
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400&display=swap" rel="stylesheet">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        .dinput
        {
            height: 40px;
            background-color: #e6e6e6;
            border-radius: 20px;
            border: none;
            width: 200px;
            font-size: 15px;
            border-color: #e6e6e6;
            padding: 0 30px;
        }
        span
        {
            color: #666666;
            font-size: 20px;
        }
    </style>    
</head>
<body>
    <header>
        <!--
            1. Navbar
            2. Font
        -->
    </header>
    <div class="dash">
        <div class="profile">
            <h1>YOUR PROFILE DASHBOARD</h1>
            <h2>Profile</h2>
            <div class="per_info">
                <img src="resources/img/profile_img.jpg" class="img_profile">
                <p><?php echo $row[4]; ?></p>
                <button id="show" style="border:none;background:none;color:#06A2D7;margin-left:40px;">Update Account Information</button>
            </div>
          <div>
                <p>First Name: <?php echo $row[1]; ?></p><hr>
                <p>Last Name: <?php echo $row[2]; ?></p><hr>
                <p>Username: <?php echo $row[5]; ?></p><hr>
                <p>Age: <?php echo $row[3]; ?></p><hr>
          </div> 
        </div>
        <dialog id="myFirstDialog" style="width:50%;background-color:#F4FFEF;border:1px dotted black;">  
            <h2>Update Information</h2>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td><span>First Name:- </span></td>
                        <td><input type="text" name="First_Name" value="<?php echo $row[1]; ?>" disabled class="dinput"/></td>
                    </tr>
                    <tr>
                        <td><span>Last Name:- </span></td>
                        <td><input type="text" name="Last_Name" value="<?php echo $row[2]; ?>" class="dinput" required/></td>
                    </tr>
                    <tr>
                        <td><span>Age:- </span></td>
                        <td><input type="number" name="Age" min="18" value="<?php echo $row[3]; ?>" class="dinput" required/></td>
                    </tr>
                    <tr>
                        <td><span>Email:- </span></td>
                        <td><input type="text" name="Email" value="<?php echo $row[4]; ?>" class="dinput" disabled/></td>
                    </tr>
                    <tr>
                        <td><span>Username:- </span></td>
                        <td><input type="text" name="Username" value="<?php echo $row[5]; ?>" class="dinput" required/></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" value="Update"/></td>
                        <td><button id="hide">Close</button></td>
                    </tr>
                </table>        
            </form> 
        </dialog>
        <script type="text/JavaScript">  
            (function() {    
            var dialog = document.getElementById('myFirstDialog');    
            document.getElementById('show').onclick = function() {    
            dialog.show();    
        };    
            document.getElementById('hide').onclick = function() {    
             dialog.close();    
        };    
        })();   
    </script>
        <div class="activity">
            <h1>Your Activity</h1>
            <div>
               <!-- <table>
                <tr height="50px">
                    <td rowspan="2"><h3>November 2019</h3></td>
                </tr>
                <tr height="120px">
                    <td><div class="vertical"></div></td>
                    <td><div class="act_content">
                        qwertyuiopppasdfghjklzxcvbnm
                    </div></td>
                </tr>
                <tr height="100px">    
                    <td rowspan="2"><h3>December 2019</h3></td>
                </tr>
                <tr height="130px">
                    <td><div class="vertical"></div></td>
                    <td><div class="act_content">
                        zxcvbnmasdfghjklqwertyuiopzxcvbnmasdfghj<br>
                        qwertyuiopasdfghjklmnbvcxzlkjhgfdsapoiuytrewq
                    </div></td>
                </tr>
                <tr>
                    <td rowspan="2"><h3>January 2020</h3></td>
                </tr>
                </table>-->
                <h3>October 2019</h3>
                <div class="vertical">asdfghjklpoiuytrewqzxcvbnm</div>
                <h3>November 2019</h3>
                <div class="vertical">qwertyuiiopasdfghjklzxcvbnm<br>
                    asdfghjklzxcvbnmqwertyuiop
                </div>
                <h3>December 2019</h3>
                <hr>
                <h2>Suggested Companies <a href="#" class="Companies"><small>View all &gt;</small></a></h2>

            </div>
        </div>
    </div>
</body>
</html>
