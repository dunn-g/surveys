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

   $q = 'SELECT * FROM significancepathology WHERE SurveyId = "'.$surveyid.'"' ;
   $r = mysqli_query( $dbc , $q ) ;
#if maybe needed here! check no of rows returned!
   if ($r) {
      $row01 = mysqli_fetch_array( $r , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $r ) ;

   } 
   $heritageAry = array();
   $heritageAry = explode(',',$row['HeritageStatus']);
   $significanceAry = array();
   $significanceAry = explode(',',$row['Significance']);
   echo "<br>";         

#-------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form12.php");

      # set checkboxes if blank

      # set dropdowns if blank
      (!isset($_POST['significantStreet'])) ? $significantStreet = '' : $significantStreet = $_POST['significantStreet'];
      (!isset($_POST['heritagestatus']))    ? $heritage          = '' : $heritage          = implode(',', $_POST['heritagestatus']);
      (!isset($_POST['significance']))      ? $signif            = '' : $signif            = implode(',', $_POST['significance']);

      # set notes if blank
      (!isset($_POST['significancenotes'])) ? $significancenotes = '' : $significancenotes = $_POST['significancenotes'];
      (!isset($_POST['buildingchanges']))   ? $buildingchanges   = '' : $buildingchanges   = $_POST['buildingchanges'];

      # these need to be set to cope with slightly different logic
      if (!isset($_POST['occupierinfo'])) {
         $occupierinfo = 0;
         $occupierinfonotes = '';
      } else {
         $occupierinfo = 1;
         $occupierinfonotes = $_POST['occupierinfonotes'];
      }
      
      $sql = "UPDATE significancepathology ".      
               " SET SignificanceStreetscape='" . clean_input($significantStreet) .
               "', HeritageStatus='"            . clean_input($heritage) .
               "', Significance='"              . clean_input($signif) .
               "', SignificanceNotes='"         . clean_input($significancenotes) .
               "', BuildingChanges='"           . clean_input($buildingchanges) .
               "', OccupierInfo="               . clean_input($occupierinfo) .
               ",  OccupierInfoNotes='"         . clean_input($occupierinfonotes) .
               "' WHERE surveyId=" . $surveyid;
      
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

#-------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 12</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Significance and Pathology</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>

      <style>
      </style>

      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideOccupierInfoNotes();
           showOccupierInfoNotes();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
         
         function hideOccupierInfoNotes() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("occinfonotesSel");
             obj2 = document.getElementById("occinfonotesHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj1.value = "";
         }

         function showOccupierInfoNotes() {
             if (!document.getElementById) return;
             oisobj = document.getElementById("occupierinfoChk");
             if (oisobj.checked == true){
                obj1 = document.getElementById("occinfonotesSel");
                obj2 = document.getElementById("occinfonotesHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideOccupierInfoNotes();
             }
         }         
      </script>

   </head>
   <body>
      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div> 
      <!--end of Navigation bar-->

      <div>
      <aside class="info1" name="pathology">
         <p align=left><strong>* Info!</strong><br>
            These spaces are for notes on the pathology
            of the building. <br>Use photographic evidence
            wherever possible to back up statements.
         </p>
      </aside>
      </div>
      <!-- Page content &#128712; &#8505; &#9432 and &#x1F6C8 -->

      <div class="main" style="display:table;">
      <form id="form12" method="POST" action="" >
         <table class="formtwelve" style="border: 0">
            <tr>
               <th>
                  Streetscape
               </th>
               <td>
                  <select id="significantStreet" name="significantStreet" size=1 >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Not Significant"            <?php echo $row['SignificanceStreetscape'] == "Not Significant" ? " selected" : ""; ?>              >Not Significant</option>
                     <option value="Part of streetscene"        <?php echo $row['SignificanceStreetscape'] == "Part of streetscene" ? " selected" : ""; ?>        >Part of streetscene</option>
                     <option value="Significant local landscape"<?php echo $row['SignificanceStreetscape'] == "Significant local landscape" ? " selected" : ""; ?>>Significant local landscape</option>
                     <option value="Significant building"       <?php echo $row['SignificanceStreetscape'] == "Significant building" ? " selected" : ""; ?>       >Significant building</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="SignificantStreetscape_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To give an indication of the building in its setting 
                  both within the urban and natural environment</span>
                  <p style="font-size : 16"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>
                  Heritage Status
               </th>
               <td>
                  <select id="heritagestatus" name="heritagestatus[]" multiple="multiple" size=4 >
                     <option value="" disabled >Please choose...</option>
                     <option value="Unlisted"           <?php echo (isset($heritageAry) && in_array('Unlisted', $heritageAry))            ? " selected" : ""; ?>>Unlisted</option>
                     <option value="Locally Listed"     <?php echo (isset($heritageAry) && in_array('Locally Listed', $heritageAry))      ? " selected" : ""; ?>>Locally Listed</option>
                     <option value="Nationally Listed"  <?php echo (isset($heritageAry) && in_array('Nationally Listed', $heritageAry))   ? " selected" : ""; ?>>Nationally Listed</option>
                     <option value="Conservation Area"  <?php echo (isset($heritageAry) && in_array('Conservation Area', $heritageAry))   ? " selected" : ""; ?>>Conservation Area</option>
                     <option value="World Heritage Site"<?php echo (isset($heritageAry) && in_array('World Heritage Site', $heritageAry)) ? " selected" : ""; ?>>World Heritage Site</option>
                     <option value="AONB"               <?php echo (isset($heritageAry) && in_array('AONB', $heritageAry))                ? " selected" : ""; ?>>AONB</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>
                  Significance
               </th>
               <td>
                  <select id="significance" name="significance[]" multiple="multiple" size=4 >
                     <option value="" disabled >Please choose...</option>
                     <option value="N/A"       <?php echo (isset($significanceAry) && in_array('N/A', $significanceAry))        ? " selected" : ""; ?>>N/A</option>
                     <option value="Historic"  <?php echo (isset($significanceAry) && in_array('Historic', $significanceAry))   ? " selected" : ""; ?>>Historic</option>
                     <option value="Aesthetic" <?php echo (isset($significanceAry) && in_array('Aesthetic', $significanceAry))  ? " selected" : ""; ?>>Aesthetic</option>
                     <option value="Evidential"<?php echo (isset($significanceAry) && in_array('Evidential', $significanceAry)) ? " selected" : ""; ?>>Evidential</option>
                     <option value="Communal"  <?php echo (isset($significanceAry) && in_array('Communal', $significanceAry))   ? " selected" : ""; ?>>Communal</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="significance_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To identify the reason for significance in line with 
                  CADW, HES, HBC and Historic England’s guidelines. 
                  Remember that the property may require a full Heritage 
                  Impact Assessment or at the very least the Assessment 
                  of Significance form to be completed.</span>
                  <p style="font-size : 16"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th title="inbuilt tooltip" >Significance Notes *</th>
               <td>
                  <textarea name="significancenotes" cols="30" rows="3" ><?php echo $row['SignificanceNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Building Changes *</th>
               <td>
                  <textarea name="buildingchanges" cols="30" rows="3" ><?php echo $row['BuildingChanges']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="buildingchanges_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Write up brief notes on the pathology of the building 
                  (alterations, additions etc ) that might influence the 
                  Retrofit proposals.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Occupier info supplied</th>
               <td>
                  <input type="checkbox" class="chk" id="occupierinfoChk" name="occupierinfo" onchange="showOccupierInfoNotes()" value="<?php echo ($row['OccupierInfo']=='1' ? '1' : '0');?>" <?php echo ($row['OccupierInfo']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="occupierinfo_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This is to indicate whether the occupier has supplied any information
                  on the property that is important. Bills, Guarantees, etc. This allows
                  the surveyor to record the data given by the occupier, either a record
                  of files given, or any relevant data from seen docs. Pictures should
                  also be taken of the information if it is not possible to keep copies.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
         </table>
         <div class="query" id="occinfonotesDiv">
            <label for="occinfonotesSel" id="occinfonotesHdr">Occupier Info Notes &nbsp;</label>
            <span>
<!--               <input type="text" id="occinfonotesSel" name="occupierinfonotes" value="<?php echo $row['OccupierInfoNotes']?>">-->
               <textarea id="occinfonotesSel" name="occupierinfonotes" value="<?php echo $row['OccupierInfoNotes']?>"></textarea>
            </span>
         </div>
  
<!--
      <br>         
      <br>         
      <br>         
      <br> 
      <div id="pagefooter" >
         <a href="form10.php" title="Form 10">Previous</a>		
         <input type="submit" value="Save Changes" name="submit">
         <a href="form14.php" title="Form 14">Next</a>		
      </div>
-->
         <div class='pagefooter'>
            <!--Always at bottom!-->
            <a href="form10.php" title="Form 10">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a href="form14.php" title="Form 14">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>   
      </div>
<!--
      <div class="cpyrght" >
      <br><small>&copy; <em>STBA 2020</em></small>
      </div>
-->      
   <footer>
   </footer>  
   </body>
</html>
