<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php

// the message
// $msg = "First line of text \nSecond line of text";

if (isset($_POST['submit'])) {
    $to = "banjoko2020@gmail.com";
    $subject = wordwrap($_POST['subject'], 70);
    $body = $_POST['body'];
    $header = "From: " . $_POST['email'];
    mail($to, $subject, $body, $header);    

    echo "<div><div class='alert alert-success alert-dismissible' role='alert' style='width:30%; text-align:center; margin-left:auto; margin-right:auto;'>Your mail is sent<button type='button' class='close' data-dismiss='alert' aria-label='close'><span>&times;</span></button></div></div>";
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
                <h1>Contact</h1>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject" class="sr-only">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter mail subject" required>
                        </div>
                         <div class="form-group">
                            <textarea name="body" id="body" class="form-control" cols="50" rows="10" required></textarea>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">
                    </form>                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<hr>
<?php include "includes/footer.php";?>
