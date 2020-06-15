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

   $sql = 'SELECT * FROM windowsdoors WHERE SurveyId = "'.$surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $sql ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $windowtypeAry = array();
   $windowtypeAry = explode(',',$row['WindowsType']);

   $windowfrmAry = array();
   $windowfrmAry = explode(',',$row['WindowFrame']);

   $winrevealsIntAry = array();
   $winrevealsIntAry = explode(',',$row['WindowsRevealsInternal']);

   $winrevealsExtAry = array();
   $winrevealsExtAry = explode(',',$row['WindowsRevealsExternal']);

   echo "<br>";         

#-------------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form40.php");

      # set checkboxes if blank
      (!isset($_POST['windowsframesignificant']))   ? $windowsframesignificant   = 0  : $windowsframesignificant   = 1;
      (!isset($_POST['windowsglazingsignificant'])) ? $windowsglazingsignificant = 0  : $windowsglazingsignificant = 1;
      (!isset($_POST['windowdraughtproofed']))      ? $windowdraughtproofed      = 0  : $windowdraughtproofed      = 1;
      (!isset($_POST['windowssecure']))             ? $windowssecure             = 0  : $windowssecure             = 1;
      
      # set dropdowns if blank
      (!isset($_POST['windowstype']))            ? $windtype               = '' : $windtype               = implode(',', $_POST['windowstype']);
      (!isset($_POST['windowframe']))            ? $windframe              = '' : $windframe              = implode(',', $_POST['windowframe']);
      (!isset($_POST['windowsrevealsinternal'])) ? $windowsrevealsint      = '' : $windowsrevealsint      = implode(',', $_POST['windowsrevealsinternal']);
      (!isset($_POST['windowsrevealsexternal'])) ? $windowsrevealsext      = '' : $windowsrevealsext      = implode(',', $_POST['windowsrevealsexternal']);
      (!isset($_POST['windowsretrofit']))        ? $windowsretrofit        = '' : $windowsretrofit        = $_POST['windowsretrofit'];
      (!isset($_POST['windowseals']))            ? $windowseals            = '' : $windowseals            = $_POST['windowseals'];

      # set notes if blank
      (!isset($_POST['windowsnotes'])) ? $windowsnotes = '' : $windowsnotes = $_POST['windowsnotes'];

      $sql = "UPDATE windowsdoors ".      
               " SET WindowsFrameSignificant="  . clean_input($windowsframesignificant) .
               ",  WindowsGlazingSignificant="  . clean_input($windowsglazingsignificant) .
               ",  WindowsType='"               . clean_input($windtype) .
               "', WindowFrame='"               . clean_input($windframe) .
               "', WindowDraughtProofed="       . clean_input($windowdraughtproofed) . 
               ",  WindowsRevealsInternal='"    . clean_input($windowsrevealsint) . 
               "', WindowsRevealsExternal='"    . clean_input($windowsrevealsext) .
               "', WindowSeals='"               . clean_input($windowseals) .
               "', WindowsRetrofit='"           . clean_input($windowsretrofit) .
               "', WindowsSecure="              . clean_input($windowssecure) .
               ",  WindowsNotes='"              . clean_input($windowsnotes) .
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      print_r($_POST);
      echo "<br>";         
      print_r($sql);
      echo "<br>";  
      
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

#-------------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 40</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Windows</em></strong></h2>
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
               <th>Windows Frame Significant</th>
               <td>
                  <input type="checkbox" class="chk" name="windowsframesignificant" value="<?php echo ($row['WindowsFrameSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['WindowsFrameSignificant']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="wndwfrmsgnfcnt_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Some frames will be significant and might limit EEM choices.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Windows Glazing Significant</th>
               <td>
                  <input type="checkbox" class="chk" name="windowsglazingsignificant" value="<?php echo ($row['WindowsGlazingSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['WindowsGlazingSignificant']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="glazing_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Some glazing might be significant and might limit EEM choices
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Windows Type</th>
               <td>
                  <select id="windowstype" name="windowstype[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="Single"           <?php echo (isset($windowtypeAry) && in_array('Single', $windowtypeAry))           ? " selected" : ""; ?>>Single</option>
                     <option value="Double Pre 2002"  <?php echo (isset($windowtypeAry) && in_array('Double Pre 2002', $windowtypeAry))  ? " selected" : ""; ?>>Double Pre 2002</option>
                     <option value="Double Post 2002" <?php echo (isset($windowtypeAry) && in_array('Double Post 2002', $windowtypeAry)) ? " selected" : ""; ?>>Double Post 2002</option>
                     <option value="Secondary"        <?php echo (isset($windowtypeAry) && in_array('Secondary', $windowtypeAry))        ? " selected" : ""; ?>>Secondary</option>
                     <option value="Triple"           <?php echo (isset($windowtypeAry) && in_array('Triple', $windowtypeAry))           ? " selected" : ""; ?>>Triple</option>
                     <option value="Other"            <?php echo (isset($windowtypeAry) && in_array('Other', $windowtypeAry))            ? " selected" : ""; ?>>Other</option>
                  </select>
                  <div class="tooltip" id="windowstype_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Type of glazing gives an indication of thermal performance 
                     and opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Window Frame</th>
               <td>
                  <select id="windowframeid" name="windowframe[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="Wood"  <?php echo (isset($windowfrmAry) && in_array('Wood',  $windowfrmAry)) ? " selected" : ""; ?>>Wood</option>
                     <option value="Metal" <?php echo (isset($windowfrmAry) && in_array('Metal', $windowfrmAry)) ? " selected" : ""; ?>>Metal</option>
                     <option value="PVCu"  <?php echo (isset($windowfrmAry) && in_array('PVCu',  $windowfrmAry)) ? " selected" : ""; ?>>PVCu</option>
                     <option value="Other" <?php echo (isset($windowfrmAry) && in_array('Other', $windowfrmAry)) ? " selected" : ""; ?>>Other</option>
                  </select>
                  <div class="tooltip" id="windowframe_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Frame material gives an indication of thermal performance 
                     and opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Window Draught Proofed</th><!-- ###chnge to checkbox!!!###-->
               <td>
                  <input type="checkbox" class="chk" name="windowdraughtproofed" value="<?php echo ($row['WindowDraughtProofed']=='1' ? '1' : '0');?>" <?php echo ($row['WindowDraughtProofed']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="wndwfrmsgnfcnt_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Draughtiness gives an indication of thermal performance 
                     and opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr> 
            <tr>
               <th>Window Reveals Internal</th>
               <td>
                  <select id="windowsrevealsinternal" name="windowsrevealsinternal[]" multiple="multiple" size="3" >
                     <option value="" disabled >Please choose...</option>
                     <option value="&gt;20mm frame width" <?php echo (isset($winrevealsIntAry) && in_array('&gt;20mm frame width', $winrevealsIntAry)) ? " selected" : ""; ?>>&gt;20mm frame width</option>
                     <option value="&lt;20mm frame width" <?php echo (isset($winrevealsIntAry) && in_array('&lt;20mm frame width', $winrevealsIntAry)) ? " selected" : ""; ?>>&lt;20mm frame width</option>
                  </select>
                  <div class="tooltip" id="intreveal_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     This highlights the potential limiting factor for IWI. 
                     Windows may vary hence multiple choice.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Windows Reveals External</th>
               <td>
                  <select id="windowsrevealsexternal" name="windowsrevealsexternal[]" multiple="multiple" size="3" >
                     <option value="" disabled >Please choose...</option>
                     <option value="&gt;20mm frame width" <?php echo (isset($winrevealsExtAry) && in_array('&gt;20mm frame width', $winrevealsExtAry)) ? " selected" : ""; ?>>&gt;20mm frame width</option>
                     <option value="&lt;20mm frame width" <?php echo (isset($winrevealsExtAry) && in_array('&lt;20mm frame width', $winrevealsExtAry)) ? " selected" : ""; ?>>&lt;20mm frame width</option>
                  </select>
                  <div class="tooltip" id="extreveal_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     This highlights the potential limiting factor for IWI. 
                     Windows may vary hence multiple choice.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Window Seals</th>
               <td>
                  <select id="windowseals" name="windowseals" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="All frame seals good"  <?php echo $row['WindowSeals'] == "All frame seals good" ? " selected" : ""; ?> >All frame seals good</option>
                     <option value="Some frame seals good" <?php echo $row['WindowSeals'] == "Some frame seals good" ? " selected" : ""; ?>>Some frame seals good</option>
                     <option value="No frame seals good"   <?php echo $row['WindowSeals'] == "No frame seals good" ? " selected" : ""; ?>  >No frame seals good</option>
                  </select>
                  <div class="tooltip" id="seals_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Junction between frame and walls can be a major source of rainwater ingress
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr> 
            <tr>
               <th>Windows Secure</th>
               <td>
                  <input type="checkbox" class="chk" name="windowssecure" value="<?php echo ($row['WindowsSecure']=='1' ? '1' : '0');?>" <?php echo ($row['WindowsSecure']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="secure_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     This relates to a home complying with standard insurance 
                     requests to be secured with internal locking mechanism or 
                     to comply with Secure By Design classification
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr> 
            <tr>
               <th>Windows Retrofit</th>
               <td>
                  <select id="windowsretrofit" name="windowsretrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['WindowsRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['WindowsRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['WindowsRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['WindowsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Windows Notes</th>
               <td>
                  <textarea name="windowsnotes" rows="2" cols="30" ><?php echo $row['WindowsNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form34.php" title="Form 34">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form42.php" title="Form 42">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
