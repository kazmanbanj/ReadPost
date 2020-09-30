<?php 

//========== DATABASE HELPER FUNCTIONS ==========//

// to escape any string going into the db
function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

// to redirect users
function redirect($location)
{
    global $connection;
    header("Location:" . $location);
    exit; // used in place of return
}

// for the global connection
function query($query)
{
    global $connection;
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    return $result;
}

// func for fetching records
function fetchRecords($result)
{
    return mysqli_fetch_array($result);
}

// counting records from the database
function count_records($result)
{
    return mysqli_num_rows($result);
}
//========== END DATABASE HELPER FUNCTIONS ==========//






//========== GENERAL HELPER FUNCTION ==========//

// to egt the username logged in
function get_user_name()
{
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }
}

//========== END GENERAL HELPER FUNCTION ==========//






//========== AUTHENTICATION HELPER FUNCTIONS ==========//

// to restrict certain pages to users only e.g.users.php
function is_admin()
{
    global $connection;
    if (isLoggedIn()) {
        $result = query("SELECT user_role FROM users WHERE user_id = ".$_SESSION['user_id']." ");
        $row = fetchRecords($result);
        if ($row['user_role'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    return false;
}
//========== END AUTHENTICATION HELPER FUNCTIONS ==========//





//========== USER SPECIFIC HELPERS =================//

// get all posts by a specific user
function get_all_user_posts()
{
    return query("SELECT * FROM posts WHERE user_id = ". loggedInUserId() ." ");
}

// get all comments by a specific user
function get_all_posts_user_comments()
{
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id = ". loggedInUserId() ." ");
}

// get all categories by a specific user
function get_all_user_categories()
{
    return query("SELECT * FROM categories WHERE user_id = ". loggedInUserId() ." ");
}

function get_all_user_published_posts()
{
    return query("SELECT * FROM posts WHERE user_id = ". loggedInUserId() ." AND post_status = 'published' ");
}

function get_all_user_draft_posts()
{
    return query("SELECT * FROM posts WHERE user_id = ". loggedInUserId() ." AND post_status = 'draft' ");
}

function get_all_user_approved_posts_comments()
{
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id = ". loggedInUserId() ." AND comment_status = 'approved' ");
}

function get_all_user_unapproved_posts_comments()
{
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id = ". loggedInUserId() ." AND comment_status = 'unapproved'");
}
//========== END USER SPECIFIC HELPERS =================//






// to request either a post method or a get method
function ifItIsMethod($method = null)
{
    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    } else {
        return false;
    }
}

// to check the user role that is logged in
function isLoggedIn()
{
    if (isset($_SESSION['user_role'])) {
        return true;
    } else {
        return false;
    }
}

// to check logged in user ids for the like button
function loggedInUserId()
{
    if (isLoggedIn()) {
        $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] ."'");
        confirmQuery($result);
        $user = mysqli_fetch_array($result);
        if (mysqli_num_rows($result) >= 1) {        //  similar to the tenery operator in userLikedThisPost()
            return $user['user_id'];
        }
    }
    return false;
}

// function for a post liked by a user
function userLikedThisPost($post_id = '')
{
    $result = query("SELECT * FROM likes WHERE user_id=" . loggedInUserId() . " AND post_id={$post_id}");
    confirmQuery($result);
    return mysqli_num_rows($result) >= 1 ? true : false;
}

// to check if users is logged in and redirect
function checkIfUserIsLoggedInAndRedirect($redirectLocation = null)
{
    if (isLoggedIn()) {
        redirect($redirectLocation);
    }
}

// fetching all likes
function getPostLikes($post_id)
{
    $result = query("SELECT * FROM likes WHERE post_id = $post_id");
    confirmQuery($result);
    echo mysqli_num_rows($result);
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

// refactoring the dynamic data in the admin bar chart code
function checkUserRole($table, $column, $role)
{
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$role' ";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result);
}

// to avoid duplicate username entry
function username_exists($username)
{
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

// to avoid duplicate email entry
function email_exists($email)
{
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

// to register users
function register_user($username, $email, $password)
{
    global $connection;

        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        // encrypting the password 
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12) );

        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber' ) ";
        $register_user_query = mysqli_query($connection, $query);
        confirmQuery($register_user_query);
}

// to log in users
function login_users($username, $password)
{
    global $connection;

    $username = trim($username);
    $password = trim($password);

    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    // fetching the user details from the db
    $query = "SELECT * from users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if(!$select_user_query) {
        die("Query Failed" . mysqli_error($connection));
    }

    while($row = mysqli_fetch_array($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_email = $row['user_email'];
        $db_user_image = $row['user_image'];
        $db_user_role = $row['user_role'];

        //  encrypting the password
        if (password_verify($password, $db_user_password)) {
            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;

            redirect("/readpost/admin");
        } else {
            return false;
        }
    }
    return true;
}

?>