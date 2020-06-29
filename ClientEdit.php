<?php
session_start();

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if ($_SESSION[ 'SurveyorId' ] == 10 ){
   require( '../connectlaptop_db.php' ) ;
} else {
   require( '../connect_db.php' );
}      

$clientid = $_SESSION['clientid'];

$sql = 'SELECT * FROM aaclients WHERE ClientId = "'.$clientid.'"' ;
$rslt = mysqli_query( $dbc , $sql ) ;

$row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ); 


if (isset ($_POST['submit'])){
   
   header("location:ClientEdit.php");
   
   $sql = "UPDATE aaclients".      
            " SET ClientName='"  .clean_input($_POST['cname']).
            "', ClientAddress='" .clean_input($_POST['address']).
            "', ContactName='"   .clean_input($_POST['contact']).
            "', ContactPhone='"  .clean_input($_POST['phone']).
            "', ContactMobile='" .clean_input($_POST['mobile']).
            "', ContactEmail='"  .clean_input($_POST['email']).
            "' WHERE ClientId = ". $clientid;

   if (mysqli_query( $dbc , $sql )) {
      echo "Record updated successfully";
      sleep(1);
      #echo "<meta http-equiv='refresh' content='0'>";
   } else {
     echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
   }

   # Close the connection.
   mysqli_close( $dbc ) ;
}

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!DOCTYPE HTML>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Amend Client Details</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Amend Client Details</em></strong></h2>
      </header>
      
   </head>
   <body>
      <form action="" method="POST"   > 
         <table>
            <tr><th>Name    : </th><td><input type="text" name="cname"   value="<?php echo $row['ClientName']?>"></td></tr>
            <tr><th>Address : </th><td><input type="text" name="address" value="<?php echo $row['ClientAddress']?>"></td></tr>
            <tr><th>Contact : </th><td><input type="text" name="contact" value="<?php echo $row['ContactName']?>"></td></tr>
            <tr><th>Phone   : </th><td><input type="text" name="phone"   value="<?php echo $row['ContactPhone']?>"></td></tr>
            <tr><th>Mobile  : </th><td><input type="text" name="mobile"  value="<?php echo $row['ContactMobile']?>"></td></tr>
            <tr><th>eMail   : </th><td><input type="text" name="email"   value="<?php echo $row['ContactEmail']?>"></td></tr>
         </table>
         <input type="submit" value="Save Changes" name="submit"> 
         <a href="getchoice.php">Home</a>
       
      </form>
   </body>
</html>      