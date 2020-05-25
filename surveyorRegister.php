<?php # DISPLAY COMPLETE SURVEYOR REGISTRATION PAGE.

   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   # Connect to the database.
   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      

$fn = $ln = $e = $p = '';

# Set page title and display header section.
$page_title = 'Register' ;
include ( 'includes/header.html' ) ;

# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  
  # Initialize an error array.
  $errors = array();

  # Check for a first name.
  if ( empty( $_POST[ 'first_name' ] ) )
  { $errors[] = 'Enter your first name.' ; }
  else
  { $fn = mysqli_real_escape_string( $dbc, trim( $_POST[ 'first_name' ] ) ) ; }

  # Check for a last name.
  if (empty( $_POST[ 'last_name' ] ) )
  { $errors[] = 'Enter your last name.' ; }
  else
  { $ln = mysqli_real_escape_string( $dbc, trim( $_POST[ 'last_name' ] ) ) ; }

  # Check for an email address:
  if ( empty( $_POST[ 'email' ] ) )
  { $errors[] = 'Enter your email address.'; }
  else
  { $e = mysqli_real_escape_string( $dbc, trim( $_POST[ 'email' ] ) ) ; }

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
    $q = "SELECT SurveyorId FROM aasurveyor WHERE emailAddress='$e'" ;
    $r = @mysqli_query ( $dbc, $q ) ;

    if ( @mysqli_num_rows( $r ) != 0 ) $errors[] = 'Email address already registered. <a href="surveylogin.php">Login</a>' ;
  }
  
  # On success register user inserting into 'users' database table.
  if ( empty( $errors ) ) 
  {
   #$hashPassword = password_hash("password", PASSWORD_BCRYPT);

    $sql = "INSERT INTO aasurveyor 
            (SurveyorFirstName, SurveyorLastName, emailAddress, passwd, registrationDate) 
            VALUES ('$fn', '$ln', '$e', '$passwd_enc', NOW() )";  #SHA2('$p',256)

    $rslt = mysqli_query ( $dbc, $sql ) ;
    
    if ($rslt)
    { echo '<h2>Registered!</h2><p>You are now registered.</p><p><a href="survey_login.php">Login</a></p>'; }
  
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
      <title>Register Surveyor</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Register New Surveyor</em></strong></h2>
      </header>
      <style>
         .error  {color: #FF0000;}
      </style>
      
   </head>
<body>
   <form action="surveyorRegister.php" method="POST"   > 
   <p><span class="error">* required field</span></p>
      <table>
         <tr>
            <th>First Name:</th>
            <td><input type="text" name="first_name" size="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></td>
         </tr>
         <tr>
            <th>Last Name:</th>
            <td><input type="text" name="last_name" size="20" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></td>
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



