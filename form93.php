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

   $q = 'SELECT * FROM additionalinformation  WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $invequipAry = array();
   $invequipAry = explode(',',$row['InvasiveEquipment']);
#--------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form93.php");

      # set checkboxes if blank
      (!isset($_POST['protimeterpin']))    ? $protimeterpin    = 0 : $protimeterpin    = 1;
      (!isset($_POST['protimeternoninv'])) ? $protimeternoninv = 0 : $protimeternoninv = 1;
      (!isset($_POST['protimeterwood']))   ? $protimeterwood   = 0 : $protimeterwood   = 1;
      (!isset($_POST['thermalcamera']))    ? $thermalcamera    = 0 : $thermalcamera    = 1;
      (!isset($_POST['relhumidmeter']))    ? $relhumidmeter    = 0 : $relhumidmeter    = 1;
      (!isset($_POST['binoculars']))       ? $binoculars       = 0 : $binoculars       = 1;
      (!isset($_POST['ladder']))           ? $ladder           = 0 : $ladder           = 1;
      (!isset($_POST['torch']))            ? $torch            = 0 : $torch            = 1;
      (!isset($_POST['crackgauge']))       ? $crackgauge       = 0 : $crackgauge       = 1;
      (!isset($_POST['lightmeter']))       ? $lightmeter       = 0 : $lightmeter       = 1;
      (!isset($_POST['laserortape']))      ? $laserortape      = 0 : $laserortape      = 1;
      (!isset($_POST['level']))            ? $level            = 0 : $level            = 1;
      (!isset($_POST['plumbline']))        ? $plumbline        = 0 : $plumbline        = 1;
      (!isset($_POST['airpresstest']))     ? $airpresstest     = 0 : $airpresstest     = 1;
      (!isset($_POST['soundmeter']))       ? $soundmeter       = 0 : $soundmeter       = 1;
      
      # set textboxes if blank

      # set dropdowns if blank
      (!isset($_POST['invasiveequipment'])) ? $invasiveequipment = '' : $invasiveequipment = implode(',', $_POST['invasiveequipment']);

      # set notes if blank
      (!isset($_POST['investigationsnotes'])) ? $investigationsnotes = '' : $investigationsnotes = $_POST['investigationsnotes'];

      $sql = "UPDATE additionalinformation ".      
               " SET ProtimeterPin="      . clean_input($protimeterpin) .
               ",  ProtimeterNonInv="     . clean_input($protimeternoninv) .
               ",  ProtimeterWood="       . clean_input($protimeterwood) . 
               ",  ThermalCamera="        . clean_input($thermalcamera) .
               ",  RelHumidMeter="        . clean_input($relhumidmeter) .
               ",  Binoculars="           . clean_input($binoculars) .
               ",  Ladder="               . clean_input($ladder) .
               ",  Torch="                . clean_input($torch) .
               ",  CrackGauge="           . clean_input($crackgauge) .
               ",  LightMeter="           . clean_input($lightmeter) .
               ",  SoundMeter="           . clean_input($soundmeter) .
               ",  LaserOrTape="          . clean_input($laserortape) .
               ",  Level="                . clean_input($level) .
               ",  PlumbLine="            . clean_input($plumbline) .
               ",  AirPressTest="         . clean_input($airpresstest) .
               ",  InvasiveEquipment='"   . clean_input($invasiveequipment) .
               "', InvestigationsNotes='" . clean_input($investigationsnotes) .
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
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 93</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Investigative Equipment Used</em></strong></h2>
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
               <th>Protimeter masonry pin</th>
               <td>
                  <input type="checkbox" name="protimeterpin" value="<?php echo ($row['ProtimeterPin']=='1' ? '1' : '0');?>" <?php echo ($row['ProtimeterPin']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Protimeter masonry non-invasive</th>
               <td>
                  <input type="checkbox" name="protimeternoninv" value="<?php echo ($row['ProtimeterNonInv']=='1' ? '1' : '0');?>" <?php echo ($row['ProtimeterNonInv']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Protimeter wood</th>
               <td>
                  <input type="checkbox"  name="protimeterwood" value="<?php echo ($row['ProtimeterWood']=='1' ? '1' : '0');?>" <?php echo ($row['ProtimeterWood']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Thermal imaging camera</th>
               <td>
                  <input type="checkbox"  name="thermalcamera" value="<?php echo ($row['ThermalCamera']=='1' ? '1' : '0');?>" <?php echo ($row['ThermalCamera']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Relative humidity meter</th>
               <td>
                  <input type="checkbox"  name="relhumidmeter" value="<?php echo ($row['RelHumidMeter']=='1' ? '1' : '0');?>" <?php echo ($row['RelHumidMeter']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Binoculars</th>
               <td>
                  <input type="checkbox" name="binoculars" value="<?php echo ($row['Binoculars']=='1' ? '1' : '0');?>" <?php echo ($row['Binoculars']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Ladder</th>
               <td>
                  <input type="checkbox" name="ladder" value="<?php echo ($row['Ladder']=='1' ? '1' : '0');?>" <?php echo ($row['Ladder']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Torch</th>
               <td>
                  <input type="checkbox"  name="torch" value="<?php echo ($row['Torch']=='1' ? '1' : '0');?>" <?php echo ($row['Torch']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Crack width gauge</th>
               <td>
                  <input type="checkbox"  name="crackgauge" value="<?php echo ($row['CrackGauge']=='1' ? '1' : '0');?>" <?php echo ($row['CrackGauge']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Light meter</th>
               <td>
                  <input type="checkbox" name="lightmeter" value="<?php echo ($row['LightMeter']=='1' ? '1' : '0');?>" <?php echo ($row['LightMeter']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Sound level meter</th>
               <td>
                  <input type="checkbox" name="soundmeter" value="<?php echo ($row['SoundMeter']=='1' ? '1' : '0');?>" <?php echo ($row['SoundMeter']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Laser/tape measure</th>
               <td>
                  <input type="checkbox" name="laserortape" value="<?php echo ($row['LaserOrTape']=='1' ? '1' : '0');?>" <?php echo ($row['LaserOrTape']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Level</th>
               <td>
                  <input type="checkbox"  name="level" value="<?php echo ($row['Level']=='1' ? '1' : '0');?>" <?php echo ($row['Level']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Plumb line</th>
               <td>
                  <input type="checkbox"  name="plumbline" value="<?php echo ($row['PlumbLine']=='1' ? '1' : '0');?>" <?php echo ($row['PlumbLine']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Air pressure test equipment</th>
               <td>
                  <input type="checkbox"  name="airpresstest" value="<?php echo ($row['AirPressTest']=='1' ? '1' : '0');?>" <?php echo ($row['AirPressTest']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Equipment used, Invasive</th>
               <td>
                  <select id="invasiveequipment" name="invasiveequipment[]" multiple="multiple"  size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Boroscope"            <?php echo (isset($invequipAry) && in_array('Boroscope', $invequipAry))            ? " selected" : ""; ?>>Boroscope</option>
                     <option value="Calcium Carbide test" <?php echo (isset($invequipAry) && in_array('Calcium Carbide test', $invequipAry)) ? " selected" : ""; ?>>Calcium Carbide test</option>
                     <option value="Salt test"            <?php echo (isset($invequipAry) && in_array('Salt test', $invequipAry))            ? " selected" : ""; ?>>Salt test</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Investigations Notes</th>
               <td>
                  <textarea name="investigationsnotes" rows="2" cols="30"><?php echo $row['InvestigationsNotes']?></textarea>
               </td>
            </tr>               
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form92.php" title="Form 92">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form95.php" title="Form 95">Next</a>
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
