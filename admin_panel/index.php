<?php
	session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="@housamz">
	<meta name="description" content="Mass Admin Panel">
	<title>Admin Panel</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-h21C2fcDk/eFsW9sC9h0dhokq5pDinLNklTKoxIZRUn3+hvmgQSffLLQ4G4l2eEr" crossorigin="anonymous">
</head>

<body>
	<div class="container" style="margin-top:30px">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<h1 class="text-center">Admin Panel</h1>
				<h2 class="text-center">Sign in</h2>
				<div>
					<form action="index.php" method="post" name="login">
					<input type="text" class="form-control" placeholder="Email" name="email" required autofocus><br>
					<input type="password" class="form-control" placeholder="Password" name="password" required><br>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="signin">
						Sign in</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
     $con = mysqli_connect("localhost","root","root","predictor");
     if(!$con)
         die("Connection error:- " + mysqli_connect_error());
    if(isset($_POST["signin"]))
    {
        $d = $_POST["email"];
        $e = $_POST["password"];
        $s1 = "SELECT Password FROM login where Username = '" . $d . "' OR Email='" . $d . "';";
        $result = $con->query($s1);
        $ans = mysqli_fetch_row($result);
        if($e == $ans[0])
        {
            $_SESSION["username1"] = $d;
            $_SESSION["password2"] = $e;
            header("Location: home.php");
        }
        else
        {
            echo "<script>alert('Username or Password is incorrect')</script>";
        }
    }
?>