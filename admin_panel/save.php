<?php
		include("includes/connect.php");
		$cat = $_POST['cat'];
		$cat_get = $_GET['cat'];
		$act = $_POST['act'];
		$act_get = $_GET['act'];
		$id = $_POST['id'];
		$id_get = $_GET['id'];
		
		if($cat == "login" || $cat_get == "login"){
			$User_ID = mysqli_real_escape_string($link,$_POST["User_ID"]);
			$Username = mysqli_real_escape_string($link,$_POST["Username"]);
			$Email = mysqli_real_escape_string($link,$_POST["Email"]);
			$Password = mysqli_real_escape_string($link,$_POST["Password"]);
			if($act == "add"){
				mysqli_query($link, "INSERT INTO `user` (  `User_ID` , `Email` , `Username` , `Password` ) VALUES ( '".$User_ID."' , '".$Email."' , '".$Username."' , '".$Password."') ");
				mysqli_query($link, "INSERT INTO `login` (  `User_ID` , `Username` , `Email` , `Password` ) VALUES ( '".$User_ID."' , '".$Username."' , '".$Email."' , '".$Password."' ) ");
				echo $link->error;
			}elseif ($act == "edit"){
				mysqli_query($link, "UPDATE `login` SET  `User_ID` =  '".$User_ID."' , `Username` =  '".$Username."' , `Email` =  '".$Email."' , `Password` =  '".$Password."'  WHERE `User_ID` = '".$id."' "); 	
				mysqli_query($link, "UPDATE `user` SET  `User_ID` =  '".$User_ID."' , `First_Name` =  '".$First_Name."' , `Last_Name` =  '".$Last_Name."' , `Age` =  '".$Age."' , `Email` =  '".$Email."' , `Username` =  '".$Username."' , `Password` =  '".$Password."' , `img` =  '".$img."'  WHERE `User_ID` = '".$id."' "); 	
			}elseif ($act_get == "delete"){
				mysqli_query($link, "DELETE FROM `login` WHERE User_ID = '".$id_get."' ");
				mysqli_query($link, "DELETE FROM `user` WHERE User_ID = '".$id_get."' ");
			}
			header("location:"."login.php");
		}
		if($cat == "prediction" || $cat_get == "prediction"){
			$Prediction_ID = mysqli_real_escape_string($link,$_POST["Prediction_ID"]);
			$Comp_ID = mysqli_real_escape_string($link,$_POST["Comp_ID"]);
			$User_ID = mysqli_real_escape_string($link,$_POST["User_ID"]);
			$Prediction = mysqli_real_escape_string($link,$_POST["Prediction"]);
			$Date = mysqli_real_escape_string($link,$_POST["Date"]);
			$Open = mysqli_real_escape_string($link,$_POST["Open"]);
			$Close = mysqli_real_escape_string($link,$_POST["Close"]);
			$Shares_Traded = mysqli_real_escape_string($link,$_POST["Shares_Traded"]);
			$Turnover = mysqli_real_escape_string($link,$_POST["Turnover"]);
			if($act == "add"){
				mysqli_query($link, "INSERT INTO `prediction` (  `Prediction_ID` , `Comp_ID` , `User_ID` , `Prediction` , `Date` , `Open` , `Close` , `Shares_Traded` , `Turnover` ) VALUES ( '".$Prediction_ID."' , '".$Comp_ID."' , '".$User_ID."' , '".$Prediction."' , '".$Date."' , '".$Open."' , '".$Close."' , '".$Shares_Traded."' , '".$Turnover."' ) ");
			}elseif ($act == "edit"){
				mysqli_query($link, "UPDATE `prediction` SET  `Prediction_ID` =  '".$Prediction_ID."' , `Comp_ID` =  '".$Comp_ID."' , `User_ID` =  '".$User_ID."' , `Prediction` =  '".$Prediction."' , `Date` =  '".$Date."' , `Open` =  '".$Open."' , `Close` =  '".$Close."' , `Shares_Traded` =  '".$Shares_Traded."' , `Turnover` =  '".$Turnover."'  WHERE `id` = '".$id."' "); 	
			}elseif ($act_get == "delete"){
				mysqli_query($link, "DELETE FROM `prediction` WHERE id = '".$id_get."' ");
			}
			header("location:"."prediction.php");
		}
		
		if($cat == "s_c_details" || $cat_get == "s_c_details"){
			$Comp_ID = mysqli_real_escape_string($link,$_POST["Comp_ID"]);
			$Comp_Name = mysqli_real_escape_string($link,$_POST["Comp_Name"]);
			$Comp_Website = mysqli_real_escape_string($link,$_POST["Comp_Website"]);
			$Headquaters = mysqli_real_escape_string($link,$_POST["Headquaters"]);
			$Founded = mysqli_real_escape_string($link,$_POST["Founded"]);
			$Industry = mysqli_real_escape_string($link,$_POST["Industry"]);
			$Symbol = mysqli_real_escape_string($link,$_POST["Symbol"]);
			$Series = mysqli_real_escape_string($link,$_POST["Series"]);
			$ISIN_Code = mysqli_real_escape_string($link,$_POST["ISIN_Code"]);
			$Img = mysqli_real_escape_string($link,$_POST["Img"]);
			echo $Comp_ID;

			if($act == "add"){
				mysqli_query($link, "INSERT INTO `s_c_details` (  `Comp_ID` , `Comp_Name` , `Comp_Website` , `Headquaters` , `Founded` , `Industry` , `Symbol` , `Series` , `ISIN_Code` , `Img` ) VALUES ( '".$Comp_ID."' , '".$Comp_Name."' , '".$Comp_Website."' , '".$Headquaters."' , '".$Founded."' , '".$Industry."' , '".$Symbol."' , '".$Series."' , '".$ISIN_Code."' , '".$Img."' ) ");
			}elseif ($act == "edit"){
				mysqli_query($link, "UPDATE `s_c_details` SET  `Comp_ID` =  '".$Comp_ID."' , `Comp_Name` =  '".$Comp_Name."' , `Comp_Website` =  '".$Comp_Website."' , `Headquaters` =  '".$Headquaters."' , `Founded` =  '".$Founded."' , `Industry` =  '".$Industry."' , `Symbol` =  '".$Symbol."' , `Series` =  '".$Series."' , `ISIN_Code` =  '".$ISIN_Code."' , `Img` =  '".$Img."'  WHERE `Comp_ID` = '".$id."' "); 	
			}elseif ($act_get == "delete"){
				mysqli_query($link, "DELETE FROM `s_c_details` WHERE Comp_ID = '".$id_get."' ");
			}
			echo $link->error;
			header("location:"."s_c_details.php");
}

if($cat == "stock_details" || $cat_get == "stock_details"){
	$Comp_ID = mysqli_real_escape_string($link,$_POST["Comp_ID"]);
	$Symbol = mysqli_real_escape_string($link,$_POST["Symbol"]);
	$Open = mysqli_real_escape_string($link,$_POST["Open"]);
	$Close = mysqli_real_escape_string($link,$_POST["Close"]);
	$Volume = mysqli_real_escape_string($link,$_POST["Volume"]);
	$Prev = mysqli_real_escape_string($link,$_POST["prev_close"]);
	echo $Comp_ID;
	
	if($act == "add"){
		mysqli_query($link, "INSERT INTO `s_c_details` (  `Comp_ID` , `Symbol` ) VALUES ( '".$Comp_ID."' , '".$Symbol."') ");
		mysqli_query($link, "INSERT INTO `stock_details` (  `Comp_ID` , `Symbol` , `Open` , `Close` , `Volume` , `prev_close`) VALUES ( '".$Comp_ID."' , '".$Symbol."' , '".$Open."' , '".$Close."' , '".$Volume. "' , '".$Prev."' ) ");
		echo $link->error;
	}elseif ($act == "edit"){
		mysqli_query($link, "UPDATE `stock_details` SET  `Comp_ID` =  '".$Comp_ID."' , `Symbol` =  '".$Symbol."' , `Open` =  '".$Open."' , `Close` =  '".$Close."' , `Volume` =  '".$Volume."' , `prev_close` = '".$Prev."'  WHERE `Comp_ID` = '".$id."' "); 	
	}elseif ($act_get == "delete"){
		mysqli_query($link, "DELETE FROM `stock_details` WHERE Comp_ID = '".$id_get."' ");
	}
	header("location:"."stock_details.php");
}

if($cat == "user" || $cat_get == "user"){
	$User_ID = mysqli_real_escape_string($link,$_POST["User_ID"]);
	$First_Name = mysqli_real_escape_string($link,$_POST["First_Name"]);
	$Last_Name = mysqli_real_escape_string($link,$_POST["Last_Name"]);
	$Age = mysqli_real_escape_string($link,$_POST["Age"]);
	$Email = mysqli_real_escape_string($link,$_POST["Email"]);
	$Username = mysqli_real_escape_string($link,$_POST["Username"]);
	$Password = mysqli_real_escape_string($link,$_POST["Password"]);
	$img = mysqli_real_escape_string($link,$_POST["img"]);
	
	if($act == "add"){
		mysqli_query($link, "INSERT INTO `login` (  `User_ID` , `Email` , `Username` , `Password` ) VALUES ( '".$User_ID."' ,'".$Email."' , '".$Username."' , '".$Password."') ");
		mysqli_query($link, "INSERT INTO `user` (  `User_ID` , `First_Name` , `Last_Name` , `Age` , `Email` , `Username` , `Password` , `img` ) VALUES ( '".$User_ID."' , '".$First_Name."' , '".$Last_Name."' , '".$Age."' , '".$Email."' , '".$Username."' , '".$Password."' , '".$img."' ) ");
		echo $link->error;
	}
	elseif ($act == "edit"){
		mysqli_query($link, "UPDATE `user` SET  `User_ID` =  '".$User_ID."' , `First_Name` =  '".$First_Name."' , `Last_Name` =  '".$Last_Name."' , `Age` =  '".$Age."' , `Email` =  '".$Email."' , `Username` =  '".$Username."' , `Password` =  '".$Password."' , `img` =  '".$img."'  WHERE `User_ID` = '".$id."' "); 	
		mysqli_query($link, "UPDATE `login` SET  `User_ID` =  '".$User_ID."' , `Username` =  '".$Username."' , `Email` =  '".$Email."' , `Password` =  '".$Password."'  WHERE `User_ID` = '".$id."' "); 	
	}
	elseif ($act_get == "delete"){
		mysqli_query($link, "DELETE FROM `login` WHERE User_ID = '".$id_get."' ");
		mysqli_query($link, "DELETE FROM `user` WHERE User_ID = '".$id_get."' ");
	}
	header("location:"."user.php");
}

if($cat == "users" || $cat_get == "users"){
	$name = mysqli_real_escape_string($link,$_POST["name"]);
	$email = mysqli_real_escape_string($link,$_POST["email"]);
	$password = mysqli_real_escape_string($link,$_POST["password"]);
	$role = mysqli_real_escape_string($link,$_POST["role"]);
	
	
	if($act == "add"){
		mysqli_query($link, "INSERT INTO `users` (  `name` , `email` , `password` , `role` ) VALUES ( '".$name."' , '".$email."' , '".md5($password)."', '".$role."' ) ");
	}elseif ($act == "edit"){
		mysqli_query($link, "UPDATE `users` SET  `name` =  '".$name."' , `email` =  '".$email."' , `role` =  '".$role."'  WHERE `id` = '".$id."' "); 	
	}elseif ($act_get == "delete"){
		mysqli_query($link, "DELETE FROM `users` WHERE id = '".$id_get."' ");
	}
	// header("location:"."users.php");
}
?>