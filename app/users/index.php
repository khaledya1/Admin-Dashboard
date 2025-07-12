<?php 
include_once '../../core/config.php';
include_once '../../shared/head.php';
auth();
include_once '../../shared/header.php';
include_once '../../shared/aside.php';

$select = "SELECT u1.*, rules.title AS rule_title, u2.name AS created_by_name
           FROM users u1
           JOIN rules ON u1.rule = rules.id
           LEFT JOIN users u2 ON u1.created_by = u2.id";

$data = mysqli_query($connection, $select);
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
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Users
              <a class="btn btn-primary float-end" href="<?= base_url('app/users/create.php') ?>">Create New</a>
              </h5>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created By</th>
                    <th>Rule</th>
                    <th colspan="2" class=" text-center" >Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                     foreach($data as $row ):?>
                      <td><?= $row['id'] ?></td>
                      <td><?= $row['name'] ?></td>
                      <td><?= $row['email'] ?></td>
                      <td><?= $row['created_by_name'] ?? '---' ?></td>
                      <td><?= $row['rule_title'] ?></td>
                      <td><a class="btn btn-warning btn-sm" href="./view.php?view=<?=$row['id']?>">View</a></td>
                      <td><a class="btn btn-danger btn-sm" href="<?=base_url('app/users/delete_user.php')?>?delete=<?=$row['id']?>">Delete</a></td>
                  </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

<?php 
include_once '../../shared/footer.php';
include_once '../../shared/script.php';
?>
