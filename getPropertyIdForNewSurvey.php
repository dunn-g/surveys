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
   #echo $_SESSION['name'];
  $sql = 'SELECT distinct p.PropertyId, p.UPRN, p.AddressLookUp 
         FROM aaproperty p
         WHERE p.PropertyId not in (SELECT distinct psl.PropertyId 
                                    FROM aapropertysurveylink psl  ) ' ;
  $rslt = mysqli_query( $dbc , $sql ) ;


   if ( $rslt )
   {
echo '<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Get Property Survey</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <style>
         th {
            white-space: nowrap;
         }
         td {
            white-space: nowrap;
         }
      </style>
   </head>
   <body>
      <h1>Select Property for New Survey('.$_SESSION[ 'SurveyorId'].')</h1>
      <table><tr><th>Property</th><th>UPRN</th><th>Address</th></tr>';

      while ( $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ) 
      {
         $arpropertyid[] = $row['PropertyId'];
         $uprn[]         = $row['UPRN'];
         $Address[]      = $row['AddressLookUp' ] ;
         echo '<tr><td>' . $row['PropertyId'] . ' </td><td>' . $row['UPRN'] . ' </td><td> ' . $row[ 'AddressLookUp' ] . '</td></tr>' ;
      }
   echo '</table>
   
      <br>
      <form name="PropertyEdit" method="POST" action="" >

         Enter Property Id <input type="text" name="propertyid" autofocus>
         <input type="submit" value="submit" name="submit"> 

      </form>    
   </body>
</html>';

   } 
   else { 
      echo '<p>' . mysqli_error( $dbc ) . '</p>'  ; 
   }
}
else
{
  # Handle the form submission.
  # Empty check.

   if ( !empty ( $_POST['propertyid'] ) )
   {
     $propertyselected = $_POST['propertyid'];
     $q2 = 'SELECT distinct p.PropertyId, p.UPRN, p.AddressLookUp FROM aaproperty p
            WHERE p.PropertyId = ' . $propertyselected ;
     $r2 = mysqli_query( $dbc , $q2 ) ;
   
      if ( $r2 )
      {

         $row = mysqli_fetch_array( $r2 , MYSQLI_ASSOC );

         if (isset($_POST['submit'])) {
               $_SESSION['property'] = $row['PropertyId'];
               $_SESSION['uprn']     = $row['UPRN'];
               $_SESSION['address']  = $row['AddressLookUp'];

               if ($_SESSION[ 'SurveyorId' ] == 10 ){
                  header( 'location:CreateNewForm10LOCAL.php' ) ; 
               } else {
                  header( 'location:CreateNewForm10.php' ) ; 
               }      
               exit() ;
         };
      }
   }
  else
  { 
    $propertyid = NULL ;
    echo 'You must enter a property id' ;
  }
   # Close the connection.
   mysqli_close( $dbc ) ;
}

?>