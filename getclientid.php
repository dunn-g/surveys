<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Get Client</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
   </head>
   <body>
<?php # CONNECT TO MySQL DATABASE.

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      

if ( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ) 
{
  # Display the form.

   $sql = 'SELECT * FROM aaclients' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

   #$row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC );

   echo '<h1>Select Client To Edit</h1>' ;

   if ( $rslt )
   {
      echo '<table><tr><th>Client</th><th>Name</th></tr>';

      while ( $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ) 
      {
         echo '<tr><td>' . $row['ClientId'] . ' </td><td>' . $row['ClientName'] . ' </td></tr>' ;
      }
  echo '</table>' ;

   } else { echo '<p>' . mysqli_error( $dbc ) . '</p>'  ; }
   echo "<br>";

  echo '
      <form name="clienttoedit" method="POST" action="" >

         Enter client required <input type="text" name="clientid">
         <input type="submit" value="submit" name="submit"> 

      </form> ';   
}
else
{
  # Handle the form submission.
  # Empty check.

   if ( !empty ( $_POST['clientid'] ) )
   {
     $clientselected = $_POST['clientid'];
/*      $sql2 = 'SELECT distinct c.clientid, c.ClientName, c.Contact FROM aaclients c
            WHERE c.ClientId = ' . $clientselected ;
     $rslt2 = mysqli_query( $dbc , $sql2 ) ;
 */print_r($_POST['clientid']);
echo '<br>';   
print_r($clientselected);
echo '<br>';
/*print_r($sql2);
echo '<br>';
print_r($rslt2);
echo '<br>';
      if ( $rslt2 )
      {
          echo '<table><tr><th>Client</th><th>Contact</th><th>Address</th></tr>';

         while ( $row = mysqli_fetch_array( $rslt2 , MYSQLI_ASSOC ) ) 
         {
            echo '<tr><td>' . $row['ClientId'] . ' </td><td>' . $row['ClientName'] . ' </td><td> ' . $row[ 'Address' ] . '</td></tr>' ;
         }
         echo '</table>' ;
         
         echo '<br>';
 */         
         if (isset($_POST['submit'])) {
               $_SESSION['clientid']  = $clientselected;
               print_r($_SESSION);
               header( 'Location: ClientEdit.php' ) ; 
               exit() ;
         };
     # }
   }
  else
  { 
    $clientid = NULL ;
    echo 'You must enter a client id' ;
  }

   # Close the connection.
   mysqli_close( $dbc ) ;
}
?>
          
   </body>
</html>