<?php

/********************************************************************** 
Defining the connection to the database______


***********************************************************************/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'urbandictionary');

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($connection === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


?>