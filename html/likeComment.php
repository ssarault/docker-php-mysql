<?php
    include_once('loggedIn.php');

    if (isset($_POST['back'])) {
        $back = "location: " . $_POST['back'];
    } else {
        $back = "location: index.php";
    }

    if (!isset($_POST['id']) || (!isset($_POST['upVote']) && !isset($_POST['downVote'])) ||
            (isset($_POST['upVote']) && isset($_POST['downVote']))) {

        header($back);
        exit();
    }

    if (intval($_POST['id'])) {
        $commentId = (int)$_POST['id'];
    } else {
        header($back);
        exit();
    }

    if (isset($_POST['upVote'])) {
        $sql = "UPDATE comments SET up_votes = (up_votes + 1) WHERE id = {$commentId};";
    } else {
        $sql = "UPDATE comments SET down_votes = (down_votes + 1) WHERE id = {$commentId};";
    }

    $db->query($sql);

    header($back);
    exit();
?>