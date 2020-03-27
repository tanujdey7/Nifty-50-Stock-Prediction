<?php
$con = mysqli_connect("localhost","root","");
if(!$con)
    die("Connection error:- " + mysqli_connect_error());
    /*$s1 = "CREATE DATABASE Predictor";
    $con->query($s1);*/
    $s2 = "USE Predictor";
    $con->query($s2);
    echo $con->error;
    $s3 = "CREATE TABLE User
            (
                User_ID int(6) AUTO_INCREMENT,
                First_Name varchar(20),
                Last_Name varchar(20),
                Age int(3),
                Email varchar(40),
                Username varchar(30),
                Password varchar(30),
                PRIMARY KEY(User_ID)
            )    ";
    $con->query($s3);
    echo $con->error;
    $s4 = "CREATE TABLE Login
            (  
                User_ID int(6) AUTO_INCREMENT,
                Username varchar(30),
                Password varchar(30),
                FOREIGN KEY (User_ID) REFERENCES User(User_ID)
            );";
    $con->query($s4);
    echo $con->error;
    $s5 = "CREATE TABLE S_C_Details
            (
                Comp_ID int(6) AUTO_INCREMENT,
                Comp_Name varchar(30) NOT NULL,
                Comp_Website varchar(50),
                Type varchar(50),
                Headquaters varchar(50),
                Founded int(5),
                Industry varchar(30),
                Img blob,
                PRIMARY KEY(Comp_ID)
            );";
    $con->query($s5);       
    echo $con->error;
    $s6 = "CREATE TABLE Stock_Details
            (
                Comp_ID int(6) AUTO_INCREMENT,
                Date date,
                Open double(20,5),
                Close double(20,5),
                Shares_traded double(20,5),
                Turnover double(20,5),
                FOREIGN KEY (Comp_ID) REFERENCES S_C_Details(Comp_ID)
            );";
    $con->query($s6);
    echo $con->error;
    $s7 = "CREATE TABLE Prediction
            (
                Prediction_ID int(6) AUTO_INCREMENT,
                Comp_ID int(6),
                User_ID int(6),
                Prediction double(20,5),
                Date date,
                Open double(20,5),
                Close double(20,5),
                Shares_Traded double(20,5),
                Turnover double(20,5),
                PRIMARY KEY(Prediction_ID),
                FOREIGN KEY (Comp_ID) REFERENCES S_C_Details(Comp_ID),
                FOREIGN KEY (User_ID) REFERENCES User(User_ID)
            );";
    $con->query($s7);
    echo $con->error;
    $s8 = "CREATE TABLE News
            (
                News_Name varchar(30),
                Date date,
                Content varchar(100)
            );";
    $con->query($s8);
    echo $con->error;                        
    
?>