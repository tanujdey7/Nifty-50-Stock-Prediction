<?php
    if(count($_POST)>0) {
        foreach($_POST as $key=>$value) {
            if(empty($_POST[$key])) {
                $message = ucwords($key) . " field is required";
                break;
            }
        }
        if(!isset($message)) {
            if(!filter_var($_POST["userid"], FILTER_VALIDATE_INT)) {
            $message = " userid is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["username"])) {
            $message = "username is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["password"])) {
            $message = "password is required";
            }
        }
        if(!isset($message)) {
            $message = "Login successful";	
        }
    }
?>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form name="formRegistration" method="post" action="login.php">
    <div align="center" class="message"><?php if(isset($message)) echo $message; ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
    <tr class="tableheader">
        <td align="center" colspan="2">Login Page</td>
    </tr>
    <tr class="tablerow">
        <td align="right">UserId</td>
        <td><input type="number" name="userid"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Username</td>
        <td><input type="text" name="username"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Password</td>
        <td><input type="password" name="password"></td>
    </tr>
    <tr class="tableheader">
        <td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
    </tr>
    </table>
    </form>
<?php
        $servername ="localhost";
        $username = "root";
    
        $con = mysqli_connect($servername,$username,"","project");
        if(!$con)
            die("Connection Error:- " + mysqli_connect_error());
        {
            $table = "CREATE TABLE Login
        (
        Userid int(20),
        Username varchar(20),
        Password varchar(20)
        );";

        mysqli_query($con,$table);
        }

        if(isset($_POST["submit"]))
        {
        $uid = $_POST["userid"];
        $uname = $_POST["username"];
        $password = $_POST["password"];
        $mysql = "INSERT INTO Login(Userid,Username,Password) VALUES('$uid','$uname','$password');";
        mysqli_query($con,$mysql);
        echo mysqli_error($con);
        }
    ?>
    </body>
</html>