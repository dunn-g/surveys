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

   $q = 'SELECT * FROM services WHERE SurveyId = "'.$surveyid.'"' ;

   $rslt = mysqli_query( $dbc , $q ) ;

   if ($rslt) {
      $row01 = mysqli_fetch_array( $rslt , MYSQLI_ASSOC ) ;
      $row = array_map('trim', $row01); 

      mysqli_free_result( $rslt ) ;

   } 

   $dhwfuelAry = array();
   $dhwfuelAry = explode(',',$row['DHWFuel']);

   $dhwcylAry = array();
   $dhwcylAry = explode(',',$row['DHWCylinder']);
#--------------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form83.php");

      # set dropdowns if blank
      (!isset($_POST['dhwfuel']))     ? $dhwfuel     = '' : $dhwfuel     = implode(',', $_POST['dhwfuel']);
      (!isset($_POST['dhwcylinder'])) ? $dhwcylinder = '' : $dhwcylinder = implode(',', $_POST['dhwcylinder']);
      (!isset($_POST['dhwpipework'])) ? $dhwpipework = '' : $dhwpipework = $_POST['dhwpipework'];

      # set notes if blank
      (!isset($_POST['dhwcylindermakemodelsize']) ) ? $dhwcylindermakemodelsize = '' : $dhwcylindermakemodelsize = $_POST['dhwcylindermakemodelsize'];

      $sql = "UPDATE services ".      
               " SET DHWFuel='"                . clean_input($dhwfuel) .
               "', DHWCylinder='"              . clean_input($dhwcylinder) .
               "', DHWCylinderMakeModelSize='" . clean_input($dhwcylindermakemodelsize) .
               "', DHWPipework='"              . clean_input($dhwpipework) .
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
      <title>Form 83</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>DHW</em></strong></h2>
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
               <th>DHW Fuel</th>
               <td>
                  <select id="dhwfuel" name="dhwfuel[]" multiple="multiple" size="4">
                     <option value="" disabled >Please choose...</option>
                     <option value="Electric"   <?php echo (isset($dhwfuelAry) && in_array('Electric', $dhwfuelAry))   ? " selected" : ""; ?>>Electric</option>
                     <option value="Mains Gas"  <?php echo (isset($dhwfuelAry) && in_array('Mains Gas', $dhwfuelAry))  ? " selected" : ""; ?>>Mains Gas</option>
                     <option value="Bottle Gas" <?php echo (isset($dhwfuelAry) && in_array('Bottle Gas', $dhwfuelAry)) ? " selected" : ""; ?>>Bottle Gas</option>
                     <option value="Solid fuel" <?php echo (isset($dhwfuelAry) && in_array('Solid fuel', $dhwfuelAry)) ? " selected" : ""; ?>>Solid fuel</option>
                     <option value="Bio fuel"   <?php echo (isset($dhwfuelAry) && in_array('Bio fuel', $dhwfuelAry))   ? " selected" : ""; ?>>Bio fuel</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>DHW Cylinder</th>
               <td>
                  <select id="dhwcylinder" name="dhwcylinder[]" multiple="multiple" size="4"> <!-- reset to 160-->
                     <option value="" disabled >Please choose...</option>
                     <option value="No cylinder"                                           <?php echo (isset($dhwcylAry) && in_array('No cylinder', $dhwcylAry))                                           ? " selected" : ""; ?>>No cylinder</option>
                     <option value="Insulated (&gt;80mm loose or &gt;50mm factory fitted)" <?php echo (isset($dhwcylAry) && in_array('Insulated (&gt;80mm loose or &gt;50mm factory fitted)', $dhwcylAry)) ? " selected" : ""; ?>>Insulated(&gt;80mm loose or &gt;50mm factory fitted)</option>
                     <option value="Uninsulated/Poor DHW Cylinder"                         <?php echo (isset($dhwcylAry) && in_array('Uninsulated/Poor DHW Cylinder', $dhwcylAry))                         ? " selected" : ""; ?>>Uninsulated/Poor DHW Cylinder</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>DHW Cylinder Make Model Size</th>
               <td>
                  <textarea placeholder="Note here manufacturer, model and any further info" name="dhwcylindermakemodelsize" rows="2" cols="30"><?php echo $row['DHWCylinderMakeModelSize']?></textarea>
               </td>
            </tr>
            <tr>
               <th>DHW Pipework</th>
               <td>
                  <select id="dhwpipework" name="dhwpipework" size="1">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Insulated pipework"           <?php echo $row['DHWPipework'] == "Insulated pipework" ? " selected" : ""; ?>          >Insulated pipework</option>
                     <option value="Uninsulated pipework"         <?php echo $row['DHWPipework'] == "Uninsulated pipework" ? " selected" : ""; ?>        >Uninsulated pipework</option>
                  </select>
               </td>
            </tr> 
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form82.php" title="Form 82">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form84.php" title="Form 84">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
