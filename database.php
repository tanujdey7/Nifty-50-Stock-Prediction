<?php
$con = mysqli_connect("localhost","root","root  ","predictor");
    if(!$con)
         die("Connection error:- " + mysqli_connect_error());
         session_start();
?>