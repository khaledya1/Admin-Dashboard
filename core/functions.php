<?php 
include_once './config.php';

session_start();
define('BASE_URL', 'http://localhost/CoreAdmin/');
function base_url($path = null) {
    return BASE_URL . $path;
}

function redirect($var=null) {
    echo "<script> window.location.replace('http://localhost/CoreAdmin/$var'); </script>";
    exit;

}

function restore_session_from_cookie($connection) {
    if (!isset($_SESSION['admin']) && isset($_COOKIE['isAdmin'])) {
        $email = $_COOKIE['isAdmin'];

        $select_query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $select_query);
        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            $_SESSION['admin'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'image' => $user['image'],
                'country' => $user['country'],
            ];
        }
    }
}

function auth( $rule1 = null , $rule2 = null ) {
    global $connection;
    restore_session_from_cookie($connection);

    if($_COOKIE['isAdmin']){
        $rule = $_SESSION['admin']['rule_number'];
        if($rule == 1 || $rule == $rule1 || $rule == $rule2){
            
        }
        else{
        redirect("pages/error404.php");
        }

    }
    else{
        redirect("pages/login.php");
        }
}


?>