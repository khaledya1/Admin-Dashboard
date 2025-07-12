<?php 
include_once '../core/config.php';
include_once '../shared/head.php';
auth(2,3);


if(isset($_POST['update'])){
    $id = $_SESSION['admin']['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country = $_POST['country'];

    $check_email_query = "SELECT * FROM users WHERE email='$email' AND id != '$id' ";
    $check_result = mysqli_query($connection, $check_email_query);

    if(mysqli_num_rows($check_result) > 0) {
    $_SESSION['message'] = "This email is already exists";
    redirect('pages/profile.php');
    exit;
    }
    if(empty($_FILES['image']['name'])) {
        $image_name = $_SESSION['admin']['image'];
    } else {
        if($_SESSION['admin']['image'] != "def.webp"){
            unlink("../app/users/upload/" . $_SESSION['admin']['image']);
        }
        $image_name = rand(0,255) . rand(0,255) . time() . $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $location = "../app/users/upload/$image_name";
        move_uploaded_file($tmp_name, $location);
    }
    $update_query = "UPDATE users SET name='$name', email='$email', country='$country', image='$image_name' WHERE id='$id'";
    $u = mysqli_query($connection, $update_query);
    
    $_SESSION['admin']['name'] = $name;
    $_SESSION['admin']['email'] = $email;
    $_SESSION['admin']['country'] = $country;
    $_SESSION['admin']['image'] = $image_name;

    redirect('pages/profile.php');
}

if (isset($_POST['remove_image'])) {
    $id = $_SESSION['admin']['id'];
    if ($_SESSION['admin']['image'] == "def.webp") {
        redirect('pages/profile.php');
    } else {
        unlink("../app/users/upload/" . $_SESSION['admin']['image']);
        $image_name = "def.webp";
        $update_query = "UPDATE users SET image='$image_name' WHERE id='$id'";
        $u = mysqli_query($connection, $update_query);
        $_SESSION['admin']['image'] = $image_name;
        redirect('pages/profile.php');
    }
}

if(isset($_POST['change_password'])){
    $id = $_SESSION['admin']['id'];
    $select = "SELECT * FROM users WHERE id = '$id' ";
    $result = mysqli_query($connection, $select);
    $user = mysqli_fetch_assoc($result);

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $database_password = $user['password'];
    
    $check_password = password_verify($current_password,$database_password);

    if($check_password){

        if($new_password == $confirm_password){
            $new_hash_password = password_hash($new_password,PASSWORD_DEFAULT);
            $update = "UPDATE users SET password = '$new_hash_password' WHERE id = '$id' ";
            $u = mysqli_query($connection , $update);
            $_SESSION['message'] = "update password successfully";
            redirect('pages/profile.php');

        }else{
            $_SESSION['message'] = "wrong confirm password";
            redirect('pages/profile.php');
        }
        

    }else{
        $_SESSION['message'] = "wrong current password";
        redirect('pages/profile.php');

    }
}


?>