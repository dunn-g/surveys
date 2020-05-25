<?php # DISPLAY COMPLETE LOGGED OUT PAGE.

# Access session.
session_start() ;

# Redirect if not logged in.
if ( !isset( $_SESSION[ 'SurveyorId' ] ) ) { require_once ( 'survey_login_tools.php' ) ; load() ; }

# Set page title and display header section.
$page_title = 'Goodbye' ;
include ( 'includes/header.html' ) ;

# Clear existing variables.
$_SESSION = array() ;
  
# Destroy the session.
session_destroy() ;

# Display body section.
echo '<div>
      <h1>Goodbye!</h1>
      <p><span class="goodbye">You are now logged out.</span></p>
      <p><a href="index.php">Login</a></p><br>
      </div>' ;
sleep(5);
echo '<script>window.close()</script>';
# Display footer section.
include ( 'includes/footer.html' ) ;

?>