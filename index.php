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

                // this is for the page finder
                $per_page = 10;

                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = "";
                }

                if ($page == "" || $page == 1) {
                    $page_1 = 0;
                } else {
                    $page_1 = ($page * $per_page) - $per_page;
                }

                // to display draft posts only for logged in admins
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                    $post_query_count = "SELECT * FROM posts";
                } else {
                    $post_query_count = "SELECT * FROM posts WHERE post_status = 'published' ";
                }

                // this is for the pager
                $find_count = mysqli_query($connection, $post_query_count);
                $count = mysqli_num_rows($find_count);

                // in case no post is available yet
                if ($count < 1) {
                    echo "<h1 class='text-center'>NO POSTS AVAILABLE YET</h1>";
                } else {
                    $count = ceil($count / $per_page);                    

                    // this is for fetching data
                    $query = "SELECT * FROM posts LIMIT $page_1, $per_page";
                    $select_all_posts_query = mysqli_query($connection, $query);

                        while($row = mysqli_fetch_assoc($select_all_posts_query)) {
                            $post_id = $row['post_id'];
                            $post_title = $row['post_title'];
                            $post_author = $row['post_user'];
                            $post_date = $row['post_date'];
                            $post_image = $row['post_image'];
                            $post_content = substr($row['post_content'], 0,300);
                            $post_status = $row['post_status'];

                            ?>

                    <!-- <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1> -->

                    <!-- First Blog Post -->
                    <div style="border: 3px solid #dfdfdf; border-radius: 20px; padding: 10px; word-wrap: break-word;">
                        <h2>
                            <a href="post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="author_posts.php?author=<?php echo $post_author; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author; ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> Posted on: <?php echo $post_date; ?></p>
                        <!-- <hr> -->
                        <a href="post/<?php echo $post_id; ?>">
                        <img width="250" class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                        </a>
                        <!-- <hr> -->
                        <p style="word-wrap:break-word !important;"><?php echo $post_content; ?></p>
                        <a class="btn btn-primary" href="post/<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    </div>
                    <hr>

                <?php } } ?>
                        <!-- pager list -->
            <ul class="pager">
                <?php
                for($i = 1; $i <= $count; $i++) {
                    if ($i == $page) {
                        echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
                    } else {
                        echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
                    }
                }
                ?>
            </ul>
                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div style="margin-bottom:20px">
            <?php include "includes/sidebar.php"; ?>
            </div>

        </div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php"; ?>
