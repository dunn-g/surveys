<?php # CONNECT TO MySQL DATABASE.

   session_start();

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);

   if ($_SESSION[ 'SurveyorId' ] == 10 ){
      require( '../connectlaptop_db.php' ) ;
   } else {
      require( '../connect_db.php' );
   }      
   
   $propertyid = $_SESSION['property'];
   $uprn       = $_SESSION['uprn'];
   $address    = $_SESSION['address'];

   $qry = 'SELECT s.SurveyorFirstName, s.SurveyorLastName ' .
            'FROM aasurveyor s ' .
            'WHERE s.SurveyorId = "'. $_SESSION[ 'SurveyorId' ] .'"' ;
            
   echo "<br>";         
   $rslt = mysqli_query( $dbc , $qry ) ;
   #echo "<br>";         

   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $surveyorname = ($row['SurveyorFirstName'] . " " . $row['SurveyorLastName'] );
      
      mysqli_free_result( $rslt ) ;

   } 
   echo "<br>";         
#---------------------------------------------------------

   if (isset ($_POST['submit'])){

      header("location:CreateNewForm10.php");

      # set vars not selected 
      $dateadded = date("Y-m-d H:i:s");

      # set checkboxes if blank
      (!isset($_POST['partywallissue']))  ? $partywallissue  = 0   : $partywallissue  = 1;
      (!isset($_POST['righttolight']))    ? $righttolight    = 0   : $righttolight    = 1;
      (!isset($_POST['existingepc']))     ? $existingepc     = 0   : $existingepc     = 1;
      (!isset($_POST['conditionsurvey'])) ? $conditionsurvey = 0   : $conditionsurvey = 1;
      
      # set date textboxes if blank
      (!isset($_POST['inspectiondate'])) ? $inspectiondate  = date("Y-m-d H:i:s") : $inspectiondate  = date("Y-m-d H:i:s",strtotime(clean_input($_POST['inspectiondate'])));
      (!isset($_POST['epcdate']))        ? $epcdate         = DBNull.value        : $epcdate         = date("Y-m-d",strtotime($_POST['epcdate']));
      
      # set numeric textboxes if blank
      (!isset($_SESSION[ 'SurveyorId' ])) ? $surveyorid = '99' : $surveyorid       = clean_input($_SESSION['SurveyorId']);

      # set dropdowns if blank
      (!isset($_POST['floodrisk']))               ? $floodrisk               = '' : $floodrisk               = clean_input($_POST['floodrisk']);
      (!isset($_POST['exposurerating']))          ? $exposurerating          = '' : $exposurerating          = clean_input($_POST['exposurerating']);
      (!isset($_POST['retrofitadvisorfeedback'])) ? $retrofitadvisorfeedback = '' : $retrofitadvisorfeedback = clean_input($_POST['retrofitadvisorfeedback']);
      (!isset($_POST['tenure']))                  ? $tenure                  = '' : $tenure                  = clean_input($_POST['tenure']);
      (!isset($_POST['propertyType']))            ? $propertyType            = '' : $propertyType            = clean_input($_POST['propertyType']);
      (!isset($_POST['propertyStyle']))           ? $propertyStyle           = '' : $propertyStyle           = clean_input($_POST['propertyStyle']);
      (!isset($_POST['propertyposition']))        ? $propertyposition        = '' : $propertyposition        = clean_input($_POST['propertyposition']);
      (!isset($_POST['propertyDate']))            ? $propertyDate            = '' : $propertyDate            = clean_input($_POST['propertyDate']);
      (!isset($_POST['storeys']))                 ? $storeys                 = '' : $storeys                 = clean_input($_POST['storeys']);
      (!isset($_POST['numBedrooms']))             ? $numBedrooms             = '' : $numBedrooms             = clean_input($_POST['numBedrooms']);
      (!isset($_POST['bedroomOccupancy']))        ? $bedroomOccupancy        = '' : $bedroomOccupancy        = clean_input($_POST['bedroomOccupancy']);
      (!isset($_POST['orientation']))             ? $orientation             = '' : $orientation             = clean_input($_POST['orientation']);

      $sql = "INSERT INTO basicinfo ".      
               "(Inspectiondate, FloodRisk, ExposureRating, PartyWallIssue, PartyWallNotes, RightToLight, RightToLightNotes " . 
               ", ExistingEPC, EPCDate, ConditionSurvey, ConditionSurveyNotes, RetrofitAdvisorFeedback " .
               ", SurveyorID, Tenure, PropertyType, PropertyStyle, PropertyPosition, MainPropertyDate " . 
               ", Storeys, NumberBedrooms, BedroomOccupancy, OrientationMainFrontElevation, DateAdded) " .
               "VALUES('" . $inspectiondate ."', '". $floodrisk ."', '". $exposurerating ."', ". $partywallissue . 
               ",  '". $partywallnotes ."', ". $righttolight .", '". $righttolightnotes ."', ". $existingepc . 
               ",  '". $epcdate ."', ". $conditionsurvey .", '". $conditionsurveynotes ."', '". $retrofitadvisorfeedback .
               "',  ". $surveyorid  .", '". $tenure ."', '". $propertyType ."', '". $propertyStyle ."', '". $propertyposition . 
               "', '". $propertyDate ."', '". $storeys ."', '". $numBedrooms ."', '". $bedroomOccupancy ."', '". $orientation .
               "', '". $dateadded . "')";
               
      #echo "<br>";   
      #print_r($_POST);
      #echo "<br>";         
      #echo "<br>";         
      #print_r($sql);
      #echo "<br>";  
      #echo "<br>";         
      
      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
         sleep(1);
         #echo "<meta http-equiv='refresh' content='0'>";
         # call function to build all tables ** or here!
         $newsurveyid = mysqli_insert_id($dbc);
         #print_r($newsurveyid);
         buildAllTables($dbc, $newsurveyid, $propertyid) ;
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($dbc);
      }
      
      $_SESSION['survey'] = $newsurveyid;

      # Close the connection.
      mysqli_close( $dbc ) ;
   }

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function buildAllTables($dbc, $newsurveyid, $propertyid) {
   # find latest survey id  
      #use LAST_INSERT_ID() last value stored in auto_increment column
   # loop thru' all survey tables writing new id to each one 
    
   $selquery = "SELECT table_name 
         FROM information_schema.tables 
         WHERE table_schema = 'surveys' AND table_name NOT LIKE 'aa%' AND table_name NOT LIKE 'basicinfo' ";
    
   $selresult = mysqli_query( $dbc, $selquery ) 
                or die("Couldn't execute query");
   #$selrow = mysqli_fetch_array( $selresult , MYSQLI_ASSOC );
   #print_r($selresult);   
   if ( $selresult )
   {
      while ( $selrow = mysqli_fetch_array( $selresult , MYSQLI_ASSOC ) ) 
      {
         # insert new survey id into each table
         $tablename = $selrow['table_name' ];
         $insquery = "INSERT INTO `$tablename`(surveyid) VALUES ($newsurveyid) ";

         if (mysqli_query( $dbc , $insquery )) {
            echo "'$tablename' updated successfully";
         } else {
           echo "Error: " . $insquery . "<br>" . mysqli_error($dbc);
         }
      }
      # add code for insering into property survey link
      $pslquery = "INSERT INTO aapropertysurveylink(PropertyId, SurveyId) VALUES ($propertyid, $newsurveyid) ";
      if (mysqli_query( $dbc , $pslquery )) {
         echo "Property Survey Link updated successfully";
      } else {
        echo "Error: " . $pslquery . "<br>" . mysqli_error($dbc);
      }
      
  } else { echo '<p>' . mysqli_error( $dbc ) . '</p>'  ; }
}

#---------------------------------------------------------   
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 10</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>

      <header>
         <h2><strong><em>New Survey - Basic Property Information</em></strong></h2>
         <h4><pre>Id: <?php echo $propertyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h4>
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
           
           hideEPCdate() ;
           showEPCdate() ;
           
           hideConditionSurveyNotes() ;
           showConditionSurveyNotes() ;
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

      <!-- Page content -->
      <div class="main" style="display:table;">
      <form id="form10" method="POST" >
         <table class="" style="border: 0">
            <tr>
               <th>Inspection Date</th>
               <td><input type="date" name="inspectiondate" value="" ></td>
            </tr>
            <tr>
               <th>Surveyor</th>
               <td>
                  <input type="text" name="surveyor_name" value="<?php echo $surveyorname ?>">
               </td>
            </tr>
            <tr>
               <th>Flood Risk</th>
               <td>
                  <select id="floodrisk" name="floodrisk" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"        >None</option>
                     <option value="Flood Zone 1">Flood Zone 1</option>
                     <option value="Flood Zone 2">Flood Zone 2</option>
                     <option value="Flood Zone 3">Flood Zone 3</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="floodrisk_tt"  >
                  <span class="tooltiptext" style="width: 450px;margin-left=0px;padding-left=0px;">
NRW, Environment Agency, SEPA or DfI.<br>
Flood Zone 1: 0.1% risk<br>
Flood Zone 2: 0.1 - 1% risk river, 0.1 - 0.5% risk sea<br>
Flood Zone 3: >1% risk river, >0.5% risk sea
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Exposure Rating</th>
               <td>
                  <select id="exposureRating" name="exposurerating" size="1" style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Sheltered"  >Sheltered</option>
                     <option value="Moderate"   >Moderate</option>
                     <option value="Severe"     >Severe</option>
                     <option value="Very Severe">Very Severe</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="exposureRating_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  CATEGORIES OF EXPOSURE TO WIND DRIVEN RAIN from NHBC or BRE
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Party Wall Issue</th>
               <td>
                  <input type="checkbox" id="partywallissueChk" onchange="showPartyWallNotes()" name="partywallissue" >
               </td>
               <th id="partywallnotesHdr" > &nbsp; &nbsp; PartyWallNotes</th>
               <td>
                  <input type="text" id="partywallnotesSel" name="partywallnotes" >
               </td>
            </tr>
            <tr>
               <th>Right to Light Issue</th>
               <td>
                  <input type="checkbox" id="righttolightChk" onchange="showRightToLightNotes()" name="righttolight" >
               </td>
               <th id="righttolightHdr" > &nbsp; &nbsp; Right to Light Notes</th>
               <td>
                  <input type="text" id="righttolightSel" name="righttolightnotes" >
               </td>
            </tr>
            <tr>
               <th>Existing EPC</th>
               <td>
                  <input type="checkbox" id="existingepcChk" onchange="showEPCdate()" name="existingepc" >
               </td>
               <td>
               <div class="tooltip" id="retrofitadvisorfeedback_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Check EPC Register 
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
               <th id="existingepcHdr" > &nbsp; &nbsp; EPC Date </th>
               <td>
                  <input type="date" id="existingepcSel" name="existingepcdate" >
               </td>
            </tr>
            <tr>
               <th>Condition Survey Available</th>
               <td>
                  <input type="checkbox" id="conditionsurveyChk" onchange="showConditionSurveyNotes()" name="conditionsurvey" >
               </td>
               <th id="conditionsurveyHdr" > &nbsp; &nbsp; Condition Survey Notes</th>
               <td>
                  <input type="text" id="conditionsurveySel" name="conditionsurveynotes" >
               </td>
            </tr>
            <tr>
               <th>Retrofit Advisor feedback</th>
               <td>
                  <select id="retrofitadvisorfeedback" name="retrofitadvisorfeedback" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Energy Reduction"    >Energy Reduction</option>
                     <option value="Cost Reduction"      >Cost Reduction</option>
                     <option value="Damp solution"       >Damp solution</option>
                     <option value="IAQ solution"        >IAQ solution</option>
                     <option value="Mould solution"      >Mould solution</option>
                     <option value="Comfort improvemment">Comfort improvemment</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="retrofitadvisorfeedback_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This provides context including any EEM requirements (eg. MEES)
                  and opportunities for owner with regard towards incentives.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Tenure</th>
               <td>
                  <select id="tenure" name="tenure" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Owner Occupier"           >Owner Occupier</option>
                     <option value="Rented (Social)"          >Rented (Social)</option>
                     <option value="Rented (Private)"         >Rented (Private)</option>
                     <option value="Joint Property Ownership" >Joint Property Ownership</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="tenure_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
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
                  <select id="propertyTypeSlct" onchange="showPropertyPosition()" name="propertyType" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="House"     >House</option>
                     <option value="Flat"      >Flat</option>
                     <option value="Maisonette">Maisonette</option>
                     <option value="Bungalow"  >Bungalow</option>
                     <option value="Park Home" >Park Home</option>
                  </select>
               </td>
               <th id="propertypositionhdr">Property Position</th>
               <td>
                  <select id="propertypositionsel" name="propertyposition" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Basement"    >Basement</option>
                     <option value="Ground Floor">Ground Floor</option>
                     <option value="Mid Floor"   >Mid Floor</option>
                     <option value="Top Floor"   >Top Floor</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Property Style</th>
               <td>
                  <select id="propertyStyle" name="propertyStyle" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Detached"     >Detached</option>
                     <option value="Semi-Detached">Semi-Detached</option>
                     <option value="Mid Terrace"  >Mid Terrace</option>
                     <option value="End Terrace"  >End Terrace</option>
                     <option value="Enclosed Mid-Terrace">Enclosed Mid-Terrace</option>
                     <option value="Enclosed End-Terrace">Enclosed End-Terrace</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Property Date</th>
               <td>
                  <select id="propertyDate" name="propertyDate" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Pre 1919" >Pre 1919</option>
                     <option value="1920-1945">1920-1945</option>
                     <option value="1946-1965">1946-1975</option>
                     <option value="1966-1975">1966-1975</option>
                     <option value="1976-1981">1976-1981</option>
                     <option value="1982-2002">1982-2002</option>
                     <option value="2003-2006">2003-2006</option>
                     <option value="2007-2011">2007-2011</option>
                     <option value="2012-2014">2012-2014</option>
                     <option value="2014-2020">2014-2020</option>
                     <option value="2020-onwards">2020-onwards</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="property_date"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
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
                  <select id="storeys" name="storeys" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                     <option value="More than 4">More than 4</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="numstoreys"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
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
                  <select id="numBedrooms" name="numBedrooms" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                     <option value="5">5</option>
                     <option value="More than 5">More than 5</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Bedroom Occupancy</th>
               <td>
                  <select id="bedroomOccupancy" name="bedroomOccupancy" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Less than 1">Less than 1</option>
                     <option value="1"          >1</option>
                     <option value="More than 1">More than 1</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="bedroomOccupancy"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
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
                  <select id="orientation" name="orientation" size=1 style="width: 160px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="North"     >North</option>
                     <option value="North East">North East</option>
                     <option value="East"      >East</option>
                     <option value="South East">South East</option>
                     <option value="South"     >South</option>
                     <option value="South West">South West</option>
                     <option value="West"      >West</option>
                     <option value="North West">North West</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="bedroomOccupancy"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
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
      <br>         
      <br> 
      <div id="pagefooter" >
         <a href="getchoice.php" title="Home">Home</a>
         <input type="submit" value="Save" name="submit">
         <a href="form12.php" title="Form 12" >Next</a>		
      </div>
      </form>  
      </div> 
   <footer>
   
   </footer>
   </body>
</html>
