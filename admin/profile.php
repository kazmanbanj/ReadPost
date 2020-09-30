<?php include "includes/admin_header.php" ?>

<!-- to display the user details -->
<?php
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_profile_query = mysqli_query($connection, $query);
    while($row = mysqli_fetch_array($select_user_profile_query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }
}
?>

<div id="wrapper">
    <!-- Navigation -->
    <?php include "includes/admin_nav.php" ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome to Your Dashboard,<?php echo ' ' . strtoupper($_SESSION['username']); ?>
                    </h1>
                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="user_firstname">Firstname:</label>
                            <p><?php echo $user_firstname; ?></p>
                        </div>

                        <div class="form-group">
                            <label for="user_lastname">Lastname:</label>
                                <p><?php echo $user_lastname; ?></p>
                        </div>

                        <div class="form-group">
                            <label for="user_role">Role:</label>
                            <p><?php echo $user_role; ?></p>
                        </div>

                        <div class="form-group">
                            <label for="username">Username:</label>
                            <p><?php echo $username; ?></p>
                        </div>

                        <div class="form-group">
                            <label for="user_email">Email:</label>
                            <p><?php echo $user_email; ?></p>
                        </div>

                        <div class="form-group">
                            <label for="user_email">Profile Image:</label>
                            <img width="250" class="img-responsive" src="/readpost/images/<?php echo $user_image; ?>" alt="">
                        </div>

                        <!-- try later -->
                        <div class="form-group">
                            <button class='btn' type="submit"><a href="users.php?source=edit_user&edit_user={$user_id}">Edit</a></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
    <?php include "includes/admin_footer.php" ?>