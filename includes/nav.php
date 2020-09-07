<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Start Bootstrap</a>
            </div>
            <!-- fetching the nav links -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php 
                    $query = "SELECT * FROM categories";
                    $select_all_categories_query = mysqli_query($connection, $query);

                    while($row = mysqli_fetch_assoc($select_all_categories_query)) {
                    $cat_id = $row['cat_id'];
                    $cat_title = $row['cat_title'];

                    // to highlight the nav link for the current page
                    $category_class = '';
                    $registration_class = '';
                    $pageName = basename($_SERVER['PHP_SELF']); //to know the current page
                    $registration = 'registration.php';

                    if(isset($_GET['category']) && $_GET['category'] == $cat_id) {
                        $category_class = 'active';
                    } elseif($pageName = $registration) {
                        $registration_class = 'active';
                    }

                        echo "<li class='$category_class'><a href='category.php?category=$cat_id'>{$cat_title}</a></li>";
                    }
                    ?>
                    <li>
                        <a href="admin">Admin</a>
                    </li>
                    <li class="<?php echo '$registration_class'; ?>">
                        <a href="registration.php">Register</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>

                    <?php
                        // session_start();
                        if(isset($_SESSION['user_role'])) {
                            if(isset($_GET['p_id'])) {
                                $the_post_id = $_GET['p_id'];
                                // echo "<li><a href='admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit post</a></li>";
                                echo "<li><a href='admin'>Welcome, {$_SESSION['firstname']}</a></li>";
                            }
                        }
                    ?>

                    <!-- <li>
                        <a href="#">Services</a>
                    </li> -->
                    <!-- <li>
                        <a href="#">Contact</a>
                    </li> -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>