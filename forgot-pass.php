<?php
    include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="vendors/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="resources/css/stylelogin.css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css'>
        <title> Forgot Password </title>
    </head>
    <body>
        <div style="height:300px;width:400px;border-radius:25px;position:relative;">
            <div class="" style="height:300px;border-radius:25px;">
                <form action="" method="POST">
                    <h2>Forgot your password?</h2>
                    <!-- <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div> -->
                    <span>Enter your email address</span><br>
                    <input type="email" placeholder="Email" name="email" required/>
                    <input class = "submit" type="submit" name="submit" value="Submit"/>
                </form>
            </div>
            </div>
        <script src="resources/js/login.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
                        function nice1()
                        {
                            swal("Successful", "Password sent to your mail id", "success").then(function(){
                                                    window.location.assign('login.php');
                                                })
                        }
                    </script>
                    <script>
                    function nice2()
                        {
                            swal("Unsuccessful", "Oops!Looks like you don't have account,please register", "error").then(function(){
                                                    window.location.assign('login.php');
                                                })
                        }
                    </script>
</body>
</html>
<?php
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\Exception; 
      
    require 'vendor/autoload.php'; 
    if(isset($_POST["submit"]))
    {        
        $email = $_POST["email"];
        $s1 = "Select * from user WHERE Email='" . $email . "';";
        $res = $con->query($s1);
        if($res->num_rows == 1)
        {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%&';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $pass = implode($pass);
            $s2 = "UPDATE user SET Password='" . $pass . "' WHERE Email='" . $email . "';";
            $con->query($s2);
            $s3 = "UPDATE login SET Password='" . $pass . "' WHERE Email='" . $email . "';";
            $con->query($s3);
            $mail = new PHPMailer(true); 
            $msg = "<p style='font-size:20px;'>Your password reset request has been generated:- </p>
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
                $mail->addAddress($email);  
                
                $mail->isHTML(true);                                   
                $mail->Subject = 'Password Reset'; 
                $mail->Body    = $msg; 
                $mail->AltBody = 'Body in plain text for non-HTML mail clients'; 
                $mail->send(); 
               // echo "Mail has been sent successfully!"; 
                echo "<script>nice1();</script>";
            } catch (Exception $e) { 
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
            }
        }
        else
        {
            echo "<script>nice2();</script>";
        } 
    }
?>