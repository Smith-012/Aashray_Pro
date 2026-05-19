<?php
include 'header.php';
include '../connection.php';
$sql = "SELECT * FROM past_users ORDER BY deleted_at DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid">
  <div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-heading">Former Users</h2>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr class="admin-nowrap">
          <th>Id</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Username</th>
          <th>Date of Birth</th>
          <th>Gender</th>
          <th>Address</th>
          <th>City</th>
          <th>Pincode</th>
          <th>State</th>
          <th>Contact No</th>
          <th>E-mail ID</th>
          <th>Registration Date</th>
          <th>Unregistered At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
              <td><?= $row["first_name"] ?></td>
              <td><?= $row["last_name"] ?></td>
              <td><?= $row["user_username"] ?></td>
              <td><?= date('d-m-Y', strtotime($row["dob"])) ?></td>
              <td><?= $row["gender"] ?></td>
              <td><?= $row["address"] ?></td>
              <td><?= $row["city"] ?></td>
              <td><?= $row["pincode"] ?></td>
              <td><?= $row["state"] ?></td>
              <td><?= $row["contact_no"] ?></td>
              <td><?= $row["email_id"] ?></td>
              <td><?= date('d-m-Y h:i:s A', strtotime($row["reg_date"])) ?></td>
              <td><?= $row['deleted_at'] ? date('d-m-Y h:i:s A', strtotime($row['deleted_at'])) : '' ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="14" class="text-center">No archived users found</td>
          </tr>
        <?php endif; ?>
        <?php $conn->close(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>