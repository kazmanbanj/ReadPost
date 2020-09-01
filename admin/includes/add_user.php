<?php

if(isset($_POST['create_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    // $post_image = $_FILES['image']['name'];
    // $post_image_temp = $_FILES['image']['tmp_name'];

    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // move_uploaded_file($post_image_temp, "../images/$post_image");

    // another way of encrypting the password 
    // $password = password_hash('$user_password', PASSWORD_BCRYPT, array('cost' => 12) );       OR

    $query = "SELECT randSalt from users";
    $select_randsalt_query = mysqli_query($connection, $query);
    if(!$select_randsalt_query) {
        die("Query Failed" . mysql_error($connection));
    }

    // just fetching a single row. so no need for while loop
    $row = mysqli_fetch_array($select_randsalt_query);
    $salt = $row['randSalt'];

    // password cryting
    $password = crypt($user_password, $salt);

    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) ";
    $query .= "VALUES('{$user_firstname}', '{$user_lastname}', '{$user_role}', '{$username}', '{$user_email}', '{$password}' ) ";

    $create_user_query = mysqli_query($connection, $query);

    confirmQuery($create_user_query);
    
    echo "<div class='alert alert-success'>User created: " . " " . "<a href='users.php'>View All Users</a></div> ";
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
          <input type="text" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
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
            <option value="subscriber">Select Options</option>
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
        </select>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="user_email">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Add user">
    </div>
</form>