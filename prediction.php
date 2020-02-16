<?php
    if(count($_POST)>0) {
        foreach($_POST as $key=>$value) {
            if(empty($_POST[$key])) {
                $message = ucwords($key) . " field is required";
                break;
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["pred_id"])) {
            $message = "prediction id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["comp_id"])) {
            $message = "company id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["user_id"])) {
            $message = "user id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["pred"])) {
            $message = "prediction is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["date"])) {
            $message = "date is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["open"])) {
            $message = "open price of stock is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["close"])) {
            $message = "close price of stock is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["traded"])) {
            $message = "total traded quantity is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["turnover"])) {
            $message = "turnover amount is required";
            }
        }
        if(!isset($message)) {
            $message = "Stock details added successful";
        }
    }
?>
<html>
<head>
    <title>Prediction Details</title>
</head>
<body>
    <form name="formprediction_details" method="post" action="prediction_details.php">
    <div align="center" class="message"><?php if(isset($message)) echo $message; ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
    <tr class="tableheader">
        <td align="center" colspan="2">Prediction Table</td>
    </tr>
    <tr class="tablerow">
        <td align="right">Prediction Id</td>
        <td><input type="number" name="pred_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Id</td>
        <td><input type="number" name="comp_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">User Id</td>
        <td><input type="text" name="user_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Prediction</td>
        <td><input type="number" name="pred"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Date</td>
        <td><input type="text" name="date"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Open Price</td>
        <td><input type="text" name="open"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Close Price</td>
        <td><input type="text" name="close"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Shares Traded</td>
        <td><input type="text" name="traded"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Turnover</td>
        <td><input type="text" name="turnover"></td>
    </tr>
    <tr class="tableheader">
        <td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
    </tr>
    </table>
    </form>
<?php
        $servername ="localhost";
        $username = "root";
    
        $con = mysqli_connect($servername,$username,"","project");
        if(!$con)
            die("Connection Error:- " + mysqli_connect_error());
        {
            $table = "CREATE TABLE Prediction
        (
        Prediction_id varchar(20),
        Company_id varchar(20),
        User_id varchar(20),
        Prediction varchar(20),
        Dateof_Data date,
        Open_Price varchar(30),
        Close_Price varchar(20),
        Shares_Traded varchar(20),
        Turnover varchar(20)
        );";

        mysqli_query($con,$table);
        }

        if(isset($_POST["submit"]))
        {
        $pid=$_POST["pred_id"];
        $cid = $_POST["comp_id"];
        $uid = $_POST["user_id"];
        $pre=$_POST["pred"];
        $date = $_POST["date"];
        $open = $_POST["open"];
        $close = $_POST["close"];
        $st = $_POST["traded"];
        $tur = $_POST["turnover"];
        $mysql = "INSERT INTO Prediction(Prediction_id,Company_id,User_id,Prediction,Dateof_Data,Open_Price,Close_Price,Shares_Traded,Turnover) VALUES('$pid','$cid','$uid','$pre','$date','$open','$close','$st','$tur');";
        mysqli_query($con,$mysql);
        echo mysqli_error($con);
        }
    ?>
    </body>
</html>