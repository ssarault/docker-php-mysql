<?php
    include_once('loggedIn.php');

    $back = "location: index.php";

    if (!isset($_POST['userId']) || !isset($_POST['postName']) ||
            (!isset($_POST['content']) && !is_uploaded_file($_FILES['image']['tmp_name'])) ||
            (isset($_POST['content']) && is_uploaded_file($_FILES['image']['tmp_name']))) {

        header($back);
        exit();
    }

    $userId = $_POST['userId'];
    $postName = $_POST['postName'];

    if (isset($_POST['content'])) {

        $content = $_POST['content'];

    } else {

        $tmp = explode('.', $_FILES['image']['name']);
        $fileExt = $tmp[count($tmp) - 1];
        $allowedExts = ["gif", "jpg", "jpeg","png"];
        if (in_array($fileExt, $allowedExts)) {
            $dirname = preg_replace('/\s+/', '_', $_POST['postName']);
            $path = "./images/users/posts/{$dirname}";
            if (!file_exists($path))
                mkdir($path, 0777);
            $fileName = $_FILES['image']['name'];
            $filePath = $path . "/" . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'], $filePath);
            $content = "<image src=\"/images/users/posts/{$dirname}/{$fileName}\" />";
        } else {
            header($back);
            exit();
            // $content = "failed";
        }
    }
    

    $statement = $db->prepare("INSERT INTO posts (user_id, name, content)
        VALUES (:user, :name, :content);");

    $statement->execute([":user" => $userId, ":name" => $postName, ":content" => $content]);

    header($back);
    exit();

?>