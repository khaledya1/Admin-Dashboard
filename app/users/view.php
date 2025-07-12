<?php 
include_once '../../core/config.php';
include_once '../../shared/head.php';
auth();
include_once '../../shared/header.php';
include_once '../../shared/aside.php';


if(isset($_GET['view'])) {
    $id = $_GET['view'];
    $select = "SELECT * from user_data where id = '$id'";
    $data = mysqli_query($connection, $select);
    $row = mysqli_fetch_assoc($data);
    $numrows = mysqli_num_rows($data);
    if($numrows == 0) {
        redirect('pages/error404.php');
        exit();
    }
}
else {
  redirect('pages/error404.php');
  exit();
}

?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Tables</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav>
    </div>

<section class="section">
  <div class="row">
    <div class="col-lg-8 offset-lg-2">
      <div class="card text-center shadow-lg py-4">

        <div class="d-flex justify-content-center mb-3">
          <img src="<?= base_url('app/users/upload/') . $row['image'] ?>" 
               alt="User Image" 
               class="img-fluid rounded-circle border border-3" 
               style="width: 180px; height: 180px; object-fit: cover;">
        </div>

        <div class="card-body">
          <h4 class="card-title mb-3"><?= $row['name'] ?></h4>
          <p class="card-text fs-5"><strong>ID :</strong> <?= $row['id'] ?></p>
          <p class="card-text fs-5"><strong>Email :</strong> <?= $row['email'] ?></p>
          <p class="card-text fs-5"><strong>Rule :</strong> <?= $row['title'] ?></p>
          <p class="card-text fs-5"><strong>Created By :</strong> <?= $row['created_by'] ?? '---' ?></p>
          <a href="<?= base_url('app/users') ?>" class="btn btn-outline-secondary mt-3 px-4">Back</a>
        </div>

      </div>
    </div>
  </div>
</section>

  </main>
<?php 
include_once '../../shared/footer.php';
include_once '../../shared/script.php';
?>