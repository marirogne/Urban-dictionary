<?php 
//Ending the session
session_start();

//$_SESSION = array();
session_unset();
session_destroy();
//Sends the users to the index page
header("location: ../index.php");
exit;


?>