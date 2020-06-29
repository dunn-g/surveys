<?php

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);
   
   # connect on localhost for user root 
   #and no password at present
   #$dbc = mysqli_connect("localhost", "tydunnco_gdunn", "PtandAuKa567", "tydunnco_Surveys"  )
   #$dbc = mysqli_connect("localhost", "tydunnco_gdunn", "C0r0ll4CK)^ZFR", "tydunnco_surveys"  )
   $dbc = mysqli_connect("localhost", "u4whuhxfak8y7", "8ba7vqb7b9m7", "dbfzqzpp7383hr"  )
      OR die(mysqli_connect_error() );
  
   #set encoding to match PHP script encoding
   mysqli_set_charset($dbc, "utf8");

?>
