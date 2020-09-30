<?php

if(isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];

    // fetching the user details from db
    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_users_query = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_users_query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];               
        // $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }


// updating and posting the edit user data
if(isset($_POST['edit_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    $user_image = $_FILES['image']['name'];
    $user_image_temp = $_FILES['image']['tmp_name'];

    $username = $_POST['username'];
    // $user_email = $_POST['user_email'];
    // $user_password = $_POST['user_password'];

    move_uploaded_file($user_image_temp, "../images/profile/$user_image");

    // this to edit the password and encrypt it in db
    // $query = "SELECT randSalt FROM users";
    // $select_randsalt_query = mysqli_query($connection, $query);
    // if(!$select_randsalt_query) {
    //     die("Query Failed" . mysqli_error($connection));
    // }
    // $row = mysqli_fetch_array($select_randsalt_query);
    // $salt = $row['randSalt'];
    // $hashed_password = crypt($user_password, $salt);

    // the above password encryption OR this below

    // if(!empty($user_password)) {
    //     $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id ";
    //     $get_user_query = mysqli_query($connection, $query_password);
    //     confirmQuery($get_user_query);
    //     // here, we fetch just a single column so no while loop
    //     $row = mysqli_fetch_array($get_user_query);
    //     $db_user_password = $row['user_password'];
    // }

    // if ($db_user_password != $user_password) {
    //     $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
    // }

    // setting the db column name with the form name assigned to finally update the database
    $query = "UPDATE users SET ";
    $query .= "user_firstname = '{$user_firstname}', ";
    $query .= "user_lastname = '{$user_lastname}', ";
    $query .= "user_role = '{$user_role}', ";
    $query .= "username = '{$username}', ";
    $query .= "user_image = '{$user_image}' ";
    // $query .= "user_password = '{$hashed_password}' ";
    $query .= "WHERE user_id = {$the_user_id}";

    $edit_user_query = mysqli_query($connection, $query);
    confirmQuery($edit_user_query);
    echo "User Updated" . "  <a href='users.php'>View Users?</a>";
}

} else {
    header("Location: index.php");
}

?>

<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
         <label for="user_firstname">Firstname</label>
          <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <label for="user_role">Role</label>
        <select name="user_role" id="">
            <option value="subscriber"><?php echo $user_role; ?></option>

            <?php
            if($user_role == 'admin') {
                echo "<option value='subscriber'>subscriber</option>";
            } else {
                echo "<option value='admin'>admin</option>";
            }
            ?>
            <!-- <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option> -->
        </select>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
    </div>

    <!-- <div class="form-group">
        <label for="user_email">Email</label>
        <input type="email" value="<?php //echo $user_email; ?>" class="form-control" name="user_email">
    </div> -->

    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" name="image">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update user">
    </div>
</form>