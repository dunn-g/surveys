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

   $q = 'SELECT * FROM water WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   #if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      
      mysqli_free_result( $rslt ) ;

   } 
#-----------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form87.php");

      # set checkboxes if blank
      (!isset($_POST['watermeter']))       ? $watermeter  = 0      : $watermeter  = 1;
      (!isset($_POST['waterbutt']))        ? $waterbutt  = 0       : $waterbutt  = 1;
      (!isset($_POST['bath']))             ? $bath = 0             : $bath = 1;
      (!isset($_POST['toiletsdualflush'])) ? $toiletsdualflush = 0 : $toiletsdualflush = 1;
      (!isset($_POST['shower']))           ? $shower = 0           : $shower = 1;
      
      # set notes if blank
      (!isset($_POST['waternotes'])) ? $waternotes = '' : $waternotes = $_POST['waternotes'];

      $sql = "UPDATE water ".      
               " SET WaterMeter='"     . clean_input($watermeter) .
               "', Waterbutt='"        . clean_input($waterbutt) .
               "', Bath='"             . clean_input($bath) .
               "', ToiletsDualFlush='" . clean_input($toiletsdualflush) .
               "', Shower='"           . clean_input($shower) . 
               "', Waternotes='"       . clean_input($waternotes) . 
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      #print_r($_POST);
      #echo "<br>";         
      #print_r($sql);
      #echo "<br>";  
      
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
#-----------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 87</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Water</em></strong></h2>
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
   </head>
   <body>

      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->

      <div class="main" style="display:table;">
      <form method="post" action="" onsubmit="return validate(this)">
         <table class="formten" style="border: 0">
            <tr>
               <th>WaterMeter</th>
               <td>
                  <input type="checkbox" class="chk" name="watermeter" value="<?php echo ($row['WaterMeter']=='1' ? '1' : '0');?>" <?php echo ($row['WaterMeter']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Waterbutt</th>
               <td>
                  <input type="checkbox" class="chk" name="waterbutt" value="<?php echo ($row['Waterbutt']=='1' ? '1' : '0');?>" <?php echo ($row['Waterbutt']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Bath</th>
               <td>
                  <input type="checkbox" class="chk" name="bath" value="<?php echo ($row['Bath']=='1' ? '1' : '0');?>" <?php echo ($row['Bath']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>ToiletsDualFlush</th>
               <td>
                  <input type="checkbox" class="chk" name="toiletsdualflush" value="<?php echo ($row['ToiletsDualFlush']=='1' ? '1' : '0');?>" <?php echo ($row['ToiletsDualFlush']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr> 
            <tr>
               <th>Shower</th>
               <td>
                  <input type="checkbox" class="chk" name="shower" value="<?php echo ($row['Shower']=='1' ? '1' : '0');?>" <?php echo ($row['Shower']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Water Notes</th>
               <td>
                  <textarea name="waternotes" rows="2" cols="30"><?php echo $row['Waternotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form84.php" title="Form 84">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form90.php" title="Form 90">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
