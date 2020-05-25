<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Get Survey</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <style>
         th, td {
            white-space: nowrap;
         }
      </style>
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
   
   #print_r($_SESSION);

if ( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ) 
{
  # Display the form.

  $q = ' SELECT distinct l.SurveyId, p.UPRN, p.AddressLookUp 
         FROM aaproperty p
         inner join aapropertysurveylink l on p.PropertyId = l.PropertyId
         WHERE 1' ;
  $r = mysqli_query( $dbc , $q ) ;
   #print_r($r);
      echo '<h1>Survey / Property Link</h1>' ;

   if ( $r )
   {
      echo '<table><tr><th>SurveyId</th><th>UPRN</th><th>Address</th></tr>';
      $i = 0;
      while ( $row = mysqli_fetch_array( $r , MYSQLI_ASSOC ) ) 
      {
         $arSurveyId[] = $row['SurveyId'];
         $uprn[]       = $row['UPRN'];
         $Address[]    = $row['AddressLookUp' ] ;
         echo '<tr><td>' . $row['SurveyId'] . ' </td><td>' . $row['UPRN'] . ' </td><td> ' . $row[ 'AddressLookUp' ] . '</td></tr>' ;
         $i++;
      }
  echo '</table>' ;

   } else { echo '<p>' . mysqli_error( $dbc ) . '</p>'  ; }
   echo "<br>";

  echo '
      <form name="get_survey" method="POST" action="" >

         Survey Id <input type="text" name="surveyid" autofocus>
         <input type="submit" value="submit" name="submit"> 

      </form> ';   

}
else
{
  # Handle the form submission.
  # Empty check.

   if ( !empty ( $_POST['surveyid'] ) )
   {
     $surveyselected = $_POST['surveyid'];
     $q2 = ' SELECT distinct l.SurveyId, p.UPRN, p.AddressLookUp FROM aaproperty p
            inner join aapropertysurveylink l on p.PropertyId = l.PropertyId
            WHERE l.SurveyId = ' . $surveyselected ;
     $r2 = mysqli_query( $dbc , $q2 ) ;
   
      if ( $r2 )
      {
         echo '<table><tr><th>Survey</th><th>UPRN</th><th>Address</th></tr>';

         while ( $row = mysqli_fetch_array( $r2 , MYSQLI_ASSOC ) ) 
         {
            echo '<tr><td>' . $row['SurveyId'] . ' </td><td>' . $row['UPRN'] . ' </td><td> ' . $row[ 'AddressLookUp' ] . '</td></tr>' ;
            $subSurvey  = $row['SurveyId'];
            $subuprn    = $row['UPRN'];
            $subAddress = $row['AddressLookUp'];
         }
         echo '</table>' ;
         
         #echo $subSurvey . "  " . $subAddress;
         echo '<br>
         
         <input type="hidden" name="survey"  value=" ' . $subSurvey . ' ">
         <input type="hidden" name="uprn"    value=" ' . $subuprn . ' " >
         <input type="hidden" name="address" value=" ' . $subAddress . ' " >
         <br>';
          
         #print_r($_SESSION);
    
         if (isset($_POST['submit'])) {
               $_SESSION['survey']  = $subSurvey;
               $_SESSION['uprn']    = $subuprn;
               $_SESSION['address'] = $subAddress;
               #print_r($_SESSION);
               sleep(1);
               header( 'Location: form10.php' ) ; 
               exit() ;
         };

      }
   }
  else
  { 
    $surveyid = NULL ;
    echo 'You must enter a survey id' ;
  }
}
?>

          
   </body>
</html>