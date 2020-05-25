<?php # DISPLAY COMPLETE LOGIN PAGE.

#=========================================#
#     originally named SurveyLogin.php    #
#=========================================#

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
<form action="survey_login_action.php" method="POST">
   <div>
      <img style="margin 0px" height="110px" align="right" src="images/STBA_LOGO_RGB.jpg" alt="STBA Logo" ></img>
      <h2>Welcome to</h2>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h1>STBA’s Responsible Retrofit Survey Database</h1>
      <br><br><h3>Please Login</h3>
      
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
      <img style="margin 0px" height="350px" align="right" src="images/Responsible_retrofit_small.png" alt="Retrofit Logo" ></img>
   </div>
</form>

<?php 

# Display footer section.
include ( 'includes/footer.html' ) ; 

?>
