
<?php
// start session
session_start();
// register session variables
if ( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ) 
{
   # Display the form.
   echo '
<!DOCTYPE HTML>
<html lang = "en">
   <head><meta charset="UTF-8">
      <!--<img style="margin 0px" height="60px" align="right" src="stba_logo_rgb-sm.jpg" alt="STBA Logo" ></img>-->
      <link rel="shortcut icon" type="image/x-icon" href="stba_logo_rgb-sm.jpg" />
      <title>Surveys - Select Option</title>
      <link rel="stylesheet" href="Survey_StyleSheet.css" type="text/css"/>
      <style>
         body {
            color: blue;
            height: 100%;
         }

         a {
            background-color: #f2f4f4;
         }
         
         div {
            width: 100%;
            height: 740px;
            display: block;
            position: relative;
         }

         div::after {
            content: "";
            background: url("images/old-houses.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; 
            opacity: 0.25;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            position: absolute;
            z-index: -1;   
         }
         
         .stba_logo{
            margin: 0px; 
            height: 100px;
            width 100px;
            align: right; 
            src: url"images/stba_logo_rgb.jpg"; 
            alt: "STBA Logo";
            display: inline-block;
     
            <!--Responsible retrofit small.png-->
         
         }
      </style>
   </head>
   <body>
      
      <form class="getchoice" action="" method="POST">
      <div>
         <p>
         <h2>Welcome to the STBA’s Responsible Retrofit Survey database</h2>
         <img style="margin 0px" height="110px" align="right" src="images/STBA_LOGO_RGB.jpg" alt="STBA Logo" ></img>
         These forms will allow you to capture information on a dwelling so that<br>
         a responsible retrofit plan can be created from the data collected.
         <br><br>
         Remember to take photos of all the areas of the dwelling so that a<br>
         Retrofit Designer can work with confidence and full knowledge of the<br>
         risks associated with any project.
         </p>
         <br><br><br>
       
         <p>You are now logged in as, ' . $_SESSION['SurveyorFirstName'] . ' ' . $_SESSION['SurveyorLastName'] .'(' . $_SESSION[ 'SurveyorId' ] .')  or,   <a style="background-color:transparent"; href="survey_goodbye.php">Logout</a></p>
         
         <img style="margin 0px" height="350px" align="right" src="images/Responsible_retrofit_small.png" alt="Retrofit Logo" ></img>
         
         <h3>Please select one of the following :-</h3>
         <br>
         <br>

         <ul>
            <li>Property: &nbsp;<a href="PropertyCreate.php" name="createproperty" title="Create New Property">Add New Property</a> &nbsp;&nbsp; <a href="getpropertyid.php" name="editproperty" title="Edit Property">Amend Existing Property Details</a></li>
            <br>
            <li>Survey: &nbsp;&nbsp;&nbsp;<a href="getPropertyIdForNewSurvey.php" name="createsurvey" title="Add New Survey">Add New Survey</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="getsurveyid.php" name="editsurvey" title="Amend Existing Survey Details">Amend Existing Survey Details</a></li>
            <br>
            <li>Surveyor: <a href="surveyorCreate.php" name="createsurveyor" title="Add New Surveyor">Add New Surveyor</a> &nbsp; <a href="surveyorAmend.php" name="editsurveyor" title="Amend Surveyor Details">Amend Existing Surveyor Details</a></li>
            <br>      
            <li>Client: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="ClientCreate.php" name="createclient" title="Add New Client">Add New Client</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="getclientid.php" name="editclient" title="Amend Existing Client Details">Amend Existing Client Details</a></li>	
            <br>
      </div>
      </form>
   </body>
</html>';

      $name = 'title';
      $_SESSION['name'] = $name;

   }
   else
   {
     # Handle the form submission.
     # Empty check.

      if ( !empty ( $_POST['editoradd'] ) )
      {
         
         #print_r($_POST);
         
      };
   }
?>


