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

  $q = 'SELECT * FROM windowsdoors WHERE SurveyId = "'.$surveyid.'"' ;
  $rslt = mysqli_query( $dbc , $q ) ;

#if maybe needed here! check no of rows returned!
   if ($rslt) {
      $row = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      
      mysqli_free_result( $rslt ) ;

   } 
   echo "<br>";         
#--------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form46.php");

      # set checkboxes if blank

      # set dropdowns if blank
      (!isset($_POST['conservatoryheat'])) ? $conservatoryheat = '' : $conservatoryheat = $_POST['conservatoryheat'];
      (!isset($_POST['conservatorytype'])) ? $conservatorytype = '' : $conservatorytype = $_POST['conservatorytype'];

      # set notes if blank
      (!isset($_POST['conservatorynotes'])) ? $conservatorynotes = '' : $conservatorynotes = $_POST['conservatorynotes'];
      
      # these need to be set to cope with slightly different logic
      if (!isset($_POST['conservatory'])) {
         $conservatory = 0;
         $thermallyseparated = '';
      } else {
         $conservatory = 1;
         $thermallyseparated = $_POST['thermallyseparated'];
      } 

      $sql = "UPDATE windowsdoors ".      
               " SET Conservatory="      . clean_input($conservatory) .
               ",  ThermallySeparated='" . clean_input($thermallyseparated) .
               "', ConservatoryType='"   . clean_input($conservatorytype) .
               "', ConservatoryHeat='"   . clean_input($conservatoryheat) .
               "', ConservatoryNotes='"  . clean_input($conservatorynotes) .
               "' WHERE SurveyId=" . $surveyid;
      echo "<br>";   
      #print_r($_POST);
      #echo "<br>";         
      #print_r($sql);
      #echo "<br>";  
      
      if (mysqli_query( $dbc , $sql )) {
         echo "Record updated successfully";
         sleep(1);
         echo "<meta http-equiv='refresh' content='0'>";
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
      <title>Form 46</title>
      
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Conservatory</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
         window.onload = function() {
           hidethermalseparation();
           showthermalseparation();
         }
         
         $(function(){
            $("#nav-placeholder").load("nav.html");
         });
         
         function hidethermalseparation() {
             if (!document.getElementById) return;
             obj1 = document.getElementById("thermallyseparatedSel");
             obj2 = document.getElementById("thermallyseparatedHdr");
             tstt = document.getElementById("thermallyseparated_tt");
             obj1.style.display="none";
             obj2.style.display="none";
             tstt.style.display="none";
         }

         function showthermalseparation() {
             if (!document.getElementById) return;
             conobj = document.getElementById("conservatoryChk");
             if (conobj.checked == true){
               obj1 = document.getElementById("thermallyseparatedSel");
               obj2 = document.getElementById("thermallyseparatedHdr");
               tstt = document.getElementById("thermallyseparated_tt");
               obj1.style.display="block";
               obj2.style.display="block";
               tstt.style.display="block";
             } else {
               hidethermalseparation();
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
               <th>Conservatory</th>
               <td>
                  <input type="checkbox" id="conservatoryChk" name="conservatory" onchange="showthermalseparation()" value="<?php echo ($row['Conservatory']=='1' ? '1' : '0');?>" <?php echo ($row['Conservatory']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>               
            <tr>
               <th id="thermallyseparatedHdr">Thermally separated</th>
               <td>
                  <select id="thermallyseparatedSel" name="thermallyseparated" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Thermally separated"  <?php echo $row['ThermallySeparated'] == "Thermally separated"   ? " selected" : ""; ?>>Thermally separated</option>
                     <option value="No thermal separation"<?php echo $row['ThermallySeparated'] == "No thermal separation" ? " selected" : ""; ?>>No thermal separation</option>
                  </select>
               </td>
               <td>
               <div class="tooltip" id="thermallyseparated_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Conservatories and how they relate the main building affect thermal
                  performance and opportunities for improvements.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>               
            <tr>
               <th>Conservatory Type</th>
               <td>
                  <select id="conservatorytype" name="conservatorytype" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Fully single glazed"     <?php echo $row['ConservatoryType'] == "Fully single glazed" ? " selected" : ""; ?>    >Fully single glazed</option>
                     <option value="Fully double glazed"     <?php echo $row['ConservatoryType'] == "Fully double glazed" ? " selected" : ""; ?>    >Fully double glazed</option>
                     <option value="Part wall single glazed" <?php echo $row['ConservatoryType'] == "Part wall single glazed" ? " selected" : ""; ?>>Part wall single glazed</option>
                     <option value="Part wall double glazed" <?php echo $row['ConservatoryType'] == "Part wall double glazed" ? " selected" : ""; ?>>Part wall double glazed</option>
                     <option value="Other"                   <?php echo $row['ConservatoryType'] == "Other" ? " selected" : ""; ?>                  >Other</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Conservatory Heating</th>
               <td>
                  <select id="conservatoryheat" name="conservatoryheat" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="None"                     <?php echo $row['ConservatoryHeat'] == "None" ? " selected" : ""; ?>                     >None</option>
                     <option value="Connected to main heating"<?php echo $row['ConservatoryHeat'] == "Connected to main heating" ? " selected" : ""; ?>>Connected to main heating</option>
                     <option value="Indepentently heated"     <?php echo $row['ConservatoryHeat'] == "Indepentently heated" ? " selected" : ""; ?>     >Indepentently heated</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Conservatory Notes</th>
               <td>
                  <textarea name="conservatorynotes" rows="2" cols="30"><?php echo $row['ConservatoryNotes']?></textarea>
               </td>
            </tr>               
         </table>

         <div class="pagefooter" >
            <a href="form44.php" title="Form 44">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form50.php" title="Form 50">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
