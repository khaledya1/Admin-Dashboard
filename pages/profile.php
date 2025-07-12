<?php 
include_once '../core/config.php';
include_once '../shared/head.php';
auth(2,3);
include_once '../shared/header.php';
include_once '../shared/aside.php';

$id = $_SESSION['admin']['id'];
$select_query = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($connection, $select_query);
$user = mysqli_fetch_assoc($result);
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?= base_url('app/users/upload/').$_SESSION['admin']['image'] ?>" alt="Profile" class="rounded-circle">
              <h2><?= $_SESSION['admin']['name'] ?></h2>
              <h3><?= $_SESSION['admin']['email'] ?></h3>
              <div class="social-links mt-2">
                <a href="" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="https://www.facebook.com/khaled.yasser.452330" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/khaled_yasser124/" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.linkedin.com/in/khaled--yasser/" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
                    <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"> 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?= $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?= $_SESSION['admin']['name'] ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $_SESSION['admin']['email'] ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8"><?= $_SESSION['admin']['country'] ?></div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form id="removeImageForm" method="post" action="<?= base_url('controller/profile.php') ?>">
                  <input type="hidden" name="remove_image" value="1">
                  </form>

                  <form method="post" action="<?= base_url('controller/profile.php') ?>" enctype="multipart/form-data" >

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="<?= base_url('app/users/upload/') . $_SESSION['admin']['image'] ?>" alt="Profile">
                        <div class="pt-2">
                          <label for="image_upload" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></label>
                          <input type="file" id="image_upload" name="image" class="d-none" >
                          <button type="button" class="btn btn-danger btn-sm" title="Remove my profile image" 
                          onclick="document.getElementById('removeImageForm').submit();"><i class="bi bi-trash"></i></button>

                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="<?= $_SESSION['admin']['name'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?= $_SESSION['admin']['email'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Country</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="country" type="text" class="form-control" id="country" value="<?= $_SESSION['admin']['country'] ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button name="update" type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="post" action="<?= base_url('controller/profile.php') ?>" >

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="current_password" required type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="new_password" required type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="confirm_password" required type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

<?php 
include_once '../shared/footer.php';
include_once '../shared/script.php';
?>