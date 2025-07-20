<?php 
include_once '../core/config.php';
include_once '../shared/head.php';
auth(2,3);

if (isset($_POST['update'])) {
    $id      = $_SESSION['admin']['id'];
    $name    = validation($_POST['name']);
    $email   = validation($_POST['email']);
    $country = validation($_POST['country']);

    if (string_validation($name)) {
        $_SESSION['message'] = "MAX NAME IS 20 AND MIN IS 3";
        redirect('pages/profile.php');
        exit;
    }

    if (email_validation($email)) {
        $_SESSION['message'] = "You Must Enter Valid Email";
        redirect('pages/profile.php');
        exit;
    }

    if (string_validation($country)) {
        $_SESSION['message'] = "MAX COUNTRY IS 20 AND MIN IS 3";
        redirect('pages/profile.php');
        exit;
    }

    $check_email_query = "SELECT * FROM users WHERE email='$email' AND id != '$id'";
    $check_result      = mysqli_query($connection, $check_email_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "This email is already exists";
        redirect('pages/profile.php');
        exit;
    }

    if (empty($_FILES['image']['name'])) {
        $image_name = $_SESSION['admin']['image'];
    } else {
        $validation_result = image_validation($_FILES['image']);
        if ($validation_result !== false) {
            $_SESSION['message'] = $validation_result;
            redirect('pages/profile.php');
            exit;
        }

        if ($_SESSION['admin']['image'] !== "def.webp") {
            unlink("../app/users/upload/" . $_SESSION['admin']['image']);
        }

        $image_name = rand(0,255) . rand(0,255) . time() . $_FILES['image']['name'];
        $tmp_name   = $_FILES['image']['tmp_name'];
        $location   = "../app/users/upload/$image_name";
        move_uploaded_file($tmp_name, $location);
    }

    $update_query = "UPDATE users 
                     SET name='$name', email='$email', country='$country', image='$image_name' 
                     WHERE id='$id'";
    mysqli_query($connection, $update_query);

    $_SESSION['admin']['name']    = $name;
    $_SESSION['admin']['email']   = $email;
    $_SESSION['admin']['country'] = $country;
    $_SESSION['admin']['image']   = $image_name;

    redirect('pages/profile.php');
}

if (isset($_POST['remove_image'])) {
    $id = $_SESSION['admin']['id'];

    if ($_SESSION['admin']['image'] === "def.webp") {
        redirect('pages/profile.php');
    } else {
        unlink("../app/users/upload/" . $_SESSION['admin']['image']);
        $image_name   = "def.webp";
        $update_query = "UPDATE users SET image='$image_name' WHERE id='$id'";
        mysqli_query($connection, $update_query);
        $_SESSION['admin']['image'] = $image_name;
        redirect('pages/profile.php');
    }
}

if (isset($_POST['change_password'])) {
    $id      = $_SESSION['admin']['id'];
    $select  = "SELECT password FROM users WHERE id = '$id'";
    $result  = mysqli_query($connection, $select);
    $user    = mysqli_fetch_assoc($result);

    $current_password  = validation($_POST['current_password']);
    $new_password      = validation($_POST['new_password']);
    $confirm_password  = validation($_POST['confirm_password']);

    if (string_validation($new_password, 6, 50)) {
        $_SESSION['message'] = "NEW PASSWORD MUST BE BETWEEN 6 AND 50 CHARACTERS";
        redirect('pages/profile.php');
        exit;
    }

    if (string_validation($confirm_password, 6, 50)) {
        $_SESSION['message'] = "CONFIRM PASSWORD MUST BE BETWEEN 6 AND 50 CHARACTERS";
        redirect('pages/profile.php');
        exit;
    }

    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['message'] = "Wrong current password";
        redirect('pages/profile.php');
        exit;
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = "Wrong confirm password";
        redirect('pages/profile.php');
        exit;
    }

    $new_hash_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update            = "UPDATE users SET password = '$new_hash_password' WHERE id = '$id'";
    mysqli_query($connection, $update);
    $_SESSION['message'] = "Update password successfully";
    redirect('pages/profile.php');
}
?>
