<?php
include '../connection.php';
include 'header.php';
$sql = "SELECT * FROM admins";
$result = $conn->query($sql);
?>
<div class="container-fluid">
  <div class="mb-3 admin-panel-banner">
    <h2 class="admin-panel-title">Admin Panel</h2>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-heading">Admins</h2>
    <div>
      <button class="btn btn-success" id="btnNewAdmin" data-bs-toggle="modal" data-bs-target="#newAdminModal">+ New Admin</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle text-center">
      <thead class="table-dark">
        <tr class="admin-nowrap">
          <th>ID</th>
          <th>Username</th>
          <th>Password</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row["admin_id"] ?></td>
              <td><?= htmlspecialchars($row["admin_username"]) ?></td>
              <td>**********</td>
              <td>
                <button class="btn btn-sm btn-primary btnChangePwd" data-admin-id="<?= $row['admin_id'] ?>" data-admin-username="<?= htmlspecialchars($row['admin_username']) ?>">Change Password</button>
                <button class="btn btn-sm btn-danger btnDeleteAdmin ms-2" data-admin-id="<?= $row['admin_id'] ?>" data-admin-username="<?= htmlspecialchars($row['admin_username']) ?>">Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No admins found</td>
          </tr>
        <?php endif; ?>
        <?php $conn->close(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>
<div class="modal fade" id="newAdminModal" tabindex="-1" aria-labelledby="newAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAdminModalLabel">Create New Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="newAdminForm" autocomplete="off">
          <div class="mb-3">
            <label for="newAdminUsername" class="form-label">Username</label>
            <input type="text" id="newAdminUsername" name="username" class="form-control" maxlength="20" required>
          </div>
          <div class="mb-3">
            <label for="newAdminPassword" class="form-label">New Password</label>
            <div class="input-group">
              <input type="password" id="newAdminPassword" name="password" class="form-control" minlength="4" maxlength="20" required>
              <button class="btn btn-outline-secondary" type="button" id="toggleNewAdminPassword">Show</button>
            </div>
          </div>
          <div class="mb-3">
            <label for="newAdminConfirm" class="form-label">Confirm Password</label>
            <div class="input-group">
              <input type="password" id="newAdminConfirm" name="confirm" class="form-control" minlength="4" maxlength="20" required>
              <button class="btn btn-outline-secondary" type="button" id="toggleNewAdminConfirm">Show</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="createAdminBtn">Create Admin</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changePwdModal" tabindex="-1" aria-labelledby="changePwdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePwdModalLabel">Change Admin Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="changePwdForm" autocomplete="off">
          <input type="hidden" id="chgAdminId" name="admin_id" value="">
          <div class="mb-2"><strong>Admin's Username : <span id="chgAdminUser"></span></strong></div>
          <div class="mb-3">
            <label for="chgNewPassword" class="form-label">New Password</label>
            <div class="input-group">
              <input type="password" id="chgNewPassword" name="password" class="form-control" minlength="4" maxlength="20" required>
              <button class="btn btn-outline-secondary" type="button" id="toggleChgNewPwd">Show</button>
            </div>
          </div>
          <div class="mb-3">
            <label for="chgConfirmPassword" class="form-label">Confirm Password</label>
            <div class="input-group">
              <input type="password" id="chgConfirmPassword" name="confirm" class="form-control" minlength="4" maxlength="20" required>
              <button class="btn btn-outline-secondary" type="button" id="toggleChgConfirmPwd">Show</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="changePwdBtn">Change Password</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const createBtn = document.getElementById('createAdminBtn');
    const newAdminUsernameEl = document.getElementById('newAdminUsername');
    if (newAdminUsernameEl) {
      newAdminUsernameEl.addEventListener('input', function() {
        if (this.value.length > 20) this.value = this.value.slice(0, 20);
      });
    }
    createBtn.addEventListener('click', function() {
      const username = document.getElementById('newAdminUsername').value.trim();
      const password = document.getElementById('newAdminPassword').value;
      const confirm = document.getElementById('newAdminConfirm').value;
      if (!username) {
        Swal.fire('Validation', 'Please enter a username', 'warning');
        return;
      }
      if (password.length < 4) {
        Swal.fire('Validation', 'Password must be at least 4 characters long', 'warning');
        return;
      }
      if (password !== confirm) {
        Swal.fire('Validation', 'Passwords do not match', 'warning');
        return;
      }
      createBtn.disabled = true;
      fetch('create_admin.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password)
      }).then(r => r.json()).then(data => {
        createBtn.disabled = false;
        if (data && data.success) {
          Swal.fire({
            title: 'Success',
            text: data.message || 'Admin created',
            icon: 'success'
          }).then(() => location.reload());
        } else {
          Swal.fire('Error', (data && data.message) ? data.message : 'Failed to create admin', 'error');
        }
      }).catch(err => {
        createBtn.disabled = false;
        Swal.fire('Error', 'Server error: ' + err.message, 'error');
      });
    });
    const togglePwd = document.getElementById('toggleNewAdminPassword');
    const toggleConfirm = document.getElementById('toggleNewAdminConfirm');
    const pwdInput = document.getElementById('newAdminPassword');
    const confInput = document.getElementById('newAdminConfirm');
    const newAdminPwdEl = document.getElementById('newAdminPassword');
    const newAdminConfirmEl = document.getElementById('newAdminConfirm');
    if (newAdminPwdEl) {
      newAdminPwdEl.addEventListener('input', function() {
        if (this.value.length > 20) this.value = this.value.slice(0, 20);
      });
    }
    if (newAdminConfirmEl) {
      newAdminConfirmEl.addEventListener('input', function() {
        if (this.value.length > 20) this.value = this.value.slice(0, 20);
      });
    }

    function toggleInput(btn, input) {
      if (!btn || !input) return;
      btn.addEventListener('click', function() {
        if (input.type === 'password') {
          input.type = 'text';
          btn.textContent = 'Hide';
        } else {
          input.type = 'password';
          btn.textContent = 'Show';
        }
      });
    }
    toggleInput(togglePwd, pwdInput);
    toggleInput(toggleConfirm, confInput);
  });
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btnChangePwd').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = this.getAttribute('data-admin-id');
        var user = this.getAttribute('data-admin-username');
        document.getElementById('chgAdminId').value = id;
        document.getElementById('chgAdminUser').textContent = user;
        document.getElementById('chgNewPassword').value = '';
        document.getElementById('chgConfirmPassword').value = '';
        var modal = new bootstrap.Modal(document.getElementById('changePwdModal'));
        modal.show();
      });
    });

    function toggle(btnId, inputId) {
      var btn = document.getElementById(btnId);
      var input = document.getElementById(inputId);
      if (!btn || !input) return;
      btn.addEventListener('click', function() {
        if (input.type === 'password') {
          input.type = 'text';
          btn.textContent = 'Hide';
        } else {
          input.type = 'password';
          btn.textContent = 'Show';
        }
      });
    }
    toggle('toggleChgNewPwd', 'chgNewPassword');
    toggle('toggleChgConfirmPwd', 'chgConfirmPassword');
    var chgNewPwdEl = document.getElementById('chgNewPassword');
    var chgConfirmEl = document.getElementById('chgConfirmPassword');
    if (chgNewPwdEl) {
      chgNewPwdEl.addEventListener('input', function() {
        if (this.value.length > 20) this.value = this.value.slice(0, 20);
      });
    }
    if (chgConfirmEl) {
      chgConfirmEl.addEventListener('input', function() {
        if (this.value.length > 20) this.value = this.value.slice(0, 20);
      });
    }
    document.getElementById('changePwdBtn').addEventListener('click', function() {
      var id = document.getElementById('chgAdminId').value;
      var pwd = document.getElementById('chgNewPassword').value;
      var conf = document.getElementById('chgConfirmPassword').value;
      if (!id) return;
      if (pwd.length < 4) {
        Swal.fire('Validation', 'Password must be at least 4 characters', 'warning');
        return;
      }
      if (pwd !== conf) {
        Swal.fire('Validation', 'Passwords do not match', 'warning');
        return;
      }
      this.disabled = true;
      fetch('change_admin_password.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'admin_id=' + encodeURIComponent(id) + '&password=' + encodeURIComponent(pwd)
      }).then(async (r) => {
        this.disabled = false;
        const text = await r.text();
        let data = null;
        try {
          data = JSON.parse(text);
        } catch (e) {
          Swal.fire('Server Response', text || 'Empty response from server', 'error');
          return;
        }
        if (data && data.success) {
          Swal.fire('Success', data.message || 'Password changed', 'success').then(() => location.reload());
        } else {
          Swal.fire('Error', (data && data.message) ? data.message : 'Failed to change password', 'warning');
        }
      }).catch(err => {
        this.disabled = false;
        Swal.fire('Error', 'Server error: ' + err.message, 'error');
      });
    });
    document.querySelectorAll('.btnDeleteAdmin').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = this.getAttribute('data-admin-id');
        var user = this.getAttribute('data-admin-username');
        if (!id) return;
        Swal.fire({
          title: 'Delete Admin',
          text: "Delete admin '" + user + "'? This action cannot be undone.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel'
        }).then(function(result) {
          if (!result.isConfirmed) return;
          var btnEl = btn;
          btnEl.disabled = true;
          fetch('delete_admin.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'admin_id=' + encodeURIComponent(id) + '&admin_username=' + encodeURIComponent(user)
          }).then(r => r.json()).then(data => {
            btnEl.disabled = false;
            if (data && data.success) {
              Swal.fire('Deleted', data.message || 'Admin deleted', 'success').then(() => location.reload());
            } else {
              Swal.fire('Error', (data && data.message) ? data.message : 'Failed to delete admin', 'error');
            }
          }).catch(err => {
            btnEl.disabled = false;
            Swal.fire('Error', 'Server error: ' + err.message, 'error');
          });
        });
      });
    });
  });
</script>