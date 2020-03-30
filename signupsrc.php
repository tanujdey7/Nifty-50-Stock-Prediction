<?php
    include 'database.php';
    if(isset($_SESSION["a"]))
    {
        $a = $_SESSION["a"];
        $b = $_SESSION["b"];
        $c = $_SESSION["c"];
        $d = $_SESSION["d"];
        $s1 = "INSERT INTO user(First_Name,Email,Password) VALUES('" . $a . "','" . $b . "','" . $c . "');";
        $s2 = "INSERT INTO login(Email,Password) VALUES('" . $b . "','" . $c . "');";
        if ($con->query($s1) && $con->query($s2)) {
            $_SESSION["username"] = $b;
            $_SESSION["password"] = $c;
        // header("Location: nifty-companies.php");
        }
        echo $con->error;
        if (isset($_SESSION["d"])) {
            if ($d == '1' || $d == 'on') {
                $hour = time() + 3600 * 24 * 30;
                setcookie('username', $b, $hour);
                setcookie('password', $c, $hour);
            }
        }
        echo $_SESSION["username"];
        echo $_SESSION["password"];
        unset($_SESSION['a']);
        unset($_SESSION['b']);
        unset($_SESSION['c']);
        unset($_SESSION['d']);
        echo "<script>window.location.assign('nifty-companies.php')</script>";
    }    
?>