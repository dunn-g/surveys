<?php
   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      

   $surveyorid = $_SESSION['SurveyorId'];
   
   $sql = 'SELECT * FROM aasurveyor WHERE SurveyorId = "'.$surveyorid.'"' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

   $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC );

?>

<!DOCTYPE HTML>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Amend Surveyor Details</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <header>
         <h2><strong><em>Amend Surveyor Details</em></strong></h2>
      </header>
      
   </head>
<body>
   <form action="" method="POST"   > 
      <table>
         <tr><th>First Name:    </th><td><input type="text"     name="first_name"  value="<?php if (isset($row['SurveyorFirstName'])) echo $row['SurveyorFirstName']; ?>"></td></tr>
         <tr><th>Last Name:     </th><td><input type="text"     name="last_name"   value="<?php if (isset($row['SurveyorLastName']))  echo $row['SurveyorLastName']; ?>"></td></tr>
         <tr><th>Email Address: </th><td><input type="text"     name="email"       value="<?php if (isset($row['emailAddress']))      echo $row['emailAddress']; ?>"></td></tr>
      </table>
      <br>
      <input type="submit" value="Save Changes" name="submit"> 
   </form>

<?php
   if (isset ($_POST['submit'])){
      
      # set numeric textboxes if blank
      #(!isset($_POST['surveyorid'])) ? $surveyorid  = 99 : $surveyorid  = $_POST['surveyorid'];


      $sql = "UPDATE aasurveyor".      
               " SET SurveyorFirstName = '" .clean_input($_POST['first_name']).
               "', SurveyorLastName     = '" .clean_input($_POST['last_name']).
               "', emailAddress         = '" .clean_input($_POST['email']).
               "' WHERE SurveyorId = ". $surveyorid;
      echo '<br>';

      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
#         echo '<br>';
#         sleep(5);
         header( 'Location: getchoice.php' ) ; 
         
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
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
</body>
</html>


