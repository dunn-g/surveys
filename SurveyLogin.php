<?php # DISPLAY COMPLETE LOGIN PAGE.

# Set page title and display header section.
$page_title = 'Login' ;
include_once( 'includes/header.html' ) ;

# Display any error messages if present.
if ( isset( $errors ) && !empty( $errors ) )
{
 echo '<p id="err_msg">Oops! There was a problem:<br>' ;
 foreach ( $errors as $msg ) { echo " - $msg<br>" ; }
 echo 'Please try again or <a href="surveyorCreate.php">Register</a></p>' ;
}
?>

<!-- Display body section. -->
<h1>Login</h1>
<form action="survey_login_action.php" method="POST">
   <div>
      <table style="opacity: 1";>
         <tr>
         <th>
            Email Address: 
         </th>
         <td>
            <input type="text" name="email" autofocus>
         </td>
         </tr>
         <tr>
         <th>
            Password: 
         </th>
         <td>
            <input type="password" name="pass">
         </td>
      </table>
      <p><input type="submit" value="Login" ></p>
   </div>
</form>

<?php 

# Display footer section.
include ( 'includes/footer.html' ) ; 

?>
