<?php include 'header.php';?>


<h1>Entries</h1>
<div class="container">
    <?php

        
        $topicid = mysqli_real_escape_string($connection, $_GET['topicid']); //Avvoiding mySQL-injections
        

        //$query = "SELECT e.*, t.*, u.* FROM entry e LEFT OUTER JOIN topic t ON e.topicid = t.topicid LEFT OUTER JOIN user u ON e.authorid = u.userid WHERE t.topicid = '$topicid';";
        include 'php/showEntry.php';
        echo showEntry("SELECT e.*, t.*, u.* 
                        FROM entry e 
                        LEFT OUTER JOIN topic t 
                            ON e.topicid = t.topicid 
                        LEFT OUTER JOIN user u 
                            ON e.authorid = u.userid 
                        WHERE t.topicid = '$topicid';");
        



    ?>
</div>