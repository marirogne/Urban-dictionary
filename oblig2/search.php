<?php
include_once 'php/handleContent.php';
include_once 'header.php';
include 'php/showEntry.php';

?>

<h1>Search page</h1>

<?php


/* if(isset($_GET['searchbutton'])){
    $search = $_GET['search'];
    $search = strip_tags($search);
    $search = $connection->real_escape_string($search);
    if($search == ""){
        echo "<p>Your search is empty.</p>";
    } else {
        $query = "SELECT entrytitle FROM entry WHERE MATCH (entrytitle) AGAINST ('$search*' IN BOOLEAN MODE)";
        $result = mysqli_query($query) or die("Problem with query: " . mysqli_error());
        $count = mysqli_num_rows($result);

        echo "<h2>Search results:</h2>";
        echo "<p>You searched for $search and there are $count matches.</p>";

        $tablerows = mysqli_num_rows($result);
        $i = 0;
        while($i <$tablerows){
            $entrytitle = mysqli_result($result, $i, "entrytitle");
            echo "<h3>$entrytitle</h3>";
            $i ++;
        }
    }
} */






    if(isset($_POST['searchbutton'])) {
        $search = $_POST['search']; //Removes any characters that are not allowed in an SQL statement (Prevents SQL-injection)
        /* $search = explode(" ", $search);
        foreach($search as $search){
            $search = "+" . $search . " ";
        
            
        } */

        if($search == ""){
            echo "<p>Search field is empty</p>";
        } else {
        /* $query = "SELECT e.*, t.*, u.userid, u.username 
                  FROM entry e 
                  LEFT OUTER JOIN topic t 
                    ON e.topicid = t.topicid 
                  LEFT OUTER JOIN user u 
                    ON e.authorid = u.userid 
                  WHERE MATCH (e.entrytitle) AGAINST ('*$search*' IN BOOLEAN MODE)"; */ //Checks if there are any title with the string from the search-input field.
        //$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $query = "SELECT e.*, t.*, u.* FROM entry e LEFT OUTER JOIN topic t ON e.topicid = t.topicid LEFT OUTER JOIN user u ON e.authorid = u.userid WHERE (MATCH(e.entrytitle, e.description) AGAINST('*$search*' IN BOOLEAN MODE) OR MATCH(t.topictitle) AGAINST('*$search*' IN BOOLEAN MODE));";
        //$query = "SELECT entrytitle FROM entry WHERE entrytitle LIKE '%$search%';";
        /* include 'php/entries.php';
        showEntry("SELECT entrytitle FROM entry WHERE MATCH (entrytitle) AGAINST ('$search' IN BOOLEAN MODE)"); */
        /* $result = mysqli_query($connection, $query);
        $queryResult = mysqli_num_rows($result);  */

        $result = mysqli_query($connection, $query);
        $queryResults = mysqli_num_rows($result);
        echo "There are $queryResults results on your keyword: $search";
        echo "<div class='container'>";
        showEntry($query);
        echo "</div>";
        
        //$result = $connection->query($query);
        /* $result = mysqli_query($connection, $query);
        $queryResults = mysqli_num_rows($result);
        
        //If there are more than 0 rows in the database that is matching the database, make a div containg the information.

            if($queryResults > 0){
                echo "<div><p>There are " . $queryResults . " results! </p></div> <div class='container'>"; //Displays how many search results there are
                while($row = mysqli_fetch_assoc($result)){
                    
            //Echo divs with the entry-information    
        
                        echo "<div class='entry'>
                                
                                <h3 class='etitle'>" . $row['entrytitle'] . "</h3>
                                <p>" . $row['topictitle'] .  "</p>
                                </div>";
                        //echo $entry;   

                    
        //IF there are more than 0 results, do the following code and print the information.
      
            
                }
            echo "</div>";
            }else {
                echo "There are no search results."; //If there are no matches to the searched word. 
            }*/
        } 
    }
 
?>

</body>
</html>


<!-- "<a href='entry.php?title=" . $row['id'] . "'><div>
                    <p>" . $row['id'] . "</p>
                    <h3>" . $row['title'] . "</h3>
                    <p>" . $row['date'] . "</p>
                    <p>" . $row['description'] . "</p>
                    <p>" . $row['authorid'] . "</p>
                    <p>" . $row['topicid'] . "</p>
                    </div></a>"; -->