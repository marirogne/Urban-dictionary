<?php 
    include_once 'header.php';
    include_once 'php/handleContent.php';
    include 'php/showtopics.php';
    include 'php/showEntry.php';

    //If the user is registered, allow the visit to this page, else lead the user to the login-page
    if(isset($_SESSION['username'])){
        

    } else {
        header("location: login.php");
    }

    

?>

<h1>Profile of <?php echo $_SESSION['username']; ?></h1><!-- Displays who the user is -->

<h2>Update your information</h2>

<h3>Update username</h3>
<!-- Form for updating username -->
<form action="" method="POST">
<div>
    <label for="currentusername">Current username: </label><br />
    <input type="text" name="currentusername">
    <span><?php echo $currentusername_err; ?></span>
</div>
<div>
    <label for="newusername">New username: </label><br />
    <input type="text" name="newusername">
    <span><?php echo $newusername_err; ?></span>
</div>
    <button type="submit" value = "Submit" name="updateusername" class="update"> Update username </button>
</form>
<span><?php echo $usernameupdated; ?></span>

<h3>Update password</h3>
<!-- Form for updating password -->
<form action="" method="POST">
    <div>
        <label for="currentpassword">Old password: </label><br />
        <input type="password" name="currentpassword">
        <span><?php echo $currentpassword_err; ?></span>
    </div>
    <div>
        <label for="newpassword">New password: </label><br />
        <input type="password" name="newpassword">
        <span><?php echo $newpassword_err; ?></span>
    </div>
    <div>
        <label for="confirmnewpassword">Confirm new password: </label><br />
        <input type="password" name="confirmnewpassword">
        <span><?php echo $confirmnewpassword_err; ?></span>
    </div>
    <button type="submit" value = "Submit" name="updatepassword" class="update"> Update password </button>
</form>
<span><?php echo $passwordupdated; ?></span>

<h2>Your entries</h2>

<!--A container for the user to view all of his or hers entries-->
<div class="container">
<?php
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }
        
        /* function showEntry($query){
            //$query = "SELECT e.*, t.*, u.* FROM entry e LEFT OUTER JOIN topic t ON e.topicid = t.topicid LEFT OUTER JOIN user u ON e.authorid = u.userid WHERE u.userid =" . $_SESSION['userid'];
            $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $result = $connection->query($query);
            //$result = mysqli_query($connection, $query);
            //$queryResults = mysqli_num_rows($result);
            if($result){
                $numrows = $result->num_rows;
                if($numrows > 0){
                
                    while($row = mysqli_fetch_assoc($result)){
                        $entry = "<div class='entry'>
                            <h3 class='etitle'>" . $row['entrytitle'] . "</h3>
                            <p class='edate'>" . $row['entrydate'] . "</p>
                            <p class='etopic'>" . $row['topictitle'] . "</p>
                            <p class='edesc'>" . $row['description'] . "</p>
                            <p class='euser'>" . $row['username'] . "</p>
                            <form method='post'> 
                                <button type='submit' name='deleteEntry' value='Delete' id ='deleteEntry' class='delete'>Delete</button>
                                <input type='hidden' name='entryid' value='" . $row['entryid'] . "'>
                            </form>
                            </div>";   
                            echo $entry;
                    }
                                
                }else {
                echo "<p>There are no entries.</p>";
                }
            }
        } */

        //Prints out the information into the table
        echo showEntry("SELECT e.*, t.*, u.* 
                        FROM entry e 
                        LEFT OUTER JOIN topic t 
                            ON e.topicid = t.topicid 
                        LEFT OUTER JOIN user u 
                            ON e.authorid = u.userid 
                        WHERE u.userid =" . $_SESSION['userid']);

    ?>
</div>


<h2>Your topics</h2>

<!--A container with a table for the user to view all of his or hers topics-->
<div>
<table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date added</th>
                    <th>Author</th>
                    <th></th>
                    <th></th>
                    <?php
                        //If the user is an admin, give the overview of how many entries there are in the topic as well
                        if(isset($_SESSION['usertype'])) {
                            if($_SESSION['usertype'] == 'Admin'){
                                echo "<th>Number of entries:</th>";
                            }
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
<?php
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }

        
        //Prints out the information into the table
        echo showTopics("SELECT t.*, u.userid, u.username 
                        FROM topic t 
                        LEFT OUTER JOIN user u 
                        ON t.authorid = u.userid 
                        WHERE t.authorid = " . $_SESSION['userid']);

       /*  $query = "SELECT * FROM topic WHERE authorid =" . $_SESSION['userid']; //Prepare SQL query
        $result = $connection->query($query); //Send query to the database

        //If there are more than zero rows, fetch the rows and place each one in the table. Last cell in the table contain a form with a delete button and a hidden input-field where the user id is stored.
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<div class='topicbox'>
                <h3>" . $row['topictitle'] . "</h3>
                <form method='post'>
                <button type='submit' name='deleteTopic' value='deleteOwnTopic' id='delete' class ='delete'>Delete</button>
                <input type='hidden' name='topicid' value='" . $row['topicid'] . "'>
                </form>
                </div>";

            }
        } else {
            echo "<div><p>You have not created any topics yet.</p></div>"; //If there are no users in the database
        } */

    ?>
    </tbody>
    </table>
</div>



<?php include 'php/footer.php';?>
</body>
</html>
