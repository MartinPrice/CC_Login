<html>
<head><title>Login Page</title></head>
<body>
<form action=<?php echo $_SERVER['PHP_SELF']?> method="POST">
    Username: <input type="text" name="username"><br /><br />
    Password: <input type="text" name="password"><br /><br />
    <input type="submit" name="Login" value="Login"><br /><br />
    <?php
        if (isset($GLOBALS['message'])) {
            echo "{$GLOBALS['message']}";
        }
    ?>
</form>
</body></html>