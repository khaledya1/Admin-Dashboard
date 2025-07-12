<?php 
include_once '../core/config.php';
include_once '../core/functions.php';
include_once '../shared/head.php';

if (isset($_COOKIE['isAdmin'])) {
    redirect("");
}

$_SESSION['message'] = null;
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $select_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $select_query);
    $numrows = mysqli_num_rows($result);
    if($numrows > 0){
        $user = mysqli_fetch_assoc($result);
        $hash_password_from_database = $user['password'];
        $if_password_true = password_verify($password , $hash_password_from_database);
        if($if_password_true){
          setcookie("isAdmin", $email, time() + 86400 * 365, '/', 'localhost', false, false);
          $_SESSION['admin'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'rule_number' => $user['rule'],
            'password' => $user['password'],
            'image' => $user['image'],
            'country' => $user['country'],
          ];
          redirect("");
        }
        else{
            $_SESSION['message'] = "Wrong Password";
            }
    }else {
        $_SESSION['message'] = "Wrong Email Or Password ";
    }
}
?>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?=base_url()?>" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">NiceAdmin</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <?php  if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif ; ?>
                <?php unset($_SESSION['message']); ?>
                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" method="post">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">Please enter your email.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" name="login" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="./register.php">Create an account</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

<?php 
include_once '../shared/script.php';
?>
