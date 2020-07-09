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

   $q = 'SELECT * FROM ventilation WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $ventkitchenAry = array();
   $ventkitchenAry = explode(',',$row['VentilationKitchen']);
#---------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form80.php");

      # set checkboxes if blank
      (!isset($_POST['investigation'])) ? $investigation  = 0 : $investigation  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['ventilationstrategy'])) ? $ventilationstrategy = '' : $ventilationstrategy = $_POST['ventilationstrategy'];
      (!isset($_POST['ventilationbathroom'])) ? $ventilationbathroom = '' : $ventilationbathroom = $_POST['ventilationbathroom'];
      (!isset($_POST['ventilationkitchen']))  ? $ventilationkitchen = ''  : $ventilationkitchen  = implode(',', $_POST['ventilationkitchen']);
      (!isset($_POST['cookerhood']))          ? $cookerhood = ''          : $cookerhood          = $_POST['cookerhood'];
      (!isset($_POST['windowventilation']))   ? $windowventilation = ''   : $windowventilation   = $_POST['windowventilation'];
      (!isset($_POST['chimneyventilation']))  ? $chimneyventilation = ''  : $chimneyventilation  = $_POST['chimneyventilation'];

      # set notes if blank
      (!isset($_POST['ventilationnotes'])) ? $ventilationnotes = '' : $ventilationnotes = $_POST['ventilationnotes'];

      $sql = "UPDATE ventilation ".      
               " SET VentilationStrategy='" . clean_input($ventilationstrategy) .
               "', VentilationBathroom='"   . clean_input($ventilationbathroom) .
               "', VentilationKitchen='"    . clean_input($ventilationkitchen) .
               "', CookerHood='"            . clean_input($cookerhood) .
               "', WindowVentilation='"     . clean_input($windowventilation) .
               "', ChimneyVentilation='"    . clean_input($chimneyventilation) . 
               "', FurtherInvestigation='"  . clean_input($investigation) . 
               "', VentilationNotes='"      . clean_input($ventilationnotes) .
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
#---------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 80</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Ventilation</em></strong></h2>
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
      <form method="post" action="" >
         <table class="formten" style="border: 0">
            <tr>
               <th>Ventilation Strategy</th>
               <td>
                  <select id="ventilationstrategy" name="ventilationstrategy" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"                   <?php echo $row['VentilationStrategy'] == "None" ? " selected" : ""; ?>                  >None</option>
                     <option value="Passive Stack"          <?php echo $row['VentilationStrategy'] == "Passive Stack" ? " selected" : ""; ?>         >Passive Stack</option>
                     <option value="Naturally Ventilated"   <?php echo $row['VentilationStrategy'] == "Naturally Ventilated" ? " selected" : ""; ?>  >Naturally Ventilated</option>
                     <option value="Mechanical Ventilation" <?php echo $row['VentilationStrategy'] == "Mechanical Ventilation" ? " selected" : ""; ?>>Mechanical Ventilation</option>
                     <option value="Whole House MVHR"       <?php echo $row['VentilationStrategy'] == "Whole House MVHR" ? " selected" : ""; ?>      >Whole House MVHR</option>
                     <option value="Room based MHVR"        <?php echo $row['VentilationStrategy'] == "Room based MHVR" ? " selected" : ""; ?>       >Room based MHVR</option>
                     <option value="Whole House PIV"        <?php echo $row['VentilationStrategy'] == "Whole House PIV" ? " selected" : ""; ?>       >Whole House PIV</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="ventilationstrategy_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Overall strategy for the building
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ventilation Bathroom</th>
               <td>
                  <select id="ventilationbathroom" name="ventilationbathroom" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Mechanical extract light switch"        <?php echo $row['VentilationBathroom'] == "Mechanical extract light switch" ? " selected" : ""; ?>       >Mechanical extract light switch</option>
                     <option value="Mechanical extract humidity"            <?php echo $row['VentilationBathroom'] == "Mechanical extract humidity" ? " selected" : ""; ?>           >Mechanical extract humidity</option>
                     <option value="Mechanical extract timed"               <?php echo $row['VentilationBathroom'] == "Mechanical extract timed" ? " selected" : ""; ?>              >Mechanical extract timed</option>
                     <option value="Mechanical extract constant"            <?php echo $row['VentilationBathroom'] == "Mechanical extract constant" ? " selected" : ""; ?>           >Mechanical extract constant</option>
                     <option value="Mechanical extract blocked not working" <?php echo $row['VentilationBathroom'] == "Mechanical extract blocked not working" ? " selected" : ""; ?>>Mechanical extract blocked not working</option>
                     <option value="No mechanical extract"                  <?php echo $row['VentilationBathroom'] == "No mechanical extract" ? " selected" : ""; ?>                 >No mechanical extract</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="ventilationbathroom_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  High humidity areas legally require extract and it is good to 
                  identify the basic form that this takes. We could reduce the number 
                  of options here by combining switch and timed, but I think that it 
                  helps to have good info for ventilation.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ventilation Kitchen</th>
               <td>
                  <select id="ventilationkitchen" name="ventilationkitchen[]" multiple="multiple" size="4">
                     <option value="" disabled >Please choose...</option>
                     <option value="Mechanical extract switch"              <?php echo (isset($ventkitchenAry) && in_array('Mechanical extract switch', $ventkitchenAry))   ? " selected" : ""; ?>>Mechanical extract switch</option>
                     <option value="Mechanical extract humidity"            <?php echo (isset($ventkitchenAry) && in_array('Mechanical extract humidity', $ventkitchenAry)) ? " selected" : ""; ?>>Mechanical extract humidity</option>
                     <option value="Mechanical extract timed"               <?php echo (isset($ventkitchenAry) && in_array('Mechanical extract timed', $ventkitchenAry))    ? " selected" : ""; ?>>Mechanical extract timed</option>
                     <option value="Mechanical extract blocked not working" <?php echo (isset($ventkitchenAry) && in_array('Mechanical extract blocked not working', $ventkitchenAry)) ? " selected" : ""; ?>>Mechanical extract blocked not working</option>
                     <option value="No mechanical extract"                  <?php echo (isset($ventkitchenAry) && in_array('No mechanical extract', $ventkitchenAry)) ? " selected" : ""; ?>>No mechanical extract</option>
                     <option value="Hood Vented"                            <?php echo (isset($ventkitchenAry) && in_array('Hood Vented', $ventkitchenAry))           ? " selected" : ""; ?>>Hood Vented</option>
                     <option value="Hood Re-circulation"                    <?php echo (isset($ventkitchenAry) && in_array('Hood Re-circulation', $ventkitchenAry))   ? " selected" : ""; ?>>Hood Re-circulation</option>
                     <option value="No Hood - not working"                  <?php echo (isset($ventkitchenAry) && in_array('No Hood - not working', $ventkitchenAry)) ? " selected" : ""; ?>>No Hood - not working</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Cooker Hood</th>
               <td>
                  <select id="cookerhood" name="cookerhood" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Externally vented" <?php echo $row['CookerHood'] == "Externally vented"  ? " selected" : ""; ?>>Externally vented</option>
                     <option value="Recirculation"     <?php echo $row['CookerHood'] == "Recirculation"      ? " selected" : ""; ?>>Recirculation</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Window Ventilation</th>
               <td>
                  <select id="windowventilation" name="windowventilation" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="All"  <?php echo $row['WindowVentilation'] == "All"  ? " selected" : ""; ?>>All</option>
                     <option value="Some" <?php echo $row['WindowVentilation'] == "Some" ? " selected" : ""; ?>>Some</option>
                     <option value="None" <?php echo $row['WindowVentilation'] == "None" ? " selected" : ""; ?>>None</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Chimney Ventilation</th>
               <td>
                  <select id="chimneyventilation" name="chimneyventilation" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="All"  <?php echo $row['ChimneyVentilation'] == "All" ? " selected" : ""; ?> >All</option>
                     <option value="Some" <?php echo $row['ChimneyVentilation'] == "Some" ? " selected" : ""; ?>>Some</option>
                     <option value="None" <?php echo $row['ChimneyVentilation'] == "None" ? " selected" : ""; ?>>None</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Further Investigation</th>
               <td>
                  <input type="checkbox" class="chk" name="investigation" value="<?php echo ($row['FurtherInvestigation']=='1' ? '1' : '0');?>" <?php echo ($row['FurtherInvestigation']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Ventilation Notes</th>
               <td>
                  <textarea name="ventilationnotes" rows="2" cols="30"><?php echo $row['VentilationNotes']?></textarea>
               </td>
            </tr>
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form70.php" title="Form 70">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form82.php" title="Form 82">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
