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

  $q = 'SELECT * FROM roofchimneygutter WHERE SurveyId = "'.$surveyid.'"' ;
  $rslt = mysqli_query( $dbc , $q ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 
      
      mysqli_free_result( $rslt ) ;

   } 
   $rwgCondAry = array();
   $rwgCondAry = explode(',',$row['RWGCondition']);
   echo "<br>";         

#-----------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form20.php");

      #print_r($_POST);
      #echo "<br>";  

      # set checkboxes if blank
      (!isset($_POST['rwsignificant']))      ? $rwsignificant  = 0     : $rwsignificant  = 1;
      (!isset($_POST['roofsignificant']))    ? $roofsignificant  = 0   : $roofsignificant  = 1;
      (!isset($_POST['roofvented']))         ? $roofvented = 0         : $roofvented = 1;
      (!isset($_POST['roof2vents']))         ? $roof2vents = 0         : $roof2vents = 1;
      (!isset($_POST['roof3vents']))         ? $roof3vents = 0         : $roof3vents = 1;
      (!isset($_POST['chimneysignificant'])) ? $chimneysignificant = 0 : $chimneysignificant = 1;
      
      # set dropdowns if blank
      (!isset($_POST['rwgcondition']))          ? $rwgcond        = ''        : $rwgcond               = implode(',', $_POST['rwgcondition']);
      (!isset($_POST['rwgoodsretrofit']))       ? $rwgoodsretrofit = ''       : $rwgoodsretrofit       = $_POST['rwgoodsretrofit'];
      (!isset($_POST['mainrooftype']))          ? $mainrooftype = ''          : $mainrooftype          = $_POST['mainrooftype'];
      (!isset($_POST['mainroofcovering']))      ? $mainroofcovering = ''      : $mainroofcovering      = $_POST['mainroofcovering'];
      (!isset($_POST['roofeavevergedetail']))   ? $roofeavevergedetail = ''   : $roofeavevergedetail   = $_POST['roofeavevergedetail'];
      (!isset($_POST['roofretrofit']))          ? $roofretrofit = ''          : $roofretrofit          = $_POST['roofretrofit'];
      (!isset($_POST['roof2type']))             ? $roof2type = ''             : $roof2type             = $_POST['roof2type'];
      (!isset($_POST['roof2covering']))         ? $roof2covering = ''         : $roof2covering         = $_POST['roof2covering'];
      (!isset($_POST['roof2eavesvergedetail'])) ? $roof2eavesvergedetail = '' : $roof2eavesvergedetail = $_POST['roof2eavesvergedetail'];
      (!isset($_POST['roof2retrofit']))         ? $roof2retrofit = ''         : $roof2retrofit         = $_POST['roof2retrofit'];
      (!isset($_POST['roof3type']))             ? $roof3type = ''             : $roof3type             = $_POST['roof3type'];
      (!isset($_POST['roof3covering']))         ? $roof3covering = ''         : $roof3covering         = $_POST['roof3covering'];
      (!isset($_POST['roof3eavesvergedetail'])) ? $roof3eavesvergedetail = '' : $roof3eavesvergedetail = $_POST['roof3eavesvergedetail'];
      (!isset($_POST['roof3retrofit']))         ? $roof3retrofit = ''         : $roof3retrofit         = $_POST['roof3retrofit'];
      (!isset($_POST['chimneyretrofit']))       ? $chimneyretrofit = ''       : $chimneyretrofit       = $_POST['chimneyretrofit'];
      (!isset($_POST['chimneyvent']))           ? $chimneyvent = ''           : $chimneyvent           = $_POST['chimneyvent'];

      # set notes if blank
      (!isset($_POST['rwnotes']) )      ? $rwnotes = ''      : $rwnotes      = $_POST['rwnotes'];
      (!isset($_POST['roofnotes']) )    ? $roofnotes = ''    : $roofnotes    = $_POST['roofnotes'];
      (!isset($_POST['chimneynotes']) ) ? $chimneynotes = '' : $chimneynotes = $_POST['chimneynotes'];
         
      # these need to be set to cope with slightly different logic
      if (!isset($_POST['anyundflrvents'])) {
         $anyundflrvents = 0;
         $underfloorvents = '';
      } else {
         $anyundflrvents = 1;
         $underfloorvents = $_POST['underfloorvents'];
      } 

      $sql = "UPDATE roofchimneygutter ".      
               " SET RainwaterSignificant="  . clean_input($rwsignificant) .
               ",  RWGCondition='"           . clean_input($rwgcond) .
               "', RainwaterGoodsRetrofit='" . clean_input($rwgoodsretrofit) .
               "', RainwaterNotes='"         . clean_input($rwnotes) .
               "', RoofSignificant="         . clean_input($roofsignificant) .
               ",  MainRoofType='"           . clean_input($mainrooftype) . 
               "', MainRoofCovering='"       . clean_input($mainroofcovering) . 
               "', RoofEaveVergedetail='"    . clean_input($roofeavevergedetail) . 
               "', RoofVented="              . clean_input($roofvented) .
               ",  RoofRetrofit='"           . clean_input($roofretrofit) .
               "', Roof2Type='"              . clean_input($roof2type) .
               "', Roof2Covering='"          . clean_input($roof2covering) .
               "', Roof2EavesVergeDetail='"  . clean_input($roof2eavesvergedetail) .
               "', Roof2Vents="              . clean_input($roof2vents) .
               ",  Roof2Retrofit='"          . clean_input($roof2retrofit) .
               "', Roof3Type='"              . clean_input($roof3type) .
               "', Roof3Covering='"          . clean_input($roof3covering) .
               "', Roof3EavesVergeDetail='"  . clean_input($roof3eavesvergedetail) .
               "', Roof3Vents="              . clean_input($roof3vents) .
               ",  Roof3Retrofit='"          . clean_input($roof3retrofit) .
               "', RoofNotes='"              . clean_input($roofnotes) .
               "', ChimneySignificant="      . clean_input($chimneysignificant) .
               ",  ChimneyRetrofit='"        . clean_input($chimneyretrofit) .
               "', ChimneyVent='"            . clean_input($chimneyvent) .
               "', ChimneyNotes='"           . clean_input($chimneynotes) .
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      #print_r($_POST);
      #echo "<br>";         
      #print_r($sql);
      #echo "<br>";  
#
#     think about adding flush table 
#
      
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

#-----------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">

      <title>Form 20</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Guttering, Roof and Chimney</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>

      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
            disableMainRoofCoveringType();
            disableRoof2CoveringType();
            disableRoof3CoveringType();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
        
         function disableMainRoofCoveringType() {
             if (!document.getElementById) return;
             mrcobj = document.getElementById("mainrooftypeChk");
             mrtPtch = document.getElementById("mainroofcoverPitched");
             mrtPtch.disabled="";
             mrtFlat = document.getElementById("mainroofcoverFlat");
             mrtFlat.disabled="";
             if (mrcobj.value == "Pitched"){
                mrtFlat.disabled="True";
             } else if (mrcobj.value == "Flat"){
                mrtPtch.disabled="True";
             }
         }         

         function disableRoof2CoveringType() {
             if (!document.getElementById) return;
             r2tobj = document.getElementById("roof2typeChk");
             r2tPtch = document.getElementById("roof2coverPitched");
             r2tPtch.disabled="";
             r2tFlat = document.getElementById("roof2coverFlat");
             r2tFlat.disabled="";
             if (r2tobj.value == "Pitched"){
                r2tFlat.disabled="True";
             } else if (r2tobj.value == "Flat"){
                r2tPtch.disabled="True";
             }
         }         

         function disableRoof3CoveringType() {
             if (!document.getElementById) return;
             r3tobj = document.getElementById("roof3typeChk");
             r3tPtch = document.getElementById("roof3coverPitched");
             r3tPtch.disabled="";
             r3tFlat = document.getElementById("roof3coverFlat");
             r3tFlat.disabled="";
             if (r3tobj.value == "Pitched"){
                r3tFlat.disabled="True";
             } else if (r3tobj.value == "Flat"){
                r3tPtch.disabled="True";
             }
         }         
         
      </script>

   </head>

   <body>
      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->

      <!-- Page content -->
      <div class="main" style="display:table;">
      <form method="post" action="">
         <table class="formten" style="border: 0">
            <tr>
               <th>Rainwater Significant</th>
               <td>
                  <input type="checkbox"  name="rwsignificant" value="<?php echo ($row['RainwaterSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['RainwaterSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Rainwater Condition</th>
               <td>
                  <select id="rwgcondition" name="rwgcondition[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Good"                      <?php echo (isset($rwgCondAry) && in_array('Good', $rwgCondAry))                      ? " selected" : "" ; ?>>Good</option>
                     <option value="Blocked/Broken"            <?php echo (isset($rwgCondAry) && in_array('Blocked/Broken', $rwgCondAry))            ? " selected" : "" ; ?>>Blocked/Broken</option>
                     <option value="Insufficient size"         <?php echo (isset($rwgCondAry) && in_array('Insufficient size', $rwgCondAry))         ? " selected" : "" ; ?>>Insufficient size</option>
                     <option value="Eaves guards/felt missing" <?php echo (isset($rwgCondAry) && in_array('Eaves guards/felt missing', $rwgCondAry)) ? " selected" : "" ; ?>>Eaves guards/felt missing</option>
                     <option value="Staining evident"          <?php echo (isset($rwgCondAry) && in_array('Staining evident', $rwgCondAry))        ? " selected" : "" ; ?>>Staining evident</option>
                     <option value="Fascia boards poor"        <?php echo (isset($rwgCondAry) && in_array('Fascia boards poor', $rwgCondAry))          ? " selected" : "" ; ?>>Fascia boards poor</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="rwgcndtn_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To indicate potential source of damp issues remembering that 
                  dry spells might distort the issues associated with poorly 
                  operating drainage systems.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Rainwater Goods Retrofit</th>
               <td>
                  <select id="rwgoodsretrofit" name="rwgoodsretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['RainwaterGoodsRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['RainwaterGoodsRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['RainwaterGoodsRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['RainwaterGoodsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Rainwater Notes</th>
               <td>
                  <textarea name="rwnotes" rows="2" cols="30"><?php echo $row['RainwaterNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Chimney Significant</th>
               <td>
                  <input type="checkbox"  name="chimneysignificant" value="<?php echo ($row['ChimneySignificant']=='1' ? '1' : '0');?>" <?php echo ($row['ChimneySignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr> 
            <tr>
               <th>Chimney Vent</th>
               <td>
                  <select id="chimneyVent" name="chimneyvent" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Vented and Weatherproofed" <?php echo $row['ChimneyVent'] == "Vented and Weatherproofed" ? " selected" : ""; ?>>Vented and Weatherproofed</option>
                     <option value="Vented only"             <?php echo $row['ChimneyVent'] == "Vented only" ? " selected" : ""; ?>>Vented only</option>
                     <option value="Not vented (capped)"     <?php echo $row['ChimneyVent'] == "Not vented (capped)" ? " selected" : ""; ?>>Not vented (capped)</option>
                     <option value="Unknown"                 <?php echo $row['ChimneyVent'] == "Unknown" ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="rwgcndtn_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To identify if the chimneys are providing a ventilation route 
                  but also are at risk of water penetration.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Chimney Retrofit</th>
               <td>
                  <select id="chimneyretrofit" name="chimneyretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"                    <?php echo $row['ChimneyRetrofit'] == "None" ? " selected" : ""; ?>                   >None</option>
                     <option value="Good condition"          <?php echo $row['ChimneyRetrofit'] == "Good condition" ? " selected" : ""; ?>         >Good condition</option>
                     <option value="Poor condition (repair)" <?php echo $row['ChimneyRetrofit'] == "Poor condition (repair)" ? " selected" : ""; ?>>Poor condition (repair)</option>
                     <option value="Poor condition (remove)" <?php echo $row['ChimneyRetrofit'] == "Poor condition (remove)" ? " selected" : ""; ?>>Poor condition (remove)</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="chmnyretrofit_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Slightly different as not part of an EEM retrofit, but condition 
                  might have an impact.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Chimney Notes</th>
               <td>
                  <textarea name="chimneynotes"  rows="2" cols="30"><?php echo $row['ChimneyNotes']?></textarea>
               </td>
            </tr>               
            <tr>
               <th>Roof Significant</th>
               <td>
                  <input type="checkbox"  name="roofsignificant" value="<?php echo ($row['RoofSignificant']==1 ? 1 : 0);?>" <?php echo ($row['RoofSignificant']==1 ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Main Roof Type</th>
               <td>
                  <select id="mainrooftypeChk" name="mainrooftype" onchange="disableMainRoofCoveringType()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Pitched" <?php echo $row['MainRoofType'] == "Pitched" ? " selected" : ""; ?>>Pitched</option>
                     <option value="Flat"    <?php echo $row['MainRoofType'] == "Flat" ? " selected" : ""; ?>   >Flat</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Main Roof Covering</th>
               <td>
                  <select id="mainroofcoveringSel" name="mainroofcovering" size="1" style="width: 200px;">
                     <optgroup label="Pitched" id="mainroofcoverPitched" >
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Tiles"    <?php echo (($row['MainRoofCovering'] == "Tiles")    and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Tiles</option>
                        <option value="Slates"   <?php echo (($row['MainRoofCovering'] == "Slates")   and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Slates</option>
                        <option value="Metal"    <?php echo (($row['MainRoofCovering'] == "Metal")    and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Metal</option>
                        <option value="Thatch"   <?php echo (($row['MainRoofCovering'] == "Thatch")   and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Thatch</option>
                        <option value="Shingles" <?php echo (($row['MainRoofCovering'] == "Shingles") and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Shingles</option>
                        <option value="Asbestos sheet/tiles/slates" <?php echo (($row['MainRoofCovering'] == "Asbestos sheet/tiles/slates") and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Asbestos sheet/tiles/slates</option>
                        <option value="Stone"    <?php echo (($row['MainRoofCovering'] == "Stone")    and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Stone</option>
                        <option value="Other"    <?php echo (($row['MainRoofCovering'] == "Other")    and ($row['MainRoofType'] == "Pitched")) ? " selected" : ""; ?>>Other</option>
                     </optgroup>
                     <optgroup label="Flat" id="mainroofcoverFlat">
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Felt"        <?php echo (($row['MainRoofCovering'] == "Felt")        and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Felt</option>
                        <option value="Glass Fibre" <?php echo (($row['MainRoofCovering'] == "Glass Fibre") and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Glass Fibre</option>
                        <option value="Metal"       <?php echo (($row['MainRoofCovering'] == "Metal")       and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Metal</option>
                        <option value="Asphalt"     <?php echo (($row['MainRoofCovering'] == "Asphalt")     and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Asphalt</option>
                        <option value="EPDM"        <?php echo (($row['MainRoofCovering'] == "EPDM")        and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>EPDM</option>
                        <option value="Single Ply Membrane" <?php echo (($row['MainRoofCovering'] == "Single Ply Membrane") and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Single Ply Membrane</option>
                        <option value="Other"       <?php echo (($row['MainRoofCovering'] == "Other")       and ($row['MainRoofType'] == "Flat")) ? " selected" : ""; ?>>Other</option>
                     </optgroup>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Main Eave & Verge Detail</th>
               <td>
                  <select id="roofeavevergedetail" name="roofeavevergedetail" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="&gt;100mm soffit at eaves and verges" <?php echo $row['RoofEaveVergedetail'] == "&gt;100mm soffit at eaves and verges" ? " selected" : ""; ?>>&gt;100mm soffit at eaves and verges</option>
                     <option value="&lt;100mm soffit at eaves and verges" <?php echo $row['RoofEaveVergedetail'] == "&lt;100mm soffit at eaves and verges" ? " selected" : ""; ?>>&lt;100mm soffit at eaves and verges</option>
                     <option value="No soffits"                           <?php echo $row['RoofEaveVergedetail'] == "No soffits" ? " selected" : ""; ?>                          >No soffits</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="eaveverge_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This highlights the potential need for roof extensions if EWI 
                  is being considered.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Main Roof Vented</th>
               <td>
                  <input type="checkbox" name="roofvented" value="<?php echo ($row['RoofVented']=='1' ? '1' : '0');?>" <?php echo ($row['RoofVented']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="roofvented_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  External vents visible – eaves, tiles etc
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Roof Retrofit</th>
               <td>
                  <select id="roofretrofit" name="roofretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['RoofRetrofit'] == "Red" ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['RoofRetrofit'] == "Amber" ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['RoofRetrofit'] == "Green" ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['RoofRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Roof 2 Type</th>
               <td>
                  <select id="roof2typeChk" name="roof2type" onchange="disableRoof2CoveringType()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Pitched" <?php echo $row['Roof2Type'] == "Pitched" ? " selected" : ""; ?>>Pitched</option>
                     <option value="Flat"    <?php echo $row['Roof2Type'] == "Flat"    ? " selected" : ""; ?>   >Flat</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Covering</th>
               <td>
                  <select id="roof2covering" name="roof2covering" size="1" style="width: 200px;">
                     <optgroup label="Pitched" id="roof2coverPitched" >
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Tiles"    <?php echo $row['Roof2Covering'] == "Tiles/"   ? " selected" : ""; ?>>Tiles</option>
                        <option value="Slates"   <?php echo $row['Roof2Covering'] == "Slates"   ? " selected" : ""; ?>>Slates</option>
                        <option value="Metal"    <?php echo $row['Roof2Covering'] == "Metal"    ? " selected" : ""; ?>>Metal</option>
                        <option value="Thatch"   <?php echo $row['Roof2Covering'] == "Thatch"   ? " selected" : ""; ?>>Thatch</option>
                        <option value="Shingles" <?php echo $row['Roof2Covering'] == "Shingles" ? " selected" : ""; ?>>Shingles</option>
                        <option value="Asbestos sheet/tiles/slates" <?php echo $row['Roof2Covering'] == "Asbestos sheet/tiles/slates" ? " selected" : ""; ?>>Asbestos sheet/tiles/slates</option>
                        <option value="Stone"    <?php echo $row['Roof2Covering'] == "Stone"    ? " selected" : ""; ?>>Stone</option>
                        <option value="Other"    <?php echo $row['Roof2Covering'] == "Other"    ? " selected" : ""; ?>>Other</option>
                     </optgroup>
                        <optgroup label="Flat" id="roof2coverFlat">
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Felt"        <?php echo $row['Roof2Covering'] == "Felt"        ? " selected" : ""; ?>>Felt</option>
                        <option value="Glass Fibre" <?php echo $row['Roof2Covering'] == "Glass Fibre" ? " selected" : ""; ?>>Glass Fibre</option>
                        <option value="Metal"       <?php echo $row['Roof2Covering'] == "Metal"       ? " selected" : ""; ?>>Metal</option>
                        <option value="Asphalt"     <?php echo $row['Roof2Covering'] == "Asphalt"     ? " selected" : ""; ?>>Asphalt</option>
                        <option value="EPDM"        <?php echo $row['Roof2Covering'] == "EPDM"        ? " selected" : ""; ?>>EPDM</option>
                        <option value="Single Ply Membrane" <?php echo $row['Roof2Covering'] == "Single Ply Membrane" ? " selected" : ""; ?>>Single Ply Membrane</option>
                        <option value="Other"       <?php echo $row['Roof2Covering'] == "Other"       ? " selected" : ""; ?>>Other</option>
                     </optgroup>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Eaves & Verge Detail</th>
               <td>
                  <select id="roof2eavesvergedetail" name="roof2eavesvergedetail" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="&gt;100mm Soffit" <?php echo $row['Roof2EavesVergeDetail'] == "&gt;100mm Soffit" ? " selected" : ""; ?>>&gt;100mm Soffit</option>
                     <option value="&lt;100mm Soffit" <?php echo $row['Roof2EavesVergeDetail'] == "&lt;100mm Soffit" ? " selected" : ""; ?>>&lt;100mm Soffit</option>
                     <option value="Unknown"          <?php echo $row['Roof2EavesVergeDetail'] == "Unknown"          ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Roof 2 Vents</th>
               <td>
                  <input type="checkbox"  name="roof2vents" value="<?php echo ($row['Roof2Vents']=='1' ? '1' : '0');?>" <?php echo ($row['Roof2Vents']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Retrofit</th>
               <td>
                  <select id="roof2retrofit" name="roof2retrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['Roof2Retrofit'] == "Red"           ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['Roof2Retrofit'] == "Amber"         ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['Roof2Retrofit'] == "Green"         ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['Roof2Retrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Roof 3 Type</th>
               <td>
                  <select id="roof3typeChk" name="roof3type" onchange="disableRoof3CoveringType()" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Pitched" <?php echo $row['Roof3Type'] == "Pitched" ? " selected" : ""; ?>>Pitched</option>
                     <option value="Flat"    <?php echo $row['Roof3Type'] == "Flat"    ? " selected" : ""; ?>>Flat</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 3 Covering</th>
               <td>
                  <select id="roof3covering" name="roof3covering" size="1" style="width: 200px;">
                     <optgroup label="Pitched" id="roof3coverPitched" >
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Tiles"    <?php echo $row['Roof3Covering'] == "Tiles/"   ? " selected" : ""; ?>>Tiles</option>
                        <option value="Slates"   <?php echo $row['Roof3Covering'] == "Slates"   ? " selected" : ""; ?>>Slates</option>
                        <option value="Metal"    <?php echo $row['Roof3Covering'] == "Metal"    ? " selected" : ""; ?>>Metal</option>
                        <option value="Thatch"   <?php echo $row['Roof3Covering'] == "Thatch"   ? " selected" : ""; ?>>Thatch</option>
                        <option value="Shingles" <?php echo $row['Roof3Covering'] == "Shingles" ? " selected" : ""; ?>>Shingles</option>
                        <option value="Asbestos sheet/tiles/slates" <?php echo $row['Roof3Covering'] == "Asbestos sheet/tiles/slates" ? " selected" : ""; ?>>Asbestos sheet/tiles/slates</option>
                        <option value="Stone"    <?php echo $row['Roof3Covering'] == "Stone"    ? " selected" : ""; ?>>Stone</option>
                        <option value="Other" <?php echo $row['Roof3Covering'] == "Other" ? " selected" : ""; ?>>Other</option>
                     </optgroup>
                     <optgroup label="Flat" id="roof3coverFlat">
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Felt"        <?php echo $row['Roof3Covering'] == "Felt"        ? " selected" : ""; ?>>Felt</option>
                        <option value="Glass Fibre" <?php echo $row['Roof3Covering'] == "Glass Fibre" ? " selected" : ""; ?>>Glass Fibre</option>
                        <option value="Metal sheet" <?php echo $row['Roof3Covering'] == "Metal sheet" ? " selected" : ""; ?>>Metal sheet</option>
                        <option value="Asphalt"     <?php echo $row['Roof3Covering'] == "Asphalt"     ? " selected" : ""; ?>>Asphalt</option>
                        <option value="EPDM"        <?php echo $row['Roof3Covering'] == "EPDM"        ? " selected" : ""; ?>>EPDM</option>
                        <option value="Single Ply Membrane" <?php echo $row['Roof3Covering'] == "Single Ply Membrane" ? " selected" : ""; ?>>Single Ply Membrane</option>
                        <option value="Other flat"  <?php echo $row['Roof3Covering'] == "Other flat"  ? " selected" : ""; ?>>Other flat</option>
                     </optgroup>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 3 Eaves & Verge Detail</th>
               <td>
                  <select id="roof3eavesvergedetail" name="roof3eavesvergedetail" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="&gt;100mm Soffit" <?php echo $row['Roof3EavesVergeDetail'] == "&gt;100mm Soffit" ? " selected" : ""; ?>>&gt;100mm Soffit</option>
                     <option value="&lt;100mm Soffit" <?php echo $row['Roof3EavesVergeDetail'] == "&lt;100mm Soffit" ? " selected" : ""; ?>>&lt;100mm Soffit</option>
                     <option value="Unknown"          <?php echo $row['Roof3EavesVergeDetail'] == "Unknown"          ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Roof 3 Vents</th>
               <td>
                  <input type="checkbox"  name="roof3vents" value="<?php echo ($row['Roof3Vents']=='1' ? '1' : '0');?>" <?php echo ($row['Roof3Vents']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Roof 3 Retrofit</th>
               <td>
                  <select id="roof3retrofit" name="roof3retrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['Roof3Retrofit'] == "Red" ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['Roof3Retrofit'] == "Amber" ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['Roof3Retrofit'] == "Green" ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['Roof3Retrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Roof Notes</th>
               <td>
                  <textarea name="roofnotes" rows="2" cols="30"><?php echo $row['RoofNotes']?></textarea>
               </td>
            </tr>
         </table>
         <br>         
         <br> 
         <div id="pagefooter" style="margin-left: 200px;text-align:center;bottom: 0%;">
            <a href="form18.php" title="Form 18">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form30.php" title="Form 30">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>
   <footer>
   </footer>  
   </body>
</html>
