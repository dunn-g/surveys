<?php # PROCESS LOGIN ATTEMPT.

session_start();

# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
   # Open database connection.
   require( '../connectlaptop_db.php' ) ;
   #require( '../connect_db.php' );

   # Get connection, load, and validate functions.
   require_once( 'survey_login_tools.php' ) ;

   # Check login.
   list ( $check, $data ) = validate ( $dbc, $_POST[ 'email' ], $_POST[ 'pass' ] ) ;
   #print_r($check);
   print_r($data); sleep(5);
   # On success set session data and display logged in page.
   if ( $check )  
   {
    # Access session.
    $_SESSION[ 'SurveyorId' ]        = $data[ 'SurveyorId' ] ;
    $_SESSION[ 'SurveyorFirstName' ] = $data[ 'SurveyorFirstName' ] ;
    $_SESSION[ 'SurveyorLastName' ]  = $data[ 'SurveyorLastName' ] ;
    #load ( 'home.php' ) ;
    load ( 'getchoice.php' ) ;
   }
   # Or on failure set errors.
   else { $errors = $data; } 

   # Close database connection.
   mysqli_close( $dbc ) ; 
}

# Continue to display login page on failure.
include_once ( 'SurveyLogin.php' ) ;

?>