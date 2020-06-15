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

   $sql = 'SELECT * FROM `wall extension 1` WHERE SurveyId = "'.$surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 
   
   $ext1pcieAry = array();
   $ext1pcieAry = explode(',',$row['Ext1PermCompdIntExt']);
   echo "<br>";         
   
#----------------------------------------------------------


   if (isset ($_POST['submit'])){

      header("location:form32.php");

      #print_r($_POST);
      #echo "<br>";         

      # set checkboxes if blank
      (!isset($_POST['wallsext1significance'])) ? $wallsext1significance = 0 : $wallsext1significance = 1;
      (!isset($_POST['wallsext1insulated']))    ? $wallsext1insulated    = 0 : $wallsext1insulated    = 1;

      # set numerical textboxt if blank
      (!isset($_POST['wallsext1thickness'])) ? $wallsext1thickness = 0 : $wallsext1thickness = $_POST['wallsext1thickness'];
      
      # set dropdowns if blank
      (!isset($_POST['wallsext1intwallpermcompd']))    ? $wallsext1intwallpermcompd    = '' : $wallsext1intwallpermcompd    = $_POST['wallsext1intwallpermcompd'];
      (!isset($_POST['wallsext1type']))                ? $wallsext1type                = '' : $wallsext1type                = $_POST['wallsext1type'];
      (!isset($_POST['wallsext1insulation']))          ? $wallsext1insulation          = '' : $wallsext1insulation          = $_POST['wallsext1insulation'];
      (!isset($_POST['wallsext1insulationthickness'])) ? $wallsext1insulationthickness = '' : $wallsext1insulationthickness = $_POST['wallsext1insulationthickness'];
      (!isset($_POST['wallsext1insulationtype']))      ? $wallsext1insulationtype      = '' : $wallsext1insulationtype      = $_POST['wallsext1insulationtype'];
      (!isset($_POST['dampproofcourse']))              ? $dampproofcourse              = '' : $dampproofcourse              = $_POST['dampproofcourse'];
      (!isset($_POST['wallsext1retrofit']))            ? $wallsext1retrofit            = '' : $wallsext1retrofit            = $_POST['wallsext1retrofit'];

      # set notes textarea if blank
      (!isset($_POST['wallsext1notes']) ) ? $wallsext1notes = '' : $wallsext1notes = $_POST['wallsext1notes'];
      (!isset($_POST['dpcnotes']) )       ? $dpcnotes = ''       : $dpcnotes       = $_POST['dpcnotes'];

      if (!isset($_POST['wallsext1permeability'])) {
         $wallsext1permeability    = '';
         $wallsext1permbltycomprsd = '';
         $ext1permcompdintext      = '';
      } else if (($_POST['wallsext1permeability'] == "Moisture closed")  || ($_POST['wallsext1permeability'] == "Unknown" )){
         $wallsext1permeability    = $_POST['wallsext1permeability'];
         $wallsext1permbltycomprsd = '';
         $ext1permcompdintext      = '';
      } else {
         $wallsext1permeability    = $_POST['wallsext1permeability'];
         $wallsext1permbltycomprsd = $_POST['wallsext1permbltycomprsd'];
         $ext1permcompdintext      = implode(',', $_POST['ext1permcompdintext']);
      } 
      
      $sql = "UPDATE `wall extension 1` ".      
               " SET WallsExt1Significance='"      . clean_input($wallsext1significance) .
               "', WallsExt1Permeability='"        . clean_input($wallsext1permeability) .
               "', WallsExt1PermbltyComprsd='"     . clean_input($wallsext1permbltycomprsd) .
               "', Ext1PermCompdIntExt='"          . clean_input($ext1permcompdintext) .
               "', WallsExt1Type='"                . clean_input($wallsext1type) .
               "', WallsExt1Thickness="            . clean_input($wallsext1thickness) . 
               ",  WallsExt1Insulated="            . clean_input($wallsext1insulated) . 
               ",  WallsExt1Insulation='"          . clean_input($wallsext1insulation) . 
               "', WallsExt1InsulationThickness='" . clean_input($wallsext1insulationthickness) .
               "', WallsExt1InsulationType='"      . clean_input($wallsext1insulationtype) .
               "', WallsExt1Retrofit='"            . clean_input($wallsext1retrofit) .
               "', WallsExt1Notes='"               . clean_input($wallsext1notes) .
               "', DampProofCourse='"              . clean_input($dampproofcourse) .
               "', DPCNotes='"                     . clean_input($dpcnotes) .
               "' WHERE surveyId=" . $surveyid;
      #echo "<br>";   
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
   if (!empty($data)) {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
   }
  return $data;
}
#-----------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 32</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Walls Extension 1</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideWallsExt1InslatnQueries();
           ShowWallsExt1InslatnQueries();
           hideWallsExt1MoistPermCompd();
           showWallsExt1MoistPermCompd();
           disableIntExtComprsd();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideWallsExt1InslatnQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("wallsext1inslatndepthSel");
             obj2 = document.getElementById("wallsext1inslatndepthHdr");
             obj3 = document.getElementById("wallsext1inslatntypeSel");
             obj4 = document.getElementById("wallsext1inslatntypeHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
         }

         function ShowWallsExt1InslatnQueries() {
             if (!document.getElementById) return;
             mwiobj = document.getElementById("wallsext1insulationChk");
             if (mwiobj.checked == true){
                obj1 = document.getElementById("wallsext1inslatndepthSel");
                obj2 = document.getElementById("wallsext1inslatndepthHdr");
                obj3 = document.getElementById("wallsext1inslatntypeSel");
                obj4 = document.getElementById("wallsext1inslatntypeHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
             } else {
               hideWallsExt1InslatnQueries();
             }
         }         

         function hideWallsExt1MoistPermCompd() {
             if (!document.getElementById) return;
             we1pciesel = document.getElementById("ext1permcompdintextSel");
             we1pciehdr = document.getElementById("ext1permcompdintextHdr");
             we1pcsel = document.getElementById("wallsext1permbltycomprsdSel");
             we1pchdr = document.getElementById("wallsext1permbltycomprsdHdr");
             we1pciesel.style.display="none";
             we1pciehdr.style.display="none";
             we1pcsel.style.display="none";
             we1pchdr.style.display="none";
         }

         function showWallsExt1MoistPermCompd() {
             if (!document.getElementById) return;
             we1chkobj = document.getElementById("wallsext1permChk");
             if (we1chkobj.value == "Moisture open"){
                we1pciesel = document.getElementById("ext1permcompdintextSel");
                we1pciehdr = document.getElementById("ext1permcompdintextHdr");
                we1pcsel = document.getElementById("wallsext1permbltycomprsdSel");
                we1pchdr = document.getElementById("wallsext1permbltycomprsdHdr");
                we1pciesel.style.display="block";
                we1pciehdr.style.display="block";
                we1pcsel.style.display="block";
                we1pchdr.style.display="block";
             } else {
               hideWallsExt1MoistPermCompd();
             }
         }

         function disableIntExtComprsd() {
             if (!document.getElementById) return;
             we1pcobj = document.getElementById("wallsext1permbltycomprsdSel");
             we1pcExt = document.getElementById("ext1permcompdintextext");
             we1pcExt.disabled="";
             we1pcInt = document.getElementById("ext1permcompdintextint");
             we1pcInt.disabled="";
             if (we1pcobj.value == "Externally"){
                we1pcInt.disabled="True";
             } else if (we1pcobj.value == "Internally"){
                we1pcExt.disabled="True";
             } else if (we1pcobj.value == "Both"){
               we1pcExt.disabled="";
               we1pcInt.disabled="";
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
            <!---<th colspan="2">Sign Up Form</th> --->
            <tr>
               <th>Significance</th>
               <td>
                  <input type="checkbox" class="chk" name="wallsext1significance" value="<?php echo ($row['WallsExt1Significance']=='1' ? '1' : '0');?>" <?php echo ($row['WallsExt1Significance']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Moisture Permeability</th>
               <td>
                  <select id="wallsext1permChk" name="wallsext1permeability" onchange="showWallsExt1MoistPermCompd()" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Moisture open"   <?php echo $row['WallsExt1Permeability'] == "Moisture open" ? " selected" : ""; ?>  >Moisture open</option>
                     <option value="Moisture closed" <?php echo $row['WallsExt1Permeability'] == "Moisture closed" ? " selected" : ""; ?>>Moisture closed</option>
                     <option value="Unknown"         <?php echo $row['WallsExt1Permeability'] == "Unknown" ? " selected" : ""; ?>        >Unknown</option>
                  </select>
              </td>
            </tr>
            <tr>
               <th id="wallsext1permbltycomprsdHdr">Permeability Compromised</th>
               <td>
                  <select id="wallsext1permbltycomprsdSel" name="wallsext1permbltycomprsd" onchange="disableIntExtComprsd()" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Externally" <?php echo $row['WallsExt1PermbltyComprsd'] == "Externally" ? " selected" : ""; ?>>Externally</option>
                     <option value="Internally" <?php echo $row['WallsExt1PermbltyComprsd'] == "Internally" ? " selected" : ""; ?>>Internally</option>
                     <option value="Both"       <?php echo $row['WallsExt1PermbltyComprsd'] == "Both" ? " selected" : ""; ?>      >Both</option>
                  </select>
              </td>
            </tr>
            <tr>
               <th id="ext1permcompdintextHdr">Where Compromised?</th>
               <td>
                  <select id="ext1permcompdintextSel" name="ext1permcompdintext[]" multiple="multiple" size="4" > <!--multiple-->
                     <optgroup label="Externally" id="ext1permcompdintextext">
                        <option value="" disabled >Please choose...</option>
                        <option value="Cement render"  <?php echo (isset($ext1pcieAry) && in_array('Cement render', $ext1pcieAry))   ? " selected" : ""; ?>>Cement render</option>
                        <option value="Cement pointing"<?php echo (isset($ext1pcieAry) && in_array('Cement pointing', $ext1pcieAry)) ? " selected" : ""; ?>>Cement pointing</option>
                        <option value="Masonry paint"  <?php echo (isset($ext1pcieAry) && in_array('Masonry paint', $ext1pcieAry))   ? " selected" : ""; ?>>Masonry paint</option>
                     </optgroup>
                     <optgroup label="Internally" id="ext1permcompdintextint">
                        <option value="" disabled >Please choose...</option>
                        <option value="Cement plaster"<?php echo (isset($ext1pcieAry) && in_array('Cement plaster', $ext1pcieAry))   ? " selected" : ""; ?>>Cement plaster</option>
                        <option value="Gypsum plaster"<?php echo (isset($ext1pcieAry) && in_array('Gypsum plaster', $ext1pcieAry))   ? " selected" : ""; ?>>Gypsum plaster</option>
                        <option value="Tanked"        <?php echo (isset($ext1pcieAry) && in_array('Tanked', $ext1pcieAry))           ? " selected" : ""; ?>>Tanked</option>
                        <option value="Dry lining"    <?php echo (isset($ext1pcieAry) && in_array('Dry lining', $ext1pcieAry))       ? " selected" : ""; ?>>Dry lining</option>
                        <option value="Emulsion paint"<?php echo (isset($ext1pcieAry) && in_array('Emulsion paint', $ext1pcieAry))   ? " selected" : ""; ?>>Emulsion paint</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Wall Type</th>
               <td>
                  <select id="wallsext1type" name="wallsext1type" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Stone:granite or whinstone"   <?php echo $row['WallsExt1Type'] == "Stone:granite or whinstone" ? " selected" : ""; ?>  >Stone:granite or whinstone</option>
                     <option value="Stone:sandstone or limestone" <?php echo $row['WallsExt1Type'] == "Stone:sandstone or limestone" ? " selected" : ""; ?>>Stone:sandstone or limestone</option>
                     <option value="Solid brick"                  <?php echo $row['WallsExt1Type'] == "Solid brick" ? " selected" : ""; ?>                 >Solid brick</option>
                     <option value="Cob"                          <?php echo $row['WallsExt1Type'] == "Cob" ? " selected" : ""; ?>                         >Cob</option>
                     <option value="Cavity"                       <?php echo $row['WallsExt1Type'] == "Cavity" ? " selected" : ""; ?>                      >Cavity</option>
                     <option value="Timber frame"                 <?php echo $row['WallsExt1Type'] == "Timber frame" ? " selected" : ""; ?>                >Timber frame</option>
                     <option value="System build"                 <?php echo $row['WallsExt1Type'] == "System build" ? " selected" : ""; ?>                >System build</option>
                     <option value="Park home wall"               <?php echo $row['WallsExt1Type'] == "Park home wall" ? " selected" : ""; ?>              >Park home wall</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Thickness</th>
               <td><input type="text" name="wallsext1thickness" value="<?php echo $row['WallsExt1Thickness']?>"></td>
            </tr> 
            <tr>
               <th>Insulated</th>
               <td>
                  <input type="checkbox" id="wallsext1insulationChk" name="wallsext1insulated" onchange="ShowWallsExt1InslatnQueries()" value="<?php echo ($row['WallsExt1Insulated']=='1' ? '1' : '0');?>" <?php echo ($row['WallsExt1Insulated']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th id="wallsext1inslatntypeHdr">Insulation</th>
               <td>
                  <select id="wallsext1inslatntypeSel" name="wallsext1insulation" size="1" > <!--multiple-->
                     <option value="" disabled selected>Please choose...</option>
                     <option value="SWI EWI"                 <?php echo $row['WallsExt1Insulation'] == "SWI EWI" ? " selected" : ""; ?>                >SWI EWI</option>
                     <option value="SWI IWI"                 <?php echo $row['WallsExt1Insulation'] == "SWI IWI" ? " selected" : ""; ?>                >SWI IWI</option>
                     <option value="Filled cavity"           <?php echo $row['WallsExt1Insulation'] == "Filled cavity" ? " selected" : ""; ?>          >Filled cavity</option>
                     <option value="Filled cavity and IWI"   <?php echo $row['WallsExt1Insulation'] == "Filled cavity and IWI" ? " selected" : ""; ?>  >Filled cavity and IWI</option>
                     <option value="Filled cavity and EWI"   <?php echo $row['WallsExt1Insulation'] == "Filled cavity and EWI" ? " selected" : ""; ?>  >Filled cavity and EWI</option>
                     <option value="Unfilled cavity and IWI" <?php echo $row['WallsExt1Insulation'] == "Unfilled cavity and IWI" ? " selected" : ""; ?>>Unfilled cavity and IWI</option>
                     <option value="Unfilled cavity and EWI" <?php echo $row['WallsExt1Insulation'] == "Unfilled cavity and EWI" ? " selected" : ""; ?>>Unfilled cavity and EWI</option>
                     <option value="Dry Lining"              <?php echo $row['WallsExt1Insulation'] == "Dry Lining" ? " selected" : ""; ?>             >Dry Lining</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="wallsext1inslatndepthHdr">Insulation Thickness </th>
               <td>
                  <select id="wallsext1inslatndepthSel" name="wallsext1insulationthickness" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="50mm"   <?php echo $row['WallsExt1InsulationThickness'] == "50mm" ? " selected" : ""; ?>   >50mm</option>
                     <option value="100mm"  <?php echo $row['WallsExt1InsulationThickness'] == "100mm" ? " selected" : ""; ?>  >100mm</option>
                     <option value="150mm"  <?php echo $row['WallsExt1InsulationThickness'] == "150mm" ? " selected" : ""; ?>  >150mm</option>
                     <option value="200mm"  <?php echo $row['WallsExt1InsulationThickness'] == "200mm" ? " selected" : ""; ?>  >200mm</option>
                     <option value="Unknown"<?php echo $row['WallsExt1InsulationThickness'] == "Unknown" ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Insulation Type</th>
               <td>
                  <select id="wallsext1insulationtype" name="wallsext1insulationtype" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="EPS/XPS"            <?php echo $row['WallsExt1InsulationType'] == "EPS/XPS" ? " selected" : ""; ?>           >EPS/XPS</option>
                     <option value="Phenolic/PIR/PUR"   <?php echo $row['WallsExt1InsulationType'] == "Phenolic/PIR/PUR" ? " selected" : ""; ?>  >Phenolic/PIR/PUR</option>
                     <option value="Mineral wool"       <?php echo $row['WallsExt1InsulationType'] == "Mineral wool" ? " selected" : ""; ?>      >Mineral wool</option>
                     <option value="Natural fibres"     <?php echo $row['WallsExt1InsulationType'] == "Natural fibres" ? " selected" : ""; ?>    >Natural fibres</option>
                     <option value="Insulating plaster" <?php echo $row['WallsExt1InsulationType'] == "Insulating plaster" ? " selected" : ""; ?>>Insulating plaster</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Retrofit</th>
               <td>
                  <select id="wallsext1retrofit" name="wallsext1retrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['WallsExt1Retrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['WallsExt1Retrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['WallsExt1Retrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['WallsExt1Retrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Notes</th>
               <td>
                  <textarea name="wallsext1notes" rows="2" cols="30" ><?php echo $row['WallsExt1Notes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Damp Proof Course</th>
               <td>
                  <select id="dampproofcourse" name="dampproofcourse" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"                     <?php echo $row['DampProofCourse'] == "None" ? " selected" : ""; ?>                    >None</option>
                     <option value="Unknown"                  <?php echo $row['DampProofCourse'] == "Unknown" ? " selected" : ""; ?>                 >Unknown</option>
                     <option value="As Built"                 <?php echo $row['DampProofCourse'] == "As Built" ? " selected" : ""; ?>                >As Built</option>
                     <option value="Injected DPC"             <?php echo $row['DampProofCourse'] == "Injected DPC" ? " selected" : ""; ?>            >Injected DPC</option>
                     <option value="Multiple Injected DPCs"   <?php echo $row['DampProofCourse'] == "Multiple Injected DPCs" ? " selected" : ""; ?>  >Multiple Injected DPCs</option>
                     <option value="Physical Retrofitted DPC" <?php echo $row['DampProofCourse'] == "Physical Retrofitted DPC" ? " selected" : ""; ?>>Physical Retrofitted DPC</option>
                     <option value="Electro Osmosis"          <?php echo $row['DampProofCourse'] == "Electro Osmosis" ? " selected" : ""; ?>         >Electro Osmosis</option>
                     <option value="Dutch System"             <?php echo $row['DampProofCourse'] == "Dutch System" ? " selected" : ""; ?>            >Dutch System</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>DPC Notes</th>
               <td>
                  <textarea name="dpcnotes" rows="2" cols="30"><?php echo $row['DPCNotes']?></textarea>
               </td>
            </tr>               
         </table>
         <div class="pagefooter" >
            <a href="form30.php" title="Form 30">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form34.php" title="Form 34">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>

      <footer>
      </footer>  
   </body>
</html>
