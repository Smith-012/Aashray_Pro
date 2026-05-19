<?php
include 'header.php';
include '../connection.php';
$sql = "SELECT * FROM invoice";
$result = $conn->query($sql);
?>
<div class="container-fluid">
    <div class="mb-3 admin-panel-banner">
        <h2 class="admin-panel-title">Admin Panel</h2>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="page-heading">All Bookings</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle admin-bookings-table">
            <thead class="table-dark">
                <tr class="admin-nowrap">
                    <th>Sr No</th>
                    <th>Property No</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact No</th>
                    <th>Email ID</th>
                    <th>Rent Amount</th>
                    <th>Rent Type</th>
                    <th>Rent Period</th>
                    <th>Rent Start Date</th>
                    <th>Rent End Date</th>
                    <th>Aadhar Card No</th>
                    <th>Booking Date</th>
                    <th>Transaction ID</th>
                    <th>Payment Mode</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?= $row["invoice_id"] ?></td>
                            <td><?= $row["property_no"] ?></td>
                            <td><?= $row["user_username"] ?></td>
                            <td><?= $row["first_name"] ?></td>
                            <td><?= $row["last_name"] ?></td>
                            <td><?= $row["contact_no"] ?></td>
                            <td><?= $row["email_id"] ?></td>
                            <td>₹ <?= number_format($row["rent_amount"], 2) ?></td>
                            <td><?= $row["rent_dmn"] ?></td>
                            <td><?= $row["rent_period"] ?></td>
                            <td><?= date('d-m-Y h:i:s A', strtotime($row["rent_date"])) ?></td>
                            <td><?= date('d-m-Y h:i:s A', strtotime($row["rent_end_date"])) ?></td>
                            <td><?= $row["verification_doc_no"] ?></td>
                            <td><?= date('d-m-Y h:i:s A', strtotime($row["booking_date"])) ?></td>
                            <td><?= $row["transaction_id"] ?></td>
                            <td><?= $row["payment_mode"] ?></td>
                            <td><?= date('d-m-Y h:i:s A', strtotime($row["payment_date"])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="17" class="text-center">No bookings found</td>
                    </tr>
                <?php endif; ?>
                <?php $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>