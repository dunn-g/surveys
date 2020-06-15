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
      
      header("location:form44.php");

      # set checkboxes if blank
      (!isset($_POST['roofwindows'])) ? $roofwindows  = 0 : $roofwindows  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['roofwindowsretrofit'])) ? $roofwindowsretrofit = '' : $roofwindowsretrofit = $_POST['roofwindowsretrofit'];

      # set notes if blank
      (!isset($_POST['roofwindowsnotes'])) ? $roofwindowsnotes = '' : $roofwindowsnotes = $_POST['roofwindowsnotes'];

      $sql = "UPDATE windowsdoors ".      
               " SET Roofwindows='"       . clean_input($roofwindows) .
               "', RoofwindowsRetrofit='" . clean_input($roofwindowsretrofit) .
               "', RoofwindowsNotes='"    . clean_input($roofwindowsnotes) .
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
      <title>Form 44</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Roof Windows</em></strong></h2>
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
               <th>Roof Windows</th>
               <td>
                  <input type="checkbox" class="chk" name="roofwindows" value="<?php echo ($row['Roofwindows']=='1' ? '1' : '0');?>" <?php echo ($row['Roofwindows']=='1' ? 'checked="checked"' : '');?>>
               </td>
            </tr>
            <tr>
               <th>Roof windows Retrofit</th>
               <td>
                  <select id="roofwindowsretrofit" name="roofwindowsretrofit" size="1" >
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['RoofwindowsRetrofit'] == "Red" ? " selected" : ""; ?>>Red</option>
                     <option value="Amber"         <?php echo $row['RoofwindowsRetrofit'] == "Amber" ? " selected" : ""; ?>>Amber</option>
                     <option value="Green"         <?php echo $row['RoofwindowsRetrofit'] == "Green" ? " selected" : ""; ?>>Green</option>
                     <option value="Not Inspected" <?php echo $row['RoofwindowsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>               
            <tr>
               <th>Roof Windows Notes</th>
               <td>
                  <textarea name="roofwindowsnotes" rows="2" cols="30"><?php echo $row['RoofwindowsNotes']?></textarea>
               </td>
            </tr>               
         </table>

         <div class="pagefooter" >
            <a href="form42.php" title="Form 42">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form46.php" title="Form 46">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   

   <footer>
   </footer>  
   </body>
</html>
