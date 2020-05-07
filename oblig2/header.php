<?php
include_once 'php/handleContent.php';
    //session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <header>
    <nav>
        <ul>
            <!--All visitors of the website will be able to view the home page.-->
            <li><a href="index.php">Home</a></li>
            <!--<li><a href="topics.php">Topics</a></li>-->
            

            <!--If the visitor is not logged in, login and register user will be displayed in the menu-->
            <?php if(!isset($_SESSION["username"])) : ?> 
            
                <li> <a href="register.php">Register user</a></li>
                <li class="loginout"> <a href="login.php">Login</a></li>
            <!-- If a user is logged in, replace login and register user in the menu with profile and log out.-->
            <?php elseif(isset($_SESSION["username"])) : ?>
            <!-- <li> <a href="createEntry.php">Create entry</a></li> -->
                <li><a href="profile.php">Profile</a></li>
                
                    <!--If the user is an admin, show the admin panel in the menu in addition to the options visible to the authors-->
                    <?php if(isset($_SESSION['usertype'])) : ?>
                        <?php if($_SESSION["usertype"] == 'Admin') : ?>
                            <li><a href="adminpanel.php">Admin Panel</a></li>
                        <?php endif ?>
                    <?php endif ?>
                <li class = "loginout"><a href="php/logout.php">Log out</a></li><!--Runs the logout.php file when log out is clicked-->
            <?php endif ?>
            

        </ul>
    </nav>

    <!-- The search form, available to all from all pages -->
    <form action="search.php" method="POST" class="search">
        <input type="text" placeholder="Search..." id="search" name="search">
        <button type="submit" value="Search" name="searchbutton">Search</button>
    
    </form>
    </header>
