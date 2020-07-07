<?php
   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } 
   else {
      require( '../connect_db.php' );
   }      

$uprn = $streetnumber = $addressline1 = $addressline2 = $town =  '';
$postcode = $propertyname = $owner = $addresslookup = $message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   # Initialize an error array.
   $newPropErrors = array();

   if (empty($_POST["uprn"])) {
      $newPropErrors[] = "UPRN is required";
   } 
   else {
      $uprn = clean_input($_POST['uprn']);
   }
   
   $streetnumber = clean_input($_POST['streetnumber']);
   
   if (empty($_POST["addressline1"])) {
      $newPropErrors[] = "Street is required";
   } 
   else {      
      $addressline1 = clean_input($_POST['addressline1']);
   }
   
   $addressline2 = clean_input($_POST['addressline2']);
   
   if (empty($_POST["town"])) {
      $newPropErrors[] = "Town is required";
   } 
   else {      
      $town = clean_input($_POST['town']);
   }
   
   if (empty($_POST["postcode"])) {
      $newPropErrors[] = "Postcode is required";
   } 
   else {      
      $postcode = clean_input($_POST['postcode']);
   }
   
   $propertyname  = clean_input($_POST['propertyname']);
   
   $owner         = clean_input($_POST['owner']);
   
   $addresslookup = $_POST['streetnumber'].' '.$_POST['addressline1'].' '.$_POST['town'] ;
   
   #print_r($uprn, $streetnumber);

   if (empty($newPropErrors) ) {

      $sql = "INSERT INTO aaproperty(uprn, streetnumber,addressline1, addressline2, town, postcode, propertyname, addresslookup, owner) 
               VALUES('$uprn', '$streetnumber', '$addressline1', '$addressline2', '$town', '$postcode', '$propertyname', '$addresslookup', '$owner')";
      #print_r($sql);
      if (mysqli_query( $dbc , $sql )) {
         echo "New record created successfully";
         sleep(1);
      }  
      else {
         echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
      }

      # Close the connection.
      mysqli_close( $dbc ) ;
   } else {
      
      # Or report errors.
      echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>' ;
      foreach ( $newPropErrors as $msg )
      { echo " - $msg<br>" ; }
      echo 'Please try again.</p>';
      
      
   }
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
      <title>Add New Property</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Add New Property</em></strong></h2>
      </header>
      <style>
         .error  {color: #FF0000;}
      </style>
      
   </head>
<body>
   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"   > 
   <p><span class="error">* required field</span></p>
      <table>
         <tr><th>UPRN           : </th><td><input type="text" name="uprn"         value="<?php if (isset($_POST['uprn']))         echo $_POST['uprn'];         ?>"></td></tr>
         <tr><th>Street Number  : </th><td><input type="text" name="streetnumber" value="<?php if (isset($_POST['streetnumber'])) echo $_POST['streetnumber']; ?>"></td></tr>
         <tr><th>Address Line 1 : </th><td><input type="text" name="addressline1" value="<?php if (isset($_POST['addressline1'])) echo $_POST['addressline1']; ?>"></td></tr>
         <tr><th>Address Line 2 : </th><td><input type="text" name="addressline2" value="<?php if (isset($_POST['addressline2'])) echo $_POST['addressline2']; ?>"></td></tr>
         <tr><th>Town           : </th><td><input type="text" name="town"         value="<?php if (isset($_POST['town']))         echo $_POST['town'];         ?>"></td></tr>
         <tr><th>Postcode       : </th><td><input type="text" name="postcode"     value="<?php if (isset($_POST['postcode']))     echo $_POST['postcode'];     ?>"></td></tr>
         <tr><th>Property Name  : </th><td><input type="text" name="propertyname" value="<?php if (isset($_POST['propertyname'])) echo $_POST['propertyname']; ?>"></td></tr>
         <tr><th>Owner          : </th><td><input type="text" name="owner"        value="<?php if (isset($_POST['owner']))        echo $_POST['owner'];        ?>"></td></tr>
      </table>
      <input type="submit" value="Save" name="submit"> 
      <a href="getchoice.php">Home</a>
    
   </form>
</body>
</html>


