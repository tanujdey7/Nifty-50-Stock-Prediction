<?php
    if(count($_POST)>0) {
        foreach($_POST as $key=>$value) {
            if(empty($_POST[$key])) {
                $message = ucwords($key) . " field is required";
                break;
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["news_id"])) {
            $message = "news id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["user_id"])) {
            $message = "user id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["news_name"])) {
            $message = "name of news provider is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["date"])) {
            $message = "date is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["content"])) {
            $message = "news content is required";
            }
        }
        if(!isset($message)) {
            $message = "News details added successful";
        }
    }
?>
<html>
<head>
    <title>News Details</title>
</head>
<body>
    <form name="formnews_details" method="post" action="news.php">
    <div align="center" class="message"><?php if(isset($message)) echo $message; ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
    <tr class="tableheader">
        <td align="center" colspan="2">News Details Form</td>
    </tr>
    <tr class="tablerow">
        <td align="right">News Id</td>
        <td><input type="number" name="news_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">User Id</td>
        <td><input type="number" name="user_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">News Name</td>
        <td><input type="text" name="news_name"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Date</td>
        <td><input type="text" name="date"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">News Content</td>
        <td><input type="text" name="content"></td>
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
            $table = "CREATE TABLE News
        (
        News_id varchar(20),
        User_id varchar(20),
        News_name varchar(30),
        Dateof_News date,
        News_content varchar(20)
        );";

        mysqli_query($con,$table);
        }

        if(isset($_POST["submit"]))
        {
        $nid = $_POST["news_id"];
        $uid = $_POST["user_id"];
        $nname = $_POST["news_name"];
        $ndate = $_POST["date"];
        $nc = $_POST["content"];
        $mysql = "INSERT INTO News(News_id,User_id,News_name,Dateof_News,News_content) VALUES('$nid','$uid','$nname','$ndate','$nc');";
        mysqli_query($con,$mysql);
        echo mysqli_error($con);
        }
    ?>
    </body>
</html>