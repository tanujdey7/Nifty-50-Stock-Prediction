<?php
include "includes/header.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>Update</title>
    <style>
          .update
          {
           width:150px;
           height:50px;
           background-color:#26138e;
           border:1px solid #26138e;
           color:white;
           border-radius:10px;
           font-size:20px;
          }
    </style>
</head>
<body>
<div id="information" style="display:none;">Progress Bar</div>
<div id="progress" style="display:none;"></div>
<h1>Update Data</h1>
		<form action="" method="POST">
			<input type="submit" name="Submit" value="Update Data" class="update"/>
		</form>	
	</body>
<?php
  include '../database.php';
  ob_implicit_flush(true);
  ob_end_flush();
  set_time_limit(0);
  $s1 = "SELECT Symbol from s_c_details;";
  if(isset($_POST["Submit"]))
  {
      echo '<script>
            document.getElementById("progress").style="display:block;height:32px;width:700px;border:1px solid #26138e;border-radius:15px;";
            document.getElementById("information").style="display:block;font-size:50px;color:#26138e;";
            </script>';
      $result = $con->query($s1);
      $k =0;
      if($result->num_rows > 45 )
      {
        while($row = mysqli_fetch_row($result))
        {
          $command = escapeshellcmd('try.py ' . $row[0]);
      		$output = shell_exec($command);
          echo $output;
          $s2 = "../stock_data/" . $row[0] . ".csv";
          $file = file($s2);
          $readLines = max(0, count($file)-2);

          if($readLines > 0) {
              for ($i = $readLines; $i < count($file); $i++) {
                  $r = explode(",",$file[$i]);
                  if($i==$readLines)
                  {
                    $s4 = "UPDATE stock_details SET prev_close='" . round($r[6],3) . "' WHERE Symbol='" . $row[0] . "';";
                    $con->query($s4);
                  }
                  else{
                    $s2 = "UPDATE stock_details SET Open='" . round($r[3],3) . "',Close='" . round($r[4],3) . "',Volume='". round($r[5],3) . "' WHERE Symbol='" . $row[0]. "';";
                    $con->query($s2);
                  }
              }
            }
          $k++;
          $percent = intval($k/50 * 100)."%";
          echo '<script language="javascript">
          document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#1abc9c;height:30px;border-radius:15px;text-align:center;padding-top:0px;color:#26138e;font-size:20px;font-weight:bold;\">'. $k*2 . '%' .'</div>";
          </script>';
          flush();
        }
      }
    }   
?>
<?php
echo '<script language="javascript">
          document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#1abc9c;height:30px;border-radius:15px;text-align:center;padding-top:0px;color:#26138e;font-size:20px;font-weight:bold;\">'. "Data Updated Successfully" .'</div>";
          </script>';?>
</body>
</html>