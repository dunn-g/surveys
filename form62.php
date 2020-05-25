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

   $q = 'SELECT * FROM floors WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $f2typeAry = array();
   $f2typeAry = explode(',',$row['Floor2Type']);

   $f2instypeAry = array();
   $f2instypeAry = explode(',',$row['Floor2InslatnType']);

   $f2insdepthAry = array();
   $f2insdepthAry = explode(',',$row['Floor2InslatnDepth']);
#---------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form62.php");

      # set checkboxes if blank
      (!isset($_POST['floor2significant'])) ? $floor2significant  = 0 : $floor2significant  = 1;
      (!isset($_POST['floor2inslatn']))     ? $floor2inslatn      = 0 : $floor2inslatn      = 1;
      
      # set dropdowns if blank
      (!isset($_POST['floor2retrofit']))     ? $floor2retrofit = '' : $floor2retrofit = $_POST['floor2retrofit'];
      (!isset($_POST['floor2location']))     ? $floor2location = '' : $floor2location = $_POST['floor2location'];
      (!isset($_POST['floor2type']))         ? $flr2type       = '' : $flr2type       = implode(',', $_POST['floor2type']);
      (!isset($_POST['floor2inslatndepth'])) ? $flr2insdepth   = '' : $flr2insdepth   = implode(',', $_POST['floor2inslatndepth']);
      (!isset($_POST['floor2inslatntype']))  ? $flr2instype    = '' : $flr2instype    = implode(',', $_POST['floor2inslatntype']);

      # set notes if blank
      (!isset($_POST['floor2notes'])) ? $floor2notes = '' : $floor2notes = $_POST['floor2notes'];

      $sql = "UPDATE floors ".      
               " SET Floor2Significant=" . clean_input($floor2significant) .
               ", Floor2Retrofit='"      . clean_input($floor2retrofit) .
               "', Floor2Location='"     . clean_input($floor2location) .
               "', Floor2Type='"         . clean_input($flr2type) .
               "', Floor2Inslatn='"      . clean_input($floor2inslatn) . 
               "', Floor2InslatnDepth='" . clean_input($flr2insdepth) . 
               "', Floor2InslatnType='"  . clean_input($flr2instype) .
               "', Floor2Notes='"        . clean_input($floor2notes) .
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
#---------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 62</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Other Floors</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideFlr2InslatnQueries();
           ShowFlr2InslatnQueries();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideFlr2InslatnQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("flr2inslatndepthSel");
             obj2 = document.getElementById("flr2inslatndepthHdr");
             obj3 = document.getElementById("flr2inslatntypeSel");
             obj4 = document.getElementById("flr2inslatntypeHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
         }

         function ShowFlr2InslatnQueries() {
             if (!document.getElementById) return;
             gfiobj = document.getElementById("floor2inslatnChk");
             if (gfiobj.checked == true){
                obj1 = document.getElementById("flr2inslatndepthSel");
                obj2 = document.getElementById("flr2inslatndepthHdr");
                obj3 = document.getElementById("flr2inslatntypeSel");
                obj4 = document.getElementById("flr2inslatntypeHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
             } else {
               hideFlr2InslatnQueries();
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
               <th>Floor 2 Significant</th>
               <td>
                  <input type="checkbox"  name="floor2significant" value="<?php echo ($row['Floor2Significant']=='1' ? '1' : '0');?>" <?php echo ($row['Floor2Significant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Floor 2 Retrofit</th>
               <td>
                  <select id="floor2retrofit" name="floor2retrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['Floor2Retrofit'] == "Red"           ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['Floor2Retrofit'] == "Amber"         ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['Floor2Retrofit'] == "Green"         ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['Floor2Retrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Floor 2 Location</th>
               <td>
                  <select id="floor2location" name="floor2location" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Above Soil"          <?php echo $row['Floor2Location'] == "Above Soil"           ? " selected" : ""; ?>>Above Soil</option>
                     <option value="Above heated space"  <?php echo $row['Floor2Location'] == "Above heated space"   ? " selected" : ""; ?>>Above heated space</option>
                     <option value="Above unheated space"<?php echo $row['Floor2Location'] == "Above unheated space" ? " selected" : ""; ?>>Above unheated space</option>
                     <option value="To external air"     <?php echo $row['Floor2Location'] == "To external air"      ? " selected" : ""; ?>>To external air</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Floor 2 Type</th>
               <td>
                  <select id="floor2type" name="floor2type[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Solid floor original"    <?php echo (isset($f2typeAry) && in_array('Solid floor original', $f2typeAry))    ? " selected" : ""; ?>>Solid floor original</option>
                     <option value="Solid floor replacement" <?php echo (isset($f2typeAry) && in_array('Solid floor replacement', $f2typeAry)) ? " selected" : ""; ?>>Solid floor replacement</option>
                     <option value="Suspended timber"        <?php echo (isset($f2typeAry) && in_array('Suspended timber', $f2typeAry))        ? " selected" : ""; ?>>Suspended timber</option>
                     <option value="Suspended other"         <?php echo (isset($f2typeAry) && in_array('Suspended other', $f2typeAry))         ? " selected" : ""; ?>>Suspended other</option>
                     <option value="Unknown"                 <?php echo (isset($f2typeAry) && in_array('Unknown', $f2typeAry))                 ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Floor 2 Insulation</th>
               <td>
                  <input type="checkbox" id="floor2inslatnChk" name="floor2inslatn" onchange="ShowFlr2InslatnQueries()" value="<?php echo ($row['Floor2Inslatn']=='1' ? '1' : '0');?>" <?php echo ($row['Floor2Inslatn']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th id="flr2inslatndepthHdr">Floor 2 Insulation Thickness</th>
               <td>
                  <select id="flr2inslatndepthSel" name="floor2inslatndepth[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="As Built"<?php echo (isset($f2insdepthAry) && in_array('As Built', $f2insdepthAry)) ? " selected" : ""; ?>>As Built</option>
                     <option value="50mm"    <?php echo (isset($f2insdepthAry) && in_array('50mm', $f2insdepthAry))     ? " selected" : ""; ?>>50mm</option>
                     <option value="100mm"   <?php echo (isset($f2insdepthAry) && in_array('100mm', $f2insdepthAry))    ? " selected" : ""; ?>>100mm</option>
                     <option value="150mm"   <?php echo (isset($f2insdepthAry) && in_array('150mm', $f2insdepthAry))    ? " selected" : ""; ?>>150mm</option>
                     <option value="Unknown" <?php echo (isset($f2insdepthAry) && in_array('Unknown', $f2insdepthAry))  ? " selected" : ""; ?>>Unknown</option>
                  </select>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="flr2inslatntypeHdr">Floor 2 Inslatn Type</th>
               <td>
                  <select id="flr2inslatntypeSel" name="floor2inslatntype[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="EPS/XPS"                  <?php echo (isset($f2instypeAry) && in_array('EPS/XPS', $f2instypeAry))                  ? " selected" : ""; ?>>EPS/XPS</option>
                     <option value="Phenolic/PIR/PUR"         <?php echo (isset($f2instypeAry) && in_array('Phenolic/PIR/PUR', $f2instypeAry))         ? " selected" : ""; ?>>Phenolic/PIR/PUR</option>
                     <option value="Mineral wool/Glass fibre" <?php echo (isset($f2instypeAry) && in_array('Mineral wool/Glass fibre', $f2instypeAry)) ? " selected" : ""; ?>>Mineral wool/Glass fibre</option>
                     <option value="Natural insulation"       <?php echo (isset($f2instypeAry) && in_array('Natural insulation', $f2instypeAry))       ? " selected" : ""; ?>>Natural insulation</option>
                     <option value="Other"                    <?php echo (isset($f2instypeAry) && in_array('Other', $f2instypeAry))                    ? " selected" : ""; ?>>Other</option>
                  </select>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Floor 2 Notes</th>
               <td>
                  <textarea name="floor2notes" rows="2" cols="30"><?php echo $row['Floor2Notes']?></textarea>
               </td>
            </tr> 
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form60.php" title="Form 60">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form70.php" title="Form 70">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
