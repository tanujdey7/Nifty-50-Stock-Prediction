<?php
    include 'database.php';
    $pass = $_POST["new_pass"];
    $id = $_POST["id"];
    $s1 = "UPDATE login SET Password='" . $pass . "' WHERE User_ID='" . $id . "';";
    $con->query($s1);
    $s2 = "UPDATE user SET Password='" . $pass . "' WHERE User_ID='" . $id . "';";
    $con->query($s2);
    $s3 = "Select Email,Password from login where User_ID = '" . $id . "';";
    echo $s3;
    $res = $con->query($s3);
    echo $con->error;
    $row = mysqli_fetch_row($res);
    $_SESSION["username"] = $row[0];
    $_SESSION["password"] = $row[1];
    print_r($row);
    //echo "<script>window.location.assign('dashboard.php')</script>";
?>