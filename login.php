    <?php
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\Exception; 
    
    require 'vendor/autoload.php'; 
    include 'database.php';
    if (isset($_SESSION["username"])) {
        $s1 = "SELECT * FROM login WHERE (Username = '" . $_SESSION["username"] . "' OR Email='" . $_SESSION["username"] . "')" . " AND Password='" . $_SESSION["password"] . "';";
        $result = $con->query($s1);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows == 1) {
            header("Location: nifty-companies.php");
        }
    }
    $pass = array();
    if(isset($_POST["signup"]))
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'; //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 6; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
        $pass = implode($pass);
        //echo $pass;
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
                    <!-- <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div> -->
                    <span>or use your email for registration</span><br>
                    <input type="text" placeholder="Name" name="name" required />
                    <input type="email" placeholder="Email" name="email" required />
                    <input type="password" placeholder="Password" name="password" required />
                    <input type="checkbox" name="remember" style="" />Remember Me
                    <input class="submit" type="submit" name="signup" value="Sign Up" />
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    <script>
                        function nice1()
                        {
                            swal(
                                'Mail Sent',
                                'Password sent to your mail id',
                                'success'
                                )
                        }
                    </script>
                    <script>
                    function nice2()
                        {
                            swal(
                                'Oops!',
                                'Looks like you do not have account, please register ',
                                'error'
                                )
                        }
                    </script>
                    <script>
                        function nice() {
                            swal({  
                                    title: 'Please Check Your Mail!',
                                    text: 'Enter Your One Time Password (OTP)',
                                    content: "input",
                                    button: {
                                        text: "Confirm!",
                                        closeModal: false,
                                    },
                                })
                                .then(otp => {
                                    if (otp != "<?php echo $pass; ?>") {
                                        swal("Please Enter Correct OTP", {
                                            icon: "warning",
                                            title: 'Please Check Your Mail!',
                                            text: 'Enter Your One Time Password (OTP)',
                                            content: "input",
                                            button: {
                                                text: "Confirm!",
                                                closeModal: false,
                                            },
                                        }).then(otp => {
                                            if (otp != "<?php echo $pass; ?>") {
                                                swal("Unsuccessful", "Please Try Again!", "error").then(function(){
                                                    window.location.assign('signupfail.php');
                                                })
                                            }
                                            if (otp == "<?php echo $pass; ?>") {
                                                swal("Successful!", {
                                                    icon: "success"
                                                }).then(function(){
                                                    window.location.assign('signupsrc.php');
                                                });
                                            }
                                        })
                                    }
                                    if (otp == "<?php echo $pass; ?>") {
                                        swal("Successful!", {
                                            icon: "success",
                                            type: "success" 
                                        }).then(function(){
                                            window.location.assign('signupsrc.php');
                                        });
                                    }
                                })
                            // ${otp}
                        }
                    </script>
                </form>
            </div>
            <div class="form-container sign-in-container">
                <form action="" method="POST">
                    <h1>Sign in</h1>
                    <!-- <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div> -->
                    <span>or use your account</span><br>
                    <input type="text" placeholder="Email" name="username" required />
                    <input type="password" placeholder="Password" name="pass" required />
                    <a onclick="forgot_nice()" href="#">Forgot your password?</a>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    <script>
                        function forgot_nice() {
                            swal({
                                    title: 'Please Enter Your Credentials',
                                    content: {
                                        element: "input",
                                        attributes: {
                                            placeholder: "Type your E-mail/Username",
                                        },
                                    },
                                    button: {
                                        text: "Confirm!",
                                        closeModal: false,
                                    },
                                })
                                .then(email => {
                                    if (email != "admin@gmail.com") {
                                        swal("Please Enter Correct E-Mail/Username", {
                                            icon: "warning",
                                            title: 'Please Enter Your E-Mail/Username!',
                                            content: "input",
                                            button: {
                                                text: "Confirm!",
                                                closeModal: false,
                                            },
                                        }).then(email => {
                                            if (email != "admin@gmail.com") {
                                                swal("Unsuccessful", "Please Try Again!", "error");
                                            }
                                            if (email == "admin@gmail.com") {
                                                swal("Your Password has been send to your E-Mail! 😉", {
                                                    icon: "success"
                                                });
                                            }
                                        })
                                    }
                                    if (email == "admin@gmail.com") {
                                        swal("Your Password has been send to your E-Mail! 😉", {
                                            icon: "success"
                                        });
                                    }
                                })
                        }
                    </script>
                    <input type="submit" class="submit" name="signin" value="Sign In" />
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
    if (isset($_POST["signup"])) {
        $_SESSION["a"] = $_POST["name"];
        $_SESSION['b'] = $_POST["email"];
        $_SESSION['c'] = $_POST["password"];
        if(isset($_POST['remember']))
        {
            $_SESSION['d'] = $_POST["remember"];
        }
        $s2 = "SELECT User_ID FROM user ORDER BY User_ID DESC LIMIT 1";
        $r = $con->query($s2);
        echo $con->error;
        $uid = mysqli_fetch_row($r);
        $userid = $uid[0] + 1;
        $mail = new PHPMailer(true); 
        $msg = "<p style='font-size:20px;'>Your One time Password is:- </p>
                <div style='width:200px;background-color:#ccc;font-size:30px;height:200px;text-align:center;'>
                <p style='padding-top:39%;'><b>" . $pass ."</b></p>
        </div>";
        try { 
            $mail->SMTPDebug = 0;                                        
            $mail->isSMTP();                                             
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                              
            $mail->Username   = 'developer.predictor@gmail.com';                  
            $mail->Password   = 'predictor@5511';                         
            $mail->SMTPSecure = 'tls';                               
            $mail->Port       = 587;   
        
            $mail->setFrom('developer.predictor@gmail.com', 'Stock Predictor');            
            $mail->addAddress($_POST["email"]);  
            
            $mail->isHTML(true);                                   
            $mail->Subject = 'Registration Confirmation'; 
            $mail->Body    = $msg; 
            $mail->AltBody = 'Body in plain text for non-HTML mail clients'; 
            $mail->send();  
            //echo "Mail sent successfully";
            //echo "<script>nice1();</script>";
        } catch (Exception $e) { 
        
        }
        echo "<script>nice();</script>";    
    }
    if (isset($_POST["signin"])) {
        $d = $_POST["username"];
        $e = $_POST["pass"];
        $s1 = "SELECT Password FROM login where Username = '" . $d . "' OR Email='" . $d . "';";
        $result = $con->query($s1);
        $ans = mysqli_fetch_row($result);
        if ($e == $ans[0]) {
            $_SESSION["username"] = $d;
            $_SESSION["password"] = $e;
        } else {
            echo "<script>alert('Username or Password is incorrect')</script>";
        }
        if (isset($_POST["remember"])) {
            if ($_POST["remember"] == '1' || $_POST["remember"] == 'on') {
                $hour = time() + 3600 * 24 * 30;
                setcookie('username', $b, $hour);
                setcookie('password', $c, $hour);
            }
        }
        echo "<script>window.location.assign('nifty-companies.php')</script>";
    }
    if (isset($_GET["status"])) {
        $status = $_GET["status"];
        if ($status == "done") {
            echo "<script>nice1();</script>";
        }
        if ($status == "fail") {
            echo "<script>nice2();</script>";
        }
    }
    ?>