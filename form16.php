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

   $q = 'SELECT * FROM externalfeatures WHERE SurveyId = "'.$surveyid.'"' ;
   $rslt = mysqli_query( $dbc , $q ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      
      mysqli_free_result( $rslt ) ;

   } 
   echo "<br>";         

#------------------------------------------------------------

   if (isset ($_POST['submit'])){

      header("location:form16.php");

      # set checkboxes if blank

      # set dropdowns if blank
      (!isset($_POST['retrofitReady'])) ? $retrofitReady = '' : $retrofitReady = $_POST['retrofitReady'];

      # set notes if blank
      (!isset($_POST['externalIssues']))  ? $externalIssues = ''  : $externalIssues = $_POST['externalIssues'];

      # these need to be set to cope with slightly different logic
      if (!isset($_POST['gardenwallattached'])) {
         $gardenwallattached = 0;
         $gardenwallnotes = '';
      } else {
         $gardenwallattached = 1;
         $gardenwallnotes = $_POST['gardenwallnotes'];
      } 
      
      $sql = "UPDATE externalfeatures ".      
               " SET GardenWallAttached=" . clean_input($gardenwallattached) .
               ",  GardenWallNotes='"     . clean_input($gardenwallnotes) .
               "', ExternalIssues='"      . clean_input($externalIssues) .
               "', RetrofitReady='"       . clean_input($retrofitReady) .
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

#------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 16</title>
      
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h1><strong><em>External Issues inc garden walls</em></strong></h1>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
         <!--<h3>Survey No. : <?php echo $surveyid; ?> &nbsp; &nbsp; &nbsp;  UPRN : <?php echo $uprn; ?> &nbsp; &nbsp; &nbsp;    Address : <?php echo $address; ?></h3>-->
      </header>
      <style>
         label {
            font-weight: bold;
            margin: 8px 5px 8px 5px;
            float: left;
         }
         span {
            display: block;
            overflow: hidden;
            margin: 8px 5px 8px 5px;
            padding: 0 4px 0 6px;
         }
         .gwinput input {
            width: 200px;
         }
         aside {
            color: purple;
         }
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hidegardenwallnotes();
           showgardenwallnotes();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
         
         function hidegardenwallnotes() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("gardenwallnotesSel");
             obj2 = document.getElementById("gardenwallnotesHdr");
             obj1.style.display="none";
             obj2.style.display="none";
         }

         function showgardenwallnotes() {
             if (!document.getElementById) return;
             gwaobj = document.getElementById("gardenwallattachedChk");
             if (gwaobj.checked == true){
                obj1 = document.getElementById("gardenwallnotesSel");
                obj2 = document.getElementById("gardenwallnotesHdr");
                obj1.style.display="block";
                obj2.style.display="block";
             } else {
               hidegardenwallnotes();
             }
         }         
         
      </script>
   </head>
   <body>
      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->
      
         <aside>
         <div class="info1" name="gardenwalls">
            <p align=left><strong>Info!</strong><br>
               Walls can introduce damp into the main wall
               structure, so note and photograph any wall
               issues that need to be taken into account.  
            </p>
         </div>
         <br><br>
         <div class="info2" name="extissues" >
            <p align=left><br><strong>Info!</strong><br>  
               Note down and photograph any other issues that
               might effect the installation of EE measures 
               eg. lamp posts, gas meters, aerials etc.      
            </p><br>
         </div>
         </aside>
      <!-- Page content -->
      <div class="main" style="display:table;">
      <form method="post" action="" >
         <table class="formsixteen" style="border: 0">
            <tr>
               <th>Garden Wall Attached</th>
               <td>
                  <input type="checkbox" id="gardenwallattachedChk" name="gardenwallattached" onchange="showgardenwallnotes() " value="<?php echo ($row['GardenWallAttached']=='1' ? '1' : '0');?>" <?php echo ($row['GardenWallAttached']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="gardenwallattached_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Exterior walls abutting the building can introduce damp into 
                  walls and need to be dealt with as part of EEM retrofit process.
                  </span>
                  <p style="font-size : 14;margin-left:150px;"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
         </table>
              
            <label for="gardenwallnotes" id="gardenwallnotesHdr">Garden Wall Notes </label>
            <span><input class="gwinput" type="text" id="gardenwallnotesSel" name="gardenwallnotes" value="<?php echo $row['GardenWallNotes']?>"></span>
               <!--<th  >Garden Wall Notes</th>
               <td>
                  <input type="text" id="gardenwallnotesSel" name="gardenwallnotes" value="<?php echo $row['GardenWallNotes']?>">
               </td>-->
         <table class="formsixteen" style="border: 0">
            <tr>
               <th>External Issues</th>
               <td>
                  <textarea name="externalIssues" rows="2" cols="25"><?php echo $row['ExternalIssues']?></textarea>
               </td>
               <td>
               <div class="tooltip" id="externalIssues_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  There can be a wide variety of issues that can complicate the 
                  thermal dynamics, moisture and opportunities for EEM on the 
                  outside of the dwelling. These need to be noted and photographed 
                  for evidence. 
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Retrofit Ready</th>
               <td>
                  <select id="retrofitReady" name="retrofitReady" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"          <?php echo $row['RetrofitReady'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"        <?php echo $row['RetrofitReady'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"        <?php echo $row['RetrofitReady'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected"<?php echo $row['RetrofitReady'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="retrofitReady_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This gives the Assessor an opportunity to flag up any major 
                  issues that might affect the installation of EEM. 
                  Red: indicates that there is a fundamental reason for not 
                  undertaking EEM or that there are severe complications.
                  Amber: More investigations are required before a final decision can be made
                  Green: There is no reason why EEM installations cannot be made immediately 
                  and / or that the EEM installation will eradicate any existing condition issues / concerns
                  Not inspected: If an element has not been inspected it is important to note 
                  this as it may have a fundament effect on any works.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
         </table>
<!--
         <br>         
         <br>         
         <br>         
         <br> 
         <div id="pagefooter" >
            <a href="form14.php" title="Form 14">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a href="form18.php" title="Form 18">Next</a>		
         </div>
-->
         <div class='pagefooter'>
            <!--Always at bottom!-->
            <a class="previousf" href="form14.php" title="Form 14">Previous</a>		
            <input type="submit" value="Save Changes" name="submit"> 
            <a class="nextf" href="form18.php" title="Form 18">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
         </form>  
         </div> 
      

      <footer>
      </footer>  
   </body>
</html>
