<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['user_username']) && !isset($_SESSION['logged_in_user'])) {
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
$currentUser = $_SESSION['user_username'] ?? $_SESSION['logged_in_user'];
include 'connection.php';
$propertyNumber = $_SESSION['propertynumber'] ?? '';
$totrentamo     = $_SESSION['total_rent_amount'] ?? '';
function generateRandomTransactionCode($length = 10)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomCode = '';
  for ($i = 0; $i < $length; $i++) {
    $randomCode .= $characters[random_int(0, $charactersLength - 1)];
  }
  return $randomCode;
}
$payment_success = false;
$error_text = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay'])) {
  $payment_option = $_POST['method'] ?? '';
  $payproperty    = $_POST['propernumber'] ?? '';
  $payamount      = $_POST['rent_amo'] ?? '';
  $txid    = generateRandomTransactionCode();
  $_SESSION['transaction_id'] = $txid;
  if ($payment_option === '' || $payproperty === '' || $payamount === '') {
    $error_text = 'Missing required fields.';
  } else {
    try {
      if ($payment_option === 'UPI ID') {
        $upiid  = $_POST['upiid'] ?? '';
        $upipin = $_POST['upipin'] ?? '';
        $upiid = strtolower(trim($upiid));
        $upipin = preg_replace('/\D/', '', $upipin);
        if (strlen($upipin) > 6) $upipin = substr($upipin, 0, 6);
        $stmt = $conn->prepare("SELECT 1 FROM bank_detail WHERE upi_id = ? AND upi_pin = ?");
        $stmt->bind_param("ss", $upiid, $upipin);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
          $p1 = $conn->prepare("INSERT INTO payment (transaction_id, user_username, rent_amount, property_no, payment_mode, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
          $p1->bind_param("ssdss", $txid, $currentUser, $payamount, $payproperty, $payment_option);
          $p2 = $conn->prepare("UPDATE tenants SET payment_status = 'Done' WHERE property_no = ? AND user_username = ? AND payment_status = 'Pending'");
          $p2->bind_param("ss", $payproperty, $currentUser);
          $p3 = $conn->prepare("UPDATE invoice SET transaction_id = ?, payment_mode = ?, payment_date = NOW() 
                                WHERE property_no = ? AND user_username = ? AND (transaction_id = '' OR transaction_id IS NULL)");
          $p3->bind_param("ssss", $txid, $payment_option, $payproperty, $currentUser);
          $p4 = $conn->prepare("UPDATE properties SET booking = 'Not Available' WHERE property_no = ?");
          $p4->bind_param("s", $payproperty);
          if ($p1->execute() && $p2->execute() && $p3->execute() && $p4->execute()) {
            $payment_success = true;
          }
          $p1->close();
          $p2->close();
          $p3->close();
          $p4->close();
        }
        $stmt->close();
      } elseif ($payment_option === 'Bank Account Number') {
        $bank_account = $_POST['bankaccount'] ?? '';
        $bank_ifsc    = $_POST['bankifsc'] ?? '';
        $bank_account = preg_replace('/\D/', '', $bank_account);
        if (strlen($bank_account) > 15) $bank_account = substr($bank_account, 0, 15);
        $bank_ifsc = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $bank_ifsc));
        if (strlen($bank_ifsc) > 15) $bank_ifsc = substr($bank_ifsc, 0, 15);
        $stmt = $conn->prepare("SELECT 1 FROM bank_detail WHERE bank_account_number = ? AND bank_ifsc_code = ?");
        $stmt->bind_param("ss", $bank_account, $bank_ifsc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
          $p1 = $conn->prepare("INSERT INTO payment (transaction_id, user_username, rent_amount, property_no, payment_mode, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
          $p1->bind_param("ssdss", $txid, $currentUser, $payamount, $payproperty, $payment_option);
          $p2 = $conn->prepare("UPDATE tenants SET payment_status = 'Done' WHERE property_no = ? AND user_username = ? AND payment_status = 'Pending'");
          $p2->bind_param("ss", $payproperty, $currentUser);
          $p3 = $conn->prepare("UPDATE invoice SET transaction_id = ?, payment_mode = ?, payment_date = NOW() 
                                WHERE property_no = ? AND user_username = ? AND (transaction_id = '' OR transaction_id IS NULL)");
          $p3->bind_param("ssss", $txid, $payment_option, $payproperty, $currentUser);
          $p4 = $conn->prepare("UPDATE properties SET booking = 'Not Available' WHERE property_no = ?");
          $p4->bind_param("s", $payproperty);
          if ($p1->execute() && $p2->execute() && $p3->execute() && $p4->execute()) $payment_success = true;
          $p1->close();
          $p2->close();
          $p3->close();
          $p4->close();
        }
        $stmt->close();
      } elseif ($payment_option === 'Credit/Debit Card') {
        $card_number_raw  = $_POST['card_number'] ?? '';
        $card_number      = preg_replace('/\D/', '', $card_number_raw);
        $cvv_raw          = $_POST['cvv'] ?? '';
        $cvv              = preg_replace('/\D/', '', $cvv_raw);
        $expiry_date_raw  = $_POST['expiry_date'] ?? '';
        $expiry_date      = trim($expiry_date_raw);
        $card_holder_name_raw = $_POST['card_holder_name'] ?? '';
        $card_holder_name = strtoupper(trim($card_holder_name_raw));
        $stmt = $conn->prepare("SELECT 1 FROM bank_detail WHERE REPLACE(CAST(card_number AS CHAR), ' ', '') = ? AND card_cvv_code = ? AND card_expiry = ? AND UPPER(card_holder_name) = ?");
        $stmt->bind_param("ssss", $card_number, $cvv, $expiry_date, $card_holder_name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
          $p1 = $conn->prepare("INSERT INTO payment (transaction_id, user_username, rent_amount, property_no, payment_mode, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
          $p1->bind_param("ssdss", $txid, $currentUser, $payamount, $payproperty, $payment_option);
          $p2 = $conn->prepare("UPDATE tenants SET payment_status = 'Done' WHERE property_no = ? AND user_username = ? AND payment_status = 'Pending'");
          $p2->bind_param("ss", $payproperty, $currentUser);
          $p3 = $conn->prepare("UPDATE invoice SET transaction_id = ?, payment_mode = ?, payment_date = NOW() 
                                WHERE property_no = ? AND user_username = ? AND (transaction_id = '' OR transaction_id IS NULL)");
          $p3->bind_param("ssss", $txid, $payment_option, $payproperty, $currentUser);
          $p4 = $conn->prepare("UPDATE properties SET booking = 'Not Available' WHERE property_no = ?");
          $p4->bind_param("s", $payproperty);
          if ($p1->execute() && $p2->execute() && $p3->execute() && $p4->execute()) $payment_success = true;
          $p1->close();
          $p2->close();
          $p3->close();
          $p4->close();
        }
        $stmt->close();
      }
    } catch (Throwable $e) {
      $error_text = 'Payment failed. Please try again.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Aashray</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link href="assets/css/main.css" rel="stylesheet">
  <main class="main">
    <br><br><br>
    <section id="payment" class="section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center gy-4">
          <div class="col-lg-8">
            <div class="section-title text-center">
              <h2>Payment</h2>
              <p class="text-muted">Complete your booking payment</p>
            </div>
            <form method="POST" id="paymentform" class="p-4 shadow rounded bg-light" data-aos="fade-up" data-aos-delay="200" novalidate autocomplete="off">
              <div class="mb-3">
                <label for="method" class="form-label">&nbsp;&nbsp;&nbsp;Choose Payment Method :</label>
                <select class="form-control" id="method" name="method" required>
                  <option value="">Select Payment Method</option>
                  <option value="Credit/Debit Card" id="card">Credit/Debit Card</option>
                  <option value="Bank Account Number" id="bank">Bank Account Number</option>
                  <option value="UPI ID" id="upi">UPI ID</option>
                </select>
                <div class="invalid-feedback">Please choose a payment method.</div>
              </div>
              <div id="card_details" class="payment-method-section">
                <div class="mb-3">
                  <label for="card_number" class="form-label">Card Number</label>
                  <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Enter card number">
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Expiry Date</label>
                    <div class="d-flex gap-2">
                      <select id="expiry_month" class="form-control" required>
                        <option value="" disabled selected>MM</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                      <select id="expiry_year" class="form-control" required>
                        <option value="" disabled selected>YYYY</option>
                      </select>
                    </div>
                    <input type="hidden" id="expiry_date" name="expiry_date" value="">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="card_holder_name" class="form-label">Card Holder Name</label>
                  <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="Enter card holder name">
                </div>
              </div>
              <div id="bankAccount" class="payment-method-section">
                <div class="mb-3">
                  <label for="bankaccount" class="form-label">Bank Account Number</label>
                  <input type="text" class="form-control" id="bankaccount" name="bankaccount" placeholder="Enter Bank Account Number" maxlength="15" inputmode="numeric" aria-describedby="bankHelp">
                </div>
                <div class="mb-3">
                  <label for="bankifsc" class="form-label">Bank IFSC Code</label>
                  <input type="text" class="form-control" id="bankifsc" name="bankifsc" placeholder="Enter Bank IFSC Code" maxlength="15" aria-describedby="ifscHelp">
                </div>
              </div>
              <div id="upi_id" class="payment-method-section">
                <div class="mb-3">
                  <label for="upiid" class="form-label">UPI ID</label>
                  <input type="text" class="form-control" id="upiid" name="upiid" placeholder="example@bank" maxlength="25" minlength="3" aria-describedby="upiHelp">
                </div>
                <div class="mb-3">
                  <label for="upipin" class="form-label">UPI Pin</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="upipin" name="upipin" placeholder="Enter UPI Pin" maxlength="6" inputmode="numeric" aria-describedby="upiPinHelp">
                    <button class="btn btn-outline-secondary" type="button" id="toggleUpiPin">Show</button>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Property Number</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($propertyNumber); ?>" readonly>
                <input type="hidden" name="propernumber" value="<?= htmlspecialchars($propertyNumber); ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Amount</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($totrentamo); ?>" readonly>
                <input type="hidden" name="rent_amo" value="<?= htmlspecialchars($totrentamo); ?>">
              </div>
              <div class="text-center mt-3">
                <button type="submit" class="btn btn-success px-4" name="pay" id="pay" value="pay">Pay Now</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php if ($payment_success): ?>
    <div class="alert alert-success payment-alert" id="paymentSuccessAlert">Payment successful !!</div>
    <script>
      const sA = document.getElementById('paymentSuccessAlert');
      sA.style.display = 'block';
      setTimeout(() => {
        sA.style.display = 'none';
        window.location.href = 'invoice.php';
      }, 3000);
    </script>
  <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST" && !$payment_success): ?>
    <div class="alert alert-danger payment-alert" id="paymentErrorAlert">
      <?= htmlspecialchars($error_text ?: 'Invalid payment details. Please enter valid information.'); ?>
    </div>
    <script>
      const eA = document.getElementById('paymentErrorAlert');
      eA.style.display = 'block';
      setTimeout(() => {
        eA.style.display = 'none';
      }, 3000);
    </script>
  <?php endif; ?>
  <script>
    document.getElementById('method').addEventListener('change', function() {
      var paymentMethod = this.value;
      if (paymentMethod === 'Credit/Debit Card') {
        document.getElementById('card_details').style.display = 'block';
        document.getElementById('upi_id').style.display = 'none';
        document.getElementById('bankAccount').style.display = 'none';
        document.getElementById('card_number').setAttribute('required', 'true');
        document.getElementById('expiry_month').setAttribute('required', 'true');
        document.getElementById('expiry_year').setAttribute('required', 'true');
        document.getElementById('cvv').setAttribute('required', 'true');
        document.getElementById('card_holder_name').setAttribute('required', 'true');
        document.getElementById('bankaccount').removeAttribute('required');
        document.getElementById('bankifsc').removeAttribute('required');
        document.getElementById('upiid').removeAttribute('required');
        document.getElementById('upipin').removeAttribute('required');
      } else if (paymentMethod === 'Bank Account Number') {
        document.getElementById('card_details').style.display = 'none';
        document.getElementById('upi_id').style.display = 'none';
        document.getElementById('bankAccount').style.display = 'block';
        document.getElementById('card_number').removeAttribute('required');
        document.getElementById('expiry_month').removeAttribute('required');
        document.getElementById('expiry_year').removeAttribute('required');
        document.getElementById('cvv').removeAttribute('required');
        document.getElementById('card_holder_name').removeAttribute('required');
        document.getElementById('bankaccount').setAttribute('required', 'true');
        document.getElementById('bankifsc').setAttribute('required', 'true');
        document.getElementById('upiid').removeAttribute('required');
        document.getElementById('upipin').removeAttribute('required');
      } else if (paymentMethod === 'UPI ID') {
        document.getElementById('card_details').style.display = 'none';
        document.getElementById('upi_id').style.display = 'block';
        document.getElementById('bankAccount').style.display = 'none';
        document.getElementById('card_number').removeAttribute('required');
        document.getElementById('expiry_month').removeAttribute('required');
        document.getElementById('expiry_year').removeAttribute('required');
        document.getElementById('cvv').removeAttribute('required');
        document.getElementById('card_holder_name').removeAttribute('required');
        document.getElementById('bankaccount').removeAttribute('required');
        document.getElementById('bankifsc').removeAttribute('required');
        document.getElementById('upiid').setAttribute('required', 'true');
        document.getElementById('upipin').setAttribute('required', 'true');
      } else {
        document.getElementById('card_details').style.display = 'none';
        document.getElementById('upi_id').style.display = 'none';
        document.getElementById('bankAccount').style.display = 'none';
        document.getElementById('card_number').removeAttribute('required');
        document.getElementById('expiry_month').removeAttribute('required');
        document.getElementById('expiry_year').removeAttribute('required');
        document.getElementById('cvv').removeAttribute('required');
        document.getElementById('card_holder_name').removeAttribute('required');
        document.getElementById('bankaccount').removeAttribute('required');
        document.getElementById('bankifsc').removeAttribute('required');
        document.getElementById('upiid').removeAttribute('required');
        document.getElementById('upipin').removeAttribute('required');
      }
    });
    (function() {
      var cardNumberEl = document.getElementById('card_number');
      var expiryMonthEl = document.getElementById('expiry_month');
      var expiryYearEl = document.getElementById('expiry_year');
      var expiryHiddenEl = document.getElementById('expiry_date');
      var cvvEl = document.getElementById('cvv');
      var cardNameEl = document.getElementById('card_holder_name');
      var bankAccountEl = document.getElementById('bankaccount');
      var bankIfscEl = document.getElementById('bankifsc');
      var upiIdEl = document.getElementById('upiid');
      var upiPinEl = document.getElementById('upipin');
      var toggleUpiPinBtn = document.getElementById('toggleUpiPin');

      function formatCardNumber() {
        if (!cardNumberEl) return;
        var digits = cardNumberEl.value.replace(/\D/g, '').slice(0, 16);
        var formatted = digits.replace(/(.{4})/g, '$1 ').trim();
        cardNumberEl.value = formatted;
        if (digits.length === 16) {
          cardNumberEl.classList.remove('is-invalid');
          cardNumberEl.setCustomValidity('');
        } else if (digits.length > 0) {
          cardNumberEl.setCustomValidity('Card number must be 16 digits');
        } else {
          cardNumberEl.setCustomValidity('');
        }
      }
      (function populateYearsAndSetup() {
        if (!expiryYearEl) return;
        var now = new Date();
        var start = now.getFullYear();
        var end = start + 13;
        for (var y = start; y <= end; y++) {
          var o = document.createElement('option');
          o.value = y.toString();
          o.text = y.toString();
          expiryYearEl.appendChild(o);
        }
        expiryYearEl.addEventListener('change', function() {
          expiryYearEl.classList.remove('is-invalid');
          expiryYearEl.setCustomValidity('');
          adjustMonthOptions();
        });
        if (expiryMonthEl) expiryMonthEl.addEventListener('change', function() {
          expiryMonthEl.classList.remove('is-invalid');
          expiryMonthEl.setCustomValidity('');
        });
      })();

      function adjustMonthOptions() {
        if (!expiryMonthEl || !expiryYearEl) return;
        var now = new Date();
        var curFullYear = now.getFullYear();
        var curMonth = now.getMonth() + 1; // 1-12
        var selectedYear = parseInt(expiryYearEl.value, 10);
        for (var i = 1; i < expiryMonthEl.options.length; i++) {
          var opt = expiryMonthEl.options[i];
          var m = parseInt(opt.value, 10);
          if (selectedYear === curFullYear) {
            opt.disabled = (m <= curMonth);
          } else {
            opt.disabled = false;
          }
        }
        if (expiryMonthEl.selectedIndex > 0 && expiryMonthEl.options[expiryMonthEl.selectedIndex].disabled) {
          expiryMonthEl.selectedIndex = 0;
        }
      }

      function formatCVV() {
        if (!cvvEl) return;
        cvvEl.value = cvvEl.value.replace(/\D/g, '').slice(0, 3);
        if (cvvEl.value.length === 3) {
          cvvEl.classList.remove('is-invalid');
          cvvEl.setCustomValidity('');
        } else if (cvvEl.value.length > 0) {
          cvvEl.setCustomValidity('CVV must be 3 digits');
        } else {
          cvvEl.setCustomValidity('');
        }
      }

      function formatBankAccount() {
        if (!bankAccountEl) return;
        bankAccountEl.value = bankAccountEl.value.replace(/\D/g, '').slice(0, 15);
        if (bankAccountEl.value.length > 0) {
          bankAccountEl.setCustomValidity('');
        } else {
          bankAccountEl.setCustomValidity('');
        }
      }

      function formatIFSC() {
        if (!bankIfscEl) return;
        bankIfscEl.value = bankIfscEl.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 15);
        bankIfscEl.setCustomValidity('');
      }

      function formatUpiId() {
        if (!upiIdEl) return;
        upiIdEl.value = upiIdEl.value.toLowerCase().slice(0, 25);
        upiIdEl.setCustomValidity('');
      }

      function formatUpiPin() {
        if (!upiPinEl) return;
        upiPinEl.value = upiPinEl.value.replace(/\D/g, '').slice(0, 6);
        if (upiPinEl.value.length === 6) upiPinEl.setCustomValidity('');
      }

      function formatCardName() {
        if (!cardNameEl) return;
        cardNameEl.value = cardNameEl.value.toUpperCase().slice(0, 30);
        if (cardNameEl.value.length === 0) {
          cardNameEl.setCustomValidity('');
        } else {
          cardNameEl.setCustomValidity('');
        }
      }
      if (cardNumberEl) cardNumberEl.addEventListener('input', formatCardNumber);
      if (cvvEl) cvvEl.addEventListener('input', formatCVV);
      if (cardNameEl) cardNameEl.addEventListener('input', formatCardName);
      if (bankAccountEl) bankAccountEl.addEventListener('input', formatBankAccount);
      if (bankIfscEl) bankIfscEl.addEventListener('input', formatIFSC);
      if (upiIdEl) upiIdEl.addEventListener('input', formatUpiId);
      if (upiPinEl) upiPinEl.addEventListener('input', formatUpiPin);
      if (toggleUpiPinBtn && upiPinEl) toggleUpiPinBtn.addEventListener('click', function() {
        if (upiPinEl.type === 'password') {
          upiPinEl.type = 'text';
          toggleUpiPinBtn.textContent = 'Hide';
        } else {
          upiPinEl.type = 'password';
          toggleUpiPinBtn.textContent = 'Show';
        }
      });
      'use strict';
      const form = document.getElementById('paymentform');
      form.addEventListener('submit', function(event) {
        var method = document.getElementById('method').value;
        var msgs = [];
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        if (method === 'Credit/Debit Card') {
          var digits = (cardNumberEl ? cardNumberEl.value.replace(/\D/g, '') : '');
          if (digits.length !== 16) msgs.push('Card Number must be 16 digits');
          var mmVal = expiryMonthEl ? expiryMonthEl.value : '';
          var yyVal = expiryYearEl ? expiryYearEl.value : '';
          if (!mmVal || !yyVal) {
            msgs.push('Expiry month and year are required');
          } else {
            var mm = parseInt(mmVal, 10);
            var yyFull = parseInt(yyVal, 10);
            var now = new Date();
            var curFullYear = now.getFullYear();
            var curMonth = now.getMonth() + 1;
            if (yyFull === curFullYear && mm <= curMonth) {
              Swal.fire({
                title: 'Aashray',
                text: 'Card expired. Try another payment method.',
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
              });
              event.preventDefault();
              event.stopPropagation();
              return;
            }
          }
          if (cvvEl && cvvEl.value.replace(/\D/g, '').length !== 3) msgs.push('CVV must be 3 digits');
          if (cardNameEl && cardNameEl.value.trim().length === 0) msgs.push('Card holder name is required');
        }
        if (method === 'UPI ID') {
          var upiVal = upiIdEl ? upiIdEl.value.trim() : '';
          if (upiVal.length < 3 || upiVal.length > 25) msgs.push('UPI ID must be 3-25 characters');
          else if (!/@[A-Za-z]/.test(upiVal)) msgs.push('UPI ID must contain an @ followed by at least one letter');

          var upiPinDigits = upiPinEl ? upiPinEl.value.replace(/\D/g, '') : '';
          if (upiPinDigits.length !== 6) msgs.push('UPI PIN must be 6 digits');
        }
        if (method === 'Bank Account Number') {
          var bankDigits = bankAccountEl ? bankAccountEl.value.replace(/\D/g, '') : '';
          if (bankDigits.length === 0) msgs.push('Bank account number is required');
          else if (bankDigits.length > 15) msgs.push('Bank account number must be at most 15 digits');
        }
        if (method === 'Credit/Debit Card' && msgs.length === 0) {
          if (expiryMonthEl && expiryYearEl && expiryHiddenEl) {
            var mm = expiryMonthEl.value;
            var yyFull = expiryYearEl.value;
            var yyTwo = yyFull.slice(-2);
            expiryHiddenEl.value = mm + '/' + yyTwo;
          }
        }
        if (msgs.length > 0) {
          event.preventDefault();
          event.stopPropagation();
          Swal.fire({
            title: 'Payment form errors',
            text: msgs.join('\n'),
            icon: 'error',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
          }).then(function() {
            if (method === 'Credit/Debit Card') {
              if (msgs[0].toLowerCase().includes('card number')) cardNumberEl.focus();
              else if (msgs[0].toLowerCase().includes('expiry')) expiryMonthEl.focus();
              else if (msgs[0].toLowerCase().includes('cvv')) cvvEl.focus();
              else cardNameEl.focus();
            } else if (method === 'Bank Account Number') {
              bankAccountEl.focus();
            } else if (method === 'UPI ID') {
              if (msgs[0].toLowerCase().includes('upi pin')) upiPinEl.focus();
              else upiIdEl.focus();
            }
          });
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  </body>

</html>