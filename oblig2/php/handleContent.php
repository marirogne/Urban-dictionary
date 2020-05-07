<?php

require_once 'db/config.php';



$session = session_start();
$session;

/* $username = $_SESSION['username'];
$userid = $_SESSION['userid']; */
$_SESSION['isloggedin'] = TRUE;
/* $_SESSION["username"] = "green";
$_SESSION["userid"] = "cat";
echo "Session variables are set."; */
//setcookie('user', $username, time() + (86400 * 30), "/");
if(!$connection){
    session_unset();
    session_destroy();
    //Sends the users to the index page
    header("location: ../index.php");
    exit;

}


/* if (isset($_GET['sortTopics']) && isset($_SESSION['username'])) {
    $sortingMethod = $_GET['sortTopics'];
    $username = $_SESSION['username'];
    setcookie('sorting'. $username . '', $sortingMethod, time() + (86400 * 30), "/");
    header('location: index.php');

} */

function sanitize($variable, $connect){
    $variable = stripslashes($_POST[$variable]);
    $variable = htmlentities($variable);
    $variable = strip_tags($variable);
    $variable = $connect->real_escape_string($variable);

    return $variable;
}

/**********************************************************************
Register new user to the database______


***********************************************************************/


$username_err_reg = $password_err_reg = $confirm_password_err_reg = ""; //Preparing the error-variables     
        


//if($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['register'])){
        

    //Set variables for the input in the input-fields in the register-form
        $username_reg = sanitize("username_reg", $connection);
        $password_reg = sanitize("password_reg", $connection);
        $confirm_password_reg = sanitize("confirm_password_reg", $connection);


        

    if(empty(trim($username_reg))) {
        $username_err_reg = "Please enter a username."; //If the username-input is empty after removing whitespace, give this feedback.
    } else {
        $query = "SELECT userid FROM user WHERE username = ?"; //If the username-input is not empty, prepare an SQL-statement

        if($stmt = mysqli_prepare($connection, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username); //binds the username parameter as a string to the statement. T

            $param_username = trim($username_reg); //defines the username parameter as the trimmed username

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err_reg = "This username is already taken."; //Checking if the username is already taken and then provide feedback
                //} elseif(!preg_match("/^[a-zA-Z0-9]{5, 12}*$/", $username_reg)){
                    $username_err_reg = "Username must consist of capital A-Z, lowercase a-z, or numbers 0-9 only, and have a minimum of 5 characters and a maximum of 12.";
                } else{
                    $username_reg = trim($username_reg); //Remove whitespace and set username
                }
            } else {
                echo "Oops! Something went wrong. Please try again later."; //If the query was not prepared, display this message
            }
        
            mysqli_stmt_close($stmt); //Close statement
        }

    }

    if(empty(trim($password_reg))) {
        $password_err_reg = "Please enter a password."; //If the password-input is empty after removing whitespace, give this feedback.
    //} elseif(!preg_match("/^{5, 12}*$/", $password_reg)) {
        $password_err_reg = "Password must have atleast 5 characters and maximum 12 characters.";
    } else {
        $password_reg = trim($password_reg); //removes whitespace from the password
    }

    if(empty(trim($confirm_password_reg))) {
        $confirm_password_err_reg = "Please confirm password."; //If the confirm password-input is empty after removing whitespace, give this feedback.
    } else {
        //$confirm_password_reg = trim($confirm_password_reg);
        if($password_reg != $confirm_password_reg){
            $confirm_password_err_reg = "Password did not match."; //if the password the two password-inputs does not match, give this feedback.
        }
    }

    if(empty($username_err_reg) && empty($password_err_reg) && empty($confirm_password_err_reg)){
        $query = "INSERT INTO user (username, password) VALUES (?, ?)"; //If the error-messages for username, password, and confirm password, prepare an SQL-statement.

        if($stmt = mysqli_prepare($connection, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);//binds two string parameters to the prepared SQL-statement

            $param_username = $username_reg; //Defines the username parameter
            $param_password = password_hash($password_reg, PASSWORD_DEFAULT); //Defining the password parameter and securing the password by hashing it

            if(mysqli_stmt_execute($stmt)) {
                header("location: login.php"); //If the statement went well, transfer the user to the login-page
            } else {
                echo "Something went wrong. Please try again later."; //If the statement didn't go through, show message
            }

            mysqli_stmt_close($stmt); //Close SQL-statement

        }
    }

    //mysqli_close($connection);
}
//}





/********************************************************************** 
Logging in to the database______


***********************************************************************/

 
$username_err = $password_err = ""; //Preparing the error-variables 

//if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['login'])){

        //Set variables for the input in the input-fields in the login-form
        $username = sanitize("username", $connection); 
        $password = sanitize("password", $connection);
        

        
        if(empty(trim($username))) {
            $username_err = "Please enter your username"; //If the username-input is empty after removing whitespace, give this feedback
        } else {
            $username = trim($username); //If there is a username in the input, define username variable as the username in the input-field without the whitespace
        }

        if(empty(trim($password))) {
            $password_err = "Please enter your password.";//If the password-input is empty after removing whitespace, give this feedback
        } else {
            $password = trim($password); //If there is a password in the input, define password variable as the password in the input-field without the whitespace
        }

        if(empty($username_err) && empty($password_err)) {
            $query = "SELECT userid, username, password, type FROM user WHERE username = ?"; //If there are no error-feedback, prepare SQL-statement

            if($stmt = mysqli_prepare($connection, $query)){
                mysqli_stmt_bind_param($stmt, "s", $param_username); //binds a string parameter to the prepared SQL-statement

                $param_username = $username; //Defines the username parameter as username

                if(mysqli_stmt_execute($stmt)){

                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $usertype); //If the username exists in the database, bind these variables to the SQL-statement

                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                //If the password is equal to the hashed password, define session id, username, and usertype as variables, and transfer the user to index.php
                                
                                //session_start();
                                //$_SESSION["loggedin"] = true;
                                $_SESSION["userid"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["usertype"] = $usertype;

                                header("location: index.php");
                            } else {
                                $password_err = "The password entered was not valid."; //If the password is not equal to the hashed password in the database, return this error message.
                            }
                        }

                    } else {
                        $username_err = "Username does not exist."; //If the username doesn't exist, display this error-message
                    }
                } else {
                    echo "Ops! Try again later!"; //If the SQL-statment wasn't executed, display this error-message
                }

                mysqli_stmt_close($stmt); //Close SQL-statement
            }
        }

        //mysqli_close($connection);



    }
//}





/********************************************************************** 
Adding a new topic to the database______


***********************************************************************/


if(isset($_SESSION["username"])){

    $topictitle_err = ""; //Preparing error message

    if(isset($_POST["topic"])){

        //Set variable for the title-input
        $topictitle = sanitize("topictitle", $connection);
        

        if(empty(trim($topictitle))){
            $topictitle_err = "Please choose a topic title."; //If the title-input field is empty
        } else {

            //Checking if the topic exists, by preparing an SQL statement and checking if there is an id for the topic title
            $query = "SELECT topicid FROM topic WHERE topictitle = ?";
            if($stmt = mysqli_prepare($connection, $query)){
                mysqli_stmt_bind_param($stmt, "s", $param_topictitle);
                $param_topictitle = trim($topictitle);

                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $topictitle_err = "This topic already exist."; //IF it does, display error message
                    } else {
                        $topictitle = trim($topictitle); //If it does not, remove whitespace
                    }
                } else {
                    echo "Oops, something went wrong. Please try again later."; //If the statement is not executed correctly, echo this message.
                }

                mysqli_stmt_close($stmt); //Close statement
            }
        }
        
        //If there are no errors, prepare an SQL statement, bind parameters, define parameters, execute, otherwise echo error message. Close statement.
        if(empty($topictitle_err)){
            $query = "INSERT INTO topic (topictitle, authorid) VALUES (?, ?)";
            if($stmt = mysqli_prepare($connection, $query)){
                mysqli_stmt_bind_param($stmt, "si", $param_topictitle, $param_author);

                $param_topictitle = $topictitle;
                $param_author = $_SESSION['userid'];

                if(mysqli_stmt_execute($stmt)){
                    header("location: index.php");
                    echo "Topic added successfully.";
                } else {
                    echo "Oops, something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }

    }
}



/********************************************************************** 
Create a new entry in the database______


***********************************************************************/
if(isset($_SESSION["username"])){

    $entrytitle_err = $entrydesc_err = $entrytopic_err =""; //Preparing errors for the entry-form

    if(isset($_POST["entry"])){
        //Set variables for the input in the input-fields in the new entry-form
        $entrytitle = sanitize("entrytitle", $connection);
        $entrydesc = sanitize("entrydesc", $connection);
        $entrytopic = sanitize("entrytopic", $connection);

        
        if(empty(trim($entrytitle))){
            $entrytitle_err = "Please choose a entry title."; //If the title-input field is empty, display this error message
        } else {
            $entrytitle = trim($entrytitle); //Removes whitespace from the entry title
        }

        if(empty(trim($entrydesc))){
            $entrydesc_err = "Please enter a description."; //If the description-input field is empty, display this error message
        } else {
            $entrydesc = trim($entrydesc);
        }

        if(empty($entrytopic)){
            $entrytopic_err = "Please enter a topic."; //If the topic-input field is empty, display this error message
        } //else {
            //$entrytopic = trim($entrytopic);
        //}
        
        //If all of the error-statements are empty, prepare an SQL statement.
        if(empty($entrytitle_err) && empty($entrydesc_err) && empty($entrytopic_err)){
            $query = "INSERT INTO entry (entrytitle, description, authorid, topicid) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($connection, $query)){
                mysqli_stmt_bind_param($stmt, "ssii", $param_entrytitle, $param_entrydesc, $param_authorid, $param_topic); //Binds parameters to the values

                //Defines the parameters to be added to the table in the database.
                $param_entrytitle = $entrytitle;
                $param_entrydesc = $entrydesc;
                $param_authorid = $_SESSION['userid'];
                $param_topic = $entrytopic;


                if(mysqli_stmt_execute($stmt)){
                    header("location: index.php");//If the statement is executed correctly, take the user to the index page.
                    //echo "Entry added successfully."; 
                } else {
                    echo "Oops, something went wrong. Please try again later."; //If the statement is not executed correctly, inform the user.
                }

                mysqli_stmt_close($stmt); //Close statement.
            }
        }

    }
}

/********************************************************************** 
Updating a username in the database______


***********************************************************************/

if(isset($_SESSION["username"])){

    $currentusername_err = $newusername_err = $usernameupdated = ""; //Preparing errors and update-message for the username

    //If the update username-button is clicked.
    if(isset($_POST["updateusername"])){
        
        //Define the username variables.
        $currentusername = sanitize("currentusername", $connection);
        $newusername = sanitize("newusername", $connection);

        if(empty(trim($currentusername))){
            $currentusername_err = "Please enter your current username."; //If the current username field is empty, display message
        } elseif($currentusername != $_SESSION["username"]){
            $currentusername_err = "The username you have typed is wrong."; //If the current username field and the session username does not match, display message
        } else {
            $currentusername = trim($currentusername); //Otherwise, remove whitespace from current username
        }

        if(empty(trim($newusername))){
            $newusername_err = "Please enter a new username."; //If the new username field is empty, display message
        } else {
            $newusername = trim($newusername); //Otherwise, remove whitespace
        }

        //If the current username error variable and the new username error variable are empty, do the following code:
        if(empty($currentusername_err) && empty($newusername_err)){
            /* $id = $_SESSION['id'];
            $newusername = trim($newusername); */
            $query = "UPDATE user SET username = ? WHERE userid = ?"; //Prepare an SQL-query
            //mysqli_query($connection, $query);


            if($stmt = mysqli_prepare($connection, $query)){
                mysqli_stmt_bind_param($stmt, "si", $param_newusername, $param_userid); //If the statement is prepared, bind parameteres

                //Define the parameters for the query
                $param_newusername = $newusername;
                $param_userid = $_SESSION['userid'];

                if(mysqli_stmt_execute($stmt)){
                    //header("location: php/logout.php");
                    $usernameupdated = "Your username was successfully updated. HURRA!"; //If the query is executed, display this message
                    //$_SESSION['username'] = $newusername;
                    
                } else {
                    $usernameupdated = "Something went wrong. Please try again later."; //If the query is not executed, display this message
                }

                mysqli_stmt_close($stmt); //Close statement

            }
        }
    }



}


/********************************************************************** 
Updating a password in the database______


***********************************************************************/

if(isset($_SESSION["username"])){

    $currentpassword_err = $newpassword_err = $confirmnewpassword_err = $passwordupdated = ""; //Preparing errors and update-message for updating the password

    //If the update password-button is clicked.
    if(isset($_POST["updatepassword"])){
        
        //Defining the variables.
        $currentpassword = sanitize("currentpassword", $connection);
        $newpassword = sanitize("newpassword", $connection);
        $confirmnewpassword = sanitize("confirmnewpassword", $connection);
        $userid = $_SESSION['userid'];

        if(empty(trim($currentpassword))){
            $currentpassword_err = "Please enter your current password."; //If the current password field is empty, display this message.
     /*    } elseif($currentpassword != isset($_SESSION['password'])){
            $currentpassword_err = "The password you have typed is wrong."; */
        } else {
            $currentpassword = trim($currentpassword); //If the current password field is not empty, remove whitespace.
        }


        if(empty(trim($newpassword))){
            $newpassword_err = "Please enter a new password."; //If the new password field is empty, display this message.
        } else {
            $newpassword = trim($newpassword); //If the new password field is not empty, remove whitespace.
        }

        if(empty(trim($confirmnewpassword))){
            $confirmnewpassword_err = "Please confirm your new password."; //If the confirm new password field is empty, display this message.
       /*  } elseif($newpassword != $confirmnewpassword) {
            $confirmnewpassword_err = "The password did not match the new password.";
 */
        } else {
            $confirmnewpassword = trim($confirmnewpassword);  //If the confirm new password field is not empty, remove whitespace.
        }

        //If all the errormessages are emptym do the following code.
        if(empty($currentpassword_err) && empty($newpassword_err) && empty($confirmnewpassword_err)){

            $query = "SELECT * FROM user WHERE userid = ?"; //Prepare an SQL-query
            $stmt = mysqli_stmt_init($connection); //Make a connection with the statement
            //if the query is not prepared, exit.
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $param_userid); //Bind parameters to the SQL-statement
                $param_userid = $userid; //Define the parameter
                mysqli_stmt_execute($stmt); //Execute SQL-statement
                $result = mysqli_stmt_get_result($stmt); //Get the result og the statement.

                if($row = mysqli_fetch_assoc($result)) {
                    $checkpassword = password_verify($currentpassword, $row['password']); //Verifying the password
                    if($checkpassword == false){
                        $currentpassword_err = "Wrong password, idiot."; //If the current password is wrong, display message.
                    } elseif ($newpassword != $confirmnewpassword){
                        $confirmnewpassword_err = "The password did not match the new password."; //If the new password field and the confirm password field does not match, display message.
                    
                    //If all the password-information is entered correctly:
                    } elseif ($checkpassword == true){
                        $query = "UPDATE user SET password = ? WHERE userid = ?"; //Prepare an SQL-query
                        $stmt = mysqli_stmt_init($connection); //Make a connection to the database

                        
                        if($stmt = mysqli_prepare($connection, $query)){
                            mysqli_stmt_bind_param($stmt, "si", $param_newpassword, $param_userid);//If the statement was prepared, bind parameters to the statement

                            $param_newpassword = password_hash($newpassword, PASSWORD_DEFAULT); //Define and hash the new passowrd
                            $param_userid = $userid; //Define the userid

                            if(mysqli_stmt_execute($stmt)){

                                $passwordupdated = "Your password was successfully updated. HURRA!"; //If the statement was executed, display message.

                                
                            } else {
                                $passwordupdated = "Something went wrong. Please try again later."; //If the statement was not executed, display message.
                            }

                            mysqli_stmt_close($stmt); //Close statement
                        } else {
                            echo "Ops something went wrong, try again later."; //If the statement was not prepared.
                        }
                    }
                }

            

            }
            //mysqli_stmt_close($stmt);
        }
    }



}


/********************************************************************** 
Deleting a topic from the database______


***********************************************************************/


if(isset($_POST['deleteTopic']))
    {
        $query ="DELETE FROM topic WHERE topicid=" . $_REQUEST['topicid']; //Delete the selected row from the table and from the database.
        $connection->query($query); //Run the query
    }


/********************************************************************** 
Deleting a topic from the database______


***********************************************************************/


if(isset($_POST['deleteEntry']))
    {
        $query ="DELETE FROM entry WHERE entryid=" . $_REQUEST['entryid']; //Delete the selected row from the table and from the database.
        $connection->query($query); //Run the query
    }




/********************************************************************** 
Deleting a user from the database______


***********************************************************************/

if(isset($_POST['deleteUser']))
    {
        $query ="DELETE FROM user WHERE userid=". $_REQUEST['userid']; //Delete the selected row from the table and from the database.
        $connection->query($query); //Run the query
    }

//mysqli_close($connection);










?>