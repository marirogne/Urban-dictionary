<?php

function showUsers($query){
    
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); //Make a connection to the database
    $result = $connection->query($query); //Send query to the database

    //If there are more than zero rows, fetch the rows and place each one in the table. Last cell in the table contain a form with a delete button and a hidden input-field where the user id is stored.
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>
            <td>" . $row['userid'] . "</td>
            <td>" . $row["username"] . "</td>
            <td>" . $row["type"] . "</td>
            <td> <form method='POST'> <button type='submit' name='deleteUser' value='Delete' id='deleteUser' class ='delete'>Delete</button><input type='hidden' name='userid' value='" . $row['userid'] . "'></form></td></tr>";

        }
    } else {
        echo "There are no users in the database."; //If there are no users in the database
    }
}

?>