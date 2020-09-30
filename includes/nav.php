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
                <a class="navbar-brand" href="/readpost">READPOST</a>
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

                        echo "<li class='$category_class'><a href='/readpost/category/$cat_id'>{$cat_title}</a></li>";
                    }
                    ?>                    
                    <!-- <li>
                        <a href="/readpost/contact">Contact</a>
                    </li>  -->

                    <?php if (isLoggedIn()): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>Welcome, <?php echo $_SESSION['username']; ?> <b
                                    class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/readpost/admin"><i class="fa fa-fw fa-user"></i> Dashboard</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="/readpost/includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="/readpost/login">Login</a>
                        </li>
                        <li class="<?php echo '$registration_class'; ?>">
                            <a href="/readpost/registration">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>