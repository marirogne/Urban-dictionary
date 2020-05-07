<?php


function showTopics($query){
    
    //$query = "SELECT t.*, u.* FROM topic t LEFT OUTER JOIN user u ON t.authorid = u.userid "; //Prepare SQL query
    //include 'php/db/config.php';

    //Create a connection to database and send the query.
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $result = $connection->query($query);

    //If there are more than zero rows, fetch the rows and place each one in a cell in a row in the table. 
    if($result){
        $numrows = $result->num_rows;
        if($numrows > 0){
            while($row = mysqli_fetch_assoc($result)){
                //The topictitle provide a link to the page showing all the entries under this topic.
                $tablerow = "<tr>
                            <td><a href='index.php?topicid=". $row["topicid"] ."'>" . $row["topictitle"] . "</a></td>
                            <td>" . $row["topicdate"] . "</td>
                            <td>" . $row["username"] . "</td>";
                

                
                if(isset($_SESSION['usertype'])) {
                    //If the user is an admin, a tablecell with an overview of how many entries there are in each topic will be provided.
                    if($_SESSION['usertype'] == 'Admin'){
                        //The query for finding the number of entries for each topic, make a connection and get the result. Then write out the result in a table cell.
                        $countquery = "SELECT COUNT(*) FROM entry WHERE topicid =" . $row['topicid']; 
                        $countresult = $connection->query($countquery);
                        $countrow = $countresult->fetch_row();
                        $tablerow .= "<td>There are: " . $countrow[0] . " entries</td>";

                        //Adding a delete button for each topic 
                        $tablerow .= "<td> <form method='post'> <button type='submit' name='deleteTopic' value='Delete' id ='deleteTopic' class='delete'>Delete</button><input type='hidden' name='topicid' value='" . $row['topicid'] . "'></form></td>";
                        
                        
                    }elseif ($_SESSION['usertype'] == 'Author'){ //If the user is an author
                        //If the session-username is equal to the username that created the topic
                        if($_SESSION['username'] == $row['username']){
                            //Add a delete button for this particular user.
                            $tablerow .= "<td><form method='post'> <button type='submit' name='deleteTopic' value='Delete' id ='deleteTopic' class='delete'>Delete</button><input type='hidden' name='topicid' value='" . $row['topicid'] . "'></form></td>";
                        } else {
                            //If not, add an empty cell
                            $tablerow .="<td></td>";
                        }
                    }
                }
                $tablerow .= "</tr>"; //close the table row
                echo $tablerow; //Print the tablerow out.
            }
        } else {
        echo "<p>There are no topics.</p>"; //If there are no users in the database
        }
    }

    mysqli_close($connection); //Close connection to the database.


}

?>