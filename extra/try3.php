<?php
    include '../database.php';
    $s1 = "select Symbol from s_c_details;";
    $result = $con->query($s1);
    $file2 = fopen("percentage.csv","w");
    if($result->num_rows > 0)
    {
        while($row = mysqli_fetch_row($result))
        {
            $s2 = "../stock_data/" . $row[0] . ".csv";
            $file = file($s2);
            $readLines = max(0, count($file)-2); //n being non-zero positive integer

            if($readLines > 0) {
                for ($i = $readLines; $i < count($file); $i++) {
                    echo $file[$i] . "<br>";
                    $s3 = explode(",",$file[$i]);
                    fputcsv($file2,$s3);
                }
            } else {
                echo 'file does not have required no. of lines to read';
            }
        }
    }
?>