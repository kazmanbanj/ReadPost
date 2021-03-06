<!-- in additional to the view all post -->
<?php
include "delete_modal.php";

if(isset($_POST['checkBoxArray'])) {
    foreach ($_POST['checkBoxArray'] as $postValueId) {
        $bulk_options = $_POST['bulk_options'];

        switch($bulk_options) {
            case 'published':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                $update_to_published_status = mysqli_query($connection, $query);
                confirmQuery($update_to_published_status);
            break;
            case 'draft':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                $update_to_draft_status = mysqli_query($connection, $query);
                confirmQuery($update_to_draft_status);
            break;
            case 'delete':
                $query = "DELETE FROM posts WHERE post_id = {$postValueId} ";
                $update_to_delete_status = mysqli_query($connection, $query);
                confirmQuery($update_to_delete_status);
            break;
            case 'clone':
                $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
                $select_post_query = mysqli_query($connection, $query);  
                
                while ($row = mysqli_fetch_array($select_post_query)) {
                    $post_title         = $row['post_title'];
                    $post_category_id   = $row['post_category_id'];
                    $post_date          = $row['post_date']; 
                    $post_author        = $row['post_author'];
                    $post_user          = $row['post_user'];
                    $post_status        = $row['post_status'];
                    $post_image         = $row['post_image'] ; 
                    $post_tags          = $row['post_tags']; 
                    $post_content       = $row['post_content'];
                    
                    if (empty($post_tags)) {
                        $post_tags = "No tags";
                    }
                }    
                     
            $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_user, post_date, post_image,post_content,post_tags,post_status) ";                 
            $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}', '{$post_status}') ";     
            $copy_query = mysqli_query($connection, $query); 
            if(!$copy_query ) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
            break;
    
        }
    }
}
?>

<form action="" method="post">
    <table class="table table-bordered table-hover">
        <div id="bulkOptionContainer" class="col-xs-4" style="padding: 0;">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>

                <?php if (is_admin()): ?>
                <option value="clone">Clone</option>
                <?php endif; ?>

            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>
        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>

                <?php if (is_admin()): ?>
                    <th>Id</th>
                    <th>Users</th>
                <?php endif; ?>

                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View Post</th>
                <th>Edit</th>
                <!-- <th>Del</th> -->
                <th>Delete</th>
                <th>Views count</th>
            </tr>
        </thead>

        <tbody>

            <?php

            $user = $_SESSION['username'];

            $query = "SELECT posts.post_id, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, ";
            $query .= "posts.post_image, posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_view_counts, ";
            $query .= "categories.cat_id, categories.cat_title";
            $query .= " FROM posts ";
            $query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id WHERE post_user = '$user' ORDER BY posts.post_id DESC";

            $select_posts = mysqli_query($connection, $query);

            // in case no post is available yet
            if (mysqli_num_rows($select_posts) < 1) {
                echo "<br><br><h1 class='text-center'>YOU DON'T HAVE ANY POSTS YET</h1><br>";
            } else {
            while($row = mysqli_fetch_assoc($select_posts)) {
                $post_id = $row['post_id'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_view_counts = $row['post_view_counts'];
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<tr>";
            ?>
                
                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>
                
            <?php
                if (is_admin()):
                    echo "<td>$post_id </td>";

                    if (!empty($post_user)) {
                        echo "<td>$post_user </td>";
                    } 
                endif;

                echo "<td>$post_title </td>";

                echo "<td>$cat_title </td>";
                
                echo "<td>$post_status </td>";
                echo "<td><img src='../images/$post_image' width='100' alt='image'>$post_image </td>";
                echo "<td>$post_tags </td>";

                
                // to view all comments relating to a particular post
                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $send_comment_query = mysqli_query($connection, $query);

                // counting the comments in each post
                $count_comments = mysqli_num_rows($send_comment_query);            
                
                echo "<td><a href='./comments.php?source=view_my_comments.php'>$count_comments</a></td>";

                echo "<td>$post_date </td>";
                echo "<td><a class='btn btn-info' href='../post.php?p_id={$post_id}'>View Post</a></td>";
                echo "<td><a class='btn btn-success' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                ?>

                <!-- this is to delete a post -->
                <!-- <form action="" method="post">
                    <input type="hidden" name="post_id" value="<?php //echo $post_id; ?>">

                    <?php
                    //echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
                    ?>

                </form> -->

                <?php
                //echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";
                echo "<td><a class='btn btn-danger' onClick=\"javascript: return confirm('Are you sure you want to delete'); \" href='posts.php?source=view_my_posts&&delete={$post_id}'>Delete</a></td>";
                echo "<td><a onClick=\"javascript: return confirm('Do you want to reset views to 0'); \" href='posts.php?source=view_my_posts&&reset={$post_id}'>$post_view_counts</a></td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
</form>

<?php

// to delete a post
if(isset($_GET['delete'])) {
    $the_post_id = $_POST['post_id'];
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
    $delete_query = mysqli_query($connection, $query);
    header("Location: posts.php?source=view_my_posts");
}

if(isset($_GET['reset'])) {
    $the_post_id = $_GET['reset'];
    $query = "UPDATE posts SET post_view_counts = 0 WHERE post_id = " . mysqli_real_escape_string($connection, $_GET['reset']) . "";
    $reset_query = mysqli_query($connection, $query);
    header("Location: posts.php?source=view_my_posts");
}

?>

<script>
// this is for the delete post using bootstrap modal
$(document).ready(function(){
    $(".delete_link").on('click', function () {
        var id = $(this).attr("rel");
        var delete_url = "posts.php?delete="+ id +" ";
        $("modal_delete_link").attr("href", delete_url);
        $("#myModal").modal('show');
    })
})
</script>