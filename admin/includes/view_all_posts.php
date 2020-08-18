<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Image</th>
            <th>Tags</th>
            <th>Comments</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>

        <?php

            $query = "SELECT * FROM posts";
            $select_posts = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($select_posts)) {
                $post_id = escape($row['post_id']);
                $post_author = escape($row['post_author']);
                $post_title = escape($row['post_title']);
                $post_category_id = escape($row['post_category_id']);
                $post_status = escape($row['post_status']);
                $post_image = escape($row['post_image']);
                $post_tags = escape($row['post_tags']);
                $post_comment_count = escape($row['post_comment_count']);
                $post_date = escape($row['post_date']);

                echo "<tr>";
                echo "<td>$post_id </td>";
                echo "<td>$post_author </td>";
                echo "<td>$post_title </td>";

            $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
                $select_categories_id = mysqli_query($connection, $query);
                while($row = mysqli_fetch_assoc($select_categories_id)) {
                $cat_id = escape($row['cat_id']);
                $cat_title = escape($row['cat_title']);
                }

                echo "<td>$cat_title </td>";
                
                echo "<td>$post_status </td>";
                echo "<td><img src='../images/$post_image' width='100' alt='image'>$post_image </td>";
                echo "<td>$post_tags </td>";
                echo "<td>$post_comment_count </td>";
                echo "<td>$post_date </td>";
                echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                echo "<td><a href='posts.php?delete={$post_id}'>Delete</a></td>";
                echo "</tr>";
            }

        ?>
    </tbody>
</table>

<?php

if(isset($_GET['delete'])) {
    $the_post_id = escape($_GET['delete']);
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
    $delete_query = mysqli_query($connection, $query);
    header("Location: posts.php");
}

?>