<?php # DISPLAY COMPLETE SURVEYOR REGISTRATION PAGE.
   #$SurveyorId = 10;

   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   # Connect to the database.
   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      
      
$fname = $lname = $email = $pass1 = $pass2 = '';

# Set page title and display header section.
$page_title = 'Register' ;
include ( 'includes/header.html' ) ;

# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  
  # Initialize an error array.
  $errors = array();

  # Check for a first name.
  if ( empty( $_POST[ 'fname' ] ) )
  { $errors[] = 'Enter your first name.' ; }
  else
  { $fname = mysqli_real_escape_string( $dbc, trim( $_POST[ 'fname' ] ) ) ; }

  # Check for a last name.
  if (empty( $_POST[ 'lname' ] ) )
  { $errors[] = 'Enter your last name.' ; }
  else
  { $lname = mysqli_real_escape_string( $dbc, trim( $_POST[ 'lname' ] ) ) ; }

  # Check for an email address:
  if ( empty( $_POST[ 'email' ] ) )
  { $errors[] = 'Enter your email address.'; }
  else
  { $email = mysqli_real_escape_string( $dbc, trim( $_POST[ 'email' ] ) ) ; }

  # Check for a password and matching input passwords.
  if ( !empty($_POST[ 'pass1' ] ) )
  {
    if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] )
    { $errors[] = 'Passwords do not match.' ; }
    else
    { 
      $p = mysqli_real_escape_string( $dbc, trim( $_POST[ 'pass1' ] ) ) ; 
      $passwd_enc = password_hash($p, PASSWORD_DEFAULT);
    }
  }
  else { $errors[] = 'Enter your password.' ; }
  
  # Check if email address already registered.
  if ( empty( $errors ) )
  {
      $q = "SELECT SurveyorId
            FROM aasurveyor 
            WHERE SurveyorFirstName='$fname' AND SurveyorLastName='$lname' AND emailAddress='$email' ";
      $r = mysqli_query ( $dbc, $q ) ;
      $row = mysqli_fetch_array( $r , MYSQLI_ASSOC );

    if ( mysqli_num_rows( $r ) != 1 ) $errors[] = 'There could be a problem here! ' ;
  }
  
  # On success register user inserting into 'users' database table.
  if ( empty( $errors ) ) 
  {
#$hashPassword = password_hash("password", PASSWORD_BCRYPT);

      $sql = "UPDATE aasurveyor ".      
               "SET passwd = '" . $passwd_enc . 
               "' WHERE SurveyorId = " . $row['SurveyorId'];
      echo '<br>';

      #$rslt = mysqli_query ( $dbc, $sql ) ;
      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
         sleep(5);
         echo "<meta http-equiv='refresh' content='0'>";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
      }

      
    if ($rslt)
    { echo '<h2>Password successfully changed!</h2>'; }
  
    # Close database connection.
    mysqli_close($dbc); 

    # Display footer section and quit script:
    include ('includes/footer.html'); 
    exit();
  }
  # Or report errors.
  else 
  {
    echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>' ;
    foreach ( $errors as $msg )
    { echo " - $msg<br>" ; }
    echo 'Please try again.</p>';
    # Close database connection.
    mysqli_close( $dbc );
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
      <title>Change Password</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Change Surveyor Password</em></strong></h2>
      </header>
      <style>
         .error  {color: #FF0000;}
      </style>
      
   </head>
<body>
   <form action="surveyor_change_password.php" method="POST"   > 
   <p><span class="error">* required field</span></p>
      <table>
         <tr>
            <th>First Name:</th>
            <td><input type="text" name="fname" size="20" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>"></td>
         </tr>
         <tr>
            <th>Last Name:</th>
            <td><input type="text" name="lname" size="20" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>"></td>
         </tr>
         <tr>
            <th>Email Address:</th>
            <td><input type="text" name="email" size="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"></td>
         </tr>
         <tr>
            <th>Password:</th>
            <td><input type="password" name="pass1" size="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" ></td>
         </tr>
         <tr>
            <th>Confirm Password:</th>
            <td><input type="password" name="pass2" size="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"></td>
         </tr>
      </table>
      <input type="submit" value="Register" name="submit"> 
    
   </form>
</body>
</html>



