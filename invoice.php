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
        }).then(() => {
            window.history.back();
        });
    </script>
</body>
</html>
EOD;
  exit;
}
if (!isset($_SESSION['transaction_id'])) {
  echo "Transaction ID not found!";
  exit;
}
$transaction = $_SESSION['transaction_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aashray</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="invoice-page">
  <?php
  include 'connection.php';
  $sql = "SELECT * FROM `invoice` WHERE `transaction_id` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $transaction);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    echo <<<EOD
  <script>
    Swal.fire({
      title: 'Error',
      text: 'No invoice found for this transaction.',
      icon: 'error',
      confirmButtonText: 'OK'
    }).then(() => {
      window.location.href = "index.php";
    });
  </script>
  EOD;
    exit;
  }
  $row = $result->fetch_assoc();
  $propertyNo = $row['property_no'];
  $amount = $row['rent_amount'];
  $rentDays = $row['rent_period'];
  $sql2 = "SELECT * FROM `properties` WHERE `property_no` = ?";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->bind_param("s", $propertyNo);
  $stmt2->execute();
  $result2 = $stmt2->get_result();
  $property = $result2->num_rows > 0 ? $result2->fetch_assoc() : [];
  $conn->close();
  function divide_amount($amount, $divider)
  {
    return ($divider != 0) ? $amount / $divider : "N/A";
  }
  function calculate_end_date($startDate, $days)
  {
    $date = DateTime::createFromFormat('d-m-Y h:i:s A', $startDate);
    if (!$date) return "N/A";
    $date->modify("+$days days");
    return $date->format('d-m-Y h:i:s A');
  }
  ?>
  <div id="invoice-content" class="invoice">
    <div class="invoice-header-wrapper">
      <div class="invoice-header-brand">
        <img src="assets/img/favicon.png" alt="Logo" class="invoice-logo">
      </div>
      <h1 class="invoice-title">Invoice</h1>
      <div class="invoice-header-brand invoice-header-brand-end">
        <img src="assets/img/favicon.png" alt="Logo" class="invoice-logo">
      </div>
    </div>
    <div class="invoice-section">
      <h4>Payment Details</h4>
      <p><strong>Invoice Number :</strong> <?= $row['invoice_id'] ?></p>
      <p><strong>Transaction ID :</strong> <?= $transaction ?></p>
      <p><strong>Transaction Type :</strong> <?= $row['payment_mode'] ?></p>
      <p><strong>Transaction Date :</strong> <?= date('d-m-Y h:i:s A', strtotime($row['payment_date'])) ?></p>
      <p><strong>Transaction Amount :</strong> Rs. <?= $amount ?></p>
    </div>
    <div class="invoice-section">
      <h4>Tenant Details</h4>
      <p><strong>First Name :</strong> <?= $row['first_name'] ?></p>
      <p><strong>Last Name :</strong> <?= $row['last_name'] ?></p>
      <p><strong>Contact No. :</strong> <?= $row['contact_no'] ?></p>
      <p><strong>E-mail Id :</strong> <?= $row['email_id'] ?></p>
      <p><strong>Aadhar Card Number :</strong> <?= trim(chunk_split($row['verification_doc_no'], 4, ' ')) ?></p>
    </div>
    <div class="invoice-section">
      <h4>Property Rent Details</h4>
      <p><strong>Property Number :</strong> <?= $row['property_no'] ?></p>
      <p><strong>Rent Type :</strong> <?= $row['rent_dmn'] ?></p>
      <p><strong>Rent Period :</strong> <?= $rentDays ?></p>
      <p><strong>Rent Amount :</strong> Rs. <?= divide_amount($amount, $rentDays) ?> / <?= $row['rent_dmn'] ?></p>
      <p><strong>Check in Date :</strong> <?= $rentStart = date('d-m-Y h:i:s A', strtotime($row['rent_date'])) ?></p>
      <p><strong>Check out Date :</strong>
        <?php
        if ($row['rent_dmn'] == 'Month') {
          $totalDays = $rentDays * 30;
          $rentenddate = calculate_end_date($rentStart, $totalDays);
        } else {
          $rentenddate = calculate_end_date($rentStart, $rentDays);
        }
        echo $rentenddate;
        include 'connection.php';
        $invoiceId = $row['invoice_id'];
        $rentEndDateDB = date('Y-m-d H:i:s', strtotime($rentenddate));
        $updateSql = "UPDATE `invoice` SET `rent_end_date` = ? WHERE `invoice_id` = ?";
        $stmt_update = $conn->prepare($updateSql);
        $stmt_update->bind_param("si", $rentEndDateDB, $invoiceId);
        $stmt_update->execute();
        $conn->close();
        ?>
      </p>
      <p><strong>Booking Date :</strong> <?= date('d-m-Y h:i:s A', strtotime($row['booking_date'])) ?></p>
      <div class="total">Total Amount : Rs. <?= $amount ?></div>
    </div>
    <div class="invoice-section">
      <h4>Property Details</h4>
      <p><strong>Property Number :</strong> <?= $row['property_no'] ?? 'N/A' ?></p>
      <p><strong>Property Type :</strong> <?= $property['property_type'] ?? 'N/A' ?></p>
      <p><strong>Address :</strong> <?= $property['property_address'] ?? 'N/A' ?></p>
      <p><strong>Area :</strong> <?= $property['area'] ?? 'N/A' ?></p>
      <p><strong>City :</strong> <?= $property['city'] ?? 'N/A' ?></p>
      <p><strong>Pincode :</strong> <?= $property['pincode'] ?? 'N/A' ?></p>
      <p><strong>State :</strong> <?= $property['state'] ?? 'N/A' ?></p>
      <p><strong>Rent Amount :</strong> <?= $property['rent_amount'] ?? 'N/A' ?></p>
      <p><strong>Owner Name :</strong> <?= $property['owner_name'] ?? 'N/A' ?></p>
      <p><strong>Owner Contact No. :</strong> <?= $property['owner_contact_no'] ?? 'N/A' ?></p>
      <p><strong>Owner E-mail Id :</strong> <?= $property['owner_email_id'] ?? 'N/A' ?></p>
      <p><strong>Listing Date :</strong> <?= date('d-m-Y h:i:s A', strtotime($property['property_listing_dt'])) ?? 'N/A' ?></p>
    </div>
    <h5 class="invoice-payment-date"><strong>Payment Date :</strong> <?= date('F d, Y h:i:s A', strtotime($row['payment_date'])) ?></h5>
    <div class="actions">
      <button class="btn-home" id="redirectButton">Go to Home Page</button>
      <button class="btn-pdf" id="pdfButton" onclick="downloadPage()">Download Invoice</button>
    </div>
  </div>
  <script>
    document.getElementById("redirectButton").addEventListener("click", function() {
      Swal.fire({
        text: 'Thank you for booking !! Visit Again .',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        timer: 5000
      }).then(() => {
        window.location.href = "index.php";
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script>
    function downloadInvoiceImageLandscape() {
      const pdfButton = document.getElementById('pdfButton');
      const backButton = document.getElementById('redirectButton');
      pdfButton.style.display = 'none';
      backButton.style.display = 'none';
      const element = document.getElementById('invoice-content');
      const originalWidth = element.offsetWidth;
      element.style.width = '1200px';
      element.style.maxWidth = 'none';
      html2canvas(element, {
        scale: 3,
        useCORS: true,
        allowTaint: false,
        logging: false,
        backgroundColor: '#fff',
        windowWidth: document.body.scrollWidth,
        windowHeight: document.body.scrollHeight
      }).then(canvas => {
        element.style.width = originalWidth + 'px';
        element.style.maxWidth = '';
        canvas.toBlob(function(blob) {
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'aashray_invoice.png';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(url);
          pdfButton.style.display = 'inline-block';
          backButton.style.display = 'inline-block';
          Swal.fire({
            title: 'Downloaded',
            text: 'Your invoice has been downloaded.',
            icon: 'success',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
          }).then(() => {
            window.location.href = "index.php";
          });
        }, 'image/png');
      });
    }
    document.getElementById('pdfButton').addEventListener('click', downloadInvoiceImageLandscape);
  </script>
</body>

</html>