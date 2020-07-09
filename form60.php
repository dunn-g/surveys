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

   $gftypeAry = array();
   $gftypeAry = explode(',',$row['GroundFloorType']);

   $gfinstypeAry = array();
   $gfinstypeAry = explode(',',$row['GrndFlrInslatnType']);

   $gfinsdepthAry = array();
   $gfinsdepthAry = explode(',',$row['GrndFlrInslatnDepth']);
#-------------------------------------------------------------   

   if (isset ($_POST['submit'])){
      
      header("location:form60.php");

      # set checkboxes if blank
      (!isset($_POST['groundfloorsignificant'])) ? $groundfloorsignificant  = 0 : $groundfloorsignificant  = 1;
      (!isset($_POST['groundfloorinsulation']))  ? $groundfloorinsulation   = 0 : $groundfloorinsulation   = 1;
      
      # set dropdowns if blank
      (!isset($_POST['groundfloorretrofit']))   ? $groundfloorretrofit   = '' : $groundfloorretrofit   = $_POST['groundfloorretrofit'];
      (!isset($_POST['groundfloorlocation']))   ? $groundfloorlocation   = '' : $groundfloorlocation   = $_POST['groundfloorlocation'];
      (!isset($_POST['groundfloortype']))       ? $grndflrtype           = '' : $grndflrtype           = implode(',', $_POST['groundfloortype']);
      (!isset($_POST['grndflrinslatndepth']))   ? $gfinsdepth            = '' : $gfinsdepth            = implode(',', $_POST['grndflrinslatndepth']);
      (!isset($_POST['grndflrinslatntype']))    ? $gfinstype             = '' : $gfinstype             = implode(',', $_POST['grndflrinslatntype']);

      # set notes if blank
      (!isset($_POST['groundfloornotes'])) ? $groundfloornotes = '' : $groundfloornotes = $_POST['groundfloornotes'];

      $sql = "UPDATE floors ".      
               " SET GroundFloorSignificant="  . clean_input($groundfloorsignificant) .
               ",  GroundFloorRetrofit='"      . clean_input($groundfloorretrofit) .
               "', GroundFloorLocation='"      . clean_input($groundfloorlocation) .
               "', GroundFloorType='"          . clean_input($grndflrtype) .
               "', GroundFloorInsulation="     . clean_input($groundfloorinsulation) . 
               ",  GrndFlrInslatnDepth='"      . clean_input($gfinsdepth) . 
               "', GrndFlrInslatnType='"       . clean_input($gfinstype) .
               "', GroundFloorNotes='"         . clean_input($groundfloornotes) .
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
#-------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 60</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Ground Floor</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideGrndFlrInslatnQueries();
           ShowGrndFlrInslatnQueries();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideGrndFlrInslatnQueries() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("grndflrinslatndepthSel");
             obj2 = document.getElementById("grndflrinslatndepthHdr");
             obj3 = document.getElementById("grndflrinslatntypeSel");
             obj4 = document.getElementById("grndflrinslatntypeHdr");
             obj1.style.display="none";
             obj2.style.display="none";
             obj3.style.display="none";
             obj4.style.display="none";
         }

         function ShowGrndFlrInslatnQueries() {
             if (!document.getElementById) return;
             gfiobj = document.getElementById("groundfloorinsulationChk");
             if (gfiobj.checked == true){
                obj1 = document.getElementById("grndflrinslatndepthSel");
                obj2 = document.getElementById("grndflrinslatndepthHdr");
                obj3 = document.getElementById("grndflrinslatntypeSel");
                obj4 = document.getElementById("grndflrinslatntypeHdr");
                obj1.style.display="block";
                obj2.style.display="block";
                obj3.style.display="block";
                obj4.style.display="block";
             } else {
               hideGrndFlrInslatnQueries();
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
               <th>Ground Floor Significant</th>
               <td>
                  <input type="checkbox"  name="groundfloorsignificant" value="<?php echo ($row['GroundFloorSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['GroundFloorSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="groundfloorsignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Eg. Floor tiles, historic wooden floor boards etc. may affect options
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ground Floor Retrofit</th>
               <td>
                  <select id="groundfloorretrofit" name="groundfloorretrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['GroundFloorRetrofit'] == "Red"           ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['GroundFloorRetrofit'] == "Amber"         ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['GroundFloorRetrofit'] == "Green"         ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['GroundFloorRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ground Floor Location</th>
               <td>
                  <select id="groundfloorlocation" name="groundfloorlocation" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Above Soil"          <?php echo $row['GroundFloorLocation'] == "Above Soil"           ? " selected" : ""; ?>>Above Soil</option>
                     <option value="Above heated space"  <?php echo $row['GroundFloorLocation'] == "Above heated space"   ? " selected" : ""; ?>>Above heated space</option>
                     <option value="Above unheated space"<?php echo $row['GroundFloorLocation'] == "Above unheated space" ? " selected" : ""; ?>>Above unheated space</option>
                     <option value="To external air"     <?php echo $row['GroundFloorLocation'] == "To external air"      ? " selected" : ""; ?>>To external air</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="groundfloorlocation_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Exposure to ambient temperatures gives an indication of thermal 
                  performance and opportunities for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ground Floor Type</th>
               <td>
                  <select id="groundfloortype" name="groundfloortype[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="Solid floor original"    <?php echo (isset($gftypeAry) && in_array('Solid floor original', $gftypeAry))    ? " selected" : ""; ?>>Solid floor original</option>
                     <option value="Solid floor replacement" <?php echo (isset($gftypeAry) && in_array('Solid floor replacement', $gftypeAry)) ? " selected" : ""; ?>>Solid floor replacement</option>
                     <option value="Suspended timber"        <?php echo (isset($gftypeAry) && in_array('Suspended timber', $gftypeAry))        ? " selected" : ""; ?>>Suspended timber</option>
                     <option value="Suspended other"         <?php echo (isset($gftypeAry) && in_array('Suspended other', $gftypeAry))         ? " selected" : ""; ?>>Suspended other</option>
                     <option value="Unknown"                 <?php echo (isset($gftypeAry) && in_array('Unknown', $gftypeAry))                 ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="groundfloortype_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  The structure of the floor gives an indication of thermal performance,
                  moisture issues and opportunities for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>Ground Floor Insulation</th>
               <td>
                  <input type="checkbox" id="groundfloorinsulationChk" name="groundfloorinsulation" onchange="ShowGrndFlrInslatnQueries()" value="<?php echo ($row['GroundFloorInsulation']=='1' ? '1' : '0');?>" <?php echo ($row['GroundFloorInsulation']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th id="grndflrinslatndepthHdr">Ground Floor Insulation Thickness</th>
               <td>
                  <select id="grndflrinslatndepthSel" name="grndflrinslatndepth[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="As Built"<?php echo (isset($gfinsdepthAry) && in_array('As Built', $gfinsdepthAry)) ? " selected" : ""; ?>>As Built</option>
                     <option value="50mm"    <?php echo (isset($gfinsdepthAry) && in_array('50mm', $gfinsdepthAry))     ? " selected" : ""; ?>>50mm</option>
                     <option value="100mm"   <?php echo (isset($gfinsdepthAry) && in_array('100mm', $gfinsdepthAry))    ? " selected" : ""; ?>>100mm</option>
                     <option value="150mm"   <?php echo (isset($gfinsdepthAry) && in_array('150mm', $gfinsdepthAry))    ? " selected" : ""; ?>>150mm</option>
                     <option value="Unknown" <?php echo (isset($gfinsdepthAry) && in_array('Unknown', $gfinsdepthAry))  ? " selected" : ""; ?>>Unknown</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th id="grndflrinslatntypeHdr">Ground Floor Insulation Type</th>
               <td>
                  <select id="grndflrinslatntypeSel" name="grndflrinslatntype[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="EPS/XPS"                  <?php echo (isset($gfinstypeAry) && in_array('EPS/XPS', $gfinstypeAry)) ? " selected" : ""; ?>                  >EPS/XPS</option>
                     <option value="Phenolic/PIR/PUR"         <?php echo (isset($gfinstypeAry) && in_array('Phenolic/PIR/PUR', $gfinstypeAry))         ? " selected" : ""; ?>         >Phenolic/PIR/PUR</option>
                     <option value="Mineral wool/Glass fibre" <?php echo (isset($gfinstypeAry) && in_array('Mineral wool/Glass fibre', $gfinstypeAry)) ? " selected" : ""; ?> >Mineral wool/Glass fibre</option>
                     <option value="Natural insulation"       <?php echo (isset($gfinstypeAry) && in_array('Natural insulation', $gfinstypeAry))       ? " selected" : ""; ?>       >Natural insulation</option>
                     <option value="Other"                    <?php echo (isset($gfinstypeAry) && in_array('Other', $gfinstypeAry))                    ? " selected" : ""; ?>                    >Other</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ground Floor Notes</th>
               <td>
                  <textarea name="groundfloornotes" rows="2" cols="30"><?php echo $row['GroundFloorNotes']?></textarea>
               </td>
            </tr> 
         </table>

         <div class="pagefooter" >
            <a href="form54.php" title="Form 54">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form62.php" title="Form 62">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
