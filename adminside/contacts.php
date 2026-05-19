<?php
include 'header.php';
include '../connection.php';
$sql = "SELECT * FROM contact_us";
$result = $conn->query($sql);
?>
<div class="container-fluid">
  <div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-heading">User's Contact US Requests</h2>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr class="admin-nowrap">
          <th>Sr No</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Contact No</th>
          <th>E-mail ID</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Request Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-center"><?= $row["contact_id"] ?></td>
              <td><?= $row["first_name"] ?></td>
              <td><?= $row["last_name"] ?></td>
              <td><?= $row["contact_no"] ?></td>
              <td><?= $row["email_id"] ?></td>
              <td><?= $row["subject"] ?></td>
              <td><?= nl2br($row["message"]); ?></td>
              <td><?= date('d-m-Y h:i:s A', strtotime($row["contact_request_date"])) ?></td>
              <td>
                <button class="btn btn-danger btn-sm action-btn delete-btn" data-id="<?= $row['contact_id'] ?>">
                  Delete
                </button><br>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">No contact requests found</td>
          </tr>
        <?php endif; ?>
        <?php $conn->close(); ?>
      </tbody>
    </table>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        Swal.fire({
          title: "Are you sure?",
          text: "This record will be permanently deleted!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
            fetch('delete_record.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'contact_id=' + id
              })
              .then(response => response.text())
              .then(data => {
                Swal.fire({
                  title: "Deleted!",
                  text: data,
                  icon: "success",
                  confirmButtonText: "OK"
                }).then(() => {
                  location.reload();
                });
              })
              .catch(error => {
                Swal.fire("Error", error, "error");
              });
          }
        });
      });
    });
  });
</script>
<?php include 'footer.php'; ?>