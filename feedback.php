<?php
include 'connection.php';
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
?>
<main class="main">
  <br><br><br><br><br>
  <section id="contact" class="contact section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row justify-content-center gy-4">
        <div class="col-lg-8">
          <div class="section-title text-center">
            <h2>Feedback</h2>
            <p>We value your opinion! Please share your feedback with us.</p>
          </div>
          <form action="" method="POST" class="php-email-form aashraycontactform" role="form" id="aashrayfeedbackform" autocomplete="off" data-aos="fade-up" data-aos-delay="200" novalidate>
            <div class="row gy-4">
              <div class="col-md-6">
                <input type="text" name="feedfirstname" class="form-control" placeholder="First Name" id="feedfirstname" onkeypress="validatename(event)" oninput="formatInputforfirst()" required>
                <small id="feedfirstname-error" class="form-error-text"></small>
              </div>
              <div class="col-md-6">
                <input type="text" name="feedlastname" class="form-control" placeholder="Last Name" id="feedlastname" onkeypress="validatename(event)" oninput="formatInputforlast()" required>
                <small id="feedlastname-error" class="form-error-text"></small>
              </div>
              <div class="col-md-6">
                <input type="text" name="feedcontactnumber" class="form-control" placeholder="Contact Number"
                  id="feedcontactnumber" oninput="restrictforfeedcno(event)" onkeypress="restrictTofeedConNumbers(event)" required>
                <small id="feedcontactnumber-error" class="form-error-text"></small>
              </div>
              <div class="col-md-6">
                <input type="email" name="feedemailid" id="feedemailid" class="form-control" placeholder="Email" required>
                <small id="feedemailid-error" class="form-error-text"></small>
              </div>
              <div class="col-md-12">
                <input type="text" name="feedoccupation" class="form-control" placeholder="Occupation (Ex. :- Student , Traveler etc..)" id="feedoccupation" required>
                <small id="feedoccupation-error" class="form-error-text"></small>
              </div>
              <div class="col-md-12">
                <input type="text" name="feedsubject" class="form-control" placeholder="Subject" id="feedsubject" onkeypress="validateSubject(event)" required>
                <small id="feedsubject-error" class="form-error-text"></small>
              </div>
              <div class="col-md-12">
                <textarea rows="6" class="form-control" placeholder="Share Your Feedback" name="details" id="details" required></textarea>
                <small id="details-error" class="form-error-text"></small>
              </div>
              <div class="col-md-12 text-center">
                <h4 class="mb-2">Give Ratings</h4>
                <div class="rating">
                  <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
                  <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                  <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                  <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                  <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                </div>
              </div>
              <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary" name="Sendfeed" id="Sendfeed" value="Sendfeed">Send Feedback</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aashrayfeedbackform');
    const fields = {
      cfirstname: {
        input: document.getElementById('feedfirstname'),
        error: document.getElementById('feedfirstname-error'),
        validate: val => /^[a-zA-Z]{2,10}$/.test(val.trim()),
        message: 'First Name must be 2-10 letters only.'
      },
      clastname: {
        input: document.getElementById('feedlastname'),
        error: document.getElementById('feedlastname-error'),
        validate: val => /^[a-zA-Z]{2,10}$/.test(val.trim()),
        message: 'Last Name must be 2-10 letters only.'
      },
      ccontactnumber: {
        input: document.getElementById('feedcontactnumber'),
        error: document.getElementById('feedcontactnumber-error'),
        validate: val => /^\d{10}$/.test(val),
        message: 'Contact Number must contain 10 digits.'
      },
      cemailid: {
        input: document.getElementById('feedemailid'),
        error: document.getElementById('feedemailid-error'),
        validate: val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
        message: 'Enter a valid formated email address.'
      },
      coccupation: {
        input: document.getElementById('feedoccupation'),
        error: document.getElementById('feedoccupation-error'),
        validate: val => /^[a-zA-Z ]{2,30}$/.test(val.trim()),
        message: 'Occupation must be 2–30 letters only.'
      },
      csubject: {
        input: document.getElementById('feedsubject'),
        error: document.getElementById('feedsubject-error'),
        validate: val => val.trim().length >= 5,
        message: 'Subject must be at least 5 characters.'
      },
      message: {
        input: document.getElementById('details'),
        error: document.getElementById('details-error'),
        validate: val => val.trim().length >= 10,
        message: 'Feedback must be at least 10 characters long.'
      }
    };
    Object.values(fields).forEach(field => {
      field.input.addEventListener('input', () => {
        validateField(field);
      });
    });

    function validateField(field) {
      const value = field.input.value.trim();
      if (!field.validate(value)) {
        field.error.textContent = field.message;
        field.error.style.display = 'block';
        return false;
      } else {
        field.error.textContent = '';
        field.error.style.display = 'none';
        return true;
      }
    }
    form.addEventListener('submit', function(e) {
      let valid = true;
      Object.values(fields).forEach(field => {
        const isValid = validateField(field);
        if (!isValid) valid = false;
      });
      if (!valid) {
        e.preventDefault();
      }
    });
  });

  function validatename(event) {
    var input = event.key;
    if (!/[a-zA-Z]/.test(input)) {
      event.preventDefault();
    }
    var inputlength = event.target;
    if (inputlength.value.length >= 10) {
      inputlength.value = inputlength.value.slice(0, 10);
      event.preventDefault();
    }
  }

  function formatInputforfirst() {
    var inputField = document.getElementById("feedfirstname");
    var inputValue = inputField.value;
    var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
    inputField.value = formattedValue;
  }

  function formatInputforlast() {
    var inputField = document.getElementById("feedlastname");
    var inputValue = inputField.value;
    var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
    inputField.value = formattedValue;
  }

  function restrictTofeedConNumbers(event) {
    var key = event.key;
    if (isNaN(key)) {
      event.preventDefault();
    }
  }

  function restrictforfeedcno(event) {
    if (event.target.value.length >= 10) {
      event.target.value = event.target.value.slice(0, 10);
      event.preventDefault();
    }
  }

  function validateSubject(event) {
    var input = event.key;
    if (!/[a-z A-Z]/.test(input)) {
      event.preventDefault();
    }
    var inputlength = event.target;
    if (inputlength.value.length >= 25) {
      inputlength.value = inputlength.value.slice(0, 25);
      event.preventDefault();
    }
  }

  function clearform() {
    document.getElementById("aashrayfeedbackform").reset();
  }
</script>
<?php
if (isset($_POST['Sendfeed'])) {
  $firstname = mysqli_real_escape_string($conn, $_POST['feedfirstname']);
  $lastname = mysqli_real_escape_string($conn, $_POST['feedlastname']);
  $contactno = mysqli_real_escape_string($conn, $_POST['feedcontactnumber']);
  $emailid = mysqli_real_escape_string($conn, $_POST['feedemailid']);
  $subject = mysqli_real_escape_string($conn, $_POST['feedsubject']);
  $details = mysqli_real_escape_string($conn, $_POST['details']);
  $occupation = mysqli_real_escape_string($conn, $_POST['feedoccupation']);
  $rating = $_POST['rating'];
  $sql = "INSERT INTO `feedback`(`first_name`, `last_name`, `contact_no`, `email_id`, `occupation`, `feedback_subject`, `feedback_text`, `rating`, `feedback_share_date`)
 VALUES ('$firstname','$lastname','$contactno','$emailid','$occupation','$subject','$details','$rating',NOW())";
  if (mysqli_query($conn, $sql)) {
    echo <<<EOD
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
      <script>
        Swal.fire({
          title: 'Aashray',
          text: 'Your Feedback Submitted !!',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          window.history.back();
        });
      </script>
    EOD;
    exit;
  } else {
    echo <<<EOD
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
      <script>
        Swal.fire({
          title: 'Error',
          text: 'Failed to submit feedback. Please try again later !!',
          icon: 'error',
          confirmButtonText: 'OK'
        }).then(() => {
          window.history.back();
        });
      </script>
    EOD;
    exit;
  }
}
?>