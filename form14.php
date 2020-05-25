<?php # CONNECT TO MySQL DATABASE.

   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      

   $surveyid = $_SESSION['survey'];
   $uprn     = $_SESSION['uprn'];
   $address  = $_SESSION['address'];

   $sql = 'SELECT * FROM weatherdata WHERE SurveyId = "'.$surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

   #if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;

      mysqli_free_result( $rslt ) ;

   } 
   echo "<br>";         

#--------------------------------------------------------------

   if (isset ($_POST['submit'])){

      header("location:form14.php");

      # set checkboxes if blank
      (!isset($_POST['exposedsite']))      ? $exposedsite  = 0     : $exposedsite  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['exposureRating']))    ? $exposureRating = ''    : $exposureRating    = $_POST['exposureRating'];
      (!isset($_POST['weatherConditions'])) ? $weatherConditions = '' : $weatherConditions = $_POST['weatherConditions'];
      (!isset($_POST['priorConditions']))   ? $priorConditions = ''   : $priorConditions   = $_POST['priorConditions'];
      
      $sql = "UPDATE weatherdata ".      
               " SET ExposureRating='"       . clean_input($exposureRating) .
               "', ExposedSite="             . clean_input($exposedsite) .
               ",  WeatherConditions='"      . clean_input($weatherConditions) .
               "', WeatherPriorConditions='" . clean_input($priorConditions) .
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      
      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
         sleep(1);
         #echo "<meta http-equiv='refresh' content='0'>";
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

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

#--------------------------------------------------------------   
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 14</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>      

         <h2><strong><em>Weather Conditions</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
       $(function(){
         $("#nav-placeholder").load("nav.html");
       });
      </script>

      <script>
             function submitForm() {
               document.getElementById("form_14").submit();
               //header( "Location: form16.php" ) ;
               window.location = 'form16.php';
            };
      </script>      
   </head>
   <body>
      
      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->
      
      <!-- Page content -->
      <div class="main" style="display:table;">
      <form id="form_14" method="post" action="" >
         <table class="formfourteen" style="border: 0">
            <tr>
               <th>Exposed Site</th>
               <td>
                  <input type="checkbox" id="exposedsite" name="exposedsite" value="<?php echo ($row['ExposedSite']=='1' ? '1' : '0');?>" <?php echo ($row['ExposedSite']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="exposedsite"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     A building might be more or less sheltered than indicated by the national guidance.
                  </span>
                  <p style="font-size : 12"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Weather Conditions</th>
               <td>					
                  <select id="weatherConditions" name="weatherConditions" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Dry and Warm"<?php echo $row['WeatherConditions'] == "Dry and Warm" ? " selected" : ""; ?>>Dry and Warm</option>
                     <option value="Dry and Cool"<?php echo $row['WeatherConditions'] == "Dry and Cool" ? " selected" : ""; ?>>Dry and Cool</option>
                     <option value="Dry and Cold"<?php echo $row['WeatherConditions'] == "Dry and Cold" ? " selected" : ""; ?>>Dry and Cold</option>
                     <option value="Wet and Warm"<?php echo $row['WeatherConditions'] == "Wet and Warm" ? " selected" : ""; ?>>Wet and Warm</option>
                     <option value="Wet and Cool"<?php echo $row['WeatherConditions'] == "Wet and Cool" ? " selected" : ""; ?>>Wet and Cool</option>
                     <option value="Wet and Cold"<?php echo $row['WeatherConditions'] == "Wet and Cold" ? " selected" : ""; ?>>Wet and Cold</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="weatherconditions_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     To give an indication of potential issues identified later, eg. condensation.
                  </span>
                  <p style="font-size : 12"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Weather Prior Conditions</th>
               <td>
                  <select id="priorConditions" name="priorConditions" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Normal"              <?php echo $row['WeatherPriorConditions'] == "Normal" ? " selected" : ""; ?>>Normal</option>
                     <option value="Dry spell" <?php echo $row['WeatherPriorConditions'] == "Dry spell" ? " selected" : ""; ?>>Dry spell</option>
                     <option value="Wet spell"<?php echo $row['WeatherPriorConditions'] == "Wet spell" ? " selected" : ""; ?>>Wet spell</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="weatherpriorconditions_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     To better help to assess risk factors in play on the day of inspection
                  </span>
                  <p style="font-size : 12"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
         </table>
<!--
         <br>         
         <br>         
         <br>         
         <br> 
         <div id="pagefooter" >
            <a href="form12.php" title="Form 12">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a href="form16.php" title="Form 16">Next</a>
         </div>
--> 
        
<!--
   <div id="footer">
   I am at the bottom of the window <small>&copy; <em>STBA 2020</em></small>
   </div>
-->   
         <div class='pagefooter'>
            <!--Always at bottom!-->
            <a href="form12.php" title="Form 12">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a href="form16.php" title="Form 16">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>   
      </div>

   <footer>
   </footer>  
   </body>
</html>
