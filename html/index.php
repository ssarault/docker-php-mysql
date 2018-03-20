<?php 
    // session_start();
    include_once('loggedIn.php');
    //session_start();
    $user = $_SESSION['username'];
    $userId = $_SESSION['userId'];

    if (isset($_GET['page'])) {
        if (intval($_GET['page']))
            $page = (int)$_GET['page'];
        else
            $page = 1;
    } else {
        $page = 1;
    }

    $postsPerPage = 10;

    $start = ($page - 1) * $postsPerPage;

    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT {$start}, {$postsPerPage};";

    $result = $db->query($sql);

    $posts = $result->fetchAll(PDO::FETCH_ASSOC);

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
        <div>The posts page</div>
        <form action="/logout.php">
            <button type="submit">Logout</button>
        </form>
        <div>
            <?php foreach($posts as $post): ?>
                <ul>
                    <li><a href="/post.php?id=<?php print $post['id']; ?>"><?php print $post['name']; ?></a></li>
                    <li><?php print $post['user_id']; ?></li>
                    <li><?php print $post['content']; ?></li>
                    <li><?php print $post['number_comments']; ?></li>
                    <li><?php print $post['up_votes']; ?></li>
                    <li><?php print $post['down_votes']; ?></li>
                </ul>
            <?php endforeach; ?>
        </div>
        <div>
            <?php if ($page == 1): ?>
                <a href="/?page=2">
                    <?php print $page + 1; ?>
                </a>
            <?php else: $prev = $page - 1; $next = $page + 1; ?>
                <a href="/?page=<?php print $prev; ?>">
                    <?php print $prev; ?>
                </a>
                <a href="/?page=<?php print $next; ?>">
                    <?php print $next; ?>
                </a>
            <?php endif; ?>
        </div>
        <div>
            <p>Add post with text</p>
            <form action="/addPost.php" method="POST">
                <input type="hidden" name="userId" value="<?php print $userId; ?>" />
                <input type="text" name="postName" placeholder="name" required />
                <input type="text" name="content" placeholder="content" required />
                <input type="submit" value="Post" />
            </form>
            <p>Add post with image</p>
            <form action="/addPost.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="userId" value="<?php print $userId; ?>" />
                <input type="text" name="postName" placeholder="name" required />
                <input type="file" name="image" required />
                <input type="submit" value="Post" />
            </form>
        </div>
    </body>
</html>