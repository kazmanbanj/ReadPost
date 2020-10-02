<?php

if(isset($_POST['create_post'])) {
    $post_category_id = $_POST['post_category'];
    $post_title = $_POST['title'];
    $user_id = $_POST['user_id'];
    $post_user = $_POST['post_user'];
    $post_date = date('d-m-y');

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];

    $post_content = $_POST['post_content'];

    $post_tags = $_POST['post_tags'];
    if (empty($post_tags)) {
        $post_tags = "No tags";
    }

    // $post_comment_count = 4;
    $post_status = $_POST['post_status'];

    move_uploaded_file($post_image_temp, "../images/$post_image");

    $query = "INSERT INTO posts(post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) ";
    $query .= "VALUES({$post_category_id}, {$user_id}, '{$post_title}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}' ) ";

    $create_post_query = mysqli_query($connection, $query);

    confirmQuery($create_post_query);

    $the_post_id = mysqli_insert_id($connection);

    echo "<p class='alert alert-success'>Post Added.<a href='../post.php?p_id={$the_post_id}'> View Post</a></p>";
}

?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title" required>
    </div>

    <div class="form-group">
        <label for="post_category">Category</label>
        <select name="post_category" id="" required>
            <?php
                $query = "SELECT * FROM categories";
                $select_post_category = mysqli_query($connection, $query);
                confirmQuery($select_post_category);
                while($row = mysqli_fetch_assoc($select_post_category)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<option value='{$cat_id}'>$cat_title</option>";
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_user">Username</label>
        <select name="post_user" id="" required>
            <?php //echo "<option value='{$post_user}'>$post_user</option>"; ?>
            <?php
                $query = "SELECT * FROM users";
                $select_post_user = mysqli_query($connection, $query);
                confirmQuery($select_post_user);
                while($row = mysqli_fetch_assoc($select_post_user)) {
                $user_id = $row['user_id'];
                $username = $row['username'];

                echo "<option value='{$username}'>$username</option>";
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="user_id">User id</label>
        <select name="user_id" id="" required>
            <?php //echo "<option value='{$post_user}'>$post_user</option>"; ?>
            <?php
                $query = "SELECT * FROM users";
                $select_post_user = mysqli_query($connection, $query);
                confirmQuery($select_post_user);
                while($row = mysqli_fetch_assoc($select_post_user)) {
                $user_id = $row['user_id'];
                $username = $row['username'];

                echo "<option value='{$user_id}'>$username</option>";
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label>
        <select name="post_status" id="" required>
            <option value="draft">Select Option</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control " name="post_content" id="body" cols="30" rows="10" required>
         </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
    </div>
</form>