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

   $q = 'SELECT * FROM internalroof WHERE SurveyId = "'.$surveyid.'"' ;

   $r = mysqli_query( $dbc , $q ) ;

   $row = mysqli_fetch_array( $r , MYSQLI_ASSOC ); 
#-----------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form50.php");

      # set checkboxes if blank
      (!isset($_POST['roofsignificant'])) ? $roofsignificant = 0 : $roofsignificant = 1;
      (!isset($_POST['dormer']))          ? $dormer          = 0 : $dormer          = 1;

      # set numerical textbox if blank
      
      # set dropdowns if blank
      (!isset($_POST['roofretrofit']))         ? $roofretrofit         = '' : $roofretrofit         = $_POST['roofretrofit'];
      (!isset($_POST['mainroofinsulation']))   ? $mainroofinsulation   = '' : $mainroofinsulation   = $_POST['mainroofinsulation'];
      (!isset($_POST['mainroofinslatnthick'])) ? $mainroofinslatnthick = '' : $mainroofinslatnthick = $_POST['mainroofinslatnthick'];
      (!isset($_POST['mainroofinslatntype']))  ? $mainroofinslatntype  = '' : $mainroofinslatntype  = $_POST['mainroofinslatntype'];
      (!isset($_POST['loftconversion']))       ? $loftconversion       = '' : $loftconversion       = $_POST['loftconversion'];
      (!isset($_POST['roof2insulation']))      ? $roof2insulation      = '' : $roof2insulation      = $_POST['roof2insulation'];
      (!isset($_POST['roof2inslatnthicknss'])) ? $roof2inslatnthicknss  = 0 : $roof2inslatnthicknss  = $_POST['roof2inslatnthicknss'];
      (!isset($_POST['roof2inslatntype']))     ? $roof2inslatntype     = '' : $roof2inslatntype     = $_POST['roof2inslatntype'];

      # set notes if blank
      (!isset($_POST['introofnotes'])) ? $introofnotes = '' : $introofnotes = $_POST['introofnotes'];
      
      # these need to be set to cope with slightly different logic
      if (!isset($_POST['dormer'])) {
         $dormer          = 0;
         $dormerwindows   = '';
         $dormroofinslatn = '';
         $dormwallinslatn = '';
         $dormernotes     = '';
      } else {
         $dormer = 1;
         $dormerwindows   = $_POST['dormerwindows'];
         $dormroofinslatn = $_POST['dormroofinslatn'];
         $dormwallinslatn = $_POST['dormwallinslatn'];
         $dormernotes     = $_POST['dormernotes'];
      } 

      $sql = "UPDATE internalroof ".      
               " SET RoofSignificant="    . clean_input($roofsignificant) .
               ",  RoofRetrofit='"        . clean_input($roofretrofit) .
               "', MainRoofInsulation='"  . clean_input($mainroofinsulation) .
               "', MainRoofInslatnThick='". clean_input($mainroofinslatnthick) .
               "', MainRoofInslatnType='" . clean_input($mainroofinslatntype) . 
               "', LoftConversion='"      . clean_input($loftconversion) . 
               "', Dormer="               . clean_input($dormer) . 
               ",  DormerWindows='"       . clean_input($dormerwindows) . 
               "', DormRoofInslatn='"     . clean_input($dormroofinslatn) . 
               "', DormWallInslatn='"     . clean_input($dormwallinslatn) . 
               "', DormerNotes='"         . clean_input($dormernotes) . 
               "', Roof2Insulation='"     . clean_input($roof2insulation) .
               "', Roof2InslatnThicknss=" . clean_input($roof2inslatnthicknss) .
               ",  Roof2InslatnType='"    . clean_input($roof2inslatntype) .
               "', Loft2Conversion='"     . clean_input($loft2conversion) .
               "', IntRoofNotes='"        . clean_input($introofnotes) .
               "' WHERE surveyId=" . $surveyid;
               
      echo "<br>";   
      print_r($_POST);
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
#-----------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 50</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Loft / Room in Roof</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideDormerQueries();
           showDormerQueries();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideDormerQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("dormerwindowsSel");
             obj2 = document.getElementById("dormerwindowsHdr");
             obj3 = document.getElementById("dormroofinslatnSel");
             obj4 = document.getElementById("dormroofinslatnHdr");
             obj5 = document.getElementById("dormwallinslatnSel");
             obj6 = document.getElementById("dormwallinslatnHdr");
             obj7 = document.getElementById("dormernotesSel");
             obj8 = document.getElementById("dormernotesHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
             obj5.style.display="none";
             obj6.style.display="none";
             obj7.style.display="none";
             obj8.style.display="none";
         }

         function showDormerQueries() {
             if (!document.getElementById) return;
             dermrobj = document.getElementById("dormerChk");
             if (dermrobj.checked == true){
                obj1 = document.getElementById("dormerwindowsSel");
                obj2 = document.getElementById("dormerwindowsHdr");
                obj3 = document.getElementById("dormroofinslatnSel");
                obj4 = document.getElementById("dormroofinslatnHdr");
                obj5 = document.getElementById("dormwallinslatnSel");
                obj6 = document.getElementById("dormwallinslatnHdr");
                obj7 = document.getElementById("dormernotesSel");
                obj8 = document.getElementById("dormernotesHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
                obj5.style.display="block";
                obj6.style.display="block";
                obj7.style.display="block";
                obj8.style.display="block";
             } else {
               hideDormerQueries();
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
               <th>Roof Significant</th>
               <td>
                  <input type="checkbox"  name="roofsignificant" value="<?php echo ($row['RoofSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['RoofSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Roof Retrofit</th>
               <td>
                  <select id="roofretrofit" name="roofretrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['RoofRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['RoofRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['RoofRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['RoofRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Main Roof Insulation</th>
               <td>
                  <select id="mainroofinsulation" name="mainroofinsulation" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"          <?php echo $row['MainRoofInsulation'] == "None" ? " selected" : ""; ?>          >None</option>
                     <option value="Joists"        <?php echo $row['MainRoofInsulation'] == "Joists" ? " selected" : ""; ?>        >Joists</option>
                     <option value="Rafters"       <?php echo $row['MainRoofInsulation'] == "Rafters" ? " selected" : ""; ?>       >Rafters</option>
                     <option value="Room in roof"  <?php echo $row['MainRoofInsulation'] == "Room in roof" ? " selected" : ""; ?>  >Room in roof</option>
                     <option value="Not inspected" <?php echo $row['MainRoofInsulation'] == "Not inspected" ? " selected" : ""; ?> >Not inspected</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="mainroofinsulation_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Part L, Whole House Energy Assessment: Location and depth of insulation
                  gives an indication of thermal performance and opportunities for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Main Roof Insulation Thickness</th>
               <td>
                  <select id="mainroofinslatnthick" name="mainroofinslatnthick" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Less than 100mm"<?php echo $row['MainRoofInslatnThick'] == "Less than 100mm" ? " selected" : ""; ?>  >Less than 100mm</option>
                     <option value="100-200mm"      <?php echo $row['MainRoofInslatnThick'] == "100-200mm" ? " selected" : ""; ?> >100-200mm</option>
                     <option value="200-300mm"      <?php echo $row['MainRoofInslatnThick'] == "200-300mm" ? " selected" : ""; ?>>200-300mm</option>
                     <option value="more than 300mm"<?php echo $row['MainRoofInslatnThick'] == "more than 300mm" ? " selected" : ""; ?> >more than 300mm</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Main Roof Insulation Type</th>
               <td>
                  <select id="roofinsulationtype" name="mainroofinslatntype" size="1" >
                     <optgroup label="Joists" id="mainroofInsulTypeJoist" >
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Mineral wool / Glass fibre / EPS/XPS"<?php echo $row['MainRoofInslatnType'] == "Mineral wool / Glass fibre / EPS/XPS" ? " selected" : ""; ?>>Mineral wool / Glass fibre / EPS/XPS</option>
                        <option value="Cellulose / Natural fibres" <?php echo $row['MainRoofInslatnType'] == "Cellulose / Natural fibres" ? " selected" : ""; ?>>Cellulose / Natural fibres</option>
                        <option value="Phenolic/PIR/PUR"           <?php echo $row['MainRoofInslatnType'] == "Phenolic/PIR/PUR"           ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                     </optgroup>
                     <optgroup label="Rafters" id="mainroofInsulTypeRafter">
                        <option value="Mineral wool / Glass fibre / EPS/XPS"<?php echo $row['MainRoofInslatnType'] == "Mineral wool / Glass fibre / EPS/XPS" ? " selected" : ""; ?>>Mineral wool / Glass fibre / EPS/XPS</option>
                        <option value="Glass fibre"           <?php echo $row['MainRoofInslatnType'] == "Glass fibre"            ? " selected" : ""; ?>>Glass fibre</option>
                        <option value="Natural fibres"        <?php echo $row['MainRoofInslatnType'] == "Natural fibres"         ? " selected" : ""; ?>>Natural fibres</option>
                        <option value="Phenolic/PIR/PUR"      <?php echo $row['MainRoofInslatnType'] == "Phenolic/PIR/PUR"       ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                        <option value="Multi-foil"            <?php echo $row['MainRoofInslatnType'] == "Multi-foil"             ? " selected" : ""; ?>>Multi-foil</option>
                        <option value="Icynene foam / similar"<?php echo $row['MainRoofInslatnType'] == "Icynene foam / similar" ? " selected" : ""; ?>>Icynene foam / similar</option>
                        <option value="Unknown"               <?php echo $row['MainRoofInslatnType'] == "Unknown"                ? " selected" : ""; ?>>Unknown</option>
                     </optgroup>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Room In Loft/Loft Conversion</th>
               <td>
                  <select id="loftconversion" name="loftconversion" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="As built"  <?php echo $row['LoftConversion'] == "As built"  ? " selected" : ""; ?> >As built</option>
                     <option value="Pre 1965"  <?php echo $row['LoftConversion'] == "Pre 1965"  ? " selected" : ""; ?> >Pre 1965</option>
                     <option value="1965-1975" <?php echo $row['LoftConversion'] == "1965-1975" ? " selected" : ""; ?> >1965-1975</option>
                     <option value="1976-1981" <?php echo $row['LoftConversion'] == "1976-1981" ? " selected" : ""; ?> >1976-1981</option>
                     <option value="1982-2002" <?php echo $row['LoftConversion'] == "1982-2002" ? " selected" : ""; ?> >1982-2002</option>
                     <option value="2003-2006" <?php echo $row['LoftConversion'] == "2003-2006" ? " selected" : ""; ?> >2003-2006</option>
                     <option value="2007-2011" <?php echo $row['LoftConversion'] == "2007-2011" ? " selected" : ""; ?> >2007-2011</option>
                     <option value="2012-2014" <?php echo $row['LoftConversion'] == "2012-2014" ? " selected" : ""; ?> >2012-2014</option>
                     <option value="2014-2020" <?php echo $row['LoftConversion'] == "2014-2020" ? " selected" : ""; ?> >2014-2020</option>
                     <option value="2020 onwds"<?php echo $row['LoftConversion'] == "2020 onwds"? " selected" : ""; ?> >2020 onwds</option>
                     <option value="Unknown"   <?php echo $row['LoftConversion'] == "Unknown"   ? " selected" : ""; ?> >Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="loftconversion_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Date of loft conversion or build gives an indication of thermal
                  performance and opportunities for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Dormer</th>
               <td>
                  <input type="checkbox" id="dormerChk" name="dormer" onchange="showDormerQueries()" value="<?php echo ($row['Dormer']=='1' ? '1' : '0');?>" <?php echo ($row['Dormer']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th id="dormerwindowsHdr">Dormer Windows</th>
               <td>
                  <select id="dormerwindowsSel" name="dormerwindows" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Single"          <?php echo $row['DormerWindows'] == "Single"           ? " selected" : ""; ?>>Single</option>
                     <option value="Double Pre 2002" <?php echo $row['DormerWindows'] == "Double Pre 2002"  ? " selected" : ""; ?>>Double Pre 2002</option>
                     <option value="Double Post 2002"<?php echo $row['DormerWindows'] == "Double Post 2002" ? " selected" : ""; ?>>Double Post 2002</option>
                     <option value="Secondary"       <?php echo $row['DormerWindows'] == "Secondary"        ? " selected" : ""; ?>>Secondary</option>
                     <option value="Triple"          <?php echo $row['DormerWindows'] == "Triple"           ? " selected" : ""; ?>>Triple</option>
                     <option value="Other"           <?php echo $row['DormerWindows'] == "Other"            ? " selected" : ""; ?>>Other</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="dormroofinslatnHdr">Dormer Roof Insulation</th>
               <td>
                  <select id="dormroofinslatnSel" name="dormroofinslatn" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Sufficient"                                  <?php echo $row['DormRoofInslatn'] == "Sufficient" ? " selected" : "";                                  ?>>Sufficient</option>
                     <option value="Insufficient with potential for improvement" <?php echo $row['DormRoofInslatn'] == "Insufficient with potential for improvement" ? " selected" : ""; ?>>Insufficient with potential for improvement</option>
                     <option value="Insufficient without potential"              <?php echo $row['DormRoofInslatn'] == "Insufficient without potential" ? " selected" : "";              ?>>Insufficient without potential</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="dormwallinslatnHdr">Dormer wall/cheek Insulation</th>
               <td>
                  <select id="dormwallinslatnSel" name="dormwallinslatn" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Sufficient"                                  <?php echo $row['DormWallInslatn'] == "Sufficient" ? " selected" : "";                                  ?>>Sufficient</option>
                     <option value="Insufficient with potential for improvement" <?php echo $row['DormWallInslatn'] == "Insufficient with potential for improvement" ? " selected" : ""; ?>>Insufficient with potential for improvement</option>
                     <option value="Insufficient without potential"              <?php echo $row['DormWallInslatn'] == "Insufficient without potential" ? " selected" : "";              ?>>Insufficient without potential</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="dormernotesHdr">Dormer Notes</th>
               <td>
                  <textarea id="dormernotesSel" name="dormernotes" rows="2" cols="30"><?php echo $row['DormerNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Insulation</th>
               <td>
                  <select id="roof2insulation" name="roof2insulation" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Joists"   <?php echo $row['Roof2Insulation'] == "Joists" ? " selected" : ""; ?>  >Joists</option>
                     <option value="Rafters"  <?php echo $row['Roof2Insulation'] == "Rafters" ? " selected" : ""; ?> >Rafters</option>
                     <option value="As built" <?php echo $row['Roof2Insulation'] == "As built" ? " selected" : ""; ?>>As built</option>
                     <option value="Unknown"  <?php echo $row['Roof2Insulation'] == "Unknown" ? " selected" : ""; ?> >Unknown</option>
                     <option value="None"     <?php echo $row['Roof2Insulation'] == "None" ? " selected" : ""; ?>    >None</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Insulation Thickness</th>
               <td>
                  <select id="mainroofinslatnthick" name="mainroofinslatnthick" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Less than 100mm"<?php echo $row['Roof2InslatnThicknss'] == "Less than 100mm" ? " selected" : ""; ?>  >Less than 100mm</option>
                     <option value="100-200mm"      <?php echo $row['Roof2InslatnThicknss'] == "100-200mm" ? " selected" : ""; ?> >100-200mm</option>
                     <option value="200-300mm"      <?php echo $row['Roof2InslatnThicknss'] == "200-300mm" ? " selected" : ""; ?>>200-300mm</option>
                     <option value="more than 300mm"<?php echo $row['Roof2InslatnThicknss'] == "more than 300mm" ? " selected" : ""; ?> >more than 300mm</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof 2 Insulation Type</th>
               <td>
                  <select id="roof2inslatntype" name="roof2inslatntype" size="1" >
                     <optgroup label="Joists" id="mainroofInsulTypeJoist" >
                        <option value="" disabled selected>Please choose...</option>
                        <option value="Mineral wool / Glass fibre / EPS/XPS"<?php echo $row['Roof2InslatnType'] == "Mineral wool / Glass fibre / EPS/XPS" ? " selected" : ""; ?>    >Mineral wool / Glass fibre / EPS/XPS</option>
                        <option value="Cellulose / Natural fibres" <?php echo $row['Roof2InslatnType'] == "Cellulose / Natural fibres" ? " selected" : ""; ?>  >Cellulose / Natural fibres</option>
                        <option value="Phenolic/PIR/PUR"           <?php echo $row['Roof2InslatnType'] == "Phenolic/PIR/PUR" ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                     </optgroup>
                     <optgroup label="Rafters" id="mainroofInsulTypeRafter">
                        <option value="Mineral wool / Glass fibre / EPS/XPS"<?php echo $row['Roof2InslatnType'] == "Mineral wool / Glass fibre / EPS/XPS" ? " selected" : ""; ?>>Mineral wool / Glass fibre / EPS/XPS</option>
                        <option value="Glass fibre"           <?php echo $row['Roof2InslatnType'] == "Glass fibre"      ? " selected" : ""; ?>>Glass fibre</option>
                        <option value="Natural fibres"        <?php echo $row['Roof2InslatnType'] == "Natural fibres"   ? " selected" : ""; ?>>Natural fibres</option>
                        <option value="Phenolic/PIR/PUR"      <?php echo $row['Roof2InslatnType'] == "Phenolic/PIR/PUR" ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                        <option value="Multi-foil"            <?php echo $row['Roof2InslatnType'] == "Multi-foil"       ? " selected" : ""; ?>>Multi-foil</option>
                        <option value="Icynene foam / similar"<?php echo $row['Roof2InslatnType'] == "Icynene foam / similar"       ? " selected" : ""; ?>>Icynene foam / similar</option>
                        <option value="Unknown"               <?php echo $row['Roof2InslatnType'] == "Unknown"          ? " selected" : ""; ?>>Unknown</option>
                     </optgroup>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Room In Loft2/Loft 2 Conversion</th>
               <td>
                  <select id="loft2conversion" name="loft2conversion" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="As built"  <?php echo $row['Loft2Conversion'] == "As built"  ? " selected" : ""; ?> >As built</option>
                     <option value="Pre 1965"  <?php echo $row['Loft2Conversion'] == "Pre 1965"  ? " selected" : ""; ?> >Pre 1965</option>
                     <option value="1965-1975" <?php echo $row['Loft2Conversion'] == "1965-1975" ? " selected" : ""; ?> >1965-1975</option>
                     <option value="1976-1981" <?php echo $row['Loft2Conversion'] == "1976-1981" ? " selected" : ""; ?> >1976-1981</option>
                     <option value="1982-2002" <?php echo $row['Loft2Conversion'] == "1982-2002" ? " selected" : ""; ?> >1982-2002</option>
                     <option value="2003-2006" <?php echo $row['Loft2Conversion'] == "2003-2006" ? " selected" : ""; ?> >2003-2006</option>
                     <option value="2007-2011" <?php echo $row['Loft2Conversion'] == "2007-2011" ? " selected" : ""; ?> >2007-2011</option>
                     <option value="2012-2014" <?php echo $row['Loft2Conversion'] == "2012-2014" ? " selected" : ""; ?> >2012-2014</option>
                     <option value="2014-2020" <?php echo $row['Loft2Conversion'] == "2014-2020" ? " selected" : ""; ?> >2014-2020</option>
                     <option value="2020 onwds"<?php echo $row['Loft2Conversion'] == "2020 onwds"? " selected" : ""; ?> >2020 onwds</option>
                     <option value="Unknown"   <?php echo $row['Loft2Conversion'] == "Unknown"   ? " selected" : ""; ?> >Unknown</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Roof Notes</th>
               <td>
                  <textarea name="roofnotes" rows="2" cols="25"><?php echo $row['RoofNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form46.php" title="Form 46">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form52.php" title="Form 52">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
