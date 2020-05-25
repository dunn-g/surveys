<?php
   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      
   #$cname = $address = $contact = $phone = $mobile = $email = '';
   $cnameErr = $addressErr = $contactErr = $phoneErr = $mobileErr = $emailErr = '';
      
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   print_r($_POST);

   if (empty($_POST['cname'])) {
      $cnameErr = "Client name is required";
   } else {
      $cname = clean_input($_POST['cname']);
   }
   
   $address = clean_input($_POST['address']);
   
   if (empty($_POST['contact'])) {
      $contactErr = "Contact name is required";
   } else {      
      $contact = clean_input($_POST['contact']);
   }
   
      $phone = clean_input($_POST['phone']);
   
   if (empty($_POST['mobile'])) {
      $mobileErr = "Mobile no. is required";
   } else {      
      $mobile = clean_input($_POST['mobile']);
   }
   
   if (empty($_POST['email'])) {
      $emailErr = "Contact email no. is required";
   } else {      
      $email = clean_input($_POST['email']);
   }
   
   if (isset ($_POST['cname']) && isset($_POST['contact']) && isset($_POST['mobile']) && isset($_POST['email']) ) {

      $sql = "INSERT INTO aaclients(ClientName, ClientAddress,ContactName, ContactPhone, ContactMobile, ContactEmail)" .      
               " VALUES('".$cname."', '".$address."', '".$contact."', '".$phone."', '".$mobile."', '".$email."')";
      echo "<br>";

      if (mysqli_query( $dbc , $sql )) {
         echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
      }
      echo "<br>";
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
      <title>New Client</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Add New Client</em></strong></h2>
      </header>
      <style>
         .error  {color: #FF0000;}
      </style>
      
   </head>
<body>
   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"   > 
   <p><span class="error">* required field</span></p>
      <table>
         <tr><th>Name    : </th><td><input type="text" name="cname">  <span class="error">* <?php echo $cnameErr;?></span></td></tr>
         <tr><th>Address : </th><td><input type="text" name="address"></td></tr>
         <tr><th>Contact : </th><td><input type="text" name="contact"><span class="error">* <?php echo $contactErr;?></span></td></tr>
         <tr><th>Phone   : </th><td><input type="text" name="phone">  </td></tr>
         <tr><th>Mobile  : </th><td><input type="text" name="mobile"> <span class="error">* <?php echo $mobileErr;?></span></td></tr>
         <tr><th>eMail   : </th><td><input type="text" name="email">  <span class="error">* <?php echo $emailErr;?></span></td></tr>
      </table>
      <input type="submit" value="Save" name="submit"> 
      <a href="getchoice.php">Home</a>
    
   </form>
</body>
</html>


