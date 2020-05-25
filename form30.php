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

   $q = 'SELECT * FROM walls WHERE SurveyId = "'.$surveyid.'"' ;
   $r = mysqli_query( $dbc , $q ) ;

#if maybe needed here! check no of rows returned!
   if ($r) {
      $row01 = mysqli_fetch_array( $r , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $r ) ;

   } 

   $wallpermcompdAry = array();
   $wallpermcompdAry = explode(',',$row['WallPermCompd']);

   $instypeAry = array();
   $instypeAry = explode(',',$row['ExtWallInsType']);

   $insthcknssAry = array();
   $insthcknssAry = explode(',',$row['ExtWallInslatnThcknss']);

   $mainwalltypeAry   = array();
   $mainwalltypeAry = explode(',',$row['MainWallType']);

   echo "<br>";         

#---------------------------------------------------------------
   if (isset ($_POST['submit'])){
      
      header("location:form30.php");

/*
      print_r($_POST);
      echo "<br>";         
*/
      # set checkboxes if blank
      (!isset($_POST['extWallSignificant'])) ? $extWallSignificant = 0 : $extWallSignificant = 1;
      (!isset($_POST['extwallinsulated']))   ? $extwallinsulated   = 0 : $extwallinsulated   = 1;

      # set numerical textboxt if blank
      (!isset($_POST['extwallthickness'])) ? $extwallthickness = 0 : $extwallthickness = $_POST['extwallthickness'];
      
      # set dropdowns if blank
      (!isset($_POST['mainwalltype']))            ? $mainwalltype            = '' : $mainwalltype            = implode(',', $_POST['mainwalltype']);
      (!isset($_POST['extWallAge']))              ? $extWallAge              = '' : $extWallAge              = $_POST['extWallAge'];
      (!isset($_POST['extwallinsthickness']))     ? $extwallinsthickness     = '' : $extwallinsthickness     = implode(',', $_POST['extwallinsthickness']);
      (!isset($_POST['extwallinstype']))          ? $extwallinstype          = '' : $extwallinstype          = implode(',', $_POST['extwallinstype']);
      (!isset($_POST['extwallretrofit']))         ? $extwallretrofit         = '' : $extwallretrofit         = $_POST['extwallretrofit'];
      (!isset($_POST['dampproofcourse']))         ? $dampproofcourse         = '' : $dampproofcourse         = $_POST['dampproofcourse'];

      # set notes textarea if blank
      (!isset($_POST['extwallnotes']) )         ? $extwallnotes = ''         : $extwallnotes         = $_POST['extwallnotes'];
      (!isset($_POST['dampproofcoursenotes']) ) ? $dampproofcoursenotes = '' : $dampproofcoursenotes = $_POST['dampproofcoursenotes'];

      # these need to be set to cope with slightly different logic
      if (!isset($_POST['mainwallpermeability'])) {
         $mainwallpermeability    = '';
         $permeabilitycompromised = '';
         $wallcompd               = '';
      } else if ($_POST['mainwallpermeability'] == "Moisture open" ){
         $mainwallpermeability    = $_POST['mainwallpermeability'];
         $permeabilitycompromised = $_POST['permeabilitycompromised'];
         $wallcompd               = implode(',', $_POST['wallpermcompd']);         
      } else {
         $mainwallpermeability    = $_POST['mainwallpermeability'];
         $permeabilitycompromised = '';
         $wallcompd               = '';        
      }
      
      $sql = "UPDATE walls ".      
               " SET ExtWallSignificant='"    . clean_input($extWallSignificant) .
               "', MainWallPermeability='"    . clean_input($mainwallpermeability) .
               "', PermeabilityCompromised='" . clean_input($permeabilitycompromised) .
               "', WallPermCompd='"           . clean_input($wallcompd) .
               "', MainWallType='"            . clean_input($mainwalltype) .
               "', ExternalWallAge='"         . clean_input($extWallAge) .
               "', ExtWallThickness='"        . clean_input($extwallthickness) .
               "', ExtWallInsulated='"        . clean_input($extwallinsulated) . 
               "', ExtWallInsType='"          . clean_input($extwallinstype) . 
               "', ExtWallInslatnThcknss='"   . clean_input($extwallinsthickness) . 
               "', ExtWallRetrofit='"         . clean_input($extwallretrofit) .
               "', ExtWallNotes='"            . clean_input($extwallnotes) .
               "', DampProofCourse='"         . clean_input($dampproofcourse) .
               "', DampProofCourseNotes='"    . clean_input($dampproofcoursenotes) .
               "' WHERE surveyId=" . $surveyid;
/*
      echo "<br>";   
      print_r($_POST);
      echo "<br>";         
      print_r($sql);
      echo "<br>";  
*/      
      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
         sleep(1);
         #echo "<meta http-equiv='refresh' content='0'>";
         #header("location:form30_test.php"); // your current page
         
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

      <title>Form 30</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Main Walls</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>

      <style>
      </style>

      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           runFunctions(); 
           hideMainWallInslatnQueries();
           ShowMainWallInslatnQueries();
           hidePermeabilityCompromised();
           showPermeabilityCompromised();
           disableWallPermComprmsd();
           disableWallType();
         }
         
         function runFunctions(){
            disableWallType();
            showPermeabilityCompromised();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideMainWallInslatnQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("mainwallinslatndepthSel");
             obj2 = document.getElementById("mainwallinslatndepthHdr");
             obj3 = document.getElementById("mainwallinslatntypeSel");
             obj4 = document.getElementById("mainwallinslatntypeHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
         }

         function ShowMainWallInslatnQueries() {
             if (!document.getElementById) return;
             mwiobj = document.getElementById("mainwallinsulationChk");
             if (mwiobj.checked == true){
                obj1 = document.getElementById("mainwallinslatndepthSel");
                obj2 = document.getElementById("mainwallinslatndepthHdr");
                obj3 = document.getElementById("mainwallinslatntypeSel");
                obj4 = document.getElementById("mainwallinslatntypeHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
             } else {
               hideMainWallInslatnQueries();
             }
         }  

         function hidePermeabilityCompromised() {
             if (!document.getElementById) return;
                obj1th = document.getElementById("permcompdHdr");
                obj1td = document.getElementById("permeabilitycompromisedChk");
                obj1tt = document.getElementById("permcompdtt");
                obj1ti = document.getElementById("permcompdtt_i");
                obj2th = document.getElementById("wallpermcompdHdr");
                obj2td = document.getElementById("wallpermcompdId");
                obj1th.style.display="none";
                obj1td.style.display="none";
                obj1tt.style.display="none";
                obj1ti.style.display="none";
                obj2th.style.display="none";
                obj2td.style.display="none";
         }

         function showPermeabilityCompromised() {
             if (!document.getElementById) return;
             mwpcobj = document.getElementById("mainwallpermeabilityChk");
             if (mwpcobj.value == "Moisture open"){
                obj1th = document.getElementById("permcompdHdr");
                obj1td = document.getElementById("permeabilitycompromisedChk");
                obj1tt = document.getElementById("permcompdtt");
                obj1ti = document.getElementById("permcompdtt_i");
                obj2th = document.getElementById("wallpermcompdHdr");
                obj2td = document.getElementById("wallpermcompdId");
                obj1th.style.display="block";
                obj1td.style.display="block";
                obj1tt.style.display="block";
                obj1ti.style.display="block";
                obj2th.style.display="block";
                obj2td.style.display="block";
             } else {
               hidePermeabilityCompromised();
             }
         }  

         function disableWallPermComprmsd() {
             if (!document.getElementById) return;
             wpcobj = document.getElementById("permeabilitycompromisedChk");
             wpcExt = document.getElementById("wallpermcompdext");
             wpcExt.disabled="";
             wpcInt = document.getElementById("wallpermcompdint");
             wpcInt.disabled="";
             if (wpcobj.value == "Externally"){
                wpcInt.disabled="True";
             } else if (wpcobj.value == "Internally"){
                wpcExt.disabled="True";
             } else if (wpcobj.value == "Externally" && wpcobj.value == "Internally"){
                wpcInt.disabled="";
                wpcExt.disabled="";
             }
         }         

         function disableWallType() {
             if (!document.getElementById) return;
             mwpobj = document.getElementById("mainwallpermeabilityChk");
             mwtmo = document.getElementById("moistureopen");
             mwtmo.disabled="";
             mwtmc = document.getElementById("moistureclosed");
             mwtmc.disabled="";
             if (mwpobj.value == "Moisture open"){
                mwtmc.disabled="True";
             } else if (mwpobj.value == "Moisture closed"){
                mwtmo.disabled="True";
             } else if (mwpobj.value == "Unknown"){
                mwtmc.disabled="True";
                mwtmo.disabled="True";
             }
         }         
      </script>

   </head>

   <body>

      <!--Navigation bar   -->   
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar--> 

      <!-- Page content -->
      <div class="main" style="display:table;">
      <form method="post" action="">
         <table class="formten" style="border: 0">
            <tr>
               <th>Significant</th>
               <td>
                  <input type="checkbox"  name="extWallSignificant" value="<?php echo ($row['ExtWallSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['ExtWallSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="extWallSignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Are they significant? If so, ensure photographic evidence is taken.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Moisture Permeability</th>
               <td>
                  <select id="mainwallpermeabilityChk" name="mainwallpermeability" onchange="runFunctions()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Moisture open"  <?php echo $row['MainWallPermeability'] == "Moisture open"   ? " selected" : ""; ?>>Moisture open</option>
                     <option value="Moisture closed"<?php echo $row['MainWallPermeability'] == "Moisture closed" ? " selected" : ""; ?>>Moisture closed</option>
                     <option value="Unknown"        <?php echo $row['MainWallPermeability'] == "Unknown"         ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="mainwallpermeability_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Refer to UKCMB guidance and BSI documentation
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th id="permcompdHdr">Permeability Compromised</th>
               <td>
                  <select id="permeabilitycompromisedChk" name="permeabilitycompromised" multiple="multiple" onchange="disableWallPermComprmsd()" size="3" style="width: 200px;"> <!--multiple-->
                     <option value="" disabled >Please choose...</option>
                     <option value="Externally"<?php echo $row['PermeabilityCompromised'] == "Externally" ? " selected" : ""; ?>>Externally</option>
                     <option value="Internally"<?php echo $row['PermeabilityCompromised'] == "Internally" ? " selected" : ""; ?>>Internally</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="extpermcompd_tt"  >
                  <span id="permcompdtt" class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This identifies where any loss of breathability issues 
                  lie – externally, internally
                  </span>
                  <p id="permcompdtt_i"  style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th id="wallpermcompdHdr">Where Compromised?</th>
               <td>
                  <select id="wallpermcompdId" name="wallpermcompd[]" multiple="multiple" size="4" style="width: 200px;"> <!--multiple-->
                     <optgroup label="Externally" id="wallpermcompdext">
                        <option value="" disabled >Please choose...</option>
                        <option value="Cement render"  <?php echo (isset($wallpermcompdAry) && in_array('Cement render', $wallpermcompdAry))   ? " selected" : ""; ?>>Cement render</option>
                        <option value="Cement pointing"<?php echo (isset($wallpermcompdAry) && in_array('Cement pointing', $wallpermcompdAry)) ? " selected" : ""; ?>>Cement pointing</option>
                        <option value="Masonry paint"  <?php echo (isset($wallpermcompdAry) && in_array('Masonry paint', $wallpermcompdAry))   ? " selected" : ""; ?>>Masonry paint</option>
                     </optgroup>
                     <optgroup label="Internally" id="wallpermcompdint">
                        <option value="" disabled >Please choose...</option>
                        <option value="Cement plaster"<?php echo (isset($wallpermcompdAry) && in_array('Cement plaster', $wallpermcompdAry))   ? " selected" : ""; ?>>Cement plaster</option>
                        <option value="Gypsum plaster"<?php echo (isset($wallpermcompdAry) && in_array('Gypsum plaster', $wallpermcompdAry))   ? " selected" : ""; ?>>Gypsum plaster</option>
                        <option value="Tanked"        <?php echo (isset($wallpermcompdAry) && in_array('Tanked', $wallpermcompdAry))           ? " selected" : ""; ?>>Tanked</option>
                        <option value="Dry lining"    <?php echo (isset($wallpermcompdAry) && in_array('Dry lining', $wallpermcompdAry))       ? " selected" : ""; ?>>Dry lining</option>
                        <option value="Emulsion paint"<?php echo (isset($wallpermcompdAry) && in_array('Emulsion paint', $wallpermcompdAry))   ? " selected" : ""; ?>>Emulsion paint</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Main Wall Type</th>
               <td>
                  <select id="mainwalltypeSel" name="mainwalltype[]" multiple="multiple" size="3" style="width: 200px;">
                     <optgroup label="Moisture open" id="moistureopen">
                        <option value="" disabled >Please choose...</option>
                        <option value="Stone:granite or whinstone"  <?php echo (isset($mainwalltypeAry) && in_array('Stone:granite or whinstone', $mainwalltypeAry))   ? " selected" : ""; ?>>Stone:granite or whinstone</option>
                        <option value="Stone:sandstone or limestone"<?php echo (isset($mainwalltypeAry) && in_array('Stone:sandstone or limestone', $mainwalltypeAry)) ? " selected" : ""; ?>>Stone:sandstone or limestone</option>
                        <option value="Solid brick"                 <?php echo (isset($mainwalltypeAry) && in_array('Solid brick', $mainwalltypeAry))                  ? " selected" : ""; ?>>Solid brick</option>
                        <option value="Cob"                         <?php echo (isset($mainwalltypeAry) && in_array('Cob', $mainwalltypeAry))                          ? " selected" : ""; ?>>Cob</option>
                        <option value="Timber frame wattle & daub"  <?php echo (isset($mainwalltypeAry) && in_array('Timber frame wattle & daub', $mainwalltypeAry))   ? " selected" : ""; ?>>Timber frame wattle & daub</option>
                     </optgroup>
                     <optgroup label="Moisture closed" id="moistureclosed">
                        <option value="" disabled >Please choose...</option>
                        <option value="Cavity"        <?php echo (isset($mainwalltypeAry) && in_array('Cavity', $mainwalltypeAry))         ? " selected" : ""; ?>>Cavity</option>
                        <option value="Timber frame"  <?php echo (isset($mainwalltypeAry) && in_array('Timber frame', $mainwalltypeAry))   ? " selected" : ""; ?>>Timber frame</option>
                        <option value="System build"  <?php echo (isset($mainwalltypeAry) && in_array('System build', $mainwalltypeAry))   ? " selected" : ""; ?>>System build</option>
                        <option value="Park home wall"<?php echo (isset($mainwalltypeAry) && in_array('Park home wall', $mainwalltypeAry)) ? " selected" : ""; ?>>Park home wall</option>
                     </optgroup>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="mainwalltype_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  A basic understanding of material type gives an indication 
                  of what solutions might be available in order to improve 
                  thermal performance
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Thickness</th>
               <td><input type="text" name="extwallthickness" value="<?php echo $row['ExtWallThickness']?>"></td>
            </tr>
            <tr>
               <th>Insulated</th>
               <td>
                  <input type="checkbox" id="mainwallinsulationChk" name="extwallinsulated" onchange="ShowMainWallInslatnQueries()" value="<?php echo ($row['ExtWallInsulated']=='1' ? '1' : '0');?>" <?php echo ($row['ExtWallInsulated']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th id="mainwallinslatntypeHdr">Insulation Type</th>
               <td>
                  <select id="mainwallinslatntypeSel" name="extwallinstype[]" multiple="multiple" size="3" style="width: 200px;"> <!--multiple-->
                     <option value="" disabled >Please choose...</option>
                     <option value="SWI EWI"                <?php echo (isset($instypeAry) && in_array('SWI EWI', $instypeAry))                 ? " selected" : ""; ?>>SWI EWI</option>
                     <option value="SWI IWI"                <?php echo (isset($instypeAry) && in_array('SWI IWI', $instypeAry))                 ? " selected" : ""; ?>>SWI IWI</option>
                     <option value="Dry Lined Only"         <?php echo (isset($instypeAry) && in_array('Dry Lined Only', $instypeAry))          ? " selected" : ""; ?>>Dry Lining</option>
                     <option value="Filled cavity"          <?php echo (isset($instypeAry) && in_array('Filled cavity', $instypeAry))           ? " selected" : ""; ?>>Filled cavity</option>
                     <option value="Filled cavity and IWI"  <?php echo (isset($instypeAry) && in_array('Filled cavity and IWI', $instypeAry))   ? " selected" : ""; ?>>Filled cavity and IWI</option>
                     <option value="Filled cavity and EWI"  <?php echo (isset($instypeAry) && in_array('Filled cavity and EWI', $instypeAry))   ? " selected" : ""; ?>>Filled cavity and EWI</option>
                     <option value="Unfilled cavity and IWI"<?php echo (isset($instypeAry) && in_array('Unfilled cavity and IWI', $instypeAry)) ? " selected" : ""; ?>>Unfilled cavity and IWI</option>
                     <option value="Unfilled cavity and EWI"<?php echo (isset($instypeAry) && in_array('Unfilled cavity and EWI', $instypeAry)) ? " selected" : ""; ?>>Unfilled cavity and EWI</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th id="mainwallinslatndepthHdr">Insulation Thickness</th>
               <td>
                  <select id="mainwallinslatndepthSel" name="extwallinsthickness[]" multiple="multiple" size="3" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="50mm"    <?php echo (isset($insthcknssAry) && in_array('50mm', $insthcknssAry))    ? " selected" : ""; ?>>50mm</option>
                     <option value="100mm"   <?php echo (isset($insthcknssAry) && in_array('100mm', $insthcknssAry))   ? " selected" : ""; ?>>100mm</option>
                     <option value="150mm"   <?php echo (isset($insthcknssAry) && in_array('150mm', $insthcknssAry))   ? " selected" : ""; ?>>150mm</option>
                     <option value="200mm"   <?php echo (isset($insthcknssAry) && in_array('200mm', $insthcknssAry))   ? " selected" : ""; ?>>200mm</option>
                     <option value="Unknown" <?php echo (isset($insthcknssAry) && in_array('Unknown', $insthcknssAry)) ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Retrofit</th>
               <td>
                  <select id="extwallretrofit" name="extwallretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"          <?php echo $row['ExtWallRetrofit'] == "Red"           ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"        <?php echo $row['ExtWallRetrofit'] == "Amber"         ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"        <?php echo $row['ExtWallRetrofit'] == "Green"         ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected"<?php echo $row['ExtWallRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="extwallretrofit_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This gives the Assessor an opportunity to flag up any major 
                  issues that might affect the installation of EEM.
                  Red: indicates that there is a fundamental reason for not 
                     undertaking EEM or that there are severe complications.
                  Amber: More investigations are required before a final decision can be made
                  Green: There is no reason why EEM installations cannot be made 
                     immediately and / or that the EEM installation will eradicate 
                     any existing condition issues / concerns
                  Not inspected: If an element has not been inspected it is important 
                     to note this as it may have a fundament effect on any works.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Notes</th>
               <td>
                  <textarea name="extwallnotes" rows="2" cols="30" ><?php echo $row['ExtWallNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Damp Proof Course</th>
               <td>
                  <select id="dampproofcourse" name="dampproofcourse" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"                          <?php echo $row['DampProofCourse'] == "None"                           ? " selected" : ""; ?>>None</option>
                     <option value="Unknown"                       <?php echo $row['DampProofCourse'] == "Unknown"                        ? " selected" : ""; ?>>Unknown</option>
                     <option value="As Built"                      <?php echo $row['DampProofCourse'] == "As Built"                       ? " selected" : ""; ?>>As Built</option>
                     <option value="Injected DPC"                  <?php echo $row['DampProofCourse'] == "Injected DPC"                   ? " selected" : ""; ?>>Injected DPC</option>
                     <option value="Multiple Injected DPCs"        <?php echo $row['DampProofCourse'] == "Multiple Injected DPCs"         ? " selected" : ""; ?>>Multiple Injected DPCs</option>
                     <option value="Physical DPC Layer Retrofitted"<?php echo $row['DampProofCourse'] == "Physical DPC Layer Retrofitted" ? " selected" : ""; ?>>Physical DPC Layer Retrofitted</option>
                     <option value="Electro Osmosis"               <?php echo $row['DampProofCourse'] == "Electro Osmosis"                ? " selected" : ""; ?>>Electro Osmosis</option>
                     <option value="Dutch System"                  <?php echo $row['DampProofCourse'] == "Dutch System"                   ? " selected" : ""; ?>>Dutch System</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="dampProofCourse_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This helps to identify past levels of damp and the effectiveness
                  of any injected DPC, also highlights other sources of damp that 
                  might be affecting the building
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>DPC Notes</th>
               <td>
                  <textarea name="dampproofcoursenotes" rows="2" cols="30"><?php echo $row['DampProofCourseNotes']?></textarea>
               </td>
            </tr>
         </table>
         <div class="pagefooter" >
            <a href="form20.php" title="Form 20">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form32.php" title="Form 32">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>  
      </div> 
   <footer>
   </footer>  
   </body>
</html>
