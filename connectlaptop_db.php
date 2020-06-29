<?php

   ini_set('display_errors', 'On');
   error_reporting(E_ALL | E_STRICT);
   
   # connect on localhost for user root 
   #and no password at present

   $host="localhost";
   $acct="root";
   $pwrd="";
   $msdb="surveys";
   
   #$dbc = mysqli_connect("localhost", "root", "", "surveys"  )
   $dbc = mysqli_connect($host, $acct, $pwrd, $msdb  )
      OR die(mysqli_connect_error() );
  
   #set encoding to match PHP script encoding
   mysqli_set_charset($dbc, "utf8");

?>