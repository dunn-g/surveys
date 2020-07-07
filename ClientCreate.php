<?php
   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      
   $cname = $address = $contact = $phone = $mobile = $email = '';
      
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   # Initialize an error array.
   $clientErrors = array();
   
   #print_r($_POST);

   if (empty($_POST['cname'])) {
      $clientErrors[] = "Client name is required";
   } else {
      $cname = clean_input($_POST['cname']);
   }
   
   $address = clean_input($_POST['address']);
   
   if (empty($_POST['contact'])) {
      $clientErrors[] = "Contact name is required";
   } else {      
      $contact = clean_input($_POST['contact']);
   }
   
      $phone = clean_input($_POST['phone']);
   
   if (empty($_POST['mobile'])) {
      $clientErrors[] = "Mobile no. is required";
   } else {      
      $mobile = clean_input($_POST['mobile']);
   }
   
   if (empty($_POST['email'])) {
      $clientErrors[] = "Contact email no. is required";
   } else {      
      $email = clean_input($_POST['email']);
   }
   
   if (empty($clientErrors)) {

      $sql = "INSERT INTO aaclients(ClientName, ClientAddress,ContactName, ContactPhone, ContactMobile, ContactEmail)" .      
               " VALUES('".$cname."', '".$address."', '".$contact."', '".$phone."', '".$mobile."', '".$email."')";
      echo "<br>";

      if (mysqli_query( $dbc , $sql )) {
         echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
      }
      echo "<br>";
   } else {   
      
      # Or report errors.
      echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>' ;
      foreach ( $clientErrors as $msg )
      { echo " - $msg<br>" ; }
      echo 'Please try again.</p>';
      
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
      <!--<form action="<?php #echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"   > -->
      <form action="ClientCreate.php" method="POST"   > 
      <p><span class="error">* required field</span></p>
         <table>
            <tr><th>Name    : </th><td><input type="text" name="cname"   value="<?php if (isset($_POST['cname']))   echo $_POST['cname'];   ?>"></td></tr>
            <tr><th>Address : </th><td><input type="text" name="address" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>"></td></tr>
            <tr><th>Contact : </th><td><input type="text" name="contact" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>"></td></tr>
            <tr><th>Phone   : </th><td><input type="text" name="phone"   value="<?php if (isset($_POST['phone']))   echo $_POST['phone'];   ?>"></td></tr>
            <tr><th>Mobile  : </th><td><input type="text" name="mobile"  value="<?php if (isset($_POST['mobile']))  echo $_POST['mobile'];  ?>"></td></tr>
            <tr><th>eMail   : </th><td><input type="text" name="email"   value="<?php if (isset($_POST['email']))   echo $_POST['email'];   ?>"></td></tr>
         </table>
         <input type="submit" value="Save" name="submit"> 
         <a href="getchoice.php">Home</a>
       
      </form>
   </body>
</html>


