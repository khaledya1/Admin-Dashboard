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
                'rule_number' => $user['rule'],
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

function validation($data) {
    $data = ltrim($data);
    $data = rtrim($data);
    $data = trim($data);
    $data =  strip_tags($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function string_validation($data , $min_length = 3, $max_length = 20) {

    $is_empty = empty($data);
    $is_max_length = strlen($data) > $max_length;
    $is_min_length = strlen($data) < $min_length;
    
    if($is_empty || $is_max_length || $is_min_length) {
        return true;
    } else {
        return false;
    }

}

function email_validation($email , $min_length = 3, $max_length = 50) {
    $is_empty = empty($email);
    $is_max_length = strlen($email) > $max_length;
    $is_min_length = strlen($email) < $min_length;
    $is_not_email = !filter_var($email, FILTER_VALIDATE_EMAIL);

    if($is_empty || $is_max_length || $is_min_length || $is_not_email) {
        return true;
    } else {
        return false;
    }
}

function image_validation($file, $max_size_mb = 5, $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return 'Please upload a valid image file';
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (strpos($mime, 'image/') !== 0) {
        return 'The uploaded file is not a valid image';
    }

    $max_size_bytes = $max_size_mb * 1024 * 1024;
    if ($file['size'] > $max_size_bytes) {
        return "Size {$max_size_mb} MB is the maximum allowed size for the image";
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions)) {
        return 'Invalid file extension. Allowed extensions are: ' . implode(', ', $allowed_extensions);
    }

    return false;
}


?>