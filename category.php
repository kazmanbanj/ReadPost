<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

    <!-- Navigation -->
<?php include "includes/nav.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php
                if (isset($_GET['category'])) {
                    $post_category_id = $_GET['category'];

                    // to display draft posts only for logged in admins
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                        $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id";
                    } else {
                        $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id AND post_status = 'published' ";
                    }

                $select_all_posts_query = mysqli_query($connection, $query);

                // in case no post is available yet
                if (mysqli_num_rows($select_all_posts_query) < 1) {
                    echo "<h1 class='text-center'>NO POST AVAILABLE FOR THIS CATEGORY YET</h1>";
                } else {

                    while($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = substr($row['post_content'], 0,300);


                        ?>

                <!-- <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1> -->

                <!-- First Blog Post -->
                <div style="border: 3px solid #dfdfdf; border-radius: 20px; padding: 10px; word-wrap: break-word;">
                <h2>
                    <a href="/readpost/post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                </h2>
                <p class="lead">
                    by <a href="/readpost/author_posts.php?author=<?php echo $post_author; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on: <?php echo $post_date; ?></p>
                <!-- <hr> -->
                <a href="/readpost/post.php?p_id=<?php echo $post_id; ?>">
                <img width="250" class="img-responsive" src="/readpost/images/<?php echo $post_image; ?>" alt="">
                </a>
                <!-- <hr> -->
                <p><?php echo $post_content; ?></p>
                <a class="btn btn-primary" href="/readpost/post/<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                </div>

                <hr>

                <?php } } } else {
                    header("Location: index.php");
                } ?>

                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php"; ?>
