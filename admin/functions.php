<?php 

// to escape any string going into the db
function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

// to know counts of users who are online
function users_online()
{
    // to load instant users online
    if (isset($_GET['onlineusers'])) {

        // to know users who are online
        global $connection;
            // incase db connection doesn't work
        if (!$connection) {
            session_start();
            include("../includes/db.php");
            
            $session = session_id();
            date_default_timezone_set('Africa/Lagos');
            $date = date('l jS \of F Y h:i:s A');
            $time = time();
            $time_out_in_seconds = 5;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);

                if($count == NULL) {
                    mysqli_query($connection, "INSERT INTO users_online(session, date, time) VALUES('$session', now(), '$time')");
                } else {
                    mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
                }

            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    } // get onlineusers request isset
}
users_online();

// this is used to detect any failed query
function confirmQuery($result) {
    global $connection;
    if(!$result) {
        die("QUERY FAILED ." . mysqli_error($connection));
    }
}

// this is used to insert a data in the db
function insert_categories() {
    global $connection;
    if(isset($_POST['submit'])) {
        $cat_title = escape($_POST['cat_title']);
        if($cat_title == "" || empty($cat_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUE('{$cat_title}') ";

            $create_category_query = mysqli_query($connection, $query);

            if(!$create_category_query) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
    }
};

// this is used to update data in the db
function update_categories() {
    global $connection;
    if(isset($_GET['edit'])) {
        $cat_id = escape($_GET['edit']);
        include "includes/update_categories.php";
    }
}

// this is used to find data from the database
function findAllCategories() {
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = escape($row['cat_id']);
        $cat_title = escape($row['cat_title']);

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}

// this is used to delete a data
function deleteCategory() {
    global $connection;
    if(isset($_GET['delete'])) {
        $the_cat_id = escape($_GET['delete']);
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}

// refactoring the admin index code to count data records
function recordCount($table)
{
    global $connection;
    $query = "SELECT * FROM " . $table;
    $select_all_post = mysqli_query($connection, $query);

    $result = mysqli_num_rows($select_all_post);
    confirmQuery($result);
    return $result;
}

// refactoring the dynamic data in the admin bar chart code
function checkStatus($table, $column, $status)
{
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result);
}

function checkUserRole($table, $column, $role)
{
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$role' ";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result);
}

?>