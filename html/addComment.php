<?php
    include_once('loggedIn.php');

    if (isset($_POST['back'])) {
        $back = "location: " . $_POST['back'];
    } else {
        $back = "location: index.php";
    }

    if (!isset($_POST['content']) || !isset($_POST['userId']) || 
            !isset($_POST['postId'])) {

        header($back);
        exit();
    }

    $content = $_POST['content'];
    $userId = $_POST['userId'];
    $postId = $_POST['postId'];

    if (isset($_POST['reply'])) {

        if (!isset($_POST['treeId']) || !isset($_POST['treeLeft']) || 
                    !isset($_POST['treeRight']) || !isset($_POST['parentId'])) {

            header($back);
            exit();
        }

        $parentId = $_POST['parentId'];
        $treeId = $_POST['treeId'];
        $treeLeft = $_POST['treeLeft'];
        $treeRight = $_POST['treeRight'];

        $treeLeftNew = $treeRight;
        $treeRightNew = $treeLeftNew + 1;

        // Update comment tree to accommadate new reply

        $sql = "UPDATE comments SET tree_left = CASE WHEN tree_left >= :right THEN (tree_left + 2) ELSE tree_left END, 
                            tree_right = CASE WHEN tree_right >= :right THEN (tree_right + 2) ELSE tree_right END
                            WHERE (tree_id = :id) AND (tree_left >= :right OR tree_right >= :right);";

        $statement = $db->prepare($sql);

        $statement->execute([":right" => $treeRight, ":id" => $treeId]);

        // Now insert new reply

        $statement = $db->prepare("INSERT INTO comments 
        (user_id, post_id, parent_id, content, tree_id, tree_left, tree_right)
        VALUES (:user, :post, :parent, :content, :treeId, :left, :right);");

        $statement->execute([":user" => $userId, ":post" => $postId, ":parent" => $parentId, 
        ":content" => $content, ":treeId" => $treeId, ":left" => $treeLeftNew, ":right" => $treeRightNew]);

    } else {

        // Insert comment here if comment is not a reply

        $statement = $db->prepare("INSERT INTO comments (user_id, post_id, content)
        VALUES (:user, :post, :content);");

        $statement->execute([":user" => $userId, ":post" => $postId, ":content" => $content]);

        //$lastRow = $statement->fetch(PDO::FETCH_ASSOC);

        $lastId = $db->lastInsertId();

        $db->query("UPDATE comments SET tree_id = {$lastId} WHERE id = {$lastId};");

    }

    header($back);
    exit();
?>