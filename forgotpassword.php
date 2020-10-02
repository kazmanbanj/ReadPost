<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php

// starts here
// requiring the phpmailer
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// ends here

// this is to prevent anyody from accessing the page illegally
if (!ifItIsMethod('get') && !isset($_GET['forgot'])) {
    redirect('index');
} 

// making the forgot password work
if (ifItIsMethod('post')) {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $length = 50;
        $token = bin2hex(openssl_random_pseudo_bytes($length));

        if (email_exists($email)) {
            if ($stmt = mysqli_prepare($connection, "UPDATE users SET token='{$token}' WHERE user_email= ?")) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Instantiation and passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host       = Config::SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = Config::SMTP_USER;
                    $mail->Password   = Config::SMTP_PASSWORD;
                    $mail->Port       = Config::SMTP_PORT;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';

                    //Recipients
                    $mail->setFrom('Admin@readpost.com', 'Banjoko Kazeem');
                    $mail->addAddress($email);

                    // Content
                    // $mail->isHTML(true);
                    $mail->Subject = 'Reset your Readpost password';
                    $mail->Body    = '<p><br>
                    Hello, <br><br>
                    Follow this link to reset your Readpost password for your  account. <br><br> <a href="http://localhost/readpost/resetpassword.php?email=' .$email. '&token=' .$token. '">
                    http://localhost/readpost/resetpassword.php?email=' .$email. '&token=' .$token. '</a><br><br>
                    If you didn\'t ask to reset your password, you can ignore this email. <br><br> Thanks, <br><br>Banjoko Kazeem. <br>Your Readpost admin</p>';
                    
                    if($mail->send()) {
                        $emailSent = true;
                    } else {
                        echo "<div><div class='alert alert-danger alert-dismissible' role='alert' style='width:30%; text-align:center; margin-left:auto; margin-right:auto;'>Mail not sent<button type='button' class='close' data-dismiss='alert' aria-label='close'><span>&times;</span></button></div></div>";
                    }
                } catch (Exception $e) {
                    echo "<div><div class='alert alert-warning alert-dismissible' role='alert' style='width:30%; text-align:center; margin-left:auto; margin-right:auto;'>Mail could not be sent. Please, check your internet connection and try again<button type='button' class='close' data-dismiss='alert' aria-label='close'><span>&times;</span></button></div></div>";
                }
            } else {
                echo mysqli_error($connection);
            }
        } else {
            echo "<div><div class='alert alert-danger alert-dismissible' role='alert' style='width:30%; text-align:center; margin-left:auto; margin-right:auto;'>Email doesn't exist in our records.<button type='button' class='close' data-dismiss='alert' aria-label='close'><span>&times;</span></button></div></div>";
        }
    }
}
?>

<!-- Page Content -->
<div class="container">
    <div class="form-gap"></div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <?php if(!isset($emailSent)): ?>

                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <h2 class="text-center">Forgot Password?</h2>
                        <p>You can reset your password here.</p>
                        <div class="panel-body">
                            <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-envelope color-blue"></i></span>
                                        <input id="email" name="email" placeholder="email address" class="form-control"
                                            type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="recover-submit" class="btn btn-lg btn-primary btn-block"
                                        value="Reset Password" type="submit">
                                </div>

                                <input type="hidden" class="hide" name="token" id="token" value="">
                            </form>

                        </div><!-- Body-->

                        <?php else: ?>
                            <h2>
                                <div><div class='bg-success' style='padding: 8px; text-align:center;'>Please, check your email</div></div>
                            </h2>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<hr>

<?php include "includes/footer.php";?>

</div> <!-- /.container -->