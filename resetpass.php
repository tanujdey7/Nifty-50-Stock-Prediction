<?php
    include 'database.php';
    $pass = $_POST["new_pass"];
    $id = $_POST["id"];
    $s1 = "UPDATE login SET Password='" . $pass . "' WHERE User_ID='" . $id . "';";
    $con->query($s1);
    $s2 = "UPDATE user SET Password='" . $pass . "' WHERE User_ID='" . $id . "';";
    $con->query($s2);
    echo "<script>window.location.assign('bypass.php')</script>";
?>