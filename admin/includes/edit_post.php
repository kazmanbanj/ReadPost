<?php
if(isset($_GET['p_id'])) {
    $the_post_id = escape($_GET['p_id']);
}

// to update
$query = "SELECT * FROM posts WHERE post_id = $the_post_id";
$select_posts_by_id = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($select_posts_by_id)) {
    // gotten from the database
    $post_id = escape($row['post_id']);
    $post_author = escape($row['post_author']);
    $post_title = escape($row['post_title']);
    $post_category_id = escape($row['post_category_id']);
    $post_status = escape($row['post_status']);
    $post_image = escape($row['post_image']);
    $post_content = escape($row['post_content']);
    $post_tags = escape($row['post_tags']);
    $post_comment_count = escape($row['post_comment_count']);
    $post_date = escape($row['post_date']);
}

if(isset($_POST['update_post']))  {

    // gotten from the form
    $post_title = escape($_POST['post_title']);
    $post_category_id = escape($_POST['post_category']);
    $post_author = escape($_POST['post_author']);
    $post_status = escape($_POST['post_status']);
    $post_image = escape($_FILES['image']['name']);
    $post_image_temp = escape($_FILES['image']['tmp_name']);
    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']); 

    move_uploaded_file($post_image_temp, "../images/$post_image");

    if(empty($post_image)) {
        $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
        $select_image = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_image)) {
            $post_image = escape($row['post_image']);
        }
    }

    // setting the db column name with the form name assigned
    $query = "UPDATE posts SET ";
    $query .= "post_title = '{$post_title}', ";
    $query .= "post_category_id = '{$post_category_id}', ";
    $query .= "post_date = now(), ";
    $query .= "post_author = '{$post_author}', ";
    $query .= "post_status = '{$post_status}', ";
    $query .= "post_tags = '{$post_tags}', ";
    $query .= "post_content = '{$post_content}', ";
    $query .= "post_image = '{$post_image}' ";
    $query .= "WHERE post_id = {$the_post_id}";

    $update_post = mysqli_query($connection, $query);
    confirmQuery($update_post);
}

?>


<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title">
    </div>

    <div class="form-group">
        <label for="post_category">Category</label>
        <select name="post_category" id="">
            <?php
                $query = "SELECT * FROM categories ";
                $select_post_category = mysqli_query($connection, $query);
                while($row = mysqli_fetch_assoc($select_post_category)) {
                $cat_id = escape($row['cat_id']);
                $cat_title = escape($row['cat_title']);

                echo "<option value='{$cat_id}'>$cat_title</option>";
                }
            ?>
        </select>
    </div>

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
         <label for="author">Post Author</label>
          <input type="text" value="<?php echo $post_author; ?>" class="form-control" name="post_author">
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label>
        <input value="<?php echo $post_status; ?>" type="text" class="form-control" name="post_status">
    </div>


    <div class="form-group">
        <label for="post_image">Post Image</label>
        <img width="150" src="../images/<?php echo $post_image; ?>" alt="">
        <input type="file" name="image" id="">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control " name="post_content" id="body" cols="30" rows="10"><?php echo $post_content; ?>
        </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Publish Post">
    </div>
</form>