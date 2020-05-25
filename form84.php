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

   $renewnrgAry = array();
   $renewnrgAry = explode(',',$row['RenewableEnergy']);

   $washAry = array();
   $washAry = explode(',',$row['Washing']);
#------------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form84.php");

      # set checkboxes if blank
      (!isset($_POST['systemsworking']))   ? $systemsworking    = 0 : $systemsworking    = 1;
      
      # set dropdowns if blank
      (!isset($_POST['lighting']))        ? $lighting        = '' : $lighting        = $_POST['lighting'];
      (!isset($_POST['renewableenergy'])) ? $renewableenergy = '' : $renewableenergy = implode(',', $_POST['renewableenergy']);
      (!isset($_POST['whitegoods']))      ? $whitegoods      = '' : $whitegoods      = $_POST['whitegoods'];
      (!isset($_POST['washingdrying']))   ? $washingdrying   = '' : $washingdrying   = implode(',', $_POST['washingdrying']);

      # set notes if blank
      (!isset($_POST['lightingnotes']) )  ? $lightingnotes = '' : $lightingnotes = $_POST['lightingnotes'];
      (!isset($_POST['servicesnotes']) )  ? $servicesnotes = '' : $servicesnotes = $_POST['servicesnotes'];

      # these need to be set to cope with slightly different logic
      if (!isset($_POST['recessedlighting'])) {
         $recessedlighting = 0;
      } else {
         $recessedlighting = $_POST['recessedlighting'];
      } 
      if (!isset($_POST['buildingregsok'])) {
         $buildingregsok = 0;
      } else {
         $buildingregsok = $_POST['buildingregsok'];
      } 

      $sql = "UPDATE services ".      
               " SET Lighting='"       . clean_input($lighting) .
               "', RecessedLighting="  . clean_input($recessedlighting) .
               ",  BuildingRegsOK="    . clean_input($buildingregsok) .
               ",  LightingNotes='"    . clean_input($lightingnotes) .
               "', RenewableEnergy='"  . clean_input($renewableenergy) .
               "', WhiteGoods='"       . clean_input($whitegoods) .
               "', Washing='"          . clean_input($washingdrying) .
               "', SystemsWorking='"   . clean_input($systemsworking) . 
               "', ServicesNotes='"    . clean_input($servicesnotes) . 
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
#------------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 84</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Lighting & Electrical</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideBuildingRegsComp();
           showBuildingRegsComp();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
          
         function hideBuildingRegsComp() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("buildingregscompChk");
             obj2 = document.getElementById("buildingregscompHdr");
             ttobj = document.getElementById("buildingregsok_tt");
             obj1.style.display="none";
             obj2.style.display="none";
             ttobj.style.display="none";
         }

         function showBuildingRegsComp() {
             if (!document.getElementById) return;
             recessedlightsObj = document.getElementById("recessedlightsChk");
             if (recessedlightsObj.checked == true ){
                brobj1 = document.getElementById("buildingregscompChk");
                brobj2 = document.getElementById("buildingregscompHdr");
                ttobj = document.getElementById("buildingregsok_tt");
                brobj1.style.display="block";
                brobj2.style.display="block";
                ttobj.style.display="block";
             } else {
               hideBuildingRegsComp();
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
               <th>Lighting</th>
               <td>
                  <select id="lighting" name="lighting" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="All / mostly LED"          <?php echo $row['Lighting'] == "All / mostly LED" ? " selected" : ""; ?>         >All / mostly LED</option>
                     <option value="All / mostly Incandescent" <?php echo $row['Lighting'] == "All / mostly Incandescent" ? " selected" : ""; ?>>All / mostly Incandescent</option>
                     <option value="All / mostly CFL"          <?php echo $row['Lighting'] == "All / mostly CFL" ? " selected" : ""; ?>         >All / mostly CFL</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="lighting_tt"  >
                  <span class="tooltiptext" >
                  The type of lighting gives an indication of energy efficiency and 
                  opportunities for improvements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Downlighters/Recessed Lighting</th>
               <td>
                  <input type="checkbox" id="recessedlightsChk"  name="recessedlighting" onchange="showBuildingRegsComp()" value="<?php echo ($row['RecessedLighting']=='1' ? '1' : '0');?>" <?php echo ($row['RecessedLighting']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="recessedlighting_tt"  >
                  <span class="tooltiptext" >
                  Recessed lighting requires heat dispersion to reduce fire risk. 
                  This is achieved through either leaving space devoid of insulation 
                  above the fitting or via the use of caps over the fitting.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
               <th id="buildingregscompHdr">Building Regs Compliant</th>
               <td>
                  <input type="checkbox" id="buildingregscompChk" name="buildingregsok" value="<?php echo ($row['BuildingRegsOK']=='1' ? '1' : '0');?>" <?php echo ($row['BuildingRegsOK']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="buildingregsok_tt"  >
                  <span class="tooltiptext" >
                  <pre>This needs to comply with :- 
                  Parts B (Fire Safety), 
                  C (Site preparation and resistance to contaminates and moisture), 
                  E (Resistance to sound) and 
                  L (Conservation of fuel and power)</pre>
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Lighting Notes</th>
               <td>
                  <textarea name="lightingnotes" rows="2" cols="30"><?php echo $row['LightingNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Renewable Energy</th>
               <td>
                  <select id="renewableenergy" name="renewableenergy[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Solar Thermal" <?php echo (isset($renewnrgAry) && in_array('Solar Thermal', $renewnrgAry)) ? " selected" : ""; ?>>Solar Thermal</option>
                     <option value="Solar PV"      <?php echo (isset($renewnrgAry) && in_array('Solar PV', $renewnrgAry))      ? " selected" : ""; ?>>Solar PV</option>
                     <option value="Wind turbine"  <?php echo (isset($renewnrgAry) && in_array('Wind turbine', $renewnrgAry))  ? " selected" : ""; ?>>Wind turbine</option>
                     <option value="Hydro"         <?php echo (isset($renewnrgAry) && in_array('Hydro', $renewnrgAry))         ? " selected" : ""; ?>>Hydro</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="renewableenergy_tt"  >
                  <span class="tooltiptext" >
                  Renewable energy does not affect energy efficiency, but it can 
                  affect carbon intensity, choice of systems and indicate level of 
                  occupier knowledge and engagement thus indicating opportunities 
                  for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>White Goods</th>
               <td>
                  <select id="whitegoods" name="whitegoods" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Mostly above A" <?php echo $row['WhiteGoods'] == "Mostly above A" ? " selected" : ""; ?>>Mostly above A</option>
                     <option value="Mostly below A" <?php echo $row['WhiteGoods'] == "Mostly below A" ? " selected" : ""; ?>>Mostly below A</option>
                     <option value="Unknown"        <?php echo $row['WhiteGoods'] == "Unknown" ? " selected" : ""; ?>       >Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="whitegoods_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  White goods consume relativity high levels of electricity and 
                  whilst not fixtures it gives an indication of potential energy 
                  efficiency savings and opportunities for improvements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Washing/Drying</th>
               <td>
                  <select id="washingdrying" name="washingdrying[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="No drier"                <?php echo (isset($washAry) && in_array('No drier', $washAry))                ? " selected" : ""; ?>>No drier</option>
                     <option value="External clothes line"   <?php echo (isset($washAry) && in_array('External clothes line', $washAry))   ? " selected" : ""; ?>>External clothes line</option>
                     <option value="Internal clothes drying" <?php echo (isset($washAry) && in_array('Internal clothes drying', $washAry)) ? " selected" : ""; ?>>Internal clothes drying</option>
                     <option value="Condensing drier"        <?php echo (isset($washAry) && in_array('Condensing drier', $washAry))        ? " selected" : ""; ?>>Condensing drier</option>
                     <option value="Drier vented externally" <?php echo (isset($washAry) && in_array('Drier vented externally', $washAry)) ? " selected" : ""; ?>>Drier vented externally</option>
                     <option value="Drier vented internally" <?php echo (isset($washAry) && in_array('Drier vented internally', $washAry)) ? " selected" : ""; ?>>Drier vented internally</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="washingdrying_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The moisture associated with driers can be problematic with condensation.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>Systems Working Correctly</th>
               <td>
                  <input type="checkbox"  name="systemsworking" value="<?php echo ($row['SystemsWorking']=='1' ? '1' : '0');?>" <?php echo ($row['SystemsWorking']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Services Notes</th>
               <td>
                  <textarea name="servicesnotes" rows="2" cols="30"><?php echo $row['ServicesNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form83.php" title="Form 83">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form87.php" title="Form 87">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
