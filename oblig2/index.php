<?php

    include_once 'php/db/setup.php';
    include_once 'php/handleContent.php';
    include_once 'header.php';
    include 'php/showTopics.php';
    include 'php/showEntry.php';

    if(isset($_GET['sortTopics'])){
        if(isset($_SESSION['usertype'])){
        //session_start();
        $username = $_SESSION['username'];
        setcookie($username, $_GET['sort'], time() + (86400 * 30), "/");
        //header('location: index.php');


        /* $username = $_SESSION['username'];
        setcookie('sort' . $username . '', $_GET['sort'], time() + 3600, '/'); 
 */
        } else {
            
        }

    } 
    $username = $_SESSION['username'];
    $sort = isset($_COOKIE[$username]) ? $_COOKIE[$username] : "chronological";
    

    //session_start();

/*     if(isset($_SESSION['id'])){

    } else {
        header("location: php/login.php");
    } */
    
?>





<h1>Urban Dictionary</h1>
<div class="index"> 
    

    <?php if(isset($_SESSION["username"])) : ?>
        <!-- Form for adding a new topic, only available for registered users -->
        <h2>New entry:</h2>
        <form method="post" action="" class="entryform">
            <div>
                <label for="entrytitle">Entry title:</label><br />
                <input type ="text" id="entrytitle" name="entrytitle" placeholder="Entry title">
                <span><?php echo $entrytitle_err; ?></span> <!--Display error message if there are any errors-->
            
                </div>
                <br /><label for="entrydesc">Entry description:</label><br />
                <div>
                <textarea name="entrydesc" placeholder="Enter description..."></textarea><br />
                <span><?php echo $entrydesc_err; ?></span> <!--Display error message if there are any errors-->
                </div>
                <div>
                <label for="topic">Topic: </label><br />
                    <select id="entrytopic" name="entrytopic">

                        <?php 
                            //Gets all topics from the database and display them in the datalist as options.
                            $query = "SELECT topicid, topictitle FROM topic";
                            $result = $connection->query($query);

                            if (mysqli_num_rows($result) == 0){
                                echo "No topics available, please make a topic first."; //If there are no topics, the user is asked to first make a topic.
                            } else {
                                while($row = $result->fetch_assoc()){
                                    $id = $row['topicid'];
                                    $title = $row['topictitle'];
                                    echo '<option value="' . $id . '">' . $title . '</option>'; //If there are any topics, write them out with the id as value and the title as text.
                                }
                            }
                        ?>
                        
                    </select>
                <span><?php echo $entrytopic_err; ?></span> <!--Display error message if there are any errors-->
                </div>

            <br /><button type="submit" value = "Submit" name="entry"> Add Entry </button>

        </form>
    <?php endif ?>

    <!-- Div containing the latest entries -->
    <h2>Entries:</h2>
    <div class="container">
    
        <?php
            
            
            //Show entries on the index page, sort by date and limit to three entries.
            if(isset($_GET['topicid'])){
                $topicid = mysqli_real_escape_string($connection, $_GET['topicid']); //Avvoiding mySQL-injections
                
        
                //$query = "SELECT e.*, t.*, u.* FROM entry e LEFT OUTER JOIN topic t ON e.topicid = t.topicid LEFT OUTER JOIN user u ON e.authorid = u.userid WHERE t.topicid = '$topicid';";
                
                echo showEntry("SELECT e.*, t.*, u.* 
                                FROM entry e 
                                LEFT OUTER JOIN topic t 
                                    ON e.topicid = t.topicid 
                                LEFT OUTER JOIN user u 
                                    ON e.authorid = u.userid 
                                WHERE t.topicid = '$topicid';");
                } else {
                    echo "Click a topic to show entries.";
                }  
            
           /*  echo showEntry("SELECT e.*, t.*, u.userid, u.username 
                            FROM entry e 
                            LEFT OUTER JOIN topic t 
                            ON e.topicid = t.topicid 
                            LEFT OUTER JOIN user u 
                            ON e.authorid = u.userid 
                            ORDER BY e.entrydate DESC LIMIT 3;"); */
            
        ?>
    </div>
    <h2>Topics: </h2>
    <div class="topics">
    <?php if(isset($_SESSION["username"])) : ?>

        <!-- Form for adding a new topic, only available for registered users -->
        <form method="post" action="" class="topic">
                <label for="topictitle">Topic title:</label>
                <input type ="text" id="topictitle" name="topictitle">
            <button type="submit" value = "Submit" name="topic"> Add topic </button>
            <span><?php echo $topictitle_err; ?></span> <!--Display error message if there are any errors-->

        </form>
    <?php endif ?>

        <!-- Form for sorting the topics, either chornologically or by popularity -->
        <form method="GET" action="index.php">
            <label for="sort">Sort topics by:</label>
            <select name="sort" id="sort">
                    <option value="chronological" <?php if($sort == "chronological"){echo "selected";} ?>>Chronological</option>
                    <option value="popularity" <?php if($sort == "popularity"){echo "selected";} ?>>Popularity</option>
            </select>
            <button type="submit" name="sortTopics" value="sortTopics">Sort</button>
        </form>
        <p><?php echo "Your prefered sorting choice is: $sort." ?></p>



        <!-- Table for displaying the topics-->
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date added</th>
                    <th>Author</th>
                    <?php
                        //If the user is an admin display number of entries table heading and if the user is a registred user, display delet-table heading
                        if(isset($_SESSION['usertype'])) {
                            if($_SESSION['usertype'] == 'Admin'){
                                echo "<th>Number of entries:</th>";
                            }
                            echo "<th>Delete?</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php

                    
                    //if(isset($_COOKIE['sort']) == )
                     //Displaying the topics when clicking the sort-button
                    
                        //$sort = $_POST['sort'];
                        //display topics chronological
                        //if($_SESSION['username']){
                            if($_COOKIE[$username] == "chronological"){

                                $chronoOrder = "SELECT t.*, u.userid, u.username 
                                                FROM topic t 
                                                LEFT OUTER JOIN user u 
                                                ON t.authorid = u.userid 
                                                ORDER BY t.topictitle ASC;";
                                //Prepare SQL query
                                echo showTopics($chronoOrder); //Print the topics-list
                                
                            //Display topics by popularity
                            } elseif($_COOKIE[$username] == "popularity"){
                                $popOrder = "SELECT t.*, u.userid, u.username, COUNT(e.topicid) 
                                            FROM topic t 
                                            LEFT OUTER JOIN user u 
                                            ON t.authorid = u.userid 
                                            LEFT OUTER JOIN entry e 
                                            ON t.topicid = e.topicid 
                                            GROUP BY t.topicid 
                                            ORDER BY COUNT(e.topicid) DESC;";
                                echo showTopics($popOrder); //Print the topics-list
                            } else {}
                        //}

                        
                    
                     

                        /* if(!isset($_COOKIE['sort'])){
                            setcookie("sort", "test", "", "/", "localhost", false, "httponly");
                        } else {
                            echo $_COOKIE['sort'];
                        } */
                    /*} else {
                            echo showTopics("SELECT t.*, u.userid, u.username 
                                            FROM topic t 
                                            LEFT OUTER JOIN user u 
                                            ON t.authorid = u.userid 
                                            LEFT OUTER JOIN entry e 
                                            ON t.topicid = e.topicid;");
                    } */


                    

                    //echo showTopics("SELECT t.*, u.* FROM topic t LEFT OUTER JOIN user u ON t.authorid = u.userid;");

                    /* function showEntries($query){
                        //$query = "SELECT t.*, u.* FROM topic t LEFT OUTER JOIN user u ON t.authorid = u.userid "; //Prepare SQL query
                        include 'php/db/config.php';
                        $result = $connection->query($query);

                        //If there are more than zero rows, fetch the rows and place each one in the table. Last cell in the table contain a form with a delete button and a hidden input-field where the user id is stored.
                        if($result){
                            $numrows = $result->num_rows;
                            if($numrows > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    $tablerow = "<tr>
                                                <td>" . $row["topictitle"] . "</td>
                                                <td>" . $row["topicdate"] . "</td>
                                                <td>" . $row["username"] . "</td>
                                                <td><a href='entries.php?topicid=". $row["topicid"] ."'> Show all entries </a></td>";
                                    

                                    
                                    if(isset($_SESSION['usertype'])) {
                                        if($_SESSION['usertype'] == 'Admin'){
                                        $tablerow .= "<td> <form method='post'> <button type='submit' name='deleteTopic' value='Delete' id ='deleteTopic' class='delete'>Delete</button><input type='hidden' name='topicid' value='" . $row['topicid'] . "'></form></td>";
                                        $query = "SELECT COUNT(*) FROM entry WHERE topicid =" . $row['topicid'];
                                        $sql = $connection->query($query);
                                        $row = $sql->fetch_row();

                                        $tablerow .= "<td>There are: " . $row[0] . " entries</td>";
                                        }
                                    } 
                                    echo $tablerow; 
                                }
                            } else {
                            echo "<p>There are no topics in the database.</p>"; //If there are no users in the database
                            }
                        }



                    } */

                   
                   
                ?>

            </tbody>
        </table>
    </div> 
</div>
<?php include_once 'php/footer.php'?>
</body>
</html>





