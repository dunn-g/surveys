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

   $q = 'SELECT * FROM additionalinformation WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $occupierAry = array();
   $occupierAry = explode(',',$row['Occupier']);
#-----------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form90.php");

      # set checkboxes if blank
      (!isset($_POST['internetconnection']))    ? $internetconnection    = 0 : $internetconnection    = 1;
      (!isset($_POST['datalogger']))            ? $datalogger            = 0 : $datalogger            = 1;
      (!isset($_POST['sprinklers']))            ? $sprinklers            = 0 : $sprinklers            = 1;
      
      # set numeric textboxes if blank
      (!isset($_POST['electricitymeterreading'])) ? $electricitymeterreading  = 0 : $electricitymeterreading  = $_POST['electricitymeterreading'];
      (!isset($_POST['gasmeterreading']))         ? $gasmeterreading  = 0         : $gasmeterreading  = $_POST['gasmeterreading'];
      (!isset($_POST['dataloggernumber']))        ? $dataloggernumber  = ''       : $dataloggernumber  = $_POST['dataloggernumber'];

      # set dropdowns if blank
      (!isset($_POST['occupier']))            ? $occupier = ''            : $occupier            = implode(',', $_POST['occupier']);
      (!isset($_POST['smartmeterinstalled'])) ? $smartmeterinstalled = '' : $smartmeterinstalled = $_POST['smartmeterinstalled'];

      # set notes if blank
      (!isset($_POST['accessability']))    ? $accessability    = '' : $accessability    = $_POST['accessability'];
      (!isset($_POST['lifetimehomes']))    ? $lifetimehomes    = '' : $lifetimehomes    = $_POST['lifetimehomes'];
      (!isset($_POST['occupiercomments'])) ? $occupiercomments = '' : $occupiercomments = $_POST['occupiercomments'];
      (!isset($_POST['additionalnotes']))  ? $additionalnotes  = '' : $additionalnotes  = $_POST['additionalnotes'];

      # these need to be set to cope with slightly different logic
      if (!isset($_POST['firealarm'])) {
         $firealarm = 0;
         $firealarmpower = '';
      } else {
         $firealarm = 1;
         $firealarmpower = $_POST['firealarmpower'];
      }

      if (!isset($_POST['carbonmonoxidealarm'])) {
         $carbonmonoxidealarm = 0;
         $roomsservedbyco2alarm = 0;
      } else {
         $carbonmonoxidealarm = 1;
         $roomsservedbyco2alarm = $_POST['roomsservedbyco2alarm'];
      } 

      $sql = "UPDATE additionalinformation ".      
               " SET Accessability='"      . clean_input($accessability) .
               "', LifetimeHomes='"        . clean_input($lifetimehomes) .
               "', Occupier='"             . clean_input($occupier) .
               "', OccupierComments='"     . clean_input($occupiercomments) .
               "', InternetConnection="    . clean_input($internetconnection) . 
               ",  SmartMeterInstalled='"  . clean_input($smartmeterinstalled) .
               "', ElectrictyMeterReading=". clean_input($electricitymeterreading) .
               ",  GasMeterReading="       . clean_input($gasmeterreading) .
               ",  DataLogger="            . clean_input($datalogger) .
               ",  DataLoggerNumber="      . clean_input($dataloggernumber) .
               ",  FireAlarm="             . clean_input($firealarm) .
               ",  FireAlarmPower='"       . clean_input($firealarmpower) .
               "', Sprinklers="            . clean_input($sprinklers) .
               ",  CarbonMonoxideAlarm="   . clean_input($carbonmonoxidealarm) .
               ",  RoomsServedByCo2Alarm=" . clean_input($roomsservedbyco2alarm) .
               ",  AdditionalNotes='"      . clean_input($additionalnotes) .
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
#-----------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 90</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Additional Information</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideFireAlarmPower();
           showFireAlarmPower();
           
           hideCORoomsServed();
           showCORoomsServed();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
          
         function hideFireAlarmPower() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("firealarmpowerSel");
             obj2 = document.getElementById("firealarmpowerHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showFireAlarmPower() {
             if (!document.getElementById) return;
             firealarmObj = document.getElementById("firealarmChk");
             if (firealarmObj.checked == true ){
                obj1 = document.getElementById("firealarmpowerSel");
                obj2 = document.getElementById("firealarmpowerHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
                obj1.value = "";                
               hideFireAlarmPower();
             }
         } 
 
         function hideCORoomsServed() {
             if (!document.getElementById) return;
             coaobj1 = document.getElementById("roomsservedSel");
             coaobj2 = document.getElementById("roomsservedHdr");
             coaobj1.style.display="none";
             coaobj2.style.display="none";
         }

         function showCORoomsServed() {
             if (!document.getElementById) return;
             COAlarmObj = document.getElementById("carbonmonoxidealarmChk");
             if (COAlarmObj.checked == true ){
                coaobj1 = document.getElementById("roomsservedSel");
                coaobj2 = document.getElementById("roomsservedHdr");
                coaobj1.style.display="block";
                coaobj2.style.display="block";
             } else {
               hideCORoomsServed();
             }
         } 
        
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
               <th>Accessability</th>
               <td>
                  <textarea name="accessability" rows="2" cols="30"><?php echo $row['Accessability']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="accessability_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Raise any issues that occupier may have
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Life Time Homes</th>
               <td>
                <textarea name="lifetimehomes" rows="2" cols="30"><?php echo $row['LifetimeHomes']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="lifetimehomes_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Raise any issues that occupier may have
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th id="firealarm" >Fire Alarm</th>
               <td>
                  <input type="checkbox" class="chk" id="firealarmChk" name="firealarm" onchange="showFireAlarmPower()" value="<?php echo ($row['FireAlarm']=='1' ? '1' : '0');?>" <?php echo ($row['FireAlarm']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="firealarmpowerHdr"> Power</th>
               <td>
                  <select id="firealarmpowerSel" name="firealarmpower" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Battery"    <?php echo $row['FireAlarmPower'] == "Battery"    ? " selected" : ""; ?>>Battery</option>
                     <option value="Hard wired" <?php echo $row['FireAlarmPower'] == "Hard wired" ? " selected" : ""; ?>>Hard wired</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Carbon Monoxide Alarm</th>
               <td>
                  <input type="checkbox" class="chk" id="carbonmonoxidealarmChk" name="carbonmonoxidealarm" onchange="showCORoomsServed()" value="<?php echo ($row['CarbonMonoxideAlarm']=='1' ? '1' : '0');?>" <?php echo ($row['CarbonMonoxideAlarm']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="roomsservedHdr">All rooms served by alarm</th>
               <td>
                  <input type="checkbox" class="chk" id="roomsservedSel" name="roomsservedbyco2alarm" value="<?php echo ($row['RoomsServedByCo2Alarm']=='1' ? '1' : '0');?>" <?php echo ($row['RoomsServedByCo2Alarm']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Sprinklers</th>
               <td>
                  <input type="checkbox" class="chk" name="sprinklers" value="<?php echo ($row['Sprinklers']=='1' ? '1' : '0');?>" <?php echo ($row['Sprinklers']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th>Internet Connection</th>
               <td>
                  <input type="checkbox" class="chk" name="internetconnection" value="<?php echo ($row['InternetConnection']=='1' ? '1' : '0');?>" <?php echo ($row['InternetConnection']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Smart Meter Installed</th>
               <td>
                  <select id="smartmeterinstalled" name="smartmeterinstalled" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"           <?php echo $row['SmartMeterInstalled'] == "None" ? " selected" : ""; ?>          >None</option>
                     <option value="Type 1"         <?php echo $row['SmartMeterInstalled'] == "Type 1" ? " selected" : ""; ?>        >Type 1</option>
                     <option value="Type 2"         <?php echo $row['SmartMeterInstalled'] == "Type 2" ? " selected" : ""; ?>        >Type 2</option>
                     <option value="Type 3" <?php echo $row['SmartMeterInstalled'] == "Type 3" ? " selected" : ""; ?>>Type 3</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Data Logger</th>
               <td>
                  <input type="checkbox" class="chk" name="datalogger" value="<?php echo ($row['DataLogger']=='1' ? '1' : '0');?>" <?php echo ($row['DataLogger']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Data Logger Number</th>
               <td>
               <input type="text" name="dataloggernumber" value="<?php echo $row['DataLoggerNumber']?>">
               </td>
            </tr>
            <tr>
               <th>Electricity Meter Reading</th>
               <td>
               <input type="text" name="electricitymeterreading" value="<?php echo $row['ElectrictyMeterReading']?>">
               </td>
            </tr>
            <tr>
               <th>Gas Meter Reading</th>
               <td>
               <input type="text" name="gasmeterreading" value="<?php echo $row['GasMeterReading']?>">
               </td>
            </tr> 
            <tr>
               <th>Occupier</th>Occupier
               <td>
                  <select id="occupier" name="occupier[]" multiple="multiple" size="4">
                     <option value="" disabled >Please choose...</option>
                     <option value="Energy Aware"      <?php echo (isset($occupierAry) && in_array('Energy Aware', $occupierAry))      ? " selected" : ""; ?>>Energy Aware</option>
                     <option value="Health Aware"      <?php echo (isset($occupierAry) && in_array('Health Aware', $occupierAry))      ? " selected" : ""; ?>>Health Aware</option>
                     <option value="Ventilation Aware" <?php echo (isset($occupierAry) && in_array('Ventilation Aware', $occupierAry)) ? " selected" : ""; ?>>Ventilation Aware</option>
                     <option value="Damp Aware"        <?php echo (isset($occupierAry) && in_array('Damp Aware', $occupierAry))        ? " selected" : ""; ?>>Damp Aware</option>
                     <option value="Heritage Aware"    <?php echo (isset($occupierAry) && in_array('Heritage Aware', $occupierAry))    ? " selected" : ""; ?>>Heritage Aware</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="occupier_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To reinforce that there is behaviour change involved as well 
                  as technical challenges.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Occupier Comments</th>
               <td>
                  <textarea name="occupiercomments" rows="2" cols="30"><?php echo $row['OccupierComments']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="occupiercomments_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This might be additional information on past events, 
                  identification of specific problems.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>Additional Notes</th>
               <td>
                  <textarea name="additionalnotes" rows="2" cols="30"><?php echo $row['AdditionalNotes']?></textarea>
               </td>
            </tr>               
         </table>

         <br>         
         <br> 
         <div id="pagefooter" style="text-align:center;bottom: 0%;">
            <a href="form87.php" title="Form 87">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form92.php" title="Form 92">Next</a>
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
