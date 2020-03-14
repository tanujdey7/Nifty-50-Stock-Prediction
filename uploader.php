<!--<html>
    <body>
        <form action="" method="POST">
            <input type="file" name="fileupload"/>
            <input type="submit" name="submit" value="submit"/>
        </form>    
    </body>
</html>-->
<?php
/*if(isset($_POST["submit"]))
{
   if (move_uploaded_file($_FILES['fileupload']['tmp_name'], ".")) {
      print "Uploaded successfully!";
   } else {
      print "Upload failed!";
   }
}*/
?>
<form action="" method="post" enctype="multipart/form-data"> 
 Select File: 
 <input type="file" name="fileToUpload"/> 
 <input type="submit" value="Upload Image" name="submit"/> 
</form>
<?php 
$target_path = "d:/"; 
$target_path = $target_path.basename( $_FILES['fileToUpload']['name']); 
 
if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) { 
 echo "File uploaded successfully!"; 
} else{ 
 echo "Sorry, file not uploaded, please try again!"; 
} 
?> 