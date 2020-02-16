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
            if(!isset($_POST["firstname"])) {
            $message = "firstname is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["lastname"])) {
            $message = "lastname is required";
            }
        }
        if(!isset($message)) {
            if(!filter_var($_POST["age"], FILTER_VALIDATE_INT)) {
            $message = "age is required";
            }
        }
        if(!isset($message)) {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email";
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
            $message = "Registration successful";
        }
    }
?>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <form name="formRegistration" method="post" action="registration.php">
    <div align="center" class="message"><?php if(isset($message)) echo $message; ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
    <tr class="tableheader">
        <td align="center" colspan="2">Registration Form</td>
    </tr>
    <tr class="tablerow">
        <td align="right">UserId</td>
        <td><input type="number" name="userid"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">FirstName</td>
        <td><input type="text" name="firstname"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">LastName</td>
        <td><input type="text" name="lastname"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Age</td>
        <td><input type="number" name="age"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Email</td>
        <td><input type="email" name="email"></td>
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
            $table = "CREATE TABLE Registration
        (
        Userid int(20),
        Firstname varchar(20),
        Lastname varchar(20),
        Age int(10),
        Email varchar(30),
        Username varchar(20),
        Password varchar(20)
        );";

        mysqli_query($con,$table);
        }

        if(isset($_POST["submit"]))
        {
        $uid = $_POST["userid"];
        $fname = $_POST["firstname"];
        $lname = $_POST["lastname"];
        $age = $_POST["age"];
        $email = $_POST["email"];
        $uname = $_POST["username"];
        $password = $_POST["password"];
        $mysql = "INSERT INTO registration(Userid,Firstname,Lastname,Age,Email,Username,Password) VALUES('$uid','$fname','$lname','$age','$email','$uname','$password');";
        mysqli_query($con,$mysql);
        echo mysqli_error($con);
        }
    ?>
    </body>
</html>