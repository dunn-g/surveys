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

   $q = 'SELECT * FROM postassessmentsummary  WHERE SurveyId = "'.$surveyid.'"' ;

   $r = mysqli_query( $dbc , $q ) ;

   $row = mysqli_fetch_array( $r , MYSQLI_ASSOC );
#-------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form95.php");

      # set checkboxes if blank
      (!isset($_POST['bs7913survey']))      ? $bs7913survey      = 0 : $bs7913survey      = 1;
      (!isset($_POST['servicessurvey']))    ? $servicessurvey    = 0 : $servicessurvey    = 1;
      (!isset($_POST['structuralsurvey']))  ? $structuralsurvey  = 0 : $structuralsurvey  = 1;
      (!isset($_POST['ventilationsurvey'])) ? $ventilationsurvey = 0 : $ventilationsurvey = 1;
      (!isset($_POST['othersurvey']))       ? $othersurvey       = 0 : $othersurvey       = 1;
      
      # set textboxes if blank
      (!isset($_POST['typesurveyreqd'])) ? $typesurveyreqd = '' : $typesurveyreqd = $_POST['typesurveyreqd'];

      # set dropdowns if blank
      (!isset($_POST['technicalrisk'])) ? $technicalrisk = '' : $technicalrisk = $_POST['technicalrisk'];
      (!isset($_POST['paspathway']))    ? $paspathway    = '' : $paspathway    = $_POST['paspathway'];

      # set notes if blank
      (!isset($_POST['postassessnotes'])) ? $postassessnotes = '' : $postassessnotes = $_POST['postassessnotes'];

      # these need to be set to cope with slightly different logic
/*
      if (!isset($_POST['firealarm'])) {
         $firealarm = 0;
         $firealarmpower = '';
      } else {
         $firealarm = 1;
         $firealarmpower = $_POST['firealarmpower'];
      }

      if (!isset($_POST['othersurvey'])) {
         $othersurvey = 0;
      } else {
         $othersurvey = 1;
         $roomsservedbyco2alarm = $_POST['roomsservedbyco2alarm'];
      } 
*/
      $sql = "UPDATE postassessmentsummary ".      
               " SET BS7913Survey="    . clean_input($bs7913survey) .
               ",  StructuralSurvey="  . clean_input($structuralsurvey) .
               ",  ServicesSurvey="    . clean_input($servicessurvey) . 
               ",  VentilationSurvey=" . clean_input($ventilationsurvey) .
               ",  OtherSurvey="       . clean_input($othersurvey) .
               ",  TypeSurveyReqd='"   . clean_input($typesurveyreqd) .
               "', TechnicalRisk='"    . clean_input($technicalrisk) .
               "', PASPathway='"       . clean_input($paspathway) .
               "', PostAssessNotes='"  . clean_input($postassessnotes) .
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

?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 90</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Post Assessment Summary</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideTypeSurveyReqd();
           showTypeSurveyReqd();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
          
         function hideTypeSurveyReqd() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("typesurveyreqdSel");
             obj2 = document.getElementById("typesurveyreqdHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showTypeSurveyReqd() {
             if (!document.getElementById) return;
             othersurveyObj = document.getElementById("othersurveyChk");
             if (othersurveyObj.checked == true ){
                obj1 = document.getElementById("typesurveyreqdSel");
                obj2 = document.getElementById("typesurveyreqdHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideTypeSurveyReqd();
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
               <th id="surveyRequired" >BS7913 Survey Required?</th>
               <td>
                  <input type="checkbox" name="bs7913survey" value="<?php echo ($row['BS7913Survey']=='1' ? '1' : '0');?>" <?php echo ($row['BS7913Survey']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="bs7913survey_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  A basic Significance survey will need to be undertaken along with 
                  this survey, however a more in-depth Heritage Impact Assessment 
                  may be required.                  
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Structural Survey Required</th>
               <td>
                  <input type="checkbox" name="structuralsurvey" value="<?php echo ($row['StructuralSurvey']=='1' ? '1' : '0');?>" <?php echo ($row['StructuralSurvey']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="structuralsurvey_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This section covers rot, mould, damp and structural survey requirements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>               
            <tr>
               <th>Ventilation Survey Required</th>
               <td>
                  <input type="checkbox"  name="ventilationsurvey" value="<?php echo ($row['VentilationSurvey']=='1' ? '1' : '0');?>" <?php echo ($row['VentilationSurvey']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="ventilationsurvey_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The ventilation system will ideally be balanced
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>               
            <tr>
               <th>Services Survey Required</th>
               <td>
                  <input type="checkbox"  name="servicessurvey" value="<?php echo ($row['ServicesSurvey']=='1' ? '1' : '0');?>" <?php echo ($row['ServicesSurvey']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Other Survey Required</th>
               <td>
                  <input type="checkbox"  id="othersurveyChk" name="othersurvey" onchange="showTypeSurveyReqd()" value="<?php echo ($row['OtherSurvey']=='1' ? '1' : '0');?>" <?php echo ($row['OtherSurvey']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <th id="typesurveyreqdHdr">Type Required</th>
               <td>
                  <input type="text" id="typesurveyreqdSel" name="typesurveyreqd" value="<?php echo ($row['TypeSurveyReqd'])?>">
               </td>
            </tr>
            <tr>
               <th>Inherent Technical Risk(from table B2.)</th>
               <td>
                  <select id="technicalrisk" name="technicalrisk" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="1" <?php echo $row['TechnicalRisk'] == "1" ? " selected" : ""; ?>>1</option>
                     <option value="2" <?php echo $row['TechnicalRisk'] == "2" ? " selected" : ""; ?>>2</option>
                     <option value="3" <?php echo $row['TechnicalRisk'] == "3" ? " selected" : ""; ?>>3</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>PAS Pathway</th>
               <td>
                  <select id="paspathway" name="paspathway" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="A" <?php echo $row['PASPathway'] == "A" ? " selected" : ""; ?>>A</option>
                     <option value="B" <?php echo $row['PASPathway'] == "B" ? " selected" : ""; ?>>B</option>
                     <option value="C" <?php echo $row['PASPathway'] == "C" ? " selected" : ""; ?>>C</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Post Assessment Notes</th>
               <td>
                  <textarea name="postassessnotes" rows="2" cols="30"><?php echo $row['PostAssessNotes']?></textarea>
               </td>
            </tr>               
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form93.php" title="Form 93">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form10.php" title="Form 10">First</a>
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
