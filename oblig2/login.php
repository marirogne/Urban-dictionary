<?php
include 'php/handleContent.php';
include 'header.php';
?>

<h2>Login</h2>
    <p>Please login as a user here:</p>
    <form method="post" action="">
        <div>
            <label for="username">Username: </label><br />
            <input type ="text" id="username" name="username">
            <span><?php echo $username_err; ?></span> <!--Display error message if there are any errors-->
        </div>
        <div>
            <label for="password">Password: </label><br />
            <input type ="password" id="password" name="password">
            <span><?php echo $password_err; ?></span> <!--Display error message if there are any errors-->
        </div>
        <br /><button type="submit" value = "Submit" name="login"> Login </button>

    </form>

</body>
</html>

