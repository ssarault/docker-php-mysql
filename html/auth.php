<?php

    include_once('connection.php');
    include_once('sessions.php');

    if (!isset($_POST['username']) || !isset($_POST['password']) ||
            strlen($_POST['username']) < 2 || strlen($_POST['password']) < 4) {
        header("location: login.php?unauthorized=true");
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_POST['signup'])) {
        // if (count($username) < 2 || count($password) < 4) {
        //     header("location: index.php?unauthorized=true");
        // }

        $statement = $db->prepare("SELECT username FROM users WHERE username = :name;");
        $statement->execute([":name" => $username]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result['username'] != '') {
            header("location: login.php?unauthorized=true");
            exit();
        }

        $newPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement = $db->prepare("INSERT INTO users (username, password) VALUES
        (:name, :pswd);");

        $statement->execute([":name" => $username, ":pswd" => $newPassword]);

        $lastId = $db->lastInsertId();

        $_SESSION['username'] = $username;

        $_SESSION['userId'] = $lastId;

        header("location: index.php");
        exit();
    }

    $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
    $statement->execute([":name" => $username]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result['password'] == '') {
        header("location: login.php?unauthorized=true");
        exit();
    }

    //$hash = password_hash($password, PASSWORD_DEFAULT);
    //$password = $result['password'];

    if (password_verify($password, $result['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['userId'] = $result['id'];
        header("location: index.php");
        exit();
    } else {
        header("location: login.php?unauthorized=true");
        exit();
    }

?>