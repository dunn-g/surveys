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

   $q = 'SELECT * FROM structuralissues WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $mouldlocAry = array();
   $mouldlocAry = explode(',',$row['Mouldlocation']);

   $damplocAry = array();
   $damplocAry = explode(',',$row['Damplocation']);

   $rotlocAry = array();
   $rotlocAry = explode(',',$row['Rotlocation']);

   $structlocAry = array();
   $structlocAry = explode(',',$row['StructureLocation']);
#--------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form70.php");

      # set checkboxes if blank
      (!isset($_POST['mould']))           ? $mould  = 0          : $mould  = 1;
      (!isset($_POST['damp']))            ? $damp  = 0           : $damp  = 1;
      (!isset($_POST['rot']))             ? $rot = 0             : $rot = 1;
      (!isset($_POST['structuralissue'])) ? $structuralissue = 0 : $structuralissue = 1;
      
      # set dropdowns if blank
      (!isset($_POST['mouldlocation']))     ? $mouldloc     = '' : $mouldloc     = implode(',', $_POST['mouldlocation']);
      (!isset($_POST['damplocation']))      ? $damploc      = '' : $damploc      = implode(',', $_POST['damplocation']);
      (!isset($_POST['rotlocation']))       ? $rotloc       = '' : $rotloc       = implode(',', $_POST['rotlocation']);
      (!isset($_POST['structurelocation'])) ? $structureloc = '' : $structureloc = implode(',', $_POST['structurelocation']);

      # set notes if blank
      (!isset($_POST['structurenotes'])) ? $structurenotes = '' : $structurenotes = $_POST['structurenotes'];

      $sql = "UPDATE structuralissues ".      
               " SET Mould='"           . clean_input($mould) .
               "', Mouldlocation='"     . clean_input($mouldloc) .
               "', Damp='"              . clean_input($damp) .
               "', Damplocation='"      . clean_input($damploc) .
               "', Rot='"               . clean_input($rot) . 
               "', Rotlocation='"       . clean_input($rotloc) . 
               "', StructuralIssue='"   . clean_input($structuralissue) .
               "', StructureLocation='" . clean_input($structureloc) .
               "', StructureNotes='"    . clean_input($structurenotes) .
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
#--------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 70</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Structural Issues inc Mould & Damp</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
       $(function(){
         $("#nav-placeholder").load("nav.html");
       });
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
               <th>Mould</th>
               <td>
                  <input type="checkbox"  name="mould" value="<?php echo ($row['Mould']=='1' ? '1' : '0');?>" <?php echo ($row['Mould']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="mould_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Black mould is associated with condensation issues.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Mould Location</th>
               <td>
                  <select id="mouldlocation" name="mouldlocation[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Wall North"          <?php echo (isset($mouldlocAry) && in_array('Wall North', $mouldlocAry))          ? " selected" : ""; ?>>Wall North</option>
                     <option value="Wall South"          <?php echo (isset($mouldlocAry) && in_array('Wall South', $mouldlocAry))          ? " selected" : ""; ?>>Wall South</option>
                     <option value="Wall East"           <?php echo (isset($mouldlocAry) && in_array('Wall East', $mouldlocAry))           ? " selected" : ""; ?>>Wall East</option>
                     <option value="Wall West"           <?php echo (isset($mouldlocAry) && in_array('Wall West', $mouldlocAry))           ? " selected" : ""; ?>>Wall West</option>
                     <option value="Basement"            <?php echo (isset($mouldlocAry) && in_array('Basement', $mouldlocAry))            ? " selected" : ""; ?>>Basement</option>
                     <option value="Ground Floor"        <?php echo (isset($mouldlocAry) && in_array('Ground Floor', $mouldlocAry))        ? " selected" : ""; ?>>Ground Floor</option>
                     <option value="Upper Floors"        <?php echo (isset($mouldlocAry) && in_array('Upper Floors', $mouldlocAry))        ? " selected" : ""; ?>>Upper Floors</option>
                     <option value="Internal Wall"       <?php echo (isset($mouldlocAry) && in_array('Internal Wall', $mouldlocAry))       ? " selected" : ""; ?>>Internal Wall</option>
                     <option value="Ceiling"             <?php echo (isset($mouldlocAry) && in_array('Ceiling', $mouldlocAry))             ? " selected" : ""; ?>>Ceiling</option>
                     <option value="Window Door Reveals" <?php echo (isset($mouldlocAry) && in_array('Window Door Reveals', $mouldlocAry)) ? " selected" : ""; ?>>Window Door Reveals</option>
                     <option value="Other"               <?php echo (isset($mouldlocAry) && in_array('Other', $mouldlocAry))               ? " selected" : ""; ?>>Other</option>
                  </select>
          </td>
            </tr>
            <tr>
               <th>Damp</th>
               <td>
                  <input type="checkbox"  name="damp" value="<?php echo ($row['Damp']=='1' ? '1' : '0');?>" <?php echo ($row['Damp']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="damp_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  It is important to note which tools have been used later in the 
                  survey so that it can be assessed how exacting the damp investigation 
                  process was. It is worth remembering that dry lining is probably 
                  associated with either current or past damp problems.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Damp Location</th>
               <td>
                  <select id="damplocation" name="damplocation[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Wall North"          <?php echo (isset($damplocAry) && in_array('Wall North', $damplocAry))          ? " selected" : ""; ?>>Wall North</option>
                     <option value="Wall South"          <?php echo (isset($damplocAry) && in_array('Wall South', $damplocAry))          ? " selected" : ""; ?>>Wall South</option>
                     <option value="Wall East"           <?php echo (isset($damplocAry) && in_array('Wall East', $damplocAry))           ? " selected" : ""; ?>>Wall East</option>
                     <option value="Wall West"           <?php echo (isset($damplocAry) && in_array('Wall West', $damplocAry))           ? " selected" : ""; ?>>Wall West</option>
                     <option value="Basement"            <?php echo (isset($damplocAry) && in_array('Basement', $damplocAry))            ? " selected" : ""; ?>>Basement</option>
                     <option value="Ground Floor"        <?php echo (isset($damplocAry) && in_array('Ground Floor', $damplocAry))        ? " selected" : ""; ?>>Ground Floor</option>
                     <option value="Upper Floors"        <?php echo (isset($damplocAry) && in_array('Upper Floors', $damplocAry))        ? " selected" : ""; ?>>Upper Floors</option>
                     <option value="Internal Wall"       <?php echo (isset($damplocAry) && in_array('Internal Wall', $damplocAry))       ? " selected" : ""; ?>>Internal Wall</option>
                     <option value="Ceiling"             <?php echo (isset($damplocAry) && in_array('Ceiling', $damplocAry))             ? " selected" : ""; ?>>Ceiling</option>
                     <option value="Window Door Reveals" <?php echo (isset($damplocAry) && in_array('Window Door Reveals', $damplocAry)) ? " selected" : ""; ?>>Window Door Reveals</option>
                     <option value="Other"               <?php echo (isset($damplocAry) && in_array('Other', $damplocAry))               ? " selected" : ""; ?>>Other</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Rot</th>
               <td>
                  <input type="checkbox"  name="rot" value="<?php echo ($row['Rot']=='1' ? '1' : '0');?>" <?php echo ($row['Rot']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="rot_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Rot may be seen via spores, fruiting bodies, smell or via structural failures.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Rot Location</th>
               <td>
                  <select id="rotlocation" name="rotlocation[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Wall North"          <?php echo (isset($rotlocAry) && in_array('Wall North', $rotlocAry))          ? " selected" : ""; ?>>Wall North</option>
                     <option value="Wall South"          <?php echo (isset($rotlocAry) && in_array('Wall South', $rotlocAry))          ? " selected" : ""; ?>>Wall South</option>
                     <option value="Wall East"           <?php echo (isset($rotlocAry) && in_array('Wall East', $rotlocAry))           ? " selected" : ""; ?>>Wall East</option>
                     <option value="Wall West"           <?php echo (isset($rotlocAry) && in_array('Wall West', $rotlocAry))           ? " selected" : ""; ?>>Wall West</option>
                     <option value="Basement"            <?php echo (isset($rotlocAry) && in_array('Basement', $rotlocAry))            ? " selected" : ""; ?>>Basement</option>
                     <option value="Ground Floor"        <?php echo (isset($rotlocAry) && in_array('Ground Floor', $rotlocAry))        ? " selected" : ""; ?>>Ground Floor</option>
                     <option value="Upper Floors"        <?php echo (isset($rotlocAry) && in_array('Upper Floors', $rotlocAry))        ? " selected" : ""; ?>>Upper Floors</option>
                     <option value="Internal Wall"       <?php echo (isset($rotlocAry) && in_array('Internal Wall', $rotlocAry))       ? " selected" : ""; ?>>Internal Wall</option>
                     <option value="Ceiling"             <?php echo (isset($rotlocAry) && in_array('Ceiling', $rotlocAry))             ? " selected" : ""; ?>>Ceiling</option>
                     <option value="Window Door Reveals" <?php echo (isset($rotlocAry) && in_array('Window Door Reveals', $rotlocAry)) ? " selected" : ""; ?>>Window Door Reveals</option>
                     <option value="Other"               <?php echo (isset($rotlocAry) && in_array('Other', $rotlocAry))               ? " selected" : ""; ?>>Other</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Structural Issue</th>
               <td>
                  <input type="checkbox"  name="structuralissue" value="<?php echo ($row['StructuralIssue']=='1' ? '1' : '0');?>" <?php echo ($row['StructuralIssue']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="structuralissue_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  It is important to trace causes for cracking etc and this might 
                  be related to drainage, moisture ingress and accidental damage.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Structure Location</th>
               <td>
                  <select id="structurelocation" name="structurelocation[]" multiple="multiple" size="4" style="width: 200px;">
                     <option value="" disabled >Please choose...</option>
                     <option value="Wall North"          <?php echo (isset($structlocAry) && in_array('Wall North', $structlocAry))          ? " selected" : ""; ?>>Wall North</option>
                     <option value="Wall South"          <?php echo (isset($structlocAry) && in_array('Wall South', $structlocAry))          ? " selected" : ""; ?>>Wall South</option>
                     <option value="Wall East"           <?php echo (isset($structlocAry) && in_array('Wall East', $structlocAry))           ? " selected" : ""; ?>>Wall East</option>
                     <option value="Wall West"           <?php echo (isset($structlocAry) && in_array('Wall West', $structlocAry))           ? " selected" : ""; ?>>Wall West</option>
                     <option value="Basement"            <?php echo (isset($structlocAry) && in_array('Basement', $structlocAry))            ? " selected" : ""; ?>>Basement</option>
                     <option value="Ground Floor"        <?php echo (isset($structlocAry) && in_array('Ground Floor', $structlocAry))        ? " selected" : ""; ?>>Ground Floor</option>
                     <option value="Upper Floors"        <?php echo (isset($structlocAry) && in_array('Upper Floors', $structlocAry))        ? " selected" : ""; ?>>Upper Floors</option>
                     <option value="Internal Wall"       <?php echo (isset($structlocAry) && in_array('Internal Wall', $structlocAry))       ? " selected" : ""; ?>>Internal Wall</option>
                     <option value="Ceiling"             <?php echo (isset($structlocAry) && in_array('Ceiling', $structlocAry))             ? " selected" : ""; ?>>Ceiling</option>
                     <option value="Window Door Reveals" <?php echo (isset($structlocAry) && in_array('Window Door Reveals', $structlocAry)) ? " selected" : ""; ?>>Window Door Reveals</option>
                     <option value="Other"               <?php echo (isset($structlocAry) && in_array('Other', $structlocAry))               ? " selected" : ""; ?>>Other</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Structure Notes</th>
               <td>
                  <textarea name="structurenotes" rows="2" cols="30"><?php echo $row['StructureNotes']?></textarea>
               </td>
            </tr>               
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form62.php" title="Form 62">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form80.php" title="Form 80">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
