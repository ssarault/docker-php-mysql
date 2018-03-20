<?php
    include_once('loggedIn.php');
    //session_start();
    $user = $_SESSION['username'];
    $userId = $_SESSION['userId'];

    if (isset($_GET['id'])) {
        if (intval($_GET['id']))
            $id = (int)$_GET['id'];
        else
            $id = 1;
    } else {
        $id = 1;
    }

    if (isset($_GET['page'])) {
        if (intval($_GET['page']))
            $page = (int)$_GET['page'];
        else
            $page = 1;
    } else {
        $page = 1;
    }

    $commentsPerPage = 10;

    $start = ($page - 1) * $commentsPerPage;

    $sql = "SELECT * FROM posts WHERE id = {$id};";

    $result = $db->query($sql);

    $post = $result->fetch(PDO::FETCH_ASSOC);

    class Comment {
        public $id;
        public $user_id;
        public $post_id;
        public $parent_id;
        public $tree_id;
        public $tree_left;
        public $tree_right;
        public $content;
        public $up_votes;
        public $down_votes;
        public $replies = [];

        public function __constuct() {}
    }

    $sql = "SELECT * FROM comments WHERE post_id = {$id} AND parent_id = 0 ORDER BY id DESC
    LIMIT {$start}, {$commentsPerPage};";

    $result = $db->query($sql);

    $comments = $result->fetchAll(PDO::FETCH_CLASS, "Comment");

    //$rootComments = [];

    foreach($comments as $comment) {
        $sql = "SELECT * FROM comments WHERE tree_id = {$comment->id} AND parent_id != 0;";
        $result = $db->query($sql);
        $replies = $result->fetchAll(PDO::FETCH_CLASS, "Comment");
        //$comment['replies'] = [];
        
        foreach ($replies as $reply) {
            // if (!isset($reply['replies']))
            //     $reply['replies'] = [];

            if ($reply->parent_id == $comment->id) {
                array_push($comment->replies, $reply);
                continue;
            }

            //$replyId = $reply['id'];

            foreach($replies as $replyCheck) {
                if ($reply->parent_id == $replyCheck->id) {
                    array_push($replyCheck->replies, $reply);
                    break;
                }
            }
        }

        //var_dump($replies);
       // array_push($rootComments, $comment);
    }

    function renderReplyForm($comment) {
        GLOBAL $id;
        GLOBAL $page;

        print "<form action=\"/addComment.php\" method=\"POST\">";
        print "<input type=\"hidden\" name=\"userId\" value=\"{$userId}\" />";
        print "<input type=\"hidden\" name=\"postId\" value=\"{$comment->post_id}\" />";
        print "<input type=\"hidden\" name=\"parentId\" value=\"{$comment->id}\" />";
        print "<input type=\"hidden\" name=\"treeId\" value=\"{$comment->tree_id}\" />";
        print "<input type=\"hidden\" name=\"treeLeft\" value=\"{$comment->tree_left}\" />";
        print "<input type=\"hidden\" name=\"treeRight\" value=\"{$comment->tree_right}\" />";
        print "<input type=\"hidden\" name=\"reply\" value=\"true\" />";
        print "<input type=\"hidden\" name=\"back\" value=\"post.php?id={$id}&page={$page}\" />";
        print "<input type=\"text\" name=\"content\" placeholder=\"content\" required />";
        print "<input type=\"submit\" value=\"Post\" />";
        print "</form>";
    }

    function renderComments($comments) { 
        GLOBAL $id;
        GLOBAL $page;

        print "<ul>";
        foreach($comments as $comment) {
            print "<li>";
            print "<div>{$comment->content}</div>";
            print "<div>{$comment->user_id}</div>";
            print "<div>{$comment->up_votes}</div>";
            print "<div>{$comment->down_votes}</div>";
            print "<form action=\"likeComment.php\" method=\"POST\" >";
            print "<input type=\"hidden\" name=\"id\" value=\"{$comment->id}\" />";
            print "<input type=\"hidden\" name=\"upVote\" value=\"true\" />";
            print "<input type=\"hidden\" name=\"back\" value=\"post.php?id={$id}&page={$page}\" />";
            print "<button type=\"submit\">Upvote</button>";
            print "</form>";
            print "<form action=\"likeComment.php\" method=\"POST\" >";
            print "<input type=\"hidden\" name=\"id\" value=\"{$comment->id}\" />";
            print "<input type=\"hidden\" name=\"downVote\" value=\"true\" />";
            print "<input type=\"hidden\" name=\"back\" value=\"post.php?id={$id}&page={$page}\" />";
            print "<button type=\"submit\">Downvote</button>";
            print "</form>";

            renderReplyForm($comment);

            if ($comment->replies) {
                renderComments($comment->replies);
            }

            print "</li>";

        }

        print "</ul>";
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
        <div>Hello, <?php print $user; ?>!</div>
        <div>The post page</div>
        <form action="/logout.php">
            <button type="submit">Logout</button>
        </form>
        <div>
            <ul>
                <li><?php print $post['name']; ?></li>
                <li><?php print $post['user_id']; ?></li>
                <li><?php print $post['content']; ?></li>
                <li><?php print $post['number_comments']; ?></li>
                <li><?php print $post['up_votes']; ?></li>
                <li><?php print $post['down_votes']; ?></li>
            </ul>
        </div>
        <div>
            <?php renderComments($comments); ?>
            <form action="/addComment.php" method="POST">
                <input type="hidden" name="userId" value="<?php print $userId; ?>"/>
                <input type="hidden" name="postId" value="<?php print $id; ?>"/>
                <input type="hidden" name="back" value="<?php print "post.php?id={$id}&page={$page}"; ?>" />
                <textarea name="content" required></textarea>
                <input type="submit" value="Post" />
            </form>
        </div>
    </body>
</html>