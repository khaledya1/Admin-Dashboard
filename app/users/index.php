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
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert"> 
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <?= $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title">Users
              <a class="btn btn-primary float-end" href="<?= base_url('app/users/create.php') ?>">Create New</a>
            </h5>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Created By</th>
                  <th>Rule</th>
                  <th colspan="2" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($data as $row): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['created_by_name'] ?? '---' ?></td>
                    <td><?= $row['rule_title'] ?></td>
                    <td><a class="btn btn-warning btn-sm" href="./view.php?view=<?= $row['id'] ?>">View</a></td>
                    <td>
                    <a href="javascript:void(0);" 
                      class="btn btn-danger btn-sm delete-btn" 
                      data-url="<?= base_url('app/users/delete_user.php') ?>?delete=<?= $row['id'] ?>" 
                      data-email="<?= htmlspecialchars($row['email']) ?>"
                      data-bs-toggle="modal" 
                      data-bs-target="#confirmDeleteModal">
                      Delete
                    </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
  </section>
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-white text-danger border-bottom-0">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Confirm Deletion
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5 mb-3 text-dark">
          Are you sure you want to delete <strong class="text-danger" id="deleteEmail">this user</strong>?
        </p>
        <p class="text-muted mb-0">This action cannot be undone.</p>
      </div>
      <div class="modal-footer justify-content-center border-top-0">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmDeleteBtn" class="btn btn-danger px-4">Yes, Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  const deleteBtns = document.querySelectorAll('.delete-btn');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  const deleteEmail = document.getElementById('deleteEmail');

  deleteBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-url');
      const email = btn.getAttribute('data-email');

      deleteEmail.textContent = email;
      confirmDeleteBtn.href = url;
    });
  });
</script>

</main>

<?php 
include_once '../../shared/footer.php';
include_once '../../shared/script.php';
?>
