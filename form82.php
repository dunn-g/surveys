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

   $q = 'SELECT * FROM services WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $heatdelAry = array();
   $heatdelAry = explode(',',$row['HeatDelivery']);

   $heatctrlAry = array();
   $heatctrlAry = explode(',',$row['HeatControls']);
#-------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form82.php");

      # set checkboxes if blank
      (!isset($_POST['hybridisation']))      ? $hybridisation  = 0     : $hybridisation  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['mainspaceheatingfuel']))      ? $mainspaceheatingfuel      = '' : $mainspaceheatingfuel      = $_POST['mainspaceheatingfuel'];
      (!isset($_POST['mainheatingtype']))           ? $mainheatingtype           = '' : $mainheatingtype           = $_POST['mainheatingtype'];
      (!isset($_POST['heatdelivery']))              ? $heatdlvy                  = '' : $heatdlvy                  = implode(',', $_POST['heatdelivery']);
      (!isset($_POST['heatcontrols']))              ? $heatcont                  = '' : $heatcont                  = implode(',', $_POST['heatcontrols']);
      (!isset($_POST['secondaryspaceheatingfuel'])) ? $secondaryspaceheatingfuel = '' : $secondaryspaceheatingfuel = $_POST['secondaryspaceheatingfuel'];
      (!isset($_POST['secondaryheatingtype']))      ? $secondaryheatingtype      = '' : $secondaryheatingtype      = $_POST['secondaryheatingtype'];

      # set notes if blank
      (!isset($_POST['mainheatinginformation']))      ? $mainheatinginformation = ''      : $mainheatinginformation = $_POST['mainheatinginformation'];
      (!isset($_POST['heatcontrolsnotes']))           ? $heatcontrolsnotes = ''           : $heatcontrolsnotes = $_POST['heatcontrolsnotes'];
      (!isset($_POST['secondaryheatinginformation'])) ? $secondaryheatinginformation = '' : $secondaryheatinginformation = $_POST['secondaryheatinginformation'];
      (!isset($_POST['heatnotes']))                   ? $heatnotes = ''                   : $heatnotes = $_POST['heatnotes'];

      $sql = "UPDATE services ".      
               " SET MainSpaceHeatingFuel='"      . clean_input($mainspaceheatingfuel) .
               "', MainHeatingType='"             . clean_input($mainheatingtype) .
               "', MainHeatingInformation='"      . clean_input($mainheatinginformation) .
               "', Hybridisation="                . clean_input($hybridisation) .
               ",  HeatDelivery='"                . clean_input($heatdlvy) . 
               "', HeatControls='"                . clean_input($heatcont) . 
               "', HeatControlsNotes='"           . clean_input($heatcontrolsnotes) .
               "', SecondarySpaceHeatingFuel='"   . clean_input($secondaryspaceheatingfuel) .
               "', SecondaryHeatingType='"        . clean_input($secondaryheatingtype) .
               "', SecondaryHeatingInformation='" . clean_input($secondaryheatinginformation) .
               "', HeatNotes='"                   . clean_input($heatnotes) .
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
#-------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 82</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Heating</em></strong></h2>
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
               <th>Main Space Heating Fuel</th>
               <td>
                  <select id="mainspaceheatingfuel" name="mainspaceheatingfuel" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Electric"   <?php echo $row['MainSpaceHeatingFuel'] == "Electric"   ? " selected" : ""; ?>>Electric</option>
                     <option value="Mains Gas"  <?php echo $row['MainSpaceHeatingFuel'] == "Mains Gas"  ? " selected" : ""; ?>>Mains Gas</option>
                     <option value="Bottle Gas" <?php echo $row['MainSpaceHeatingFuel'] == "Bottle Gas" ? " selected" : ""; ?>>Bottle Gas</option>
                     <option value="Solid fuel" <?php echo $row['MainSpaceHeatingFuel'] == "Solid fuel" ? " selected" : ""; ?>>Solid fuel</option>
                     <option value="Bio fuel"   <?php echo $row['MainSpaceHeatingFuel'] == "Bio fuel"   ? " selected" : ""; ?>>Bio fuel</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="mainspaceheatingfuel_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The type of fuel gives an indication of carbon intensity and 
                  opportunities for improvements. Bio Fuel includes biomass, 
                  bio-oil and bio-gas.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Main Heating Type</th>
               <td>
                  <select id="mainheatingtype" name="mainheatingtype" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Combi Boiler"            <?php echo $row['MainHeatingType'] == "Combi Boiler"            ? " selected" : ""; ?>>Combi Boiler</option>
                     <option value="System Boiler"           <?php echo $row['MainHeatingType'] == "System Boiler"           ? " selected" : ""; ?>>System Boiler</option>
                     <option value="Back Boiler"             <?php echo $row['MainHeatingType'] == "Back Boiler"             ? " selected" : ""; ?>>Back Boiler</option>
                     <option value="Heat Pump"               <?php echo $row['MainHeatingType'] == "Heat Pump"               ? " selected" : ""; ?>>Heat Pump</option>
                     <option value="Hybrid Boiler Heat Pump" <?php echo $row['MainHeatingType'] == "Hybrid Boiler Heat Pump" ? " selected" : ""; ?>>Hybrid Boiler Heat Pump</option>
                     <option value="Room Heaters"            <?php echo $row['MainHeatingType'] == "Room Heaters"            ? " selected" : ""; ?>>Room Heaters</option>
                     <option value="Aga / Stove"             <?php echo $row['MainHeatingType'] == "Aga / Stove"             ? " selected" : ""; ?>>Aga / Stove</option>
                     <option value="CHP"                     <?php echo $row['MainHeatingType'] == "CHP"                     ? " selected" : ""; ?>>CHP</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="mainheatingtype_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The type of heat source gives an indication of energy efficiency 
                  and opportunities for improvements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Main Heating Information </th>
               <td>
                  <textarea placeholder="Note here manufacturer, model and any further info" name="mainheatinginformation" rows="2" cols="30"><?php echo $row['MainHeatingInformation']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Hybridisation - Space Available?</th>
               <td>
                  <input type="checkbox" class="chk" name="hybridisation" value="<?php echo ($row['Hybridisation']=='1' ? '1' : '0');?>" <?php echo ($row['Hybridisation']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="hybridisation_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Opportunity for hybridisation may bring opportunities for energy / money saving
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Heat Delivery Type</th>
               <td>
                  <select id="heatdelivery" name="heatdelivery[]" multiple="multiple" size="4">
                     <option value="" disabled >Please choose...</option>
                     <option value="Radiators"  <?php echo (isset($heatdelAry) && in_array('Radiators', $heatdelAry)) ? " selected" : ""; ?>>Radiators</option>
                     <option value="Underfloor" <?php echo (isset($heatdelAry) && in_array('Underfloor', $heatdelAry))  ? " selected" : ""; ?>>Underfloor</option>
                     <option value="Warmed Air" <?php echo (isset($heatdelAry) && in_array('Warmed Air', $heatdelAry))  ? " selected" : ""; ?>>Warmed Air</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Heat Controls</th>
               <td>
                  <select id="heatcontrols" name="heatcontrols[]" multiple="multiple" size="4">
                     <option value="" disabled >Please choose...</option>
                     <option value="TRV"                  <?php echo (isset($heatctrlAry) && in_array('TRV', $heatctrlAry))                  ? " selected" : ""; ?>>TRV</option>
                     <option value="Timer"                <?php echo (isset($heatctrlAry) && in_array('Timer', $heatctrlAry))                ? " selected" : ""; ?>>Timer</option>
                     <option value="Room thermostat"      <?php echo (isset($heatctrlAry) && in_array('Room thermostat', $heatctrlAry))      ? " selected" : ""; ?>>Room thermostat</option>
                     <option value="Boiler thermostat"    <?php echo (isset($heatctrlAry) && in_array('Boiler thermostat', $heatctrlAry))    ? " selected" : ""; ?>>Boiler thermostat</option>
                     <option value="Smart controls"       <?php echo (isset($heatctrlAry) && in_array('Smart controls', $heatctrlAry))       ? " selected" : ""; ?>>Smart controls</option>
                     <option value="Weather compensation" <?php echo (isset($heatctrlAry) && in_array('Weather compensation', $heatctrlAry)) ? " selected" : ""; ?>>Weather compensation</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="hybridisation_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The type of heat controller gives an indication of opportunities for improvements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Heat Controls Notes</th>
               <td>
                  <textarea name="heatcontrolsnotes" rows="2" cols="30"><?php echo $row['HeatControlsNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Secondary Space Heating Fuel</th>
               <td>
                  <select id="secondaryspaceheatingfuel" name="secondaryspaceheatingfuel" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Electric"   <?php echo $row['SecondarySpaceHeatingFuel'] == "Electric" ? " selected" : ""; ?>  >Electric</option>
                     <option value="Mains Gas"  <?php echo $row['SecondarySpaceHeatingFuel'] == "Mains Gas" ? " selected" : ""; ?> >Mains Gas</option>
                     <option value="Bottle Gas" <?php echo $row['SecondarySpaceHeatingFuel'] == "Bottle Gas" ? " selected" : ""; ?>>Bottle Gas</option>
                     <option value="Solid fuel" <?php echo $row['SecondarySpaceHeatingFuel'] == "Solid fuel" ? " selected" : ""; ?>>Solid fuel</option>
                     <option value="Bio fuel"   <?php echo $row['SecondarySpaceHeatingFuel'] == "Bio fuel" ? " selected" : ""; ?>  >Bio fuel</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Secondary Heating Type</th>
               <td>
                  <select id="secondaryheatingtype" name="secondaryheatingtype" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Boiler"       <?php echo $row['SecondaryHeatingType'] == "Boiler"       ? " selected" : ""; ?>>Boiler</option>
                     <option value="Aga / Stove"  <?php echo $row['SecondaryHeatingType'] == "Aga / Stove"  ? " selected" : ""; ?>>Aga / Stove</option>
                     <option value="Room Heaters" <?php echo $row['SecondaryHeatingType'] == "Room Heaters" ? " selected" : ""; ?>>Room Heaters</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Secondary Heating Information</th>
               <td>
                  <textarea placeholder="Note here manufacturer, model and any further info" name="secondaryheatinginformation" rows="2" cols="30"><?php echo $row['SecondaryHeatingInformation']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Heat Notes</th>
               <td>
                  <textarea name="heatnotes" rows="2" cols="30"><?php echo $row['HeatNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form80.php" title="Form 80">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form83.php" title="Form 83">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form> 
      </div>   
   <footer>
   </footer>  
   </body>
</html>
