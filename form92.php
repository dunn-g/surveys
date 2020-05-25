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

   $q = 'SELECT * FROM basicinfo WHERE SurveyId = "'.$surveyid.'"' ;

   $r = mysqli_query( $dbc , $q ) ;

   $row = mysqli_fetch_array( $r , MYSQLI_ASSOC );
#---------------------------------------------------

   if (isset ($_POST['submit'])){

      header("location:form92.php");

       #(!isset($_POST['invasiveequipment'])) ? $invasiveequipment = '' : $invasiveequipment = $_POST['invasiveequipment'];
     
      # set numeric textboxes if blank
      (!isset($_POST['wallareamain']))        ? $wallareamain        = 0 : $wallareamain        = $_POST['wallareamain'];
      (!isset($_POST['wallareaext1']))        ? $wallareaext1        = 0 : $wallareaext1        = $_POST['wallareaext1'];
      (!isset($_POST['wallareaext2']))        ? $wallareaext2        = 0 : $wallareaext2        = $_POST['wallareaext2'];
      (!isset($_POST['windowarea']))          ? $windowarea          = 0 : $windowarea          = $_POST['windowarea'];
      (!isset($_POST['doorarea']))            ? $doorarea            = 0 : $doorarea            = $_POST['doorarea'];
      (!isset($_POST['roofareamain']))        ? $roofareamain        = 0 : $roofareamain        = $_POST['roofareamain'];
      (!isset($_POST['roofareatype1']))       ? $roofareatype1       = 0 : $roofareatype1       = $_POST['roofareatype1'];
      (!isset($_POST['roofareatype2']))       ? $roofareatype2       = 0 : $roofareatype2       = $_POST['roofareatype2'];
      (!isset($_POST['floorperimetermain']))  ? $floorperimetermain  = 0 : $floorperimetermain  = $_POST['floorperimetermain'];
      (!isset($_POST['floorperimetertype2'])) ? $floorperimetertype2 = 0 : $floorperimetertype2 = $_POST['floorperimetertype2'];
      
      # set notes if blank
      (!isset($_POST['dimensionnotes']))    ? $dimensionnotes    = '' : $dimensionnotes    = $_POST['dimensionnotes'];  

      $sql = "UPDATE basicinfo ".      
               " SET WallAreaMain="     . clean_input($wallareamain) .
               ", WallAreaExt1="        . clean_input($wallareaext1) .
               ", WallAreaExt2="        . clean_input($wallareaext2) .
               ", WindowArea="          . clean_input($windowarea) .
               ", DoorArea="            . clean_input($doorarea) . 
               ", RoofAreaMain="        . clean_input($roofareamain) . 
               ", RoofAreaType1="       . clean_input($roofareatype1) .
               ", RoofAreaType2="       . clean_input($roofareatype2) .
               ", FloorPerimeterMain="  . clean_input($floorperimetermain) .
               ", FloorPerimeterType2=" . clean_input($floorperimetertype2) .
               ", DimensionNotes='"     . clean_input($dimensionnotes) .
               "' WHERE surveyId=" . $surveyid;
      echo "<br>";   
      print_r($_POST);
      echo "<br>";         
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
?>


<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8">
      <title>Form 92</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />

      <header>
         <h2><strong><em>Dimensions</em></strong></h2>
         <h3><pre>Survey No. : <?php echo $surveyid; ?>   UPRN : <?php echo $uprn; ?>     Address : <?php echo $address; ?></pre></h3>
      </header>
      <style>
      </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script>
       $(function(){
         $("#nav-placeholder").load("nav.html");
       });
       
      function submitNext(){
      formObj = document.getElementById('form92');
      formObj.action="form93.php";
      <!--formObj.submit();-->
      <!--window.location.href="form93.php";-->
      <!--header("Location:form93.php");-->
      
      }

      function submitPrev(){
      formObj = document.getElementById('form92');
      formObj.action="form90.php";
      <!--formObj.submit();-->
      }
       
      </script>
   </head>
   <body>

      <!--Navigation bar-->
      <div id="nav-placeholder">

      </div>
      <!--end of Navigation bar-->

      <div class="main" style="display:table;">
      <form id="form92" method="post" >
         <table class="formten" style="border: 0">
            <tr>
               <th>Wall Area Main</th>
               <td><input type="text" name="wallareamain" value="<?php echo $row["WallAreaMain"]?>" ></td>
               <td>
               <div class="tooltip" id="wallareamain_tt"  >
                  <span class="tooltiptext" ><!--style="top: 18px;left: 600px"> -->
                  Be consistent in terms of using metric units and either external 
                  or internal measurements
                  </span>
                  <p style="font-size : 14"> &nbsp; &nbsp; &#8505 </p>
               </div>
               </td>
            </tr>
            <tr>
               <th>Wall Area Ext 1</th>
               <td><input type="text" name="wallareaext1" value="<?php echo $row['WallAreaExt1']?>"></td>
            </tr>
            <tr>
               <th>Wall Area Ext 2</th>
               <td><input type="text" name="wallareaext2" value="<?php echo $row['WallAreaExt2']?>"></td>
            </tr>
            <tr>
               <th>Window Area</th>
               <td><input type="text" name="windowarea" value="<?php echo $row['WindowArea']?>"></td>
            </tr> 
            <tr>
               <th>Door Area</th>
               <td><input type="text" name="doorarea" value="<?php echo $row['DoorArea']?>"></td>
            </tr>
            <tr>
               <th>Roof Area Main</th>
               <td><input type="text" name="roofareamain" value="<?php echo $row["RoofAreaMain"]?>" ></td>
            </tr>
            <tr>
               <th>Roof Area Type 1</th>
               <td><input type="text" name="roofareatype1" value="<?php echo $row['RoofAreaType1']?>"></td>
            </tr>
            <tr>
               <th>Roof Area Type 2</th>
               <td><input type="text" name="roofareatype2" value="<?php echo $row['RoofAreaType2']?>"></td>
            </tr>
            <tr>
               <th>Floor Perimeter Main</th>
               <td><input type="text" name="floorperimetermain" value="<?php echo $row['FloorPerimeterMain']?>"></td>
            </tr> 
            <tr>
               <th>Floor Perimeter Type 2</th>
               <td><input type="text" name="floorperimetertype2" value="<?php echo $row['FloorPerimeterType2']?>"></td>
            </tr>
            <tr>
               <th>Dimension Notes</th>
               <td>
                  <textarea name="dimensionnotes" rows="2" cols="30"><?php echo $row['DimensionNotes']?></textarea>
               </td>
            </tr>               
         </table>
         <br>         
         <br>         
         <br>         
         <br> 
         <div class="pagefooter" >
            <a href="form90.php" title="Form 90">Previous</a>
            <input type="submit" value="Save" name="submit"> 
            <a href="form93.php" title="Form 93">Next</a>
            <br><br><small>&copy; <em>STBA 2020</em></small>
         </div>
      </form>
      </div>   
   <footer>
   </footer>  
   </body>
</html>
