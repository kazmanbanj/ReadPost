<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>

<?php
// this is to get the data fom the reg form and post to the db
// if (isset($_POST['submit'])) {
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // this is to validate users and display an error when neccessary
    $error = [
        'username' => '',
        'email' => '',
        'password' => ''
    ];

    if (strlen($username) < 6) {
        $error['username'] = 'Username needs to be longer than six characters';
    }

    if ($username == '') {
        $error['username'] = 'Username cannot be empty';
    }

    if (username_exists($username)) {
        $error['username'] = 'Username already exists, pick another one';
    }

    if ($email == '') {
        $error['email'] = 'Email cannot be empty';
    }

    if (email_exists($email)) {
        $error['email'] = 'Email already exists, <a href="index.php">Please login</a>';
    }

    if (strlen($password) < 6) {
        $error['password'] = 'password needs to be longer than six characters';
    }

    if ($password == '') {
        $error['password'] = 'Password cannot be empty';
    }

    // looping through the error array
    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
        }
    }

    if (empty($error)) {
        register_user($username, $email, $password);
        login_users($username, $password);
    }
}
?>
<!-- Navigation -->
    
    <?php  include "includes/nav.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Register</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                    <h6 class="text-center"><?php //echo $message; ?></h6>
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter Desired Username" autocomplete="on" value="<?php echo isset($username) ? $username : '' ?>">
                            <p class="text-danger bg-danger"><?php echo isset($error['username']) ? $error['username'] : '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com" autocomplete="on" value="<?php echo isset($email) ? $email : '' ?>">
                            <p class="text-danger bg-danger"><?php echo isset($error['email']) ? $error['email'] : '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                        </div>
                        <p class="text-danger bg-danger"><?php echo isset($error['password']) ? $error['password'] : '' ?></p>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<hr>
<?php include "includes/footer.php";?>
