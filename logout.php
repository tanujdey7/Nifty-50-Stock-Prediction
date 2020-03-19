<?php
    session_start();
    session_unset();
    session_destroy();
    // print_r($_SESSION);
     header("Location: index.php");
?>