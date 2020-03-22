<?php
    // $con = mysqli_connect("localhost","root","","predictor");
    // if(!$con)
    //      die("Connection error:- " + mysqli_connect_error());
    //     $i=0;
    //     for($i=0;$i<50;$i++)
    //     {    
    //         $a1 = "nifty_comp.csv";
    //         $rows1 = fopen($a1,'r');
    //         while($rec = fgetcsv($rows1))
    //         {
    //             $s2 = "INSERT INTO s_c_details VALUES('" . $rec[0] . "','" . $rec[1] . "','" . $rec[2] . "','" . $rec[3] . "','" . $rec[4] . "','" . $rec[5] . "','" . $rec[6] . "','" . $rec[7] . "','" . $rec[8] . "','" . $rec[9] . "');";
    //             $con->query($s2);
    //         }
    //         echo $con->error;
    //     }
    $command = escapeshellcmd('try.py lol');
$output = shell_exec($command);
echo $output;
?>