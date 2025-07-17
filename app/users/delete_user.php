<?php 
include_once '../../core/config.php';
include_once '../../shared/head.php';
auth();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $select_one = "SELECT * FROM users WHERE id = $id";
    $data_one = mysqli_query($connection, $select_one);
    $row = mysqli_fetch_assoc($data_one);
    $old_image = $row['image'];

    if ($old_image !== "def.webp") {
        $image_path = "upload/$old_image";
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    if (isset($_SESSION['admin']) && $_SESSION['admin']['id'] == $id) {
        setcookie('isAdmin', '', time() - 3600, '/');
        session_unset();
        session_destroy();
        mysqli_query($connection, "DELETE FROM users WHERE id = $id");
        redirect("pages/login.php");

        exit;
    } else {
        mysqli_query($connection, "DELETE FROM users WHERE id = $id");
        $_SESSION['message'] = "User Deleted Successfully!";
        redirect("app/users/");
        exit;
    }
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

?>
