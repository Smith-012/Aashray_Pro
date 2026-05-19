<?php
session_start();
if (!isset($_SESSION['logged_in_user'])) {
  echo <<<EOD
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Redirecting...</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>
<body>
  <script>
    Swal.fire({
      title: 'Aashray',
      text: 'Please sign in to Aashray before proceeding !!',
      icon: 'error',
      confirmButtonText: 'OK',
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then(() => window.history.back());
  </script>
</body>
</html>
EOD;
  exit;
}
include 'header.php';
include 'connection.php';
$property_number = "Property number not provided";
$rent_amount     = "Rent Amount not provided";
$rent_dmn        = "Rent type not available";
if (!empty($_POST['property_number'])) {
  $property_number = trim($_POST['property_number']);
  $_SESSION['propertynumber'] = $property_number;
}
if (!empty($_POST['rent_amount'])) {
  $rent_amount_raw = trim($_POST['rent_amount']);
  preg_match('/[\d,]+/', $rent_amount_raw, $numMatch);
  $rent_amount = isset($numMatch[0]) ? str_replace(',', '', $numMatch[0]) : "Rent not provided";
  $_SESSION['amount'] = $rent_amount;
  preg_match('/\b(Day|Month)\b/i', $rent_amount_raw, $typeMatch);
  $rent_dmn = $typeMatch[0] ?? "Rent type not provided";
}
$_SESSION['propertynumber'] = $property_number;
$_SESSION['amount'] = $rent_amount;
$_SESSION['user_username'] = $_SESSION['logged_in_user'];
?>
<main class="main">
  <br><br><br><br><br>
  <section id="booking" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row justify-content-center gy-4">
        <div class="col-lg-8">
          <div class="section-title text-center">
            <h2>Property Booking for Rent</h2>
          </div>
          <form method="POST" class="p-4 shadow rounded bg-light" data-aos="fade-up" data-aos-delay="200" id="bookingForm" autocomplete="off" novalidate>
            <div class="mb-3">
              <label for="property_number">&nbsp;&nbsp;&nbsp;Property Number :</label>
              <input type="text" class="form-control" name="property_number" id="property_number" value="<?= htmlspecialchars($property_number) ?>" readonly>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="firstname">&nbsp;&nbsp;&nbsp;First Name :</label>
                <input type="text" class="form-control" name="firstname" id="firstname" required oninput="capitalize(this)" maxlength="10" pattern="[A-Za-z]{1,10}">
                <div class="invalid-feedback">Please enter a valid first name (letters only, max 10 characters).</div>
              </div>
              <div class="col-md-6">
                <label for="lastname">&nbsp;&nbsp;&nbsp;Last Name :</label>
                <input type="text" class="form-control" name="lastname" id="lastname" required oninput="capitalize(this)" maxlength="10" pattern="[A-Za-z]{1,10}">
                <div class="invalid-feedback">Please enter a valid last name (letters only, max 10 characters).</div>
              </div>
            </div>
            <div class="mb-3">
              <label for="contact_no">&nbsp;&nbsp;&nbsp;Contact Number :</label>
              <input type="text" class="form-control" name="contact_no" id="contact_no" required maxlength="10" pattern="\d{10}" onkeypress="return isNumberKey(event)">
              <div class="invalid-feedback">Contact number must be exactly 10 digits.</div>
            </div>
            <div class="mb-3">
              <label for="email_id">&nbsp;&nbsp;&nbsp;E-mail Id :</label>
              <input type="email" class="form-control" name="email_id" id="email_id" required>
              <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label>&nbsp;&nbsp;&nbsp;Rent Amount :</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($rent_amount) ?>" readonly>
                <input type="hidden" name="rent_amount" value="<?= htmlspecialchars($rent_amount) ?>">
              </div>
              <div class="col-md-4">
                <label>&nbsp;&nbsp;&nbsp;Rent Type :</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($rent_dmn) ?>" readonly>
                <input type="hidden" name="rent_dmn" value="<?= htmlspecialchars($rent_dmn) ?>">
              </div>
              <div class="col-md-4">
                <label for="rent_period">&nbsp;&nbsp;&nbsp;Rent Period :</label>
                <input type="number" class="form-control" name="rent_period" id="rent_period" required min="1" step="1">
                <div class="invalid-feedback">Please enter a valid rent period (minimum 1).</div>
              </div>
            </div>
            <div class="mb-3">
              <label for="rentdate">&nbsp;&nbsp;&nbsp;Check In Date (Select date When you want to get property on rent) :</label>
              <input type="datetime-local" class="form-control" name="rentdate" id="rentdate" required>
              <div class="invalid-feedback">Please select a valid date.</div>
            </div>
            <div class="mb-3">
              <label for="verify_d">&nbsp;&nbsp;&nbsp;Aadhar Card Number :</label>
              <input type="text" class="form-control" name="verify_d" id="verify_d" placeholder="0000 0000 0000" required maxlength="14" onkeypress="return isNumberKey(event)" oninput="formatAadharNumber()" onblur="validateAadharNumber()">
              <div class="invalid-feedback">Aadhar number must be exactly 12 digits.</div>
            </div>
            <div class="text-center mt-4">
              <button type="submit" name="done" id="done" class="btn btn-primary px-4 py-2">Book</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
  function capitalize(input) {
    let val = input.value;
    if (val.length > 0) {
      input.value = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase();
    }
  }

  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
  }

  function formatAadharNumber() {
    var input = document.getElementById('verify_d');
    var val = input.value.replace(/\s+/g, '').slice(0, 12);
    var formatted = val.replace(/(.{4})/g, '$1 ').trim();
    input.value = formatted;
    if (/^\d{12}$/.test(val)) {
      input.classList.remove('is-invalid');
      input.setCustomValidity('');
    }
  }

  function validateAadharNumber() {
    var input = document.getElementById('verify_d');
    var val = input.value.replace(/\s+/g, '');
    if (val.length !== 12 || !/^\d{12}$/.test(val)) {
      input.classList.add('is-invalid');
      input.setCustomValidity('Aadhar number must be exactly 12 digits.');
    } else {
      input.classList.remove('is-invalid');
      input.setCustomValidity('');
    }
  }
  (function() {
    'use strict'
    var forms = document.querySelectorAll('#bookingForm')
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
          var invalidEl = form.querySelector(':invalid');
          if (invalidEl) {
            if (invalidEl.id === 'verify_d') {
              Swal.fire({
                title: 'Aashray',
                text: 'Aadhar Number must contain exactly 12 digits!',
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
              }).then(function() {
                invalidEl.value = '';
                invalidEl.focus();
              });
            } else {
              var msgs = [];
              var invalids = form.querySelectorAll(':invalid');
              invalids.forEach(function(el) {
                var lbl = form.querySelector('label[for="' + el.id + '"]');
                var name = lbl ? lbl.innerText.replace(/\s+/g, ' ').trim() : (el.name || el.id);
                msgs.push(name + ' is invalid');
              });
              Swal.fire({
                title: 'Aashray',
                text: msgs.join('\n'),
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
              }).then(function() {
                invalidEl.focus();
              });
            }
          }
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
  const rentInput = document.getElementById("rentdate");

  function getLocalDateTimeString(date) {
    const pad = (n) => n.toString().padStart(2, '0');
    const year = date.getFullYear();
    const month = pad(date.getMonth() + 1);
    const day = pad(date.getDate());
    const hours = pad(date.getHours());
    const minutes = pad(date.getMinutes());
    return `${year}-${month}-${day}T${hours}:${minutes}`;
  }
  const now = new Date();
  now.setHours(now.getHours() + 24);
  const minDateTime = getLocalDateTimeString(now);
  const maxDateObj = new Date();
  maxDateObj.setHours(maxDateObj.getHours() + 24);
  maxDateObj.setFullYear(maxDateObj.getFullYear() + 1);
  const maxDateTime = getLocalDateTimeString(maxDateObj);
  rentInput.min = minDateTime;
  rentInput.max = maxDateTime;
  rentInput.value = minDateTime;
</script>
<?php
if (isset($_POST['done'])) {
  $propertynumber = $_POST['property_number'];
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $contactno = trim($_POST['contact_no']);
  $emailid = trim($_POST['email_id']);
  $rentperiod = (int)$_POST['rent_period'];
  $rentdate = $_POST['rentdate'];
  $rent_amount = $_POST['rent_amount'];
  $rent_dmn = $_POST['rent_dmn'];
  $verify = str_replace(' ', '', $_POST['verify_d']);
  $userfor = $_SESSION['logged_in_user'];
  $errors = [];
  if (!preg_match("/^[A-Za-z]{1,10}$/", $firstname)) {
    $errors[] = "Invalid First Name.";
  }
  if (!preg_match("/^[A-Za-z]{1,10}$/", $lastname)) {
    $errors[] = "Invalid Last Name.";
  }
  if (!preg_match("/^\d{10}$/", $contactno)) {
    $errors[] = "Contact Number must be exactly 10 digits.";
  }
  if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid Email address.";
  }
  if ($rentperiod < 1) {
    $errors[] = "Rent Period must be at least 1.";
  }
  $today = strtotime(date("Y-m-d"));
  $maxDate = strtotime("+1 year");
  if (strtotime($rentdate) < $today) {
    $errors[] = "Rent Start Date cannot be in the past.";
  }
  if (strtotime($rentdate) > $maxDate) {
    $errors[] = "Rent Start Date cannot be more than 1 year from today.";
  }
  if (!preg_match("/^\d{12}$/", $verify)) {
    $errors[] = "Aadhar number must be exactly 12 digits.";
  }
  if (empty($errors)) {
    $total_rent = (int)$rent_amount * $rentperiod;
    $_SESSION['total_rent_amount'] = $total_rent;
    $sql = "INSERT INTO tenants (
    property_no, user_username, first_name, last_name, contact_no, email_id, 
    rent_amount, rent_dmn, rent_period, rent_date, verification_doc_no, 
    payment_status, booking_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    $sql2 = "INSERT INTO invoice (
    property_no, user_username, first_name, last_name, contact_no, email_id, 
    rent_amount, rent_dmn, rent_period, rent_date, verification_doc_no, booking_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
      "sssssssssss",
      $propertynumber,
      $userfor,
      $firstname,
      $lastname,
      $contactno,
      $emailid,
      $rent_amount,
      $rent_dmn,
      $rentperiod,
      $rentdate,
      $verify
    );
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(
      "sssssssssss",
      $propertynumber,
      $userfor,
      $firstname,
      $lastname,
      $contactno,
      $emailid,
      $total_rent,
      $rent_dmn,
      $rentperiod,
      $rentdate,
      $verify
    );
    if ($stmt->execute() && $stmt2->execute()) {
      $_SESSION['flash_success'] = "Booking Confirmed! Redirecting to payment...";
?>
    <script>
        Swal.fire({
            title: 'Success!',
            text: 'Booking Confirmed! Redirecting you to payment page..',
            icon: 'success',
            showConfirmButton: false,
            timer: 5000
        }).then(() => {
            window.location.href = 'payment.php';
        });
    </script>
<?php
      exit;
    } else {
      $_SESSION['flash_error'] = "Something went wrong. Please try again.";
?>
    <script>
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.reload();
        });
    </script>
<?php exit;
    }
  } else {
    $err_text = implode("\\n", $errors);
?>
    <script>alert('Validation Errors:\n<?= $err_text ?>');</script>
<?php
  }
}
?>
<?php include('footer.php'); ?>