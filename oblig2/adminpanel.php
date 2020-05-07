<?php

include_once 'php/handleContent.php';
include_once 'header.php';
include 'php/showUsers.php';
//session_start();

    //If the usertype is not an admin, destroy the session and send the user to the index-page.
    if(isset($_SESSION['usertype'])){
        if($_SESSION["usertype"] == 'Admin'){

        } else {
            session_destroy();
            header("location: index.php");
        }
    } else {
        session_destroy();
        header("location: index.php");
    } 

    

?>




<h1>Admin Panel</h1>


<!--Table for the users in the database-->
<table>
<thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Type</th>
        <th></th>
    </tr>
</thead>
<tbody>
    <?php
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }
        echo showUsers("SELECT userid, username, type FROM user WHERE type = 'author';"); //Echo the users from the database
        
    ?>

</tbody>
</table>


<?php include 'php/footer.php';?>
</body>
</html>


<?php 

mysqli_close($connection); //Close connection to the database




?>