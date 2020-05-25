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

   $q = 'SELECT * FROM groundvents  WHERE SurveyId = "' . $surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $q ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      
      mysqli_free_result( $rslt ) ;

   } 
   echo "<br>";         

#------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form18.php");

      # set checkboxes if blank
      (!isset($_POST['dpcbridgedbyrender']))  ? $dpcbridgedbyrender  = 0 : $dpcbridgedbyrender  = 1;
      (!isset($_POST['isventltnsufficient'])) ? $isventltnsufficient = 0 : $isventltnsufficient = 1;
      
      # set dropdowns if blank
      (!isset($_POST['extGrndLvl']))      ? $extGrndLvl       = '' : $extGrndLvl       = $_POST['extGrndLvl'];

      # set notes if blank
      (!isset($_POST['extGrndLvlNotes']))     ? $extGrndLvlNotes      = '' : $extGrndLvlNotes      = $_POST['extGrndLvlNotes'];
      (!isset($_POST['underfloorventnotes'])) ? $underfloorventnotes  = '' : $underfloorventnotes  = $_POST['underfloorventnotes'];
      (!isset($_POST['drainagenotes']))       ? $drainagenotes        = '' : $drainagenotes        = $_POST['drainagenotes'];
         
      # these need to be set to cope with slightly different logic
      if (!isset($_POST['anyundflrvents'])) {
         $anyundflrvents = 0;
         $underfloorvents = '';
      } else {
         $anyundflrvents = 1;
         $underfloorvents = $_POST['underfloorvents'];
      } 

      $sql = "UPDATE groundvents ".      
               " SET ExternalGroundLevel='" . clean_input($extGrndLvl) .
               "', DPCBridgedByRender='"    . clean_input($dpcbridgedbyrender) .
               "', ExternalGroundNotes='"   . clean_input($extGrndLvlNotes) .
               "', AnyUndflrVents="         . clean_input($anyundflrvents) .
               ",  UnderfloorVents='"       . clean_input($underfloorvents) .
               "', IsVentltnSufficient="    . clean_input($isventltnsufficient) .
               ",  UnderfloorVentNotes='"   . clean_input($underfloorventnotes) .
               "', DrainageNotes='"         . clean_input($drainagenotes) .
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

      <title>Form 18</title>

      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>

      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />
      <header>
         <h2><strong><em>Ground Level Vents</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>

      <style>
            label {
            font-weight: bold;
            margin: 8px 5px 8px 5px;
            float: left;
            width: 235px;
            text-align: right;   
            }
            span {
            display: block;
            overflow: hidden;
            margin: 8px 5px 8px 5px;
            padding: 0 4px 0 6px;
            }
            .ufvinput select {
            width: 400px;
            }
            table {
            width:100%;
            }
            th {
            width:33%;
            } 
        </style>

      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hideunderfloorvents();
           showunderfloorvents();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });

         function hideunderfloorvents() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("underfloorventsSel");
             obj2 = document.getElementById("underfloorventsHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showunderfloorvents() {
             if (!document.getElementById) return;
             ufvobj = document.getElementById("anyundflrventsChk");
             if (ufvobj.checked == true){
                obj1 = document.getElementById("underfloorventsSel");
                obj2 = document.getElementById("underfloorventsHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hideunderfloorvents();
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
      <form method="post" action="">
         <table class="formten" style="border: 0">
            <tr>
               <th>External Ground Level</th>
               <td>
                  <select id="extGrndLvl" name="extGrndLvl" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="150mm below DPC / Internal floor level"     <?php echo $row['ExternalGroundLevel'] == "150mm below DPC / Internal floor level" ? " selected" : ""; ?>>150mm below DPC / Internal floor level</option>
                     <option value="&lt;150mm below DPC / internal floor level" <?php echo $row['ExternalGroundLevel'] == "&lt;150mm below DPC / internal floor level" ? " selected" : ""; ?>>&lt;150mm below DPC / internal floor level</option>
                     <option value="At DPC level / Internal floor level"        <?php echo $row['ExternalGroundLevel'] == "At DPC level / Internal floor level" ? " selected" : ""; ?>>At DPC level / Internal floor level</option>
                     <option value="Above DPC / Internal floor level"           <?php echo $row['ExternalGroundLevel'] == "Above DPC / Internal floor level" ? " selected" : ""; ?>>Above DPC / Internal floor level</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="extGrndLvl_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To indicate potential source of damp issues. Note that
                  some areas might vary in height differential. Ensure to
                  take notes and photographs to illustrate.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            <tr>
               <th>DPC Bridged by Render</th>
               <td>
                  <input type="checkbox"  name="dpcbridgedbyrender" value="<?php echo ($row['DPCBridgedByRender']=='1' ? '1' : '0');?>" <?php echo ($row['DPCBridgedByRender']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="extGrndLvl_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To indicate potential source of damp issues.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ext. Grnd Level Notes</th>
               <td>
                  <textarea name="extGrndLvlNotes" rows="3" cols="30"><?php echo $row["ExternalGroundNotes"]?></textarea>
               </td>
            </tr>
            <tr>
               <th>Any Underfloor Vents?</th>
               <td>
                  <input type="checkbox" id="anyundflrventsChk" name="anyundflrvents" onchange="showunderfloorvents()" value="<?php echo ($row['AnyUndflrVents']=='1' ? '1' : '0');?>" <?php echo ($row['AnyUndflrVents']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="underflrVents_tt" style="display: inline;" >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  To indicate presence and effectiveness of underfloor ventilation if present.
                  </span>
                  <p style="font-size: 14; display: inline;"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            </table>
               <label for="underfloorventsSel" id="underfloorventsHdr">Underfloor Vents</label>
                <span><select class="ufvinput" id="underfloorventsSel" name="underfloorvents" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Cross vented(front and back)"    <?php echo $row['UnderfloorVents'] == "Cross vented(front and back)" ? " selected" : ""; ?>>Cross vented(front and back)</option>
                     <option value="Front vent only"                 <?php echo $row['UnderfloorVents'] == "Front vent only" ? " selected" : ""; ?>>Front vent only</option>
                     <option value="Back vent only"                  <?php echo $row['UnderfloorVents'] == "Back vent only" ? " selected" : ""; ?>>Back vent only</option>
                     <option value="Vents partially blocked/covered" <?php echo $row['UnderfloorVents'] == "Vents partially blocked/covered" ? " selected" : ""; ?>>Vents partially blocked/coveredVents partially blocked</option>
                     <option value="Other"                           <?php echo $row['UnderfloorVents'] == "Other" ? " selected" : ""; ?>>Other</option>
                  </select></span>
            <table>
            <tr>
               <th>Is Ventilation Sufficient?</th>
               <td>
                  <input type="checkbox"  name="isventltnsufficient" value="<?php echo ($row['IsVentltnSufficient']=='1' ? '1' : '0');?>" <?php echo ($row['IsVentltnSufficient']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="ventilationsufficient_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Building regulations state that there should be ventilation on two 
                  opposing external walls of not less than 1500mm² per metre run of 
                  external wall or 500mm² per metre² of floor area, whichever works 
                  out to give the higher area of ventilation.IsVentltnSufficient
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Underfloor Vent Notes</th>
               <td>
                  <textarea name="underfloorventnotes" rows="3" cols="30" ><?php echo $row['UnderfloorVentNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Drainage Notes</th>
               <td>
                  <textarea name="drainagenotes" rows="3" cols="30" ><?php echo $row['DrainageNotes']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="drainagenotes_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  RICS: To indicate condition, issues associated with drainage system.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
         </table>
         <div class="pagefooter" >
            <a href="form16.php" title="Form 16">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a href="form20.php" title="Form 20">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>  
      </div> 
   <footer>
   </footer>  
   </body>
</html>
