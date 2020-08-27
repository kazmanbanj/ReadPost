<?php

if(isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];

    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_users_query = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_users_query)) {
        $user_id = escape($row['user_id']);
        $username = escape($row['username']);
        $user_password = escape($row['user_password']);
        $user_firstname = escape($row['user_firstname']);
        $user_lastname = escape($row['user_lastname']);                
        $user_email = escape($row['user_email']);
        $user_image = escape($row['user_image']);
        $user_role = escape($row['user_role']);
    }
}

if(isset($_POST['edit_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    // $post_image = $_FILES['image']['name'];
    // $post_image_temp = $_FILES['image']['tmp_name'];

    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // move_uploaded_file($post_image_temp, "../images/$post_image");

    // if(empty($post_image)) {
    //     $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
    //     $select_post_image = mysqli_query($connection, $query);

    //     while($row = mysqli_fetch_array($select_post_image)) {
    //         $post_image = $row['post_image'];
    //     }
    // }

    // setting the db column name with the form name assigned to finally update the database
    $query = "UPDATE users SET ";
    $query .= "user_firstname = '{$user_firstname}', ";
    $query .= "user_lastname = '{$user_lastname}', ";
    $query .= "user_role = '{$user_role}', ";
    $query .= "username = '{$username}', ";
    $query .= "user_email = '{$user_email}', ";
    $query .= "user_password = '{$user_password}' ";
    $query .= "WHERE user_id = {$the_user_id}";

    $edit_user_query = mysqli_query($connection, $query);
    confirmQuery($edit_user_query);
}

?>

<form action="" method="post" enctype="multipart/form-data">
    <!-- <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title">
    </div> -->

    <!-- <div class="form-group">
        <label for="post_category">Category</label>
        <select name="post_category_id" id="">
            <option value='cat'>1</option>
            <option value='dog'>2</option>
        </select>
    </div> -->

    <!-- <div class="form-group">
        <label for="users">Users</label>
        <select name="post_user" id="">
            <option value='cat'>cat</option>
            <option value='dog'>dog</option>
        </select>
    </div> -->
    
    <div class="form-group">
         <label for="user_firstname">Firstname</label>
          <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
    </div>

    <!-- <div class="form-group">
        <label for="post_status">Post Status</label>
        <select name="post_status" id="">
            <option value="pending">Pending</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->

    <div class="form-group">
        <label for="user_role">Role</label>
        <select name="user_role" id="">
            <option value="subscriber"><?php echo $user_role; ?></option>

            <?php
            if(!$user_role == 'admin') {
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

    <div class="form-group">
        <label for="user_email">Email</label>
        <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" value="<?php echo $user_password; ?>" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update user">
    </div>
</form>