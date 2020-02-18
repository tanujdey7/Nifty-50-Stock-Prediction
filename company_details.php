<?php
    if(count($_POST)>0) {
        foreach($_POST as $key=>$value) {
            if(empty($_POST[$key])) {
                $message = ucwords($key) . " field is required";
                break;
            }
        }
        if(!isset($message)) {
            if(!filter_var($_POST["comp_id"], FILTER_VALIDATE_INT)) {
            $message = " company id is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["comp_name"])) {
            $message = "company name is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["comp_website"])) {
            $message = "company website is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["type"])) {
            $message = "company detail is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["size"])) {
            $message = "company size is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["revenue"])) {
            $message = "company revenue is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["hq"])) {
            $message = "company headquater is required";
            }
        }
        if(!isset($message)) {
            if(!filter_var($_POST["year"], FILTER_VALIDATE_INT)) {
            $message = " establishment year is required";
            }
        }
        if(!isset($message)) {
            if(!isset($_POST["industry"])) {
            $message = "industry detail is required";
            }
        }
        if(!isset($message)) {
            $message = "Company details added successful";
        }
    }
?>
<html>
<head>
    <title>Company Details</title>
</head>
<body>
    <form name="formcompany_details" method="post" action="company_details.php">
    <div align="center" class="message"><?php if(isset($message)) echo $message; ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
    <tr class="tableheader">
        <td align="center" colspan="2">Company Details Form</td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company ID</td>
        <td><input type="number" name="comp_id"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Name</td>
        <td><input type="text" name="comp_name"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Website</td>
        <td><input type="text" name="comp_website"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Description</td>
        <td><input type="text" name="type"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Size</td>
        <td><input type="text" name="size"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Revenue</td>
        <td><input type="text" name="revenue"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Company Headquater</td>
        <td><input type="text" name="hq"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Founded in</td>
        <td><input type="number" name="year"></td>
    </tr>
    <tr class="tablerow">
        <td align="right">Industry</td>
        <td><input type="text" name="industry"></td>
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
            $table = "CREATE TABLE Company_details
        (
        Company_id int(20),
        Company_name varchar(20),
        Company_website varchar(20),
        Company_description varchar(30),
        Company_size varchar(20),
        Company_revenue varchar(20),
        Company_headquater varchar(20),
        Founded int(10),
        Industry varchar(20)
        );";

        mysqli_query($con,$table);
        }

        if(isset($_POST["submit"]))
        {
        $cid = $_POST["comp_id"];
        $cname = $_POST["comp_name"];
        $cwname = $_POST["comp_website"];
        $cdesc = $_POST["type"];
        $csize = $_POST["size"];
        $crev = $_POST["revenue"];
        $chq = $_POST["hq"];
        $fyear = $_POST["year"];
        $cind = $_POST["industry"];
        $mysql = "INSERT INTO Company_details(Company_id,Company_name,Company_website,Company_description,Company_size,Company_revenue,Company_headquater,Founded,Industry) VALUES('$cid','$cname','$cwname','$cdesc','$csize','$crev','$chq','$fyear','$cind');";
        mysqli_query($con,$mysql);
        echo mysqli_error($con);
        }
    ?>
    </body>
</html>