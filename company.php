<?php
$file = fopen("nifty-companies.csv","r");
$con = mysqli_connect("localhost","root","","predictor");
if(!$con)
    die("Connection error:- " + mysqli_connect_error());
$i = 0;
while(! feof($file))
  {
      $f = fgetcsv($file);
      if($i != 0)
      {
        //echo $f[0];
        $s1 = "INSERT INTO s_c_details(Comp_Name,Industry,Symbol,Series,ISIN_Code) VALUES('" . $f[0] . "','" . $f[1] . "','" . $f[2] . "','" . $f[3] . "','" .$f[4] . "');";
        $con->query($s1);
        echo $con->error; 
       }
      $i++;
  }
fclose($file);
?>