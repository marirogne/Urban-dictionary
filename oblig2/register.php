
<!-- 



https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php

 -->
<?php

//require_once "db/config.php";
include 'php/handleContent.php';
include_once 'header.php';



?>

        <h2>Register user</h2>
        <p>Please register as a user here:</p>
        <form method="post" action="">
            <div>
                <label for="username">Username: </label><br />
                <input type ="text" id="username_reg" name="username_reg" class="form-control" value="">
                <span><?php echo $username_err_reg; ?></span> <!--Display error message if there are any errors-->
            </div>

            <div> 
                <label for="password">Password: </label><br />
                <input type ="password" id="password_reg" name="password_reg">
                <span><?php echo $password_err_reg; ?></span> <!--Display error message if there are any errors-->
            </div>

            <div> 
                <label for="confirm_password">Confirm password: </label><br />
                <input type ="password" id="confirm_password_reg" name="confirm_password_reg">
                <span><?php echo $confirm_password_err_reg; ?></span> <!--Display error message if there are any errors-->
            </div>
            <br /><button type="submit" value = "Submit" name="register"> Submit </button>

        </form>
    </body>
    </html>


