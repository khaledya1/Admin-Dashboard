<?php 
include_once '../../core/config.php';
include_once '../../shared/head.php';
auth();
include_once '../../shared/header.php';
include_once '../../shared/aside.php';

$select = "SELECT * FROM rules";
$rules = mysqli_query($connection, $select);

$profile_id = $_SESSION['admin']['id'];
$validation_error = [];
if(isset($_POST['send'])){
    $name = validation($_POST['name']);
    $email = validation($_POST['email']);
    $rule = validation($_POST['rule_id']);
    $password = 12345678;
    $image_name = "def.webp";
    $hash_password = password_hash($password , PASSWORD_DEFAULT);
    if(string_validation($name)){
        $_SESSION['message'] = "MAX NMAE IS 20 AND MIN IS 3";
        redirect('app/users/create.php');
        exit;
    }
    if(string_validation($email, 3 , 30)){
        $_SESSION['message'] = "MAX EMAIL IS 30 AND MIN IS 3";
        redirect('app/users/create.php');
        exit;
    }
    $select_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $select_query);
    $numrows = mysqli_num_rows($result);
    if($numrows > 0){
        $_SESSION['message'] = "This Email Already Exist";
        redirect('app/users/create.php');
        exit;

    }else{
      if(empty($validation_error)){
        $insert = "INSERT INTO users (name, email, password, image, country, created_by, rule)
        VALUES ('$name', '$email', '$hash_password', '$image_name', DEFAULT, '$profile_id', '$rule')";

        $i = mysqli_query($connection, $insert);
        $_SESSION['message'] = "User Created Successfully!";
        redirect('app/users/');
        exit;
     }
  }
}
?>
  <main id="main" class="main">
    <div class="d-flex justify-content-center">
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>">Home</a></li>
          <li class="breadcrumb-item">Create user</li>
        </ol>
      </nav>
    </div>
    </div>
    <section class="section">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-9 ">
          <div class="card">
                <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert"> 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title">Create User</h5>
              <form class="row g-3" method="post" >
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Your Name</label>
                  <input type="text" name="name" class="form-control" required id="inputNanme4">
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required id="inputEmail4">
                </div>
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Select Rule</label>
                    <select name="rule_id" required class="form-control" id="">
                        <option selected disabled value=""> -- Select Rule -- </option>
                        <?php foreach($rules as $rule): ?>
                            <option value="<?= $rule['id'] ?>"><?= $rule['title'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-center">
                  <button type="submit" name="send" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>
            </div>
          </div>
</main>

<?php 
include_once '../../shared/footer.php';
include_once '../../shared/script.php';
?>