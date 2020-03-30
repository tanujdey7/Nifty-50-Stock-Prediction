<?php
$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
$pass = array(); //remember to declare $pass as an array
$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
for ($i = 0; $i < 6; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
}
$pass = implode($pass);   

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
  
require '../vendor/autoload.php'; 
  
$mail = new PHPMailer(true); 
$msg = "<p style='font-size:20px;'>Your One time Password is:- </p>
        <div style='width:200px;background-color:#ccc;font-size:30px;height:200px;text-align:center;'>
        <p style='padding-top:39%;'><b>" . $pass ."</b></p>
</div>";
try { 
    $mail->SMTPDebug = 2;                                        
    $mail->isSMTP();                                             
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                              
    $mail->Username   = 'developer.predictor@gmail.com';                  
    $mail->Password   = 'predictor@5511';                         
    $mail->SMTPSecure = 'tls';                               
    $mail->Port       = 587;   
  
    $mail->setFrom('developer.predictor@gmail.com', 'Stock Predictor');            
    $mail->addAddress('sagaf.memon.sm@gmail.com');  
       
    $mail->isHTML(true);                                   
    $mail->Subject = 'Registration Confirmation'; 
    $mail->Body    = $msg; 
    $mail->AltBody = 'Body in plain text for non-HTML mail clients'; 
    $mail->send();  
} catch (Exception $e) { 
 
} 
  
?>
<html>
<p style="font-size:20px;">Your One time Password is:- </p>
<div style='width:200px;background-color:#ccc;font-size:30px;height:200px;text-align:center;'>
    <p style='position:relative;top:39%;'><?php echo "<b>" . $pass . "</b>";?></p>
</div>
</html>