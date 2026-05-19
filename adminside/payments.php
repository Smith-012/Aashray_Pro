<?php
include 'header.php';
include '../connection.php';
$sql = "SELECT * FROM payment";
$result = $conn->query($sql);
?>
<div class="container-fluid">
  <div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-heading">All Payments</h2>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle admin-payments-table">
      <thead class="table-dark">
        <tr class="admin-nowrap">
          <th>Transaction ID</th>
          <th>User</th>
          <th>Amount</th>
          <th>Property No</th>
          <th>Payment Mode</th>
          <th>Payment Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row["transaction_id"] ?></td>
              <td><?= $row["user_username"] ?></td>
              <td>₹ <?= number_format($row["rent_amount"], 2) ?></td>
              <td><?= $row["property_no"] ?></td>
              <td><?= $row["payment_mode"] ?></td>
              <td><?= date('d-m-Y h:i:s A', strtotime($row["payment_date"])) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">No payments found</td>
          </tr>
        <?php endif; ?>
        <?php $conn->close(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>