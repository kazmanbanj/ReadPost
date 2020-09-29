<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

    <!-- Navigation -->
<?php include "includes/nav.php"; ?>

<?php
// Query For The Like Button
if(isset($_POST['liked'])) {
    // 0 - TEST
    // echo "Liked";
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // 1 - SELECT POST
    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];

    // 2 - UPDATING WITH INCREASING POSTS WITH LIKES
    mysqli_query($connection, "UPDATE posts SET likes = $likes+1 WHERE post_id = $post_id");

    // 3 - CREATING LIKES FOR POST
    mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
    exit();
}

// Query For The Unlike Button
if(isset($_POST['unliked'])) {
    // 0 - TEST
    // echo "Unliked";
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // 1 - SELECT POST
    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];

    // 2 - DELETING LIKES
    mysqli_query($connection, "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id");

    // 3 - UPDATING WITH DECREASING POSTS WITH LIKES
    mysqli_query($connection, "UPDATE posts SET likes = $likes-1 WHERE post_id = $post_id");
    exit();
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php

            // getting the post id
            if (isset($_GET['p_id'])) {
                $the_post_id = $_GET['p_id'];

            // to get post view counts
            $view_query = "UPDATE posts SET post_view_counts = post_view_counts + 1 WHERE post_id = $the_post_id";
            $send_query = mysqli_query($connection, $view_query);
            if (!$send_query) {
                die("query failed!!!");
            }

            // to display draft posts only for logged in admins
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
            } else {
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
            }
            
            $select_all_posts_query = mysqli_query($connection, $query);
            if (mysqli_num_rows($select_all_posts_query) < 1) {
                echo "<h1 class='text-center'>NO POST AVAILABLE FOR THIS CATEGORY YET</h1>";
            } else {

                while($row = mysqli_fetch_assoc($select_all_posts_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];
                    $post_view_counts = $row['post_view_counts'];

                    ?>

            <h1 class="page-header">
                Posts
            </h1>

            <!-- First Blog Post -->
            <div style="border: 3px solid #dfdfdf; border-radius: 20px; padding: 10px; word-wrap: break-word;">
            <h2>
                <a href="/readpost/post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
            </h2>
            <p class="lead">
                by <a href="/readpost/author_posts.php?author=<?php echo $post_author; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author; ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> Posted on: <?php echo $post_date; ?> </p>
            <p><?php echo "$post_view_counts"; ?> views</p>
            <hr>
            <img width="250" class="img-responsive" src="/readpost/images/<?php echo $post_image; ?>" alt="">
            <hr>
            <p><?php echo $post_content; ?></p>
            <!-- <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a> -->
            </div>

            <hr>
                <!-- to edit the post on the post view -->
                <?php
                    if(isset($_SESSION['user_role'])) {
                        if(isset($_GET['p_id'])) {
                            $the_post_id = $_GET['p_id'];
                            echo "<b><a href='/readpost/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit &nbsp;</a> &nbsp;&nbsp;" ;
                        }
                    }
                ?>

                <?php } ?>
                <?php 
                    // freeing the stmt result
                    // mysqli_stmt_free_result($stmt);
                ?>

                <!-- for the like button -->
                <?php
                if (isLoggedIn()) { ?>

                    <b><a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like' ?>" href="">
                        <span class="glyphicon glyphicon-thumbs-up"
                        data-toggle='tooltip'
                        data-placement='top'
                        title='<?php echo userLikedThisPost($the_post_id) ? ' I like this before' : ' Want to like it' ?>'
                        ></span>
                        <?php echo userLikedThisPost($the_post_id) ? ' Unlike' : ' Like' ?>
                        </a></b>

                <?php } else { ?>
                    <b><a href='/readpost/login'>Login</a> to like this post</b>
                <?php } ?>
                
                
                <b>(<?php getPostLikes($the_post_id); ?> Likes)</b>

                <hr>
            <!-- Blog Comments -->
            <?php
                if (isset($_POST['create_comment'])) {
                    $the_post_id = $_GET['p_id'];

                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];

                    if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                        $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                        $create_comment_query = mysqli_query($connection, $query);
                        if(!$create_comment_query) {
                            die("Query failed" . mysqli_error($connection));
                        }

                        // // counting the comments in each post
                        // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                        // $query .= "WHERE post_id = $the_post_id";
                        // $update_comment_count = mysqli_query($connection, $query);
                    } else {
                        echo "<script>alert('Fields cannot be empty')</script>";
                    }
                }
            ?>

            <!-- Comments Form -->
            <div class="well">
                <h4>Leave a Comment:</h4>
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="Author">Author</label>
                        <input type="text" name="comment_author" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" name="comment_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="comment">Your comment</label>                        
                        <textarea class="form-control" name="comment_content" rows="3"></textarea>
                    </div>
                    <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <hr>

            <!-- Posted Comments -->
            <?php

            // fetching and displaying the approved comments
            $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
            $query .= "AND comment_status = 'approved' ";
            $query .= "ORDER by comment_id DESC ";
            $select_comment_query = mysqli_query($connection, $query);
            if(!$select_comment_query) {
                die('Query Failed' . mysqli_error($connection));
            }
            while ($row = mysqli_fetch_array($select_comment_query)) {
                $comment_date = $row['comment_date'];
                $comment_content = $row['comment_content'];
                $comment_author = $row['comment_author'];
            ?>

            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><?php echo $comment_author; ?>
                        <small><?php echo $comment_date; ?></small>
                    </h4>
                    <?php echo $comment_content; ?>
                </div>
            </div>
            <?php } } } else {
                header("Location: index.php");
            }
            ?>
            <!-- Comment -->               
        </div>
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>
    </div>
    <!-- /.row -->
    <hr>
<?php include "includes/footer.php"; ?>

<script>
// jquery script with ajax for the like button
$(document).ready(function() {
    // tooltip for the like button
    $("[data-toggle='tooltip']").tooltip();

    var post_id = <?php echo $the_post_id; ?>;
    var user_id = <?php echo loggedInUserId(); ?>;

    // LIKING
    $('.like').click(function() {
        $.ajax({
            url: "/readpost/post.php?p_id=<?php echo $the_post_id; ?>",
            type: 'post',
            data: {
                'liked': 1,
                'post_id': post_id,
                'user_id': user_id
            }
        });
    });

    // UNLIKING
    $('.unlike').click(function() {
        $.ajax({
            url: "/readpost/post.php?p_id=<?php echo $the_post_id; ?>",
            type: 'post',
            data: {
                'unliked': 1,
                'post_id': post_id,
                'user_id': user_id
            }
        });
    });
});
</script>
