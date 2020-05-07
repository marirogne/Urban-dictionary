<?php 
//include 'php/handleContent.php';
include_once 'header.php';
?>

<h1>Entry page:</h1>
<div class="container">
    <?php

        
        $entryid = mysqli_real_escape_string($connection, $_GET['entryid']); //Avvoiding mySQL-injections
        

        $query = "SELECT e.*, t.*, u.* 
        FROM entry e 
        LEFT OUTER JOIN topic t 
            ON e.topicid = t.topicid 
        LEFT OUTER JOIN user u 
            ON e.authorid = u.userid 
        WHERE e.entryid = '$entryid';";
        
        
        //$result = $connection->query($query);
        $result = mysqli_query($connection, $query);
        $queryResults = mysqli_num_rows($result);
        if ($queryResults > 0){
            //$numrows = $result->num_rows;
            //if($numrows > 0){
                while($row = mysqli_fetch_assoc($result)){
                    
                    $entry = "<div class='entry'>
                        <h3 class='etitle'>" . $row['entrytitle'] . "</h3>
                        <p class='edate'>" . $row['entrydate'] . "</p>
                        <p class='etopic'>" . $row['topictitle'] . "</p>
                        <p class='edesc'>" . $row['description'] . "</p>
                        <p class='euser'>" . $row['username'] . "</p>";
                    if(isset($_SESSION['usertype'])) {
                        if($_SESSION['usertype'] == 'Admin'){
                        $entry .= "<form method='post'> <button type='submit' name='deleteEntry' value='Delete' id ='deleteEntry' class='delete'>Delete</button><input type='hidden' name='entryid' value='" . $row['entryid'] . "'></form>";
                            
                        }
                    }
                    
                    $entry .= "</div>";
                    echo $entry;
                        
                }
            //}
        
        }

    ?>
</div>

</body>
</html>