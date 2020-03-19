    <?php
    session_start();
  //  echo $_SESSION["username"];
    //print_r($_SESSION);
    $con = mysqli_connect("localhost","root","root","predictor");
    if(!$con)
         die("Connection error:- " + mysqli_connect_error());
    if(isset($_SESSION["username"]))
    {
       $s1 = "SELECT * FROM login WHERE Username = '" . $_SESSION["username"] . "' AND Password='" . $_SESSION["password"] . "';";
       $result = $con->query($s1);
       $num_rows = mysqli_num_rows($result);
       if($num_rows == 1)
        {
            header("Location: dashboard.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="vendors/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="resources/css/stylelogin.css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css'>
        <title> Login </title>
    </head>
    <body>
        <div class="container" id="container">
            <div class="form-container sign-up-container">
                <form action="" method="POST">
                    <h1>Create Account</h1>
                    <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your email for registration</span>
                    <input type="text" placeholder="Name" name="name" required/>
                    <input type="email" placeholder="Email" name="email" required/>
                    <input type="password" placeholder="Password" name="password" required/>
                    <input class = "submit" type="submit" name="signup" value="Sign Up"/>
                </form>
            </div>
            <div class="form-container sign-in-container">
                <form action="" method="POST">
                    <h1>Sign in</h1>
                    <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your account</span>
                    <input type="text" placeholder="Email" name="username" required/>
                    <input type="password" placeholder="Password" name="pass" required/>
                    <a href="#">Forgot your password?</a>
                    <input type="submit" class = "submit" name="signin" value="Sign In"/>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with us please login with your personal info</p>
                        <button class="ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Hello, Friend!</h1>
                        <p>Enter your personal details and start journey with us</p>
                        <button class="ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="resources/js/login.js"></script>
</body>
</html>
<?php
     $con = mysqli_connect("localhost","root","","predictor");
     if(!$con)
         die("Connection error:- " + mysqli_connect_error());
    if(isset($_POST["signup"]))
    {        
        $a = $_POST["name"];
        $b = $_POST["email"];
        $c = $_POST["password"];
        $s2 = "SELECT User_ID FROM user ORDER BY User_ID DESC LIMIT 1";
        $r = $con->query($s2);
        echo $con->error;
        $uid = mysqli_fetch_row($r);
        $userid = $uid[0] + 1;
        $s1 = "INSERT INTO user(First_Name,Email,Password) VALUES('" . $a . "','" . $b . "','" . $c . "');";
        $s2 = "INSERT INTO login(Email,Password) VALUES('" . $b . "','" . $c . "');";
        if($con->query($s1) && $con->query($s2))
        {
            $_SESSION["username"] = $b;
            $_SESSION["password"] = $c;
            header("Location: nifty-companies.php");
        }   
    }
    if(isset($_POST["signin"]))
    {
        $d = $_POST["username"];
        $e = $_POST["pass"];
        $s1 = "SELECT Password FROM login where Username = '" . $d . "' OR Email='" . $d . "';";
        $result = $con->query($s1);
        $ans = mysqli_fetch_row($result);
        if($e == $ans[0])
        {
            $_SESSION["username"] = $d;
            $_SESSION["password"] = $e;
            header("Location: nifty-companies.php");
        }
        else
        {
            echo "<script>alert('Username or Password is incorrect')</script>";
        }
    }
?>