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
    $s2 = "SELECT * FROM user WHERE Username = '" . $_SESSION["username"] . "' OR Email = '" . $_SESSION["username"] . "';";
    $result = $con->query($s2);
    $row = mysqli_fetch_row($result);
    if($row[7]=="")
    {
        $img = "resources/img/profile_img.jpg";
    }
    else
    {
        $img = $row[7];
    }
    $s4 = "SELECT * from s_c_details;";
    $result_c = $con->query($s4);
if(isset($_POST["submit"]))
{
    if(isset($_FILES['fileToUpload']))
    {
        $target_path = "resources/img/profile/"; 
        $target_path = $target_path.basename( $_FILES['fileToUpload']['name']); 
        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path))
        {
            unlink($row[7]);
        }
        else
        {
            $target_path = $row[7];
        }
    }
    if($target_path == "resources/img/profile/")
    {
    }
    $s3 = "UPDATE user SET Last_Name='" . $_POST["Last_Name"] . "', Age = ' " . $_POST["Age"] . " ', Username='" . $_POST["Username"] . "', img = '" . $target_path . "' WHERE Email = '" . $row[4] . "';";
    $con->query($s3);
    echo '<meta http-equiv="refresh" content="0">';
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
                <img src="<?php echo $img ?>" class="img_profile" style="min-width:150px;min-height:100px;">
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
            <form action="" method="POST" enctype="multipart/form-data">
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
                        <td><span>Select Image:-</span></td>
                        <td><input type="file" name="fileToUpload"/></td>
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
        <h2>Suggested Companies <a href="#" class="Companies"><small>View all &gt;</small></a></h2>
        <?php
        for($i=1;$i<5;$i++)
        {
            $row = mysqli_fetch_row($result_c);
            echo "<div>";
            echo '<img src = "' . $row[9] . '" class="img_comp">';
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>