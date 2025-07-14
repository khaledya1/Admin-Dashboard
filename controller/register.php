<?php 

include_once '../core/functions.php';
include_once '../core/config.php';

if(isset($_POST['send'])){
    $name = validation($_POST['name']);
    $email = validation($_POST['email']);
    $password = validation($_POST['password']);
    $country = validation($_POST['country']);
    $created_by = 30; // Default created_by for new users,
    $rule = 3; // Default rule for new users

    if(string_validation($name)) {
        $_SESSION['message'] = "MAX NAME IS 20 AND MIN IS 3";
        redirect('pages/register.php');
        exit;
    }

    if(string_validation($email, 3, 30)) {
        $_SESSION['message'] = "MAX EMAIL IS 30 AND MIN IS 3";
        redirect('pages/register.php');
        exit;
    }

    if(string_validation($password, 6, 50)) {
        $_SESSION['message'] = "PASSWORD MUST BE BETWEEN 6 AND 50 CHARACTERS";
        redirect('pages/register.php');
        exit;
    }

    if(!empty($country) && string_validation($country)) {
        $_SESSION['message'] = "MAX COUNTRY IS 20 AND MIN IS 3";
        redirect('pages/register.php');
        exit;
    }

    $hash_password = password_hash($password , PASSWORD_DEFAULT);

    $select_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $select_query);
    $numrows = mysqli_num_rows($result);

    if($numrows > 0){
        $_SESSION['message'] = "This Email Already Exists";
        redirect('pages/register.php');
        exit;
    }

    if(empty($_FILES['image']['name'])) {
        $image_name = "def.webp";
    } else {
        $image_name = rand(0,255) . rand(0,255) . time() . $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $location = "../app/users/upload/$image_name";
        move_uploaded_file($tmp_name, $location);
    }

    if($country == ""){
        $insert = "INSERT INTO users VALUES (NULL, '$name', '$email', '$hash_password','$image_name', DEFAULT)";
    } else {
        $insert = "INSERT INTO users VALUES (NULL, '$name', '$email', '$hash_password','$image_name','$country' , '$created_by', '$rule')";
    }

    $i = mysqli_query($connection, $insert);
    redirect('pages/login.php');
}
?>
