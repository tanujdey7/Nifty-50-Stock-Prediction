<?php
    include 'database.php';
    $s1 = "DELETE FROM login WHERE User_ID = '" . $_POST['id'] . "';";
    $con->query($s1);
    echo $con->error;
    //echo $s1;
    $s2 = "DELETE FROM user WHERE User_ID = '" . $_POST['id'] . "';";
    $con->query($s2);
    echo $con->error;
    session_unset();
    session_destroy();
    setcookie('username','', time() - 3600);
    setcookie('password',"",time() - 3600);
    echo "<script>window.location.assign('index.php')</script>";
?>