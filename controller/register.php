<?php 

include_once '../core/functions.php';
include_once '../core/config.php';

if(isset($_POST['send'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $hash_password = password_hash($password , PASSWORD_DEFAULT);
    $select_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $select_query);
    $numrows = mysqli_num_rows($result);
    if($numrows > 0){
        $_SESSION['message'] = "This Email Already Exist";
    }
    else{
        if(empty($_FILES['image']['name'])) {
        $image_name = "def.webp";
    } else {
        $image_name = rand(0,255) . rand(0,255) . time() . $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $location = "../app/users/upload/$image_name";
        move_uploaded_file($tmp_name, $location);
    }

    if($country == ""){
        $insert = "INSERT INTO users VALUES (NULL, '$name', '$email', '$hash_password','$image_name',DEFAULT)";
    }else{
        $insert = "INSERT INTO users VALUES (NULL, '$name', '$email', '$hash_password','$image_name','$country')";
    }

    $i = mysqli_query($connection, $insert);
    redirect('pages/login.php');
    }
    if (isset($_SESSION['message'])) {
    redirect('pages/register.php');
    }
}

?>