<?php
include '../connection.php';
include 'header.php';
$sql = "SELECT * FROM properties";
$result = $conn->query($sql);
?>
<div class="container-fluid">
    <div class="mb-3 admin-panel-banner">
        <h2 class="admin-panel-title">Admin Panel</h2>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="page-heading">Manage Properties</h2>
        <button class="btn btn-success" onclick="redirectToPage()">+ Add New Property</button>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr class="admin-nowrap">
                    <th>Sr No</th>
                    <th>Property No</th>
                    <th>Property Type</th>
                    <th>Address</th>
                    <th>Area</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>Rent</th>
                    <th>Description</th>
                    <th>Owner Name</th>
                    <th>Owner Contact</th>
                    <th>Owner Email</th>
                    <th>Property Listing Date</th>
                    <th>Availability for Booking</th>
                    <th>Photos</th>
                    <th>Action</th>
                </tr>
                <tr class="admin-nowrap bg-light">
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="0" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="1" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="2" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="3" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="4" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="5" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="6" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="7" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="8" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="9" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="10" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="11" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="12" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="13" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" data-index="14" placeholder="Search"></th>
                    <th></th>
                    <th>
                        <button id="searchBtn" class="btn btn-primary btn-sm action-btn">Search</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $photos = [];
                        if (!empty($row["property_photos"])) {
                            $photos = json_decode($row["property_photos"], true);
                        }
                        ?>
                        <tr class="admin-nowrap bg-light">
                            <td class="text-center"><?= $row["property_sr_no"] ?></td>
                            <td><?= $row["property_no"] ?></td>
                            <td><?= $row["property_type"] ?></td>
                            <td><?= $row["property_address"] ?></td>
                            <td><?= $row["area"] ?></td>
                            <td><?= $row["city"] ?></td>
                            <td><?= $row["state"] ?></td>
                            <td><?= $row["pincode"] ?></td>
                            <td><?= $row["rent_amount"] ?></td>
                            <td><?= nl2br($row["description"]) ?></td>
                            <td><?= $row["owner_name"] ?></td>
                            <td><?= $row["owner_contact_no"] ?></td>
                            <td><?= $row["owner_email_id"] ?></td>
                            <td><?= date('d-m-Y h:i:s A', strtotime($row["property_listing_dt"])) ?></td>
                            <td><?= $row["booking"] ?></td>
                            <td>
                                <?php if (!empty($photos)): ?>
                                    <button class="btn btn-sm btn-primary mt-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#photosModal<?= $row['property_sr_no']; ?>">
                                        View Images
                                    </button>
                                    <div class="modal fade" id="photosModal<?= $row['property_sr_no']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Property Photos</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <?php foreach ($photos as $photo): ?>
                                                            <div class="col-md-4 mb-3">
                                                                <img src="../<?= htmlspecialchars($photo); ?>"
                                                                    class="img-fluid rounded shadow-sm"
                                                                    alt="Property Photo">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">No Photos</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm action-btn delete-btn" data-id="<?= $row['property_sr_no'] ?>">
                                    Remove
                                </button><br>
                                <a href="updatepropertydetail.php?property_sr_no=<?= $row['property_sr_no'] ?>"
                                    class="btn btn-warning btn-sm action-btn mt-2">
                                    Update
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15" class="text-center">No properties found</td>
                    </tr>
                <?php endif; ?>
                <?php $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.column-search');
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
        const searchBtn = document.getElementById('searchBtn');

        function filterTable() {
            const filters = Array.from(inputs).map(i => i.value.toLowerCase());
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let show = true;
                filters.forEach((filter, i) => {
                    if (filter && cells[i] && !cells[i].innerText.toLowerCase().includes(filter)) {
                        show = false;
                    }
                });
                row.style.display = show ? '' : 'none';
            });
        }
        searchBtn.addEventListener('click', filterTable);
    });

    function redirectToPage() {
        window.location.href = 'addnewproperty.php';
    }
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
                                body: 'property_sr_no=' + id
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