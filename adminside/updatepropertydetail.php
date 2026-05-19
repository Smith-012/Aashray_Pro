<?php
include('../connection.php');
include('header.php');
if (isset($_POST['save'])) {
    $property_sr_no       = $_POST['propertysrno'];
    $property_no          = $_POST['propertyno'];
    $property_type        = $_POST['property_type'];
    $property_address     = $_POST['property_addr'];
    $property_area        = $_POST['property_area'];
    $property_city        = $_POST['property_city'];
    $property_state       = $_POST['property_state'];
    $property_pincode     = $_POST['property_pincode'];
    $property_rent        = $_POST['property_rent'];
    $property_desc        = $_POST['property_detail'];
    $property_owner_name  = $_POST['property_owner_name'];
    $property_owner_cn    = $_POST['property_owner_cn'];
    $property_owner_email = $_POST['property_owner_email'];
    $folder               = $_POST['folder'];
    $availability = $_POST['availability'];
    $photo_paths = [];
    $stmt_photos = $conn->prepare("SELECT property_photos FROM properties WHERE property_sr_no = ?");
    $stmt_photos->bind_param("i", $property_sr_no);
    $stmt_photos->execute();
    $result_photos = $stmt_photos->get_result();
    $existing_photos = $result_photos->fetch_assoc();
    $stmt_photos->close();
    if ($existing_photos && !empty($existing_photos['property_photos'])) {
        $photo_paths = json_decode($existing_photos['property_photos'], true) ?? [];
    }
    if (!empty($_POST['delete_photos'])) {
        foreach ($_POST['delete_photos'] as $del_photo) {
            $file_path = "../" . $del_photo;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $photo_paths = array_diff($photo_paths, [$del_photo]);
        }
    }
    $new_order = [];
    if (!empty($_POST['photo_order'])) {
        $new_order = json_decode($_POST['photo_order'], true) ?? [];
    }
    if (!empty($new_order)) {
        $photo_paths = array_values(array_intersect($new_order, $photo_paths));
    }
    $original_folder = '';
    if ($existing_photos && !empty($existing_photos['property_photos'])) {
        $orig_list = json_decode($existing_photos['property_photos'], true) ?? [];
        if (!empty($orig_list)) {
            $original_folder = basename(dirname($orig_list[0]));
        }
    }
    if ($original_folder !== '' && isset($folder) && $folder !== $original_folder && !empty($photo_paths)) {
        $src_base = __DIR__ . '/../assets/img/Z Property Images/' . $original_folder . '/';
        $dest_base = __DIR__ . '/../assets/img/Z Property Images/' . $folder . '/';
        if (!is_dir($dest_base)) {
            mkdir($dest_base, 0777, true);
        }
        foreach ($photo_paths as $i => $rel) {
            $filename = basename($rel);
            $src_path = $src_base . $filename;
            $dest_path = $dest_base . $filename;
            if (file_exists($src_path)) {
                if (@rename($src_path, $dest_path) === false) {
                    if (@copy($src_path, $dest_path)) {
                        @unlink($src_path);
                    }
                }
                $photo_paths[$i] = 'assets/img/Z Property Images/' . $folder . '/' . $filename;
            } else {
                $old_prefix = 'assets/img/Z Property Images/' . $original_folder . '/';
                if (strpos($rel, $old_prefix) !== false) {
                    $photo_paths[$i] = str_replace($old_prefix, 'assets/img/Z Property Images/' . $folder . '/', $rel);
                }
            }
        }
    }
    if (!empty($_FILES['photos']['name'][0])) {
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
                $relative_path = str_replace("../", "", $target_path);
                $photo_paths[] = $relative_path;
            }
        }
    }
    $photos_json = json_encode(array_values($photo_paths));
    $sql = "UPDATE `properties` 
            SET `property_no` = ?, 
                `property_type` = ?, 
                `property_address` = ?, 
                `area` = ?, 
                `city` = ?, 
                `state` = ?, 
                `pincode` = ?, 
                `rent_amount` = ?, 
                `description` = ?, 
                `owner_name` = ?, 
                `owner_contact_no` = ?, 
                `owner_email_id` = ?, 
                `property_photos` = ?,
                `booking` = ?
            WHERE `property_sr_no` = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "ssssssssssssssi",
            $property_no,
            $property_type,
            $property_address,
            $property_area,
            $property_city,
            $property_state,
            $property_pincode,
            $property_rent,
            $property_desc,
            $property_owner_name,
            $property_owner_cn,
            $property_owner_email,
            $photos_json,
            $availability,
            $property_sr_no
        );
        if ($stmt->execute()) {
            echo "<script>
                      document.addEventListener('DOMContentLoaded', function() {
                          Swal.fire({
                              icon: 'success',
                              title: 'Changes saved!',
                              text: 'Property details updated successfully.',
                              confirmButtonText: 'OK'
                          }).then(() => {
                              window.location.href='dashboard.php';
                          });
                      });
                  </script>";
        } else {
            echo "<script>
                      document.addEventListener('DOMContentLoaded', function() {
                          Swal.fire({
                              icon: 'error',
                              title: 'Update Failed',
                              text: 'Error updating record: " . addslashes($stmt->error) . "'
                          });
                      });
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                  document.addEventListener('DOMContentLoaded', function() {
                      Swal.fire({
                          icon: 'error',
                          title: 'Database Error',
                          text: '" . addslashes($conn->error) . "'
                      });
                  });
              </script>";
    }
}
$property = null;
if (isset($_GET['property_sr_no'])) {
    $property_sr_no = $_GET['property_sr_no'];
    $query = "SELECT * FROM properties WHERE property_sr_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $property_sr_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    $stmt->close();
}
?>
<div class="container-fluid">
    <div class="mb-3 admin-panel-banner">
        <h2 class="admin-panel-title">Admin Panel</h2>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="page-heading">Update Property Detail</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Back</a>
    </div>
    <?php if ($property): ?>
        <?php
        $photos = json_decode($property['property_photos'], true) ?? [];
        $existing_count = count($photos);
        $max_allowed = max(0, 10 - $existing_count);
        $selectedFolder = '';
        if (!empty($photos)) {
            $first_photo = $photos[0];
            $selectedFolder = basename(dirname($first_photo));
        }
        ?>
        <form action="" method="post" enctype="multipart/form-data" class="p-4 shadow rounded bg-light" id="updatepropertyform">
            <input type="hidden" name="propertysrno" value="<?= $property['property_sr_no'] ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Property Number</label>
                    <input type="text" class="form-control" name="propertyno" value="<?= htmlspecialchars($property['property_no']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Property Type</label>
                    <select class="form-select" name="property_type" id="property_type">
                        <?php
                        $type_dirs = glob("../assets/img/Z Property Images/*", GLOB_ONLYDIR);
                        foreach ($type_dirs as $tdir) {
                            $tname = basename($tdir);
                            $isSel = ($tname === $property['property_type']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($tname, ENT_QUOTES) . "' $isSel>" . htmlspecialchars($tname) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Property Address</label>
                <input type="text" class="form-control" name="property_addr" value="<?= htmlspecialchars($property['property_address']) ?>">
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Area</label>
                    <input type="text" class="form-control" name="property_area" value="<?= htmlspecialchars($property['area']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="property_city" value="<?= htmlspecialchars($property['city']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pincode</label>
                    <input type="text" maxlength="6" class="form-control" name="property_pincode" value="<?= htmlspecialchars($property['pincode']) ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" name="property_state" value="<?= htmlspecialchars($property['state']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rent Amount</label>
                    <input type="text" class="form-control" name="property_rent" value="<?= htmlspecialchars($property['rent_amount']) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Property Description</label>
                <textarea rows="5" class="form-control" name="property_detail"><?= htmlspecialchars($property['description']) ?></textarea>
            </div>
            <h4 class="mt-4 mb-3">Owner Details</h4>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Owner Name</label>
                    <input type="text" class="form-control" name="property_owner_name" value="<?= htmlspecialchars($property['owner_name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Owner Contact</label>
                    <input type="text" class="form-control" name="property_owner_cn" value="<?= htmlspecialchars($property['owner_contact_no']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Owner Email</label>
                    <input type="email" class="form-control" name="property_owner_email" value="<?= htmlspecialchars($property['owner_email_id']) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Availability</label>
                <select class="form-select" name="availability" required>
                    <option value="Available" <?php if ($property['booking'] == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Not Available" <?php if ($property['booking'] == 'Not Available') echo 'selected'; ?>>Not Available</option>
                </select>
            </div>
            <h4 class="mt-4 mb-3">Property Photos</h4>
            <p class="text-muted">Drag photos to change order</p>
            <div id="photo-list" class="row mb-3">
                <?php
                if (!empty($photos)) {
                    foreach ($photos as $photo) {
                        echo "
                        <div class='col-md-3 text-center photo-item' data-photo='$photo'>
                            <img src='../$photo' class='img-fluid mb-2 rounded shadow admin-photo-thumb'>
                            <div>
                                <input type='checkbox' name='delete_photos[]' value='$photo'> Remove
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p>No photos uploaded.</p>";
                }
                ?>
            </div>
            <input type="hidden" name="photo_order" id="photo_order">
            <div class="mb-3">
                <label for="folder" class="form-label">Select Folder for Image Store</label>
                <select class="form-select" name="folder" id="folder" required>
                    <?php
                    $dirs = glob("../assets/img/Z Property Images/*", GLOB_ONLYDIR);
                    foreach ($dirs as $dir) {
                        $folderName = basename($dir);
                        $isSelected = ($folderName === $selectedFolder) ? "selected" : "";
                        echo "<option value='$folderName' $isSelected>$folderName</option>";
                    }
                    ?>
                    <option value="__new__">+ Create new folder...</option>
                </select>
                <div id="new-folder-group" class="mt-2 admin-hidden">
                    <div class="input-group">
                        <input type="text" class="form-control" name="new_folder_name" id="new_folder_name" placeholder="Enter new folder name (letters, numbers, -, _ and spaces)">
                        <button type="button" id="create-folder-btn" class="btn btn-success">Create</button>
                    </div>
                    <small class="form-text text-muted">Allowed: letters, numbers, hyphen, underscore and spaces.</small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Photos (Optional)</label>
                <p class="text-muted">
                    You already uploaded <strong><?= $existing_count ?></strong> photos.
                    You can upload <strong><?= $max_allowed ?></strong> more (maximum 10).
                </p>
                <?php if ($max_allowed > 0): ?>
                    <input type="file" class="form-control" id="photos" name="photos[]" multiple>
                <?php else: ?>
                    <p class="text-danger">You have reached the maximum limit of 10 photos. Delete some to upload more.</p>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <button type="submit" name="save" class="btn btn-primary me-2">Save Changes</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Property not found!</div>
    <?php endif; ?>
</div>
<?php
include 'footer.php';
$conn->close();
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputPhotos = document.getElementById("photos");
        const photoList = document.getElementById("photo-list");
        const photoOrderInput = document.getElementById("photo_order");
        if (inputPhotos) {
            inputPhotos.addEventListener("change", function() {
                const existingCount = <?= $existing_count ?>;
                const maxAllowed = 10 - existingCount;
                const selectedCount = this.files.length;
                if (selectedCount > maxAllowed) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Upload limit exceeded',
                        text: 'You already have ' + existingCount + ' images. ' +
                            'You can only upload ' + maxAllowed + ' more.',
                        confirmButtonText: 'OK'
                    });
                    this.value = "";
                }
            });
        }
        if (photoList) {
            Sortable.create(photoList, {
                animation: 150,
                onEnd: function() {
                    let order = [];
                    document.querySelectorAll(".photo-item").forEach(item => {
                        order.push(item.getAttribute("data-photo"));
                    });
                    photoOrderInput.value = JSON.stringify(order);
                }
            });
            let initialOrder = [];
            document.querySelectorAll(".photo-item").forEach(item => {
                initialOrder.push(item.getAttribute("data-photo"));
            });
            photoOrderInput.value = JSON.stringify(initialOrder);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const folderSel = document.getElementById('folder');
        const typeSel = document.getElementById('property_type');
        const newFolderGroup = document.getElementById('new-folder-group');
        const newFolderInput = document.getElementById('new_folder_name');
        const createFolderBtn = document.getElementById('create-folder-btn');
        const formEl = document.getElementById('updatepropertyform');
        if (!folderSel || !typeSel) return;

        const toggleNewFolder = () => {
            if (folderSel.value === '__new__') {
                newFolderGroup.style.display = 'block';
                newFolderInput?.setAttribute('required', 'required');
            } else {
                newFolderGroup.style.display = 'none';
                newFolderInput?.removeAttribute('required');
            }
        };

        folderSel.addEventListener('change', function() {
            toggleNewFolder();
            if (!this.value || this.value === '__new__') return;
            const opt = Array.from(typeSel.options).find(o => o.value === this.value);
            if (opt) typeSel.value = this.value;
        });

        typeSel.addEventListener('change', function() {
            if (!this.value) return;
            const opt = Array.from(folderSel.options).find(o => o.value === this.value);
            if (opt) folderSel.value = this.value;
        });

        const addOptionIfMissing = (selectEl, value) => {
            let exists = false;
            Array.from(selectEl.options).forEach(o => {
                if (o.value === value) exists = true;
            });
            if (!exists) {
                const opt = document.createElement('option');
                opt.value = value;
                opt.text = value;
                const newOpt = Array.from(selectEl.options).find(o => o.value === '__new__');
                if (newOpt) {
                    selectEl.add(opt, newOpt);
                } else {
                    selectEl.add(opt);
                }
            }
        };

        if (createFolderBtn) {
            createFolderBtn.addEventListener('click', function() {
                const rawName = newFolderInput.value.trim();
                if (!rawName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Enter folder name',
                        text: 'Please enter a name for the new folder.'
                    });
                    return;
                }
                if (!/^[A-Za-z0-9 _-]+$/.test(rawName)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid name',
                        text: 'Use letters, numbers, spaces, hyphen, or underscore only.'
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
                            addOptionIfMissing(folderSel, data.folder);
                            addOptionIfMissing(typeSel, data.folder);
                            folderSel.value = data.folder;
                            typeSel.value = data.folder;
                            toggleNewFolder();
                            newFolderInput?.removeAttribute('required');
                            newFolderGroup.style.display = 'none';
                            newFolderInput.value = '';
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
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Request failed'
                        });
                    });
            });
        }

        if (formEl) {
            formEl.addEventListener('submit', function(e) {
                if (folderSel.value === '__new__') {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Folder required',
                        text: 'Please create the folder first using the Create button, then submit.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        toggleNewFolder();
    });
</script>