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
#--------------------------------------------------------

   if (isset ($_POST['submit'])){
      
      header("location:form54.php");

      # set checkboxes if blank
      (!isset($_POST['fireplacessignificant']))      ? $fireplacessignificant  = 0      : $fireplacessignificant  = 1;
      (!isset($_POST['builtinfittingssignificant'])) ? $builtinfittingssignificant  = 0 : $builtinfittingssignificant  = 1;
      
      # set dropdowns if blank
      (!isset($_POST['fireplacesretrofit']))      ? $fireplacesretrofit = ''      : $fireplacesretrofit = $_POST['fireplacesretrofit'];
      (!isset($_POST['builtinfittingsretrofit'])) ? $builtinfittingsretrofit = '' : $builtinfittingsretrofit = $_POST['builtinfittingsretrofit'];

      # set notes if blank
      (!isset($_POST['fireplacesnotes']))      ? $fireplacesnotes = ''      : $fireplacesnotes = $_POST['fireplacesnotes'];
      (!isset($_POST['builtinfittingsnotes'])) ? $builtinfittingsnotes = '' : $builtinfittingsnotes = $_POST['builtinfittingsnotes'];
      
      $sql = "UPDATE internal ".      
               " SET FireplacesSignificant="     . clean_input($fireplacessignificant) .
               ",  FireplacesRetrofit='"         . clean_input($fireplacesretrofit) .
               "', FireplacesNotes='"            . clean_input($fireplacesnotes) .
               "', BuiltInFittingsSignificant="  . clean_input($builtinfittingssignificant) .
               ",  BuiltInFittingsRetrofit='"    . clean_input($builtinfittingsretrofit) . 
               "', BuiltInFittingsNotes='"       . clean_input($builtinfittingsnotes) . 
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
#--------------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 54</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Internal Fixtures & Fireplaces</em></strong></h2>
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
               <th>Fireplaces Significant</th>
               <td>
                  <input type="checkbox"  name="fireplacessignificant" value="<?php echo ($row['FireplacesSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['FireplacesSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="fireplacessignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  This might be a range of details etc that have an impact on EEM
                  like IWI as many fireplaces on external walls.
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Fireplaces Retrofit</th>
               <td>
                  <select id="fireplacesretrofit" name="fireplacesretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['FireplacesRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['FireplacesRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['FireplacesRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['FireplacesRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Fireplaces Notes</th>
               <td>
                  <textarea name="fireplacesnotes" rows="2" cols="30"><?php echo $row['FireplacesNotes']?></textarea>
               </td>
            </tr>
            <tr>
               <th>Built-In Fittings Significant</th>
               <td>
                  <input type="checkbox"  name="builtinfittingssignificant" value="<?php echo ($row['BuiltInFittingsSignificant']=='1' ? '1' : '0');?>" <?php echo ($row['BuiltInFittingsSignificant']=='1' ? 'checked="checked"' : '');?>>
               </td>
               <td>
               <div class="tooltip" id="internalwallsignificant_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  EEM like IWI for example stairs on an external wall might be 
                  compromised by thermal bridging. 
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr> 
            <tr>
               <th>Built-In Fittings Retrofit</th>
               <td>
                  <select id="builtinfittingsretrofit" name="builtinfittingsretrofit" size="1" style="width: 200px;">
                     <option value="" disabled selected>Please choose...</option>
                     <option value="Red"           <?php echo $row['BuiltInFittingsRetrofit'] == "Red" ? " selected" : ""; ?>          >Red</option>
                     <option value="Amber"         <?php echo $row['BuiltInFittingsRetrofit'] == "Amber" ? " selected" : ""; ?>        >Amber</option>
                     <option value="Green"         <?php echo $row['BuiltInFittingsRetrofit'] == "Green" ? " selected" : ""; ?>        >Green</option>
                     <option value="Not Inspected" <?php echo $row['BuiltInFittingsRetrofit'] == "Not Inspected" ? " selected" : ""; ?>>Not Inspected</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Built-In Fittings Notes</th>
               <td>
                  <textarea name="builtinfittingsnotes" rows="2" cols="30"><?php echo $row['BuiltInFittingsNotes']?></textarea>
               </td>
            </tr>
         </table>

         <div class="pagefooter" >
            <a href="form52.php" title="Form 52">Previous</a>		
            <input type="submit" value="Save" name="submit"> 
            <a href="form60.php" title="Form 60">Next</a>		
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
