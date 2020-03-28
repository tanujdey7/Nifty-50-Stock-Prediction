<?php
    /*include '../database.php';
    $file2 = fopen("../profile.csv","r");
    $file = fopen("../desc.csv","r");
    $i = 1;
    print_r(fgetcsv($file2));
    while(! feof($file2))
    {
        $f1 = fgetcsv($file2);
        $s1 = "UPDATE SET Address='" . $f1[0] . "', City='" . $f1[1] . "', Pincode=" . $f1[2] . ", Country='" . $f1[3] . "', Contact='" . $f1[4] . "', Comp_Website='" . $f1[5] . "', Sector='" . $f1[6] . "', Industry='" . $f1[7] . "', Employee=" . $f1[8] . ", CEO='" . $f1[9] . "', Chairnam_MD='" . $f1[10] . "', Director='" . $f1[11] . "' WHERE Comp_ID=" . $i . ";";
        echo $s1 . "<br>";
        $i++;
    }*/
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="nifty50_mcwb.csv"');
    $url = "https://www1.nseindia.com/content/indices/nifty50_mcwb.csv";
   $file_name = "mar_cap.csv";
   file_put_contents( $file_name,file_get_contents($url));
?>