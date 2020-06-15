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

   $doorframeAry = array();
   $doorframeAry = explode(',',$row['ExtDoorFrame']);

   $doorglazeAry = array();
   $doorglazeAry = explode(',',$row['ExtDoorGlazing']);

   echo "<br>";         

#------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form42.php");

      # set checkboxes if blank
      (!isset($_POST['extdoorssignificant']))  ? $extdoorssignificant  = 0 : $extdoorssignificant  = 1;
      (!isset($_POST['extdoorsdraughtproof'])) ? $extdoorsdraughtproof = 0 : $extdoorsdraughtproof = 1;
      (!isset($_POST['extdoorssecure']))       ? $extdoorssecure       = 0 : $extdoorssecure       = 1;
      
      # set dropdowns if blank
      (!isset($_POST['extdoorframe']))        ? $doorframe           = '' : $doorframe           = implode(',', $_POST['extdoorframe']);
      (!isset($_POST['extdoorglazing']))      ? $doorglaze           = '' : $doorglaze           = implode(',', $_POST['extdoorglazing']);
      (!isset($_POST['doorrevealsinternal'])) ? $doorrevealsinternal = '' : $doorrevealsinternal = $_POST['doorrevealsinternal'];
      (!isset($_POST['doorrevealsexternal'])) ? $doorrevealsexternal = '' : $doorrevealsexternal = $_POST['doorrevealsexternal'];
      (!isset($_POST['doorseals']))           ? $doorseals           = '' : $doorseals           = $_POST['doorseals'];
      (!isset($_POST['extdoorsretrofit']))    ? $extdoorsretrofit    = '' : $extdoorsretrofit    = $_POST['extdoorsretrofit'];
      (!isset($_POST['lobby']))               ? $lobby               = '' : $lobby               = $_POST['lobby'];

      # set notes if blank
      (!isset($_POST['extdoorsnotes'])) ? $extdoorsnotes = '' : $extdoorsnotes = $_POST['extdoorsnotes'];
      
      $sql = "UPDATE windowsdoors ".      
               " SET ExtDoorsSignificant='" . clean_input($extdoorssignificant) .
               "', ExtDoorFrame='"          . clean_input($doorframe) .
               "', ExtDoorGlazing='"        . clean_input($doorglaze) .
               "', ExtDoorsDraught='"       . clean_input($extdoorsdraughtproof) .
               "', DoorRevealsInternal='"   . clean_input($doorrevealsinternal) . 
               "', DoorRevealsExternal='"   . clean_input($doorrevealsexternal) . 
               "', DoorSeals='"             . clean_input($doorseals) . 
               "', ExtDoorsRetrofit='"      . clean_input($extdoorsretrofit) .
               "', ExtDoorsSecure='"        . clean_input($extdoorssecure) .
               "', ExtDoorsNotes='"         . clean_input($extdoorsnotes) .
               "', Lobby='"                 . clean_input($lobby) .
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

#------------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 42</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>External Doors</em></strong></h2>
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
               <th>Ext. Doors Significant</th>
               <td>
                  <input type="checkbox" class="chk" name="extdoorssignificant" value="<?php echo ($row['ExtDoorsSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['ExtDoorsSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Ext. Door Frame</th>
               <td>
                  <select id="extdoorframe" name="extdoorframe[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="Wood"  <?php echo (isset($doorframeAry) && in_array('Wood', $doorframeAry)) ? " selected" : ""; ?>>Wood</option>
                     <option value="PVCu"  <?php echo (isset($doorframeAry) && in_array('PVCu', $doorframeAry)) ? " selected" : ""; ?>>PVCu</option>
                     <option value="GRP"   <?php echo (isset($doorframeAry) && in_array('GRP',  $doorframeAry)) ? " selected" : ""; ?>>GRP</option>
                     <option value="Other" <?php echo (isset($doorframeAry) && in_array('Other',$doorframeAry)) ? " selected" : ""; ?>>Other</option>
                  </select>
                  <div class="tooltip" id="extdoorframe_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Frame material gives an indication of thermal performance and 
                     opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>               
            <tr>
               <th>Ext. Door Glazing</th>
               <td>
                  <select id="extdoorglazing" name="extdoorglazing[]" multiple="multiple" size="4" >
                     <option value="" disabled >Please choose...</option>
                     <option value="Single"           <?php echo (isset($doorglazeAry) && in_array('Single', $doorglazeAry))           ? " selected" : ""; ?>>Single</option>
                     <option value="Double Pre 2002"  <?php echo (isset($doorglazeAry) && in_array('Double Pre 2002', $doorglazeAry))  ? " selected" : ""; ?>>Double Pre 2002</option>
                     <option value="Double Post 2002" <?php echo (isset($doorglazeAry) && in_array('Double Post 2002', $doorglazeAry)) ? " selected" : ""; ?>>Double Post 2002</option>
                     <option value="Triple"           <?php echo (isset($doorglazeAry) && in_array('Triple', $doorglazeAry))           ? " selected" : ""; ?>>Triple</option>
                  </select>
                  <div class="tooltip" id="extdoorframe_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Type of glazing gives an indication of thermal performance and 
                     opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Ext. Doors Draught Proof</th>
               <td>
                  <input type="checkbox" class="chk" name="extdoorsdraughtproof" value="<?php echo ($row['ExtDoorsDraught']=='1' ? '1' : '0');?>" <?php echo ($row['ExtDoorsDraught']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="extdoorsdraughtproof_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Draughtiness gives an indication of thermal performance and 
                     opportunities for improvements.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Door Reveals Internal</th>
               <td>
                  <select id="doorrevealsinternal" name="doorrevealsinternal" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="&gt;20mm frame width" <?php echo $row['DoorRevealsInternal'] == "&gt;20mm frame width" ? " selected" : ""; ?>>&gt;20mm frame width</option>
                     <option value="&lt;20mm frame width" <?php echo $row['DoorRevealsInternal'] == "&lt;20mm frame width" ? " selected" : ""; ?>>&lt;20mm frame width</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Door Reveals External</th>
               <td>
                  <select id="doorrevealsexternal" name="doorrevealsexternal" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="&gt;20mm frame width" <?php echo $row['DoorRevealsExternal'] == "&gt;20mm frame width" ? " selected" : ""; ?>>&gt;20mm frame width</option>
                     <option value="&lt;20mm frame width" <?php echo $row['DoorRevealsExternal'] == "&lt;20mm frame width" ? " selected" : ""; ?>>&lt;20mm frame width</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ext Doors Secure</th>
               <td>
                  <input type="checkbox" class="chk" name="extdoorssecure" value="<?php echo ($row['ExtDoorsSecure']=='1' ? '1' : '0');?>" <?php echo ($row['ExtDoorsSecure']=='1' ? 'checked="checked"' : '');?>>
                  <div class="tooltip" id="extdoorssecure_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     This relates to a home complying with standard insurance requests 
                     to be secured with 5 lever locks / multi-point locking mechanisms 
                     or to comply with Secure By Design classification.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>               
            <tr>
               <th>Door Seals</th>
               <td>
                  <select id="doorseals" name="doorseals" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="All frame seals good"  <?php echo $row['DoorSeals'] == "All frame seals good"  ? " selected" : ""; ?>>All frame seals good</option>
                     <option value="Some frame seals good" <?php echo $row['DoorSeals'] == "Some frame seals good" ? " selected" : ""; ?>>Some frame seals good</option>
                     <option value="No frame seals good"   <?php echo $row['DoorSeals'] == "No frame seals good"   ? " selected" : ""; ?>>No frame seals good</option>
                  </select>
                  <div class="tooltip" id="doorseals_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     Junction between frame and walls can be a major source of rainwater ingress.
                     </span>
                     <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
                  </div>
               </td>
            </tr>
            <tr>
               <th>Ext Doors Retrofit</th>
               <td>
                  <select id="extdoorsretrofit" name="extdoorsretrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['ExtDoorsRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['ExtDoorsRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['ExtDoorsRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['ExtDoorsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ext Doors Notes</th>
               <td>
                  <input type="text" name="extdoorsnotes" value="<?php echo $row['ExtDoorsNotes']?>">
               </td>
            </tr>               
            <tr>
               <th>Lobby</th>
               <td>
                  <select id="lobby" name="lobby" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="External lobby one entrance"  <?php echo $row['Lobby'] == "External lobby one entrance" ? " selected" : ""; ?> >External lobby one entrance</option>
                     <option value="External lobby all entrances" <?php echo $row['Lobby'] == "External lobby all entrances" ? " selected" : ""; ?>>External lobby all entrances</option>
                     <option value="Internal lobby one entrance"  <?php echo $row['Lobby'] == "Internal lobby one entrance" ? " selected" : ""; ?> >Internal lobby one entrance</option>
                     <option value="Internal lobby all entrances" <?php echo $row['Lobby'] == "Internal lobby all entrances" ? " selected" : ""; ?>>Internal lobby all entrances</option>
                     <option value="None"                         <?php echo $row['Lobby'] == "None" ? " selected" : ""; ?>                        >None</option>
                  </select>
                  <div class="tooltip" id="lobby_tt"  >
                     <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                     The presence of lobbies gives an indication of thermal performance
                     and opportunities for improvements.
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
         <div class="pagefooter" >
            <a href="form40.php" title="Form 40">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form44.php" title="Form 44">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
