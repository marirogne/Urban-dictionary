<?php

function showEntry($query){
    //$query = "SELECT e.*, t.*, u.* FROM entry e LEFT OUTER JOIN topic t ON e.topicid = t.topicid LEFT OUTER JOIN user u ON e.authorid = u.userid WHERE u.userid =" . $_SESSION['userid'];
    
    //Make a connection to the databse, and send the query to get the results.
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $result = $connection->query($query);
    //$result = mysqli_query($connection, $query);
    //$queryResults = mysqli_num_rows($result);

    //If there are more than 0 rows in the database that is matching the database, make a div containg the information.
    if($result){
        $numrows = $result->num_rows;
        if($numrows > 0){
        
            while($row = mysqli_fetch_assoc($result)){
                $entry = "<div class='entry'>
                    <h3 class='etitle'>" . $row['entrytitle'] . "</h3>
                    <p class='edate'>" . $row['entrydate'] . "</p>
                    <p class='etopic'>" . $row['topictitle'] . "</p>
                    <p class='edesc'>" . $row['description'] . "</p>
                    <p class='euser'>" . $row['username'] . "</p>";


                    if(isset($_SESSION['usertype'])) {
                        //If the usertype is an admin, provide a delete-button for each entry.
                        if($_SESSION['usertype'] == 'Admin'){
                        $entry .= " <form method='post'> 
                        <button type='submit' name='deleteEntry' value='Delete' id ='deleteEntry' class='delete'>Delete</button>
                        <input type='hidden' name='entryid' value='" . $row['entryid'] . "'>
                    </form>";
    
                        //If the usertype is an author, provide a deletebutton on the entries with a username matching the session username
                        }elseif ($_SESSION['usertype'] == 'Author'){
                            if($_SESSION['username'] == $row['username']){
                                $entry .= " <form method='post'> 
                                <button type='submit' name='deleteEntry' value='Delete' id ='deleteEntry' class='delete'>Delete</button>
                                <input type='hidden' name='entryid' value='" . $row['entryid'] . "'>
                            </form>";
                            } 
                        }
                    }
                
                    $entry .= "</div>"; //Close the div
                    echo $entry; //Print out the entry
            }
                        
        }else {
        echo "<p>There are no entries.</p>"; //If there are no entries matching the query, echo this.
        }
    }

    mysqli_close($connection); //Close connection to the database.
}


?>