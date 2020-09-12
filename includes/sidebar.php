<!-- to log in users from the sidebar -->
<?php
    if(ifItIsMethod('post')){
        if(isset($_POST['login'])){
            if(isset($_POST['username']) && isset($_POST['password'])){
                login_user($_POST['username'], $_POST['password']);
            }else {
                redirect('index');
            }
        }
    }
?>

<div class="col-md-4">
    <!-- Blog Search Well -->
    <div class="well">
        <h4>Blog Search</h4>
        <form action="/readpost/search.php" method="post">
        <div class="input-group">
            <input name="search" type="text" class="form-control">
            <span class="input-group-btn">
                <button name="submit" class="btn btn-default" type="submit">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
        </form>
        <!-- /.input-group -->
    </div>

    <!-- Login -->
    <div class="well">
        <?php if(isset($_SESSION['user_role'])): ?>
            <h4>Logged in as <?php echo $_SESSION['username']; ?></h4>
            <a href="/readpost/includes/logout.php" class="btn btn-primary">Logout</a>
        <?php else: ?>
            <h4>Login</h4>
            <form method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="Enter username">
            </div>
            <div class="input-group">
                <input name="password" type="password" class="form-control" placeholder="Enter password">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary" name="login">
                        Submit
                    </button>
                </span>
            </div>
            <div class="form-group">
                <a href="forgotpassword.php?forgot=<?php echo uniqid(true); ?>">Forgot Password</a>
            </div>
            </form>
        <?php endif; ?>
        <!-- /.input-group -->
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
    <?php 
        $query = "SELECT * FROM categories LIMIT 5";
        $select_categories_sidebar = mysqli_query($connection, $query);
    ?>
        <h4>Blog Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">

                <?php
                while($row = mysqli_fetch_assoc($select_categories_sidebar)) {
                    $cat_id = $row['cat_id'];
                    $cat_title = $row['cat_title'];
                    echo "<li><a href='category.php?category=$cat_id'>{$cat_title}</a></li>";
                }
                ?>
                <!-- <li><a href="#">Category Name</a>
                </li>
                <li><a href="#">Category Name</a>
                </li>
                <li><a href="#">Category Name</a>
                </li>
                <li><a href="#">Category Name</a>
                </li> -->
                </ul>
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php include "widget.php"; ?>
</div>