<?php

/********************************************************************** 
Creating the database______


***********************************************************************/

//Defining the variables about the database and creates a connection. If the connection fails, then die.
$server = 'localhost';
$username = 'root';
$password = '';
$dbName = 'urbanDictionary';

$connect = new mysqli($server, $username, $password);

if($connect->connect_error){
    die("Failed to connect.");
}


//If it is not possible to select the database, then create it, and then select it.
if(!mysqli_select_db($connect, $dbName)){
    $createDB = "CREATE DATABASE " . $dbName;
    $connect->query($createDB);
    $connect->select_db($dbName);
    createTables($connect);
    /* if ($connect->query($createDB) === true) {
        echo "Created database.";
    } else {
        echo "Failed to create database.";
    }
 */
}

function createTables($connect){
//Create the user table and place it in the database

    $userTable = "CREATE TABLE user(
        userid INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(250) NOT NULL UNIQUE,
        password VARCHAR(250) NOT NULL,
        type ENUM('Author', 'Admin') NOT NULL DEFAULT 'Author',
        CONSTRAINT pk_user PRIMARY KEY (userid)
    )";

    $connect->query($userTable);

    //Create the dummy-admin of the database, hashing the password, and placing it in the database as admin.

    $password = "admin";
    $adminpassword = password_hash($password, PASSWORD_DEFAULT);

    $createAdmin = "INSERT INTO user (username, password, type) VALUES ('Admin', '$adminpassword', 'Admin')";

    $connect->query($createAdmin);


    //Create the topic table and place it in the database

    $topicTable = "CREATE TABLE topic (
        topicid INT NOT NULL AUTO_INCREMENT,
        topictitle VARCHAR(250) NOT NULL UNIQUE,
        topicdate DATETIME DEFAULT NOW() NOT NULL,
        authorid INT NOT NULL,
        CONSTRAINT pk_topic PRIMARY KEY (topicid),
        CONSTRAINT fk_topic_author FOREIGN KEY (authorid) REFERENCES user(userid) ON UPDATE CASCADE ON DELETE CASCADE
    )";

    $connect->query($topicTable);

    //Create the entry table and place it in the database

    $entryTable = "CREATE TABLE entry (
        entryid INT NOT NULL AUTO_INCREMENT,
        entrytitle VARCHAR(250) NOT NULL,
        entrydate DATETIME DEFAULT NOW(),
        description VARCHAR(1000) NOT NULL, 
        authorid INT NOT NULL,
        topicid INT NOT NULL,
        CONSTRAINT pk_entry PRIMARY KEY (entryid),
        CONSTRAINT fk_entry_author FOREIGN KEY (authorid) REFERENCES user(userid) ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_entry_topic FOREIGN KEY (topicid) REFERENCES topic(topicid) ON UPDATE CASCADE ON DELETE CASCADE
        )";


    $connect->query($entryTable);

    //Alter entry table and add fulltext index to it
    $entryidx = "ALTER TABLE entry ADD FULLTEXT KEY ft_entry (entrytitle, description);";

    $connect ->query($entryidx);

    //Alter topic table and add fulltext index to it
    $topicidx = "ALTER TABLE topic ADD FULLTEXT KEY ft_topic (topictitle);";

    $connect ->query($topicidx);


    //Close connection to the database
    $connect->close();


}




?>