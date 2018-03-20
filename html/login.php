<?php 
    // session_start();
    //include_once('connection.php');
    include_once('sessions.php');

    if (isset($_SESSION['username'])) {
        header("location: index.php");
    } 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Page Title</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php if (array_key_exists('unauthorized', $_GET)): ?>
            <div>You must login before you access the site</div>
        <?php endif; ?>
        <p>Login</p>
        <form action="/auth.php" method="POST">
            <input type="text" name="username" placeholder="username"/>
            <input type="password" name="password" placeholder="password"/>
            <input type="submit" value="Login"/>
        </form>
        <br/>
        <br/>
        <p>Signup</p>
        <form action="/auth.php" method="POST">
            <input type="text" name="username" placeholder="username"/>
            <input type="password" name="password" placeholder="password"/>
            <input type="hidden" name="signup" value="true"/>
            <input type="submit" value="Signup"/>
        </form>
    </body>
</html>