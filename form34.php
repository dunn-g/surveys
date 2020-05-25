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

   $sql = 'SELECT * FROM `wall extension 2` WHERE SurveyId = "'.$surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 
   
   $ext2pcieAry = array();
   $ext2pcieAry = explode(',',$row['Ext2PermCompdIntExt']);
   echo "<br>";         

#-----------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form34.php");

      # set checkboxes if blank
      (!isset($_POST['wallext2significant'])) ? $wallext2significant = 0 : $wallext2significant = 1;
      (!isset($_POST['wallsext2insulated']))  ? $wallsext2insulated  = 0 : $wallsext2insulated  = 1;

      # set numerical textboxt if blank
      (!isset($_POST['wallext2thickness'])) ? $wallext2thickness = 0 : $wallext2thickness = $_POST['wallext2thickness'];
      
      # set dropdowns if blank
      (!isset($_POST['wallext2Type']))                ? $wallext2Type                = '' : $wallext2Type                = $_POST['wallext2Type'];
      (!isset($_POST['wallext2Age']))                 ? $wallext2Age                 = '' : $wallext2Age                 = $_POST['wallext2Age'];
      (!isset($_POST['wallext2Insulation']))          ? $wallext2Insulation          = '' : $wallext2Insulation          = $_POST['wallext2Insulation'];
      (!isset($_POST['wallext2insulationthickness'])) ? $wallext2insulationthickness = '' : $wallext2insulationthickness = $_POST['wallext2insulationthickness'];
      (!isset($_POST['wallext2InsulationType']))      ? $wallext2InsulationType      = '' : $wallext2InsulationType      = $_POST['wallext2InsulationType'];
      (!isset($_POST['wallext2Retrofit']))            ? $wallext2Retrofit            = '' : $wallext2Retrofit            = $_POST['wallext2Retrofit'];
      (!isset($_POST['dampproofcourse']))             ? $dampproofcourse             = '' : $dampproofcourse             = $_POST['dampproofcourse'];

      # set notes textarea if blank
      (!isset($_POST['wallext2Notes']) ) ? $wallext2Notes = '' : $wallext2Notes = $_POST['wallext2Notes'];
      (!isset($_POST['dpcnotes']) )      ? $dpcnotes      = '' : $dpcnotes      = $_POST['dpcnotes'];
      
      if (!isset($_POST['wallext2permeability'])) {
         $wallext2permeability    = '';
         $wallext2permbltycomprsd = '';
         $ext2permcompdintext     = '';
      } else if (($_POST['wallext2permeability'] == "Moisture closed")  || ($_POST['wallext2permeability'] == "Unknown" )){
         $wallext2permeability    = $_POST['wallext2permeability'];
         $wallext2permbltycomprsd = '';
         $ext2permcompdintext     = '';
      } else {
         $wallext2permeability    = $_POST['wallext2permeability'];
         $wallext2permbltycomprsd = $_POST['wallext2permbltycomprsd'];
         $ext2permcompdintext     = implode(',', $_POST['ext2permcompdintext']);
      } 

      $sql = "UPDATE `wall extension 2` ".      
               " SET WallExt2Significant="        . clean_input($wallext2significant) .
               ", WallExt2Permeability='"         . clean_input($wallext2permeability) .
               "', WallExt2PermbltyComprsd='"     . clean_input($wallext2permbltycomprsd) .
               "', Ext2PermCompdIntExt='"         . clean_input($ext2permcompdintext) .
               "', WallExt2Type='"                . clean_input($wallext2Type) .
               "', WallExt2Age='"                 . clean_input($wallext2Age) .
               "', WallExt2thickness='"           . clean_input($wallext2thickness) . 
               "', WallsExt2Insulated="           . clean_input($wallsext2insulated) . 
               ",  WallExt2Insulation='"          . clean_input($wallext2Insulation) . 
               "', WallExt2insulationthickness='" . clean_input($wallext2insulationthickness) .
               "', WallExt2InsulationType='"      . clean_input($wallext2InsulationType) .
               "', WallExt2Retrofit='"            . clean_input($wallext2Retrofit) .
               "', WallExt2Notes='"               . clean_input($wallext2Notes) .
               "', DampProofCourse='"             . clean_input($dampproofcourse) .
               "', DPCNotes='"                    . clean_input($dpcnotes) .
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      print_r($_POST);
      echo "<br>";         
      print_r($sql);
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
#--------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 34</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Walls Extension 2</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideWallsExt2InslatnQueries();
           ShowWallsExt2InslatnQueries();
           hideWallsExt2MoistPermCompd();
           showWallsExt2MoistPermCompd();
           disableIntExtComprsd();
         }
        
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideWallsExt2InslatnQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("wallsext2inslatndepthSel");
             obj2 = document.getElementById("wallsext2inslatndepthHdr");
             obj3 = document.getElementById("wallsext2inslatntypeSel");
             obj4 = document.getElementById("wallsext2inslatntypeHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
         }

         function ShowWallsExt2InslatnQueries() {
             if (!document.getElementById) return;
             mwiobj = document.getElementById("wallsext2insulationChk");
             if (mwiobj.checked == true){
                obj1 = document.getElementById("wallsext2inslatndepthSel");
                obj2 = document.getElementById("wallsext2inslatndepthHdr");
                obj3 = document.getElementById("wallsext2inslatntypeSel");
                obj4 = document.getElementById("wallsext2inslatntypeHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
             } else {
               hideWallsExt2InslatnQueries();
             }
         }         

         function hideWallsExt2MoistPermCompd() {
             if (!document.getElementById) return;
             we2pciesel = document.getElementById("ext2permcompdintextSel");
             we2pciehdr = document.getElementById("ext2permcompdintextHdr");
             we2pcsel   = document.getElementById("wallsext2permbltycomprsdSel");
             we2pchdr   = document.getElementById("wallsext2permbltycomprsdHdr");
             we2pciesel.style.display="none";
             we2pciehdr.style.display="none";
             we2pcsel.style.display="none";
             we2pchdr.style.display="none";
         }

         function showWallsExt2MoistPermCompd() {
             if (!document.getElementById) return;
             we2chkobj = document.getElementById("wallsext2permChk");
             if (we2chkobj.value == "Moisture open"){
                we2pciesel = document.getElementById("ext2permcompdintextSel");
                we2pciehdr = document.getElementById("ext2permcompdintextHdr");
                we2pcsel   = document.getElementById("wallsext2permbltycomprsdSel");
                we2pchdr   = document.getElementById("wallsext2permbltycomprsdHdr");
                we2pciesel.style.display="block";
                we2pciehdr.style.display="block";
                we2pcsel.style.display="block";
                we2pchdr.style.display="block";
             } else {
               hideWallsExt2MoistPermCompd();
             }
         }

         function disableIntExtComprsd() {
             if (!document.getElementById) return;
             we2pcobj = document.getElementById("wallsext2permbltycomprsdSel");
             we2pcExt = document.getElementById("ext2permcompdintextext");
             we2pcExt.disabled="";
             we2pcInt = document.getElementById("ext2permcompdintextint");
             we2pcInt.disabled="";
             if (we2pcobj.value == "Externally"){
                we2pcInt.disabled="True";
             } else if (we2pcobj.value == "Internally"){
                we2pcExt.disabled="True";
             } else if (we2pcobj.value == "Both"){
               we2pcExt.disabled="";
               we2pcInt.disabled="";
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
               <th>Significant</th>
               <td>
                  <input type="checkbox"  name="wallext2significant" value="<?php echo ($row['WallExt2Significant']=='1' ? '1' : '0');?>" <?php echo ($row['WallExt2Significant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Moisture Permeability</th>
               <td>
                  <select id="wallsext2permChk" name="wallext2permeability" onchange="showWallsExt2MoistPermCompd()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Moisture open"   <?php echo $row['WallExt2Permeability'] == "Moisture open"   ? " selected" : ""; ?>>Moisture open</option>
                     <option value="Moisture closed" <?php echo $row['WallExt2Permeability'] == "Moisture closed" ? " selected" : ""; ?>>Moisture closed</option>
                     <option value="Unknown"         <?php echo $row['WallExt2Permeability'] == "Unknown"         ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="wallext2permblty_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Moisture open and closed refer to the basic moisture dynamics of the wall.
                  solid (brick and stone) walls are generally regarded as moisture open,
                  whilst cavities are regarded as being moisture closed.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th id="wallsext2permbltycomprsdHdr">Moisture Permeability Compromised</th>
               <td>
                  <select id="wallsext2permbltycomprsdSel" name="wallext2permbltycomprsd" onchange="disableIntExtComprsd()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Externally" <?php echo $row['WallExt2PermbltyComprsd'] == "Externally" ? " selected" : ""; ?>>Externally</option>
                     <option value="Internally" <?php echo $row['WallExt2PermbltyComprsd'] == "Internally" ? " selected" : ""; ?>>Internally</option>
                     <option value="Both"       <?php echo $row['WallExt2PermbltyComprsd'] == "Both"       ? " selected" : ""; ?>>Both</option>
                  </select>
              </td>
            </tr>
            <tr>
               <th id="ext2permcompdintextHdr">Where Compromised?</th>
               <td>
                  <select id="ext2permcompdintextSel" name="ext2permcompdintext[]" multiple="multiple" size="4" style="width: 200px;"> <!--multiple-->
                     <optgroup label="Externally" id="ext2permcompdintextext">
                       <option value="" disabled >Please choose...</option>
                       <option value="Cement render"  <?php echo (isset($ext2pcieAry) && in_array('Cement render', $ext2pcieAry))   ? " selected" : ""; ?>>Cement render</option>
                       <option value="Cement pointing"<?php echo (isset($ext2pcieAry) && in_array('Cement pointing', $ext2pcieAry)) ? " selected" : ""; ?>>Cement pointing</option>
                       <option value="Masonry paint"  <?php echo (isset($ext2pcieAry) && in_array('Masonry paint', $ext2pcieAry))   ? " selected" : ""; ?>>Masonry paint</option>
                    </optgroup>
                    <optgroup label="Internally" id="ext2permcompdintextint">
                       <option value="" disabled >Please choose...</option>
                       <option value="Cement plaster" <?php echo (isset($ext2pcieAry) && in_array('Cement plaster', $ext2pcieAry))  ? " selected" : ""; ?>>Cement plaster</option>
                       <option value="Gypsum plaster" <?php echo (isset($ext2pcieAry) && in_array('Gypsum plaster', $ext2pcieAry))  ? " selected" : ""; ?>>Gypsum plaster</option>
                       <option value="Tanked"         <?php echo (isset($ext2pcieAry) && in_array('Tanked', $ext2pcieAry))          ? " selected" : ""; ?>>Tanked</option>
                       <option value="Dry lining"     <?php echo (isset($ext2pcieAry) && in_array('Dry lining', $ext2pcieAry))      ? " selected" : ""; ?>>Dry lining</option>
                       <option value="Emulsion paint" <?php echo (isset($ext2pcieAry) && in_array('Emulsion paint', $ext2pcieAry))  ? " selected" : ""; ?>>Emulsion paint</option>
                    </optgroup>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Type</th>
               <td>
                  <select id="wallext2Type" name="wallext2Type" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Stone:granite or whinstone" <?php echo $row['WallExt2Type'] == "Stone:granite or whinstone" ? " selected" : ""; ?>>Stone:granite or whinstone</option>
                     <option value="Solid brick"                <?php echo $row['WallExt2Type'] == "Solid brick"                ? " selected" : ""; ?>>Solid brick</option>
                     <option value="Cob"                        <?php echo $row['WallExt2Type'] == "Cob"                        ? " selected" : ""; ?>>Cob</option>
                     <option value="Cavity"                     <?php echo $row['WallExt2Type'] == "Cavity"                     ? " selected" : ""; ?>>Cavity</option>
                     <option value="Timber frame"               <?php echo $row['WallExt2Type'] == "Timber frame"               ? " selected" : ""; ?>>Timber frame</option>
                     <option value="System build"               <?php echo $row['WallExt2Type'] == "System build"               ? " selected" : ""; ?>>System build</option>
                     <option value="Park home wall"             <?php echo $row['WallExt2Type'] == "Park home wall"             ? " selected" : ""; ?>>Park home wall</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Wall Ext 2 Age</th>
               <td>
                  <select id="wallext2Age" name="wallext2Age" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Before 1966"  <?php echo $row['WallExt2Age'] == "Before 1966"  ? " selected" : ""; ?>>Before 1966</option>
                     <option value="1967-1975"    <?php echo $row['WallExt2Age'] == "1967-1975"    ? " selected" : ""; ?>>1967-1975</option>
                     <option value="1976-1982"    <?php echo $row['WallExt2Age'] == "1976-1982"    ? " selected" : ""; ?>>1976-1982</option>
                     <option value="1983-1990"    <?php echo $row['WallExt2Age'] == "1983-1990"    ? " selected" : ""; ?>>1983-1990</option>
                     <option value="1996-2002"    <?php echo $row['WallExt2Age'] == "1996-2002"    ? " selected" : ""; ?>>1996-2002</option>
                     <option value="2003-2006"    <?php echo $row['WallExt2Age'] == "2003-2006"    ? " selected" : ""; ?>>2003-2006</option>
                     <option value="2007-2011"    <?php echo $row['WallExt2Age'] == "2007-2011"    ? " selected" : ""; ?>>2007-2011</option>
                     <option value="2012 onwards" <?php echo $row['WallExt2Age'] == "2012 onwards" ? " selected" : ""; ?>>2012 onwards</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Thickness</th>
               <td>
                  <input type="text" name="wallext2thickness" value="<?php echo $row['WallExt2thickness']?>">
               </td>
            </tr> 
            <tr>
               <th>Insulated</th>
               <td>
                  <input type="checkbox" id="wallsext2insulationChk" name="wallsext2insulated" onchange="ShowWallsExt2InslatnQueries()" value="<?php echo ($row['WallsExt2Insulated']=='1' ? '1' : '0');?>" <?php echo ($row['WallsExt2Insulated']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr> 
               <th id="wallsext2inslatntypeHdr">Insulation</th>
               <td>
                  <select id="wallsext2inslatntypeSel" name="wallext2Insulation" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="SWI EWI"                 <?php echo $row['WallExt2Insulation'] == "EWI" ? " selected" : ""; ?>>EWI</option>
                     <option value="SWI IWI"                 <?php echo $row['WallExt2Insulation'] == "IWI" ? " selected" : ""; ?>>IWI</option>
                     <option value="Filled cavity"           <?php echo $row['WallExt2Insulation'] == "Filled cavity" ? " selected" : ""; ?>>Filled cavity</option>
                     <option value="Filled cavity and IWI"   <?php echo $row['WallExt2Insulation'] == "Filled cavity and IWI" ? " selected" : ""; ?>>Filled cavity and IWI</option>
                     <option value="Filled cavity and EWI"   <?php echo $row['WallExt2Insulation'] == "Filled cavity and EWI" ? " selected" : ""; ?>>Filled cavity and EWI</option>
                     <option value="Unfilled cavity and IWI" <?php echo $row['WallExt2Insulation'] == "Unfilled cavity and IWI" ? " selected" : ""; ?>>Unfilled cavity and IWI</option>
                     <option value="Unfilled cavity and EWI" <?php echo $row['WallExt2Insulation'] == "Unfilled cavity and EWI" ? " selected" : ""; ?>>Unfilled cavity and EWI</option>
                     <option value="As built"                <?php echo $row['WallExt2Insulation'] == "As built" ? " selected" : ""; ?>>As built</option>
                     <option value="Dry Lining"              <?php echo $row['WallExt2Insulation'] == "Dry Lining" ? " selected" : ""; ?>>Dry Lining</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="wallsext2inslatndepthHdr">Insulation Thickness</th>
               <td>
                  <select id="wallsext2inslatndepthSel" name="wallext2insulationthickness" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="50mm"    <?php echo $row['WallExt2insulationthickness'] == "50mm" ? " selected" : ""; ?>>50mm</option>
                     <option value="100mm"   <?php echo $row['WallExt2insulationthickness'] == "100mm" ? " selected" : ""; ?>>100mm</option>
                     <option value="150mm"   <?php echo $row['WallExt2insulationthickness'] == "150mm" ? " selected" : ""; ?>>150mm</option>
                     <option value="200mm"   <?php echo $row['WallExt2insulationthickness'] == "200mm" ? " selected" : ""; ?>>200mm</option>
                     <option value="Unknown" <?php echo $row['WallExt2insulationthickness'] == "Unknown" ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Insulation Type</th>
               <td>
                  <select id="wallext2InsulationType" name="wallext2InsulationType" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="EPS/XPS"            <?php echo $row['WallExt2InsulationType'] == "EPS/XPS" ? " selected" : ""; ?>>EPS/XPS</option>
                     <option value="Phenolic/PIR/PUR"   <?php echo $row['WallExt2InsulationType'] == "Phenolic/PIR/PUR" ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                     <option value="Mineral wool"       <?php echo $row['WallExt2InsulationType'] == "Mineral wool" ? " selected" : ""; ?>>Mineral wool</option>
                     <option value="Natural fibres"     <?php echo $row['WallExt2InsulationType'] == "Natural fibres" ? " selected" : ""; ?>>Natural fibres</option>
                     <option value="Insulating plaster" <?php echo $row['WallExt2InsulationType'] == "Insulating plaster" ? " selected" : ""; ?>>Insulating plaster</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Retrofit</th>
               <td>
                  <select id="wallext2Retrofit" name="wallext2Retrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['WallExt2Retrofit'] == "Red" ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['WallExt2Retrofit'] == "Amber" ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['WallExt2Retrofit'] == "Green" ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['WallExt2Retrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Notes</th>
               <td>
                  <textarea name="wallext2Notes" rows="2" cols="30" ><?php echo $row['WallExt2Notes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Damp Proof Course</th>
               <td>
                  <select id="dampproofcourse" name="dampproofcourse" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="As Built"                 <?php echo $row['DampProofCourse'] == "As Built" ? " selected" : ""; ?>>As Built</option>
                     <option value="Injected DPC"             <?php echo $row['DampProofCourse'] == "Injected DPC" ? " selected" : ""; ?>>Injected DPC</option>
                     <option value="Multiple Injected DPCs"   <?php echo $row['DampProofCourse'] == "Multiple Injected DPCs" ? " selected" : ""; ?>>Multiple Injected DPCs</option>
                     <option value="Physical DPC layer Retrofitted" <?php echo $row['DampProofCourse'] == "Physical DPC layer Retrofitted" ? " selected" : ""; ?>>Physical DPC layer Retrofitted</option>
                     <option value="Electro Osmosis"          <?php echo $row['DampProofCourse'] == "Electro Osmosis" ? " selected" : ""; ?>>Electro Osmosis</option>
                     <option value="Dutch System"             <?php echo $row['DampProofCourse'] == "Dutch System" ? " selected" : ""; ?>>Dutch System</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Damp Proof Course Notes</th>
               <td>
                  <textarea name="dpcnotes" rows="2" cols="30"><?php echo $row['DPCNotes']?></textarea>
               </td>
            </tr>               
         </table>

         <div class="pagefooter" >
            <a href="form32.php" title="Form 32">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form40.php" title="Form 40">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form> 
      </div>
   <footer>
   </footer>  

   </body>
</html>
