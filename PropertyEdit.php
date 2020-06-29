<?php
session_start();

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if ($_SESSION[ 'SurveyorId' ] == 10 ){
   require( '../connectlaptop_db.php' ) ;
} else {
   require( '../connect_db.php' );
}      
   
$propertyid = $_SESSION['property'];
$uprn       = $_SESSION['uprn'];
$address    = $_SESSION['address'];

$sql = 'SELECT * FROM aaproperty WHERE propertyid = "'.$propertyid.'"' ;
$rslt = mysqli_query( $dbc , $sql ) ;

$row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC );
#------------------------------------------------------

if (isset ($_POST['submit'])){

   header("location:PropertyEdit.php");

   $sql = "UPDATE aaproperty".      
            " SET uprn         =  " .clean_input($_POST['uprn']).
            ", streetnumber    =  " .clean_input($_POST['streetnumber']).
            ", addressline1    = '" .clean_input($_POST['addressline1']).
            "', addressline2   = '" .clean_input($_POST['addressline2']).
            "', town           = '" .clean_input($_POST['town']).
            "', postcode       = '" .clean_input($_POST['postcode']).
            "', propertyname   = '" .clean_input($_POST['propertyname']).
            "', addresslookup  = '" .clean_input($_POST['streetnumber']).' '.clean_input($_POST['addressline1']).' '.clean_input($_POST['town']).
            "', owner          =  " .clean_input($_POST['owner']).
            " WHERE PropertyId =  " . $propertyid;
   #print_r($_POST);
   echo '<br>';
   #print_r($_SESSION);
   #echo '<br>';
   #print_r($sql);
   #echo '<br>';

   if (mysqli_query( $dbc , $sql )) {
      echo "Record updated successfully";
      sleep(1);
      echo '<br>';
   } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
   }
}

# Close the connection.
mysqli_close( $dbc ) ;

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
#------------------------------------------------------

?>

<!DOCTYPE HTML>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Property Maintenance</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Edit Property Details</em></strong></h2>
      </header>
      
   </head>
   <body>
      <form action="" method="POST"   > 
         <table>
            <tr><th>UPRN           : </th><td><input type="text" name="uprn"         value="<?php echo $row['UPRN']?>"></td></tr>
            <tr><th>Street Number  : </th><td><input type="text" name="streetnumber" value="<?php echo $row['StreetNumber']?>"></td></tr>
            <tr><th>Address Line 1 : </th><td><input type="text" name="addressline1" value="<?php echo $row['AddressLine1']?>"></td></tr>
            <tr><th>Address Line 2 : </th><td><input type="text" name="addressline2" value="<?php echo $row['AddressLine2']?>"></td></tr>
            <tr><th>Town           : </th><td><input type="text" name="town"         value="<?php echo $row['Town']?>"></td></tr>
            <tr><th>Postcode       : </th><td><input type="text" name="postcode"     value="<?php echo $row['Postcode']?>"></td></tr>
            <tr><th>Property Name  : </th><td><input type="text" name="propertyname" value="<?php echo $row['PropertyName']?>"></td></tr>
            <tr><th>Owner          : </th><td><input type="text" name="owner"        value="<?php echo $row['Owner']?>"></td></tr>
         </table>
         <br>
         <input type="submit" value="Save Changes" name="submit"> 
         <a href="getchoice.php">Home</a>
      </form>
   </body>
</html>


