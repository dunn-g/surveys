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
   
   $qry = 'SELECT b.*, CONCAT( s.SurveyorFirstName, " ", s.SurveyorLastName) AS "surveyorname" FROM basicinfo b ' .
            'LEFT JOIN aasurveyor s on b.SurveyorID = s.SurveyorId ' .
            'WHERE b.SurveyId = "'.$surveyid.'"' ;
            
   $rslt = mysqli_query( $dbc , $qry ) ;
#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 
      
      mysqli_free_result( $rslt ) ;

   } 
   $feedbackAry = array();
   $feedbackAry = explode(',',$row['RetrofitAdvisorFeedback']);
   echo "<br>";         
#----------------------------------------------------------------

   if (isset ($_POST['submit'])){

      header("location:form10.php");

      # set checkboxes if blank
      (!isset($_POST['partywallissue']))  ? $partywallissue  = 0   : $partywallissue  = 1;
      (!isset($_POST['righttolight']))    ? $righttolight    = 0   : $righttolight    = 1;
      (!isset($_POST['existingepc']))     ? $existingepc     = 0   : $existingepc     = 1;
      (!isset($_POST['conditionsurvey'])) ? $conditionsurvey = 0   : $conditionsurvey = 1;

      # set date textboxes if blank
      (!isset($_POST['inspectiondate'])) ? $inspectiondate  = date("Y-m-d H:i:s") : $inspectiondate  = date("Y-m-d H:i:s",strtotime($_POST['inspectiondate']));
      (!isset($_POST['epcdate']))        ? $epcdate         = DBNull.value        : $epcdate         = date("Y-m-d",strtotime($_POST['epcdate']));
      
      # set dropdowns if blank
      (!isset($_POST['floodrisk']))               ? $floodrisk               = '' : $floodrisk               = $_POST['floodrisk'];
      (!isset($_POST['exposurerating']))          ? $exposurerating          = '' : $exposurerating          = $_POST['exposurerating'];
      (!isset($_POST['retrofitadvisorfeedback'])) ? $advisorfeedback         = '' : $advisorfeedback         = implode(',', $_POST['retrofitadvisorfeedback']);
      (!isset($_POST['tenure']))                  ? $tenure                  = '' : $tenure                  = $_POST['tenure'];
      (!isset($_POST['propertyType']))            ? $propertyType            = '' : $propertyType            = $_POST['propertyType'];
      (!isset($_POST['propertyStyle']))           ? $propertyStyle           = '' : $propertyStyle           = $_POST['propertyStyle'];
      (!isset($_POST['propertyposition']))        ? $propertyposition        = '' : $propertyposition        = $_POST['propertyposition'];
      (!isset($_POST['propertyDate']))            ? $propertyDate            = '' : $propertyDate            = $_POST['propertyDate'];
      (!isset($_POST['storeys']))                 ? $storeys                 = '' : $storeys                 = $_POST['storeys'];
      (!isset($_POST['numBedrooms']))             ? $numBedrooms             = '' : $numBedrooms             = $_POST['numBedrooms'];
      (!isset($_POST['bedroomOccupancy']))        ? $bedroomOccupancy        = '' : $bedroomOccupancy        = $_POST['bedroomOccupancy'];
      (!isset($_POST['orientation']))             ? $orientation             = '' : $orientation             = $_POST['orientation'];

      # set notes if blank
      (!isset($_POST['partywallnotes']))       ? $partywallnotes       = '' : $partywallnotes       = $_POST['partywallnotes'];
      (!isset($_POST['righttolightnotes']))    ? $righttolightnotes    = '' : $righttolightnotes    = $_POST['righttolightnotes'];
      (!isset($_POST['conditionsurveynotes'])) ? $conditionsurveynotes = '' : $conditionsurveynotes = $_POST['conditionsurveynotes'];

      $sql = "UPDATE basicinfo ".      
               " SET Inspectiondate='"              . clean_input($inspectiondate) .
               "', FloodRisk='"                     . clean_input($floodrisk) .
               "', ExposureRating='"                . clean_input($exposurerating) .
               "', PartyWallIssue="                 . clean_input($partywallissue) .
               ",  PartyWallNotes='"                . clean_input($partywallnotes) .
               "', RightToLight="                   . clean_input($righttolight) .
               ",  RightToLightNotes='"             . clean_input($righttolightnotes) .
               "', ExistingEPC="                    . clean_input($existingepc) .
               ",  EPCDate='"                       . clean_input($epcdate) .
               "', ConditionSurvey="                . clean_input($conditionsurvey) .
               ",  ConditionSurveyNotes='"          . clean_input($conditionsurveynotes) .
               "', RetrofitAdvisorFeedback='"       . clean_input($advisorfeedback) .
               "', Tenure='"                        . clean_input($tenure) .
               "', PropertyType='"                  . clean_input($propertyType) .
               "', propertyStyle='"                 . clean_input($propertyStyle) . 
               "', PropertyPosition='"              . clean_input($propertyposition) . 
               "', MainPropertyDate='"              . clean_input($propertyDate) .
               "', Storeys='"                       . clean_input($storeys) .
               "', NumberBedrooms='"                . clean_input($numBedrooms) .
               "', BedroomOccupancy='"              . clean_input($bedroomOccupancy) .
               "', OrientationMainFrontElevation='" . clean_input($orientation) .
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
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

#----------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 10</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h1><strong><em>Basic Property Information</em></strong></h1>
         <h2><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h2>
      </header>

      <style>
      </style>    

      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hidePropertyPosition();
           showPropertyPosition();
           
           hidePartyWallNotes();
           showPartyWallNotes();
           
           hideRightToLightNotes();
           showRightToLightNotes();
           
           hideEPCdate();
           showEPCdate();
           
           hideConditionSurveyNotes();
           showConditionSurveyNotes();
         }
        
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
         
         function hidePropertyPosition() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("propertypositionsel");
             obj2 = document.getElementById("propertypositionhdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showPropertyPosition() {
             if (!document.getElementById) return;
             proptypeobj = document.getElementById("propertyTypeSlct");
             if (proptypeobj.value == "Flat"){
                obj1 = document.getElementById("propertypositionsel");
                obj2 = document.getElementById("propertypositionhdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hidePropertyPosition();
             }
         }         
         
         function hidePartyWallNotes() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("partywallnotesSel");
             obj2 = document.getElementById("partywallnotesHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showPartyWallNotes() {
             if (!document.getElementById) return;
             pwnobj = document.getElementById("partywallissueChk");
             if (pwnobj.checked == true){
                obj1 = document.getElementById("partywallnotesSel");
                obj2 = document.getElementById("partywallnotesHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hidePartyWallNotes();
             }
         }         
         
         function hideRightToLightNotes() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("righttolightSel");
             obj2 = document.getElementById("righttolightHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showRightToLightNotes() {
             if (!document.getElementById) return;
             rtlobj = document.getElementById("righttolightChk");
             if (rtlobj.checked == true){
                obj1 = document.getElementById("righttolightSel");
                obj2 = document.getElementById("righttolightHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideRightToLightNotes();
             }
         }
         
         function hideEPCdate() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("existingepcSel");
             obj2 = document.getElementById("existingepcHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showEPCdate() {
             if (!document.getElementById) return;
             epcobj = document.getElementById("existingepcChk");
             if (epcobj.checked == true){
                obj1 = document.getElementById("existingepcSel");
                obj2 = document.getElementById("existingepcHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideEPCdate();
             }
         }

         function hideConditionSurveyNotes() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("conditionsurveySel");
             obj2 = document.getElementById("conditionsurveyHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showConditionSurveyNotes() {
             if (!document.getElementById) return;
             csobj = document.getElementById("conditionsurveyChk");
             if (csobj.checked == true){
                obj1 = document.getElementById("conditionsurveySel");
                obj2 = document.getElementById("conditionsurveyHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideConditionSurveyNotes();
             }
         }
      </script>

   </head>

   <body>

      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->

      <!-- Page content &#128712; &#8505; &#9432 and &#x1F6C8 -->
      <div class="main" style="display:table;">
      <form id="form10" method="POST" >
         <table class="" style="border: 0">
            <tr>
               <th>Inspection Date</th>
               <td><input type="datetime" name="inspectiondate" value="<?php echo date("d-m-Y",strtotime($row["Inspectiondate"]));?>" ></td>
            </tr>
            <tr>
               <th>Surveyor</th>
               <td>
                  <input type="text" name="surveyor_name" value="<?php echo $row['surveyorname']?>">
               </td>
            </tr>
            <tr>
               <th>Flood Risk</th>
               <td>
                  <select id="floodrisk" name="floodrisk" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"        <?php echo $row['FloodRisk'] == "None"         ? " selected" : ""; ?>>None</option>
                     <option value="Flood Zone 1"<?php echo $row['FloodRisk'] == "Flood Zone 1" ? " selected" : ""; ?>>Flood Zone 1</option>
                     <option value="Flood Zone 2"<?php echo $row['FloodRisk'] == "Flood Zone 2" ? " selected" : ""; ?>>Flood Zone 2</option>
                     <option value="Flood Zone 3"<?php echo $row['FloodRisk'] == "Flood Zone 3" ? " selected" : ""; ?>>Flood Zone 3</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="floodrisk_tt"  >
                  <span class="tooltiptext" >
                     NRW, Environment Agency, SEPA or DfI. <br>
                     Flood Zone 1: 0.1% risks   <br>
                     Flood Zone 2: 0.1 - 1% risk river, 0.1 - 0.5% risk sea <br>
                     Flood Zone 3: >1% risk river, >0.5% risk sea
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr> 
               <th>Exposure Rating</th>
               <td>
                  <select id="exposureRating" name="exposurerating" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Sheltered"  <?php echo $row['ExposureRating'] == "Sheltered"   ? " selected" : ""; ?>>Sheltered</option>
                     <option value="Moderate"   <?php echo $row['ExposureRating'] == "Moderate"    ? " selected" : ""; ?>>Moderate</option>
                     <option value="Severe"     <?php echo $row['ExposureRating'] == "Severe"      ? " selected" : ""; ?>>Severe</option>
                     <option value="Very Severe"<?php echo $row['ExposureRating'] == "Very Severe" ? " selected" : ""; ?>>Very Severe</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="exposureRating_tt"  >
                  <span class="tooltiptext" >
                  CATEGORIES OF EXPOSURE TO WIND DRIVEN RAIN from NHBC or BRE
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Party Wall Issue</th>
               <td>
                  <input type="checkbox" id="partywallissueChk" onchange="showPartyWallNotes()" name="partywallissue" value="<?php echo ($row['PartyWallIssue']=='1' ? '1' : '0');?>" <?php echo ($row['PartyWallIssue']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="partywallnotesHdr" > &nbsp; &nbsp; PartyWallNotes</th>
               <td>
                  <input type="text" id="partywallnotesSel" name="partywallnotes" value="<?php echo $row['PartyWallNotes']?>">
               </td>
            </tr>
            <tr>
               <th>Right to Light Issue</th>
               <td>
                  <input type="checkbox" id="righttolightChk" onchange="showRightToLightNotes()" name="righttolight" value="<?php echo ($row['RightToLight']=='1' ? '1' : '0');?>" <?php echo ($row['RightToLight']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="righttolightHdr" > &nbsp; &nbsp; Rt to Light Notes</th>
               <td>
                  <input type="text" id="righttolightSel" name="righttolightnotes" value="<?php echo $row['RightToLightNotes']?>">
               </td>
            </tr>
            <tr>
               <th>Existing EPC</th>
               <td>
                  <input type="checkbox" id="existingepcChk" onchange="showEPCdate()" name="existingepc" value="<?php echo ($row['ExistingEPC']=='1' ? '1' : '0');?>" <?php echo ($row['ExistingEPC']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="existingepc_tt"  >
                  <span class="tooltiptext" >
                  Check EPC Register 
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
               <th id="existingepcHdr" > &nbsp; &nbsp; EPC Date </th>
               <td>
                  <input type="date" id="existingepcSel" name="existingepcdate" value="<?php echo $row['EPCDate']?>">
               </td>
            </tr>
            <tr>
               <th>Condition Survey Available</th>
               <td>
                  <input type="checkbox" id="conditionsurveyChk" onchange="showConditionSurveyNotes()" name="conditionsurvey" value="<?php echo ($row['ConditionSurvey']=='1' ? '1' : '0');?>" <?php echo ($row['ConditionSurvey']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="conditionsurveyHdr" > &nbsp; &nbsp; Cond Surv Notes</th>
               <td>
                  <input type="text" id="conditionsurveySel" name="conditionsurveynotes" value="<?php echo $row['ConditionSurveyNotes']?>">
               </td>
            </tr>
            <tr>
               <th>Retrofit Advisor feedback</th>
               <td>
                  <select id="retrofitadvisorfeedback" name="retrofitadvisorfeedback[]" multiple="multiple" size=4 style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Energy Reduction"    <?php echo (isset($feedbackAry) && in_array('Energy Reduction', $feedbackAry))     ? " selected" : ""; ?>>Energy Reduction</option>
                     <option value="Cost Reduction"      <?php echo (isset($feedbackAry) && in_array('Cost Reduction', $feedbackAry))       ? " selected" : ""; ?>>Cost Reduction</option>
                     <option value="Damp solution"       <?php echo (isset($feedbackAry) && in_array('Damp solution', $feedbackAry))        ? " selected" : ""; ?>>Damp solution</option>
                     <option value="IAQ solution"        <?php echo (isset($feedbackAry) && in_array('IAQ solution', $feedbackAry))         ? " selected" : ""; ?>>IAQ solution</option>
                     <option value="Mould solution"      <?php echo (isset($feedbackAry) && in_array('Mould solution', $feedbackAry))       ? " selected" : ""; ?>>Mould solution</option>
                     <option value="Comfort improvemment"<?php echo (isset($feedbackAry) && in_array('Comfort improvemment', $feedbackAry)) ? " selected" : ""; ?>>Comfort improvemment</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="retrofitadvisorfeedback_tt"  >
                  <span class="tooltiptext" >
                  Retrofit Advisor may have identified the desire for change / improvements
                  by the client and this might help dictate the forwarding planning of the project
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Tenure</th>
               <td>
                  <select id="tenure" name="tenure" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Owner Occupier"          <?php echo $row['Tenure'] == "Owner Occupier" ? " selected" : ""; ?>          >Owner Occupier</option>
                     <option value="Rented (Social)"         <?php echo $row['Tenure'] == "Rented (Social)" ? " selected" : ""; ?>         >Rented (Social)</option>
                     <option value="Rented (Private)"        <?php echo $row['Tenure'] == "Rented (Private)" ? " selected" : ""; ?>        >Rented (Private)</option>
                     <option value="Joint Property Ownership"<?php echo $row['Tenure'] == "Joint Property Ownership" ? " selected" : ""; ?>>Joint Property Ownership</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="tenure_tt"  >
                  <span class="tooltiptext" >
                  This provides context including any EEM requirements (eg. MEES)
                  and opportunities for owner with regard towards incentives.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Property Type</th>
               <td>
                  <select id="propertyTypeSlct" onchange="showPropertyPosition()" name="propertyType" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="House"     <?php echo $row['PropertyType'] == "House" ? " selected" : ""; ?>     >House</option>
                     <option value="Flat"      <?php echo $row['PropertyType'] == "Flat" ? " selected" : ""; ?>      >Flat</option>
                     <option value="Maisonette"<?php echo $row['PropertyType'] == "Maisonette" ? " selected" : ""; ?>>Maisonette</option>
                     <option value="Bungalow"  <?php echo $row['PropertyType'] == "Bungalow" ? " selected" : ""; ?>  >Bungalow</option>
                     <option value="Park Home" <?php echo $row['PropertyType'] == "Park Home" ? " selected" : ""; ?> >Park Home</option>
                  </select>
               </td>
               <th id="propertypositionhdr"> &nbsp; &nbsp; Position</th>
               <td>
                  <select id="propertypositionsel" name="propertyposition" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Basement"    <?php echo $row['PropertyPosition'] == "Basement"     ? " selected" : ""; ?>>Basement</option>
                     <option value="Ground Floor"<?php echo $row['PropertyPosition'] == "Ground Floor" ? " selected" : ""; ?>>Ground Floor</option>
                     <option value="Mid Floor"   <?php echo $row['PropertyPosition'] == "Mid Floor"    ? " selected" : ""; ?>>Mid Floor</option>
                     <option value="Top Floor"   <?php echo $row['PropertyPosition'] == "Top Floor"    ? " selected" : ""; ?>>Top Floor</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Property Style</th>
               <td>
                  <select id="propertyStyle" name="propertyStyle" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Detached"     <?php echo $row['PropertyStyle'] == "Detached" ? " selected" : ""; ?>                   >Detached</option>
                     <option value="Semi-Detached"<?php echo $row['PropertyStyle'] == "Semi-Detached" ? " selected" : ""; ?>              >Semi-Detached</option>
                     <option value="Mid Terrace"  <?php echo $row['PropertyStyle'] == "Mid Terrace" ? " selected" : ""; ?>                >Mid Terrace</option>
                     <option value="End Terrace"  <?php echo $row['PropertyStyle'] == "End Terrace" ? " selected" : ""; ?>                >End Terrace</option>
                     <option value="Enclosed Mid-Terrace"<?php echo $row['PropertyStyle'] == "Enclosed Mid-Terrace" ? " selected" : ""; ?>>Enclosed Mid-Terrace</option>
                     <option value="Enclosed End-Terrace"<?php echo $row['PropertyStyle'] == "Enclosed End-Terrace" ? " selected" : ""; ?>>Enclosed End-Terrace</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Property Date</th>
               <td>
                  <select id="propertyDate" name="propertyDate" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Pre 1919" <?php echo $row['MainPropertyDate'] == "Pre 1919" ? " selected" : ""; ?> >Pre 1919</option>
                     <option value="1920-1945"<?php echo $row['MainPropertyDate'] == "1920-1945" ? " selected" : ""; ?>>1920-1945</option>
                     <option value="1946-1965"<?php echo $row['MainPropertyDate'] == "1946-1965" ? " selected" : ""; ?>>1946-1975</option>
                     <option value="1966-1975"<?php echo $row['MainPropertyDate'] == "1966-1975" ? " selected" : ""; ?>>1966-1975</option>
                     <option value="1976-1981"<?php echo $row['MainPropertyDate'] == "1976-1981" ? " selected" : ""; ?>>1976-1981</option>
                     <option value="1982-2002"<?php echo $row['MainPropertyDate'] == "1982-2002" ? " selected" : ""; ?>>1982-2002</option>
                     <option value="2003-2006"<?php echo $row['MainPropertyDate'] == "2003-2006" ? " selected" : ""; ?>>2003-2006</option>
                     <option value="2007-2011"<?php echo $row['MainPropertyDate'] == "2007-2011" ? " selected" : ""; ?>>2007-2011</option>
                     <option value="2012-2014"<?php echo $row['MainPropertyDate'] == "2012-2014" ? " selected" : ""; ?>>2012-2014</option>
                     <option value="2014-2020"<?php echo $row['MainPropertyDate'] == "2014-2020" ? " selected" : ""; ?>>2014-2020</option>
                     <option value="2020-onwards"<?php echo $row['MainPropertyDate'] == "2020-onwards" ? " selected" : ""; ?>>2020-onwards</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="property_date_tt"  >
                  <span class="tooltiptext" >
                  This gives an indication of relevant building regulations 
                  in force at the time of construction and of associated 
                  U values of the property.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Storeys</th>
               <td>
                  <select id="storeys" name="storeys" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="1"<?php echo $row['Storeys'] == "1" ? " selected" : ""; ?>>1</option>
                     <option value="2"<?php echo $row['Storeys'] == "2" ? " selected" : ""; ?>>2</option>
                     <option value="3"<?php echo $row['Storeys'] == "3" ? " selected" : ""; ?>>3</option>
                     <option value="4"<?php echo $row['Storeys'] == "4" ? " selected" : ""; ?>>4</option>
                     <option value="More than 4"<?php echo $row['Storeys'] == "More than 4" ? " selected" : ""; ?>>More than 4</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="numstoreys_tt">
                  <span class="tooltiptext" >
                  This refers to the number of storeys within the property 
                  rather than the storey that a flat might be located on.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>Number of Bedrooms</th>
               <td>
                  <select id="numBedrooms" name="numBedrooms" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="1"<?php echo $row['NumberBedrooms'] == "1" ? " selected" : ""; ?>>1</option>
                     <option value="2"<?php echo $row['NumberBedrooms'] == "2" ? " selected" : ""; ?>>2</option>
                     <option value="3"<?php echo $row['NumberBedrooms'] == "3" ? " selected" : ""; ?>>3</option>
                     <option value="4"<?php echo $row['NumberBedrooms'] == "4" ? " selected" : ""; ?>>4</option>
                     <option value="5"<?php echo $row['NumberBedrooms'] == "5" ? " selected" : ""; ?>>5</option>
                     <option value="More than 5"<?php echo $row['NumberBedrooms'] == "More than 5" ? " selected" : ""; ?>>More than 5</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Bedroom Occupancy</th>
               <td>
                  <select id="bedroomOccupancy" name="bedroomOccupancy" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Less than 1"<?php echo $row['BedroomOccupancy'] == "Less than 1" ? " selected" : ""; ?>>Less than 1</option>
                     <option value="1"          <?php echo $row['BedroomOccupancy'] == "1" ? " selected" : ""; ?>          >1</option>
                     <option value="More than 1"<?php echo $row['BedroomOccupancy'] == "More than 1" ? " selected" : ""; ?>>More than 1</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="bedroomOccupancy_tt"  >
                  <span class="tooltiptext" >
                  This provides an indication of usage patterns and associated
                  issues of humidity, IAQ and energy use in the house. Divide 
                  the number of occupants by the number of bedrooms to give 
                  occupancy rate.</span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Orientation</th>
               <td>
                  <select id="orientation" name="orientation" size=1 style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="North"     <?php echo $row['OrientationMainFrontElevation'] == "North" ? " selected" : ""; ?>     >North</option>
                     <option value="North East"<?php echo $row['OrientationMainFrontElevation'] == "North East" ? " selected" : ""; ?>>North East</option>
                     <option value="East"      <?php echo $row['OrientationMainFrontElevation'] == "East" ? " selected" : ""; ?>      >East</option>
                     <option value="South East"<?php echo $row['OrientationMainFrontElevation'] == "South East" ? " selected" : ""; ?>>South East</option>
                     <option value="South"     <?php echo $row['OrientationMainFrontElevation'] == "South" ? " selected" : ""; ?>     >South</option>
                     <option value="South West"<?php echo $row['OrientationMainFrontElevation'] == "South West" ? " selected" : ""; ?>>South West</option>
                     <option value="West"      <?php echo $row['OrientationMainFrontElevation'] == "West" ? " selected" : ""; ?>      >West</option>
                     <option value="North West"<?php echo $row['OrientationMainFrontElevation'] == "North West" ? " selected" : ""; ?>>North West</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="orientation_tt"  >
                  <span class="tooltiptext" >
                  To give an indication of options for renewable energy and 
                  to identify risks via wind driven rain & solar irradiation
                  (cf. reverse condensation). 
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>               
         </table>
         <br>         
         <br> 
         <div id="pagefooter" style="text-align:center;bottom: 0%;">
            <a href="getchoice.php" title="Home">Home</a>
            <input type="submit" value="Save Changes" name="submit">
            <a href="form12b.php" title="Form 12" >Next</a>	
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>  
      </div>
   <footer>
   </footer>
   </body>
</html>
