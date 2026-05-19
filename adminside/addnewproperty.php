<?php
include '../connection.php';
include 'header.php';
?>
<div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
</div>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="page-heading">Add New Property</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Back</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <form action="" method="POST" id="addnewproperty" enctype="multipart/form-data" class="p-4 shadow rounded bg-light">
                <h4 class="mb-3">Property Details</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="newprono" class="form-label">Property Number</label>
                        <input type="text" class="form-control" name="newprono" id="newprono" placeholder="Ex. B-51" required>
                    </div>
                    <div class="col-md-6">
                        <label for="newprotype" class="form-label">Property Type</label>
                        <select class="form-select" name="newprotype" id="newprotype" required>
                            <option value="" disabled selected>Select property type</option>
                            <?php
                            $type_dirs = glob("../assets/img/Z Property Images/*", GLOB_ONLYDIR);
                            foreach ($type_dirs as $tdir) {
                                $typeName = basename($tdir);
                                echo "<option value='" . htmlspecialchars($typeName, ENT_QUOTES) . "'>" . htmlspecialchars($typeName) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newproaddr" class="form-label">Property Address</label>
                    <input type="text" class="form-control" name="newproaddr" id="newproaddr" placeholder="Address" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="newproarea" class="form-label">Area</label>
                        <input type="text" class="form-control" name="newproarea" id="newproarea" placeholder="Area" required>
                    </div>
                    <div class="col-md-4">
                        <label for="newprocity" class="form-label">City</label>
                        <input type="text" class="form-control" name="newprocity" id="newprocity" placeholder="City" required>
                    </div>
                    <div class="col-md-4">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" name="pincode" id="pincode" maxlength="6" pattern="\d{6}" placeholder="6 digit pincode" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="newprostate" class="form-label">State</label>
                        <input type="text" class="form-control" name="newprostate" id="newprostate" placeholder="State" required>
                    </div>
                    <div class="col-md-6">
                        <label for="rentamou" class="form-label">Rent Amount</label>
                        <input type="text" class="form-control" name="rentamou" id="rentamou" placeholder="Example: Rs.8,800 / Month" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="details" class="form-label">Property Description</label>
                    <textarea rows="5" class="form-control" name="details" id="details" placeholder="Brief description of property" required></textarea>
                </div>
                <h4 class="mt-4 mb-3">Owner Details</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="ownername" class="form-label">Owner Name</label>
                        <input type="text" class="form-control" name="ownername" id="ownername" placeholder="Property Owner Name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="ownerno" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="ownerno" id="ownerno" maxlength="10" pattern="\d{10}" placeholder="10 digit contact number" required>
                    </div>
                    <div class="col-md-4">
                        <label for="owneremail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="owneremail" id="owneremail" maxlength="50" placeholder="example@gmail.com" required>
                    </div>
                </div>
                <h4 class="mt-4 mb-3">Photos</h4>
                <div class="mb-3">
                    <label for="folder" class="form-label">Select Folder for Image Store</label>
                    <select class="form-select" name="folder" id="folder" required>
                        <option value="" disabled selected>Select a folder</option>
                        <?php
                        $dirs = glob("../assets/img/Z Property Images/*", GLOB_ONLYDIR);
                        foreach ($dirs as $dir) {
                            $folderName = basename($dir);
                            echo "<option value='" . htmlspecialchars($folderName, ENT_QUOTES) . "'>" . htmlspecialchars($folderName) . "</option>";
                        }
                        echo "<option value='__new__'>+ Create new folder...</option>";
                        ?>
                    </select>
                    <div id="new-folder-group" class="mt-2 admin-hidden">
                        <div class="input-group">
                            <input type="text" class="form-control" name="new_folder_name" id="new_folder_name" placeholder="Enter new folder name (letters, numbers, -, _ and spaces)">
                            <button type="button" id="create-folder-btn" class="btn btn-success">Create</button>
                        </div>
                        <small class="form-text text-muted">Allowed: letters, numbers, hyphen, underscore and spaces (spaces are allowed).</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="photos" class="form-label">Upload Photos (3 to 10)</label>
                    <input type="file" class="form-control" name="photos[]" id="photos" multiple required>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" value="add" name="add">+ Add New Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script>
    document.getElementById("photos").addEventListener("change", function() {
        const selectedCount = this.files.length;
        if (selectedCount < 3 || selectedCount > 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid selection',
                text: 'Please select between 3 to 10 photos.',
                confirmButtonText: 'OK'
            });
            this.value = "";
        }
    });
    const folderSelect = document.getElementById('folder');
    const newFolderGroup = document.getElementById('new-folder-group');
    const typeSelect = document.getElementById('newprotype');
    folderSelect.addEventListener('change', function() {
        if (this.value === '__new__') {
            newFolderGroup.style.display = 'block';
            document.getElementById('new_folder_name').setAttribute('required', 'required');
        } else {
            newFolderGroup.style.display = 'none';
            document.getElementById('new_folder_name').removeAttribute('required');
        }
        if (this.value && this.value !== '__new__') {
            const opt = Array.from(typeSelect.options).find(o => o.value === this.value);
            if (opt) typeSelect.value = this.value;
        }
    });
    typeSelect.addEventListener('change', function() {
        if (this.value) {
            const opt = Array.from(folderSelect.options).find(o => o.value === this.value);
            if (opt) folderSelect.value = this.value;
        }
    });
    document.getElementById('create-folder-btn').addEventListener('click', function() {
        const nameInput = document.getElementById('new_folder_name');
        const rawName = nameInput.value.trim();
        if (!rawName) {
            Swal.fire({
                icon: 'warning',
                title: 'Enter folder name',
                text: 'Please enter a name for the new folder.'
            });
            return;
        }
        fetch('create_folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'folderName=' + encodeURIComponent(rawName)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    let exists = false;
                    for (let i = 0; i < folderSelect.options.length; i++) {
                        if (folderSelect.options[i].value === data.folder) {
                            exists = true;
                            break;
                        }
                    }
                    if (!exists) {
                        const opt = document.createElement('option');
                        opt.value = data.folder;
                        opt.text = data.folder;
                        const newIndex = Array.from(folderSelect.options).findIndex(o => o.value === '__new__');
                        if (newIndex >= 0) folderSelect.add(opt, folderSelect.options[newIndex]);
                        else folderSelect.add(opt);
                    }
                    let existsType = false;
                    for (let i = 0; i < typeSelect.options.length; i++) {
                        if (typeSelect.options[i].value === data.folder) {
                            existsType = true;
                            break;
                        }
                    }
                    if (!existsType) {
                        const opt2 = document.createElement('option');
                        opt2.value = data.folder;
                        opt2.text = data.folder;
                        typeSelect.add(opt2);
                    }
                    folderSelect.value = data.folder;
                    typeSelect.value = data.folder;
                    newFolderGroup.style.display = 'none';
                    nameInput.value = '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Folder created',
                        text: 'Folder "' + data.folder + '" created.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Failed to create folder'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Request failed'
                });
            });
    });
    document.getElementById('addnewproperty').addEventListener('submit', function(e) {
        const typeVal = document.getElementById('newprotype').value;
        const folderVal = document.getElementById('folder').value;
        if (!typeVal) {
            e.preventDefault();
            Swal.fire({
                title: 'Property Type required',
                text: 'Please select a Property Type from the dropdown.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
        if (!folderVal || folderVal === '__new__') {
            e.preventDefault();
            Swal.fire({
                title: 'Folder required',
                text: 'Please select a Folder for image store. If you selected "Create new folder...", create it first using the Create button.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
        return true;
    });
</script>
<?php
if (isset($_POST['add'])) {
    $properno       = mysqli_real_escape_string($conn, $_POST['newprono']);
    $propertype     = mysqli_real_escape_string($conn, $_POST['newprotype']);
    $properadd      = mysqli_real_escape_string($conn, $_POST['newproaddr']);
    $properarea     = mysqli_real_escape_string($conn, $_POST['newproarea']);
    $propercity     = mysqli_real_escape_string($conn, $_POST['newprocity']);
    $properstate    = mysqli_real_escape_string($conn, $_POST['newprostate']);
    $properpincode  = mysqli_real_escape_string($conn, $_POST['pincode']);
    $properrent     = mysqli_real_escape_string($conn, $_POST['rentamou']);
    $properdetail   = mysqli_real_escape_string($conn, $_POST['details']);
    $properowner    = mysqli_real_escape_string($conn, $_POST['ownername']);
    $ownercont      = mysqli_real_escape_string($conn, $_POST['ownerno']);
    $owneremail     = mysqli_real_escape_string($conn, $_POST['owneremail']);
    $folder         = mysqli_real_escape_string($conn, $_POST['folder']);
    $base_images_dir = realpath(__DIR__ . '/../assets/img/Z Property Images');
    if ($base_images_dir === false) {
        $base_images_dir = __DIR__ . '/../assets/img/Z Property Images';
    }
    $available_dirs = array();
    $glob_dirs = glob($base_images_dir . '/*', GLOB_ONLYDIR);
    if ($glob_dirs !== false) {
        foreach ($glob_dirs as $d) {
            $available_dirs[] = basename($d);
        }
    }
    if (empty($propertype) || !in_array($propertype, $available_dirs, true)) {
        echo "<script>
            Swal.fire({ title: 'Invalid Property Type', text: 'Please select a valid Property Type from the dropdown.', icon: 'error', confirmButtonText: 'OK' });
        </script>";
        exit;
    }
    if (empty($folder) || $folder === '__new__' || !in_array($folder, $available_dirs, true)) {
        echo "<script>
            Swal.fire({ title: 'Invalid Folder', text: 'Please select a valid Folder for image store. If you used \"Create new folder...\", create the folder first using the Create button.', icon: 'error', confirmButtonText: 'OK' });
        </script>";
        exit;
    }
    if (count($_FILES['photos']['name']) < 3 || count($_FILES['photos']['name']) > 10) {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Please select between 3 to 10 photos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
        exit;
    }
    $photo_paths = [];
    $upload_dir = "../assets/img/Z Property Images/" . $folder . "/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['photos']['name'][$key]);
        // --- Security: Validate file type by MIME and extension ---
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $real_mime = $finfo->file($tmp_name);
        if (!in_array($file_ext, $allowed_exts, true) || !in_array($real_mime, $allowed_mimes, true)) {
            continue; // Skip any non-image file silently
        }
        // Sanitize filename to prevent directory traversal
        $safe_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file_name);
        $target_path = $upload_dir . $safe_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $photo_paths[] = "assets/img/Z Property Images/" . $folder . "/" . $safe_name;
        }
    }
    $photos_json = json_encode($photo_paths);
    $sql = "INSERT INTO `properties` (
        `property_no`, `property_type`, `property_address`, `area`, `city`, `state`,
        `pincode`, `rent_amount`, `description`, `owner_name`, `owner_contact_no`,
        `owner_email_id`, `property_photos`, `property_listing_dt`, `booking`
    ) VALUES (
        '$properno','$propertype','$properadd','$properarea','$propercity','$properstate',
        '$properpincode','$properrent','$properdetail','$properowner','$ownercont',
        '$owneremail','$photos_json', NOW(), 'Available'
    )";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'Success',
                text: 'New Property Successfully Added!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'dashboard.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Failed to add property: {$conn->error}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
$conn->close();
?>