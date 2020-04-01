<?php
include 'database.php';
$s3 = "Select Email,Password from login where User_ID = '" . $id . "';";
    echo $s3;
    $res = $con->query($s3);
    echo $con->error;
    $row = mysqli_fetch_row($res);
    $_SESSION["username"] = $row[0];
    $_SESSION["password"] = $row[1];
    print_r($row);
    echo "<script>window.location.assign('dashboard.php')</script>";
    ?>