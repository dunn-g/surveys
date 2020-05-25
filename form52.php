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

   $q = 'SELECT * FROM internal WHERE SurveyId = "'.$surveyid.'"' ;

   $r = mysqli_query( $dbc , $q ) ;

   $row = mysqli_fetch_array( $r , MYSQLI_ASSOC );
#----------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form52.php");

      # set checkboxes if blank
      (!isset($_POST['ceilingssignificant']))      ? $ceilingssignificant  = 0     : $ceilingssignificant  = 1;
      (!isset($_POST['internalwallsignificant']))    ? $internalwallsignificant  = 0   : $internalwallsignificant  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['ceilingsretrofit']))     ? $ceilingsretrofit = ''     : $ceilingsretrofit = $_POST['ceilingsretrofit'];
      (!isset($_POST['internalwallretrofit'])) ? $internalwallretrofit = '' : $internalwallretrofit = $_POST['internalwallretrofit'];

      # set notes if blank
      (!isset($_POST['ceilingsnotes']) )    ? $ceilingsnotes = ''     : $ceilingsnotes = $_POST['ceilingsnotes'];
      (!isset($_POST['internalwallnotes'])) ? $internalwallnotes = '' : $internalwallnotes = $_POST['internalwallnotes'];
      
      $sql = "UPDATE internal ".      
               " SET CeilingsSignificant="    . clean_input($ceilingssignificant) .
               ",  CeilingsRetrofit='"        . clean_input($ceilingsretrofit) .
               "', CeilingsNotes='"           . clean_input($ceilingsnotes) .
               "', InternalWallSignificant="  . clean_input($internalwallsignificant) .
               ",  InternalWallRetrofit='"    . clean_input($internalwallretrofit) . 
               "', InternalWallNotes='"       . clean_input($internalwallnotes) . 
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
#----------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 52</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Internal Wall & Ceilings</em></strong></h2>
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
            <!---<th colspan="2">Sign Up Form</th> --->
            <tr>
               <th>Ceilings Significant</th>
               <td>
                  <input type="checkbox"  name="ceilingssignificant" value="<?php echo ($row['CeilingsSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['CeilingsSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="ceilingssignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This could be coving, ceiling roses etc that might have a bearing on EEM like IWI
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Ceilings Retrofit</th>
               <td>
                  <select id="ceilingsretrofit" name="ceilingsretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['CeilingsRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['CeilingsRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['CeilingsRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['CeilingsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ceilings Notes</th>
               <td>
                  <textarea name="ceilingsnotes" rows="2" cols="30"><?php echo $row['CeilingsNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Internal Wall Significant</th>
               <td>
                  <input type="checkbox"  name="internalwallsignificant" value="<?php echo ($row['InternalWallSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['InternalWallSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="internalwallsignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This might be a range of details etc that have an impact on EEM like IWI
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Internal Wall Retrofit</th>
               <td>
                  <select id="internalwallretrofit" name="internalwallretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['InternalWallRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['InternalWallRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['InternalWallRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['InternalWallRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr> 
            <tr>
               <th>Internal Wall Notes</th>
               <td>
                  <textarea name="internalwallnotes" rows="2" cols="30"><?php echo $row['InternalWallNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form50.php" title="Form 50">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form54.php" title="Form 54">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
       </div>  
   <footer>
   </footer>  
   </body>
</html>
