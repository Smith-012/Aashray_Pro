<?php
if (!isset($conn)) {
    if (file_exists(__DIR__ . '/../connection.php')) {
        include __DIR__ . '/../connection.php';
    }
}
?>
<section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Contact Us</h2>
        <p>Got questions, need any help, or just want to say hi? Drop us a note !</p>
    </div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3704.986973723446!2d72.1419784!3d21.7585731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395f5a765997e241%3A0xd573520df6474e8e!2sWaghawadi%20Rd.%2C%20Bhavnagar%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700300000000&maptype=satellite"
                width="100%"
                height="400"
                style="border:0; border-radius:8px;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                        <h3>Address</h3>
                        <p>Waghawadi Road , Bhavnagar - 364001</p>
                    </div>
                </div>
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-telephone flex-shrink-0"></i>
                    <div>
                        <h3>Call Us</h3>
                        <p>(123) 456 7890</p>
                    </div>
                </div>
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                        <h3>Email Us</h3>
                        <p>helloaashray25@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <form action="" method="POST" class="php-email-form aashraycontactform" role="form" id="aashraycontactform" autocomplete="off" data-aos="fade-up" data-aos-delay="200" novalidate>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="cfirstname" class="form-control" placeholder="First Name" id="cfirstname" onkeypress="validatename(event)" oninput="formatInputforfirst()" required autocomplete="off">
                            <small id="cfirstname-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="clastname" class="form-control" placeholder="Last Name" id="clastname" onkeypress="validatename(event)" oninput="formatInputforlast()" required autocomplete="off">
                            <small id="clastname-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="ccontactnumber" class="form-control" placeholder="Contact Number" id="ccontactnumber" oninput="restrictInputforcno(event)" onkeypress="restrictToConNumbers(event)" required autocomplete="off">
                            <small id="ccontactnumber-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="cemailid" id="cemailid" class="form-control" placeholder="Email" required autocomplete="off">
                            <small id="cemailid-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="csubject" class="form-control" placeholder="Subject" id="csubject" onkeypress="validateSubject(event)" required autocomplete="off">
                            <small id="csubject-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-12">
                            <textarea name="message" id="message" class="form-control" rows="6" placeholder="Give Your Message" required autocomplete="off"></textarea>
                            <small id="message-error" style="color:red; display:none;"></small>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary" name="Send" value="Send" id="Send">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('aashraycontactform');
        const fields = {
            cfirstname: {
                input: document.getElementById('cfirstname'),
                error: document.getElementById('cfirstname-error'),
                validate: val => /^[a-zA-Z]{2,10}$/.test(val.trim()),
                message: 'First Name must be 2-10 letters only.'
            },
            clastname: {
                input: document.getElementById('clastname'),
                error: document.getElementById('clastname-error'),
                validate: val => /^[a-zA-Z]{2,10}$/.test(val.trim()),
                message: 'Last Name must be 2-10 letters only.'
            },
            ccontactnumber: {
                input: document.getElementById('ccontactnumber'),
                error: document.getElementById('ccontactnumber-error'),
                validate: val => /^\d{10}$/.test(val),
                message: 'Contact Number must contain 10 digits.'
            },
            cemailid: {
                input: document.getElementById('cemailid'),
                error: document.getElementById('cemailid-error'),
                validate: val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
                message: 'Enter a valid formated email address.'
            },
            csubject: {
                input: document.getElementById('csubject'),
                error: document.getElementById('csubject-error'),
                validate: val => val.trim().length >= 5,
                message: 'Subject must be at least 5 characters.'
            },
            message: {
                input: document.getElementById('message'),
                error: document.getElementById('message-error'),
                validate: val => val.trim().length >= 10,
                message: 'Message must be at least 10 characters long.'
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
        var inputField = document.getElementById("cfirstname");
        var inputValue = inputField.value;
        var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
        inputField.value = formattedValue;
    }

    function formatInputforlast() {
        var inputField = document.getElementById("clastname");
        var inputValue = inputField.value;
        var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
        inputField.value = formattedValue;
    }

    function restrictToConNumbers(event) {
        var key = event.key;
        if (isNaN(key)) {
            event.preventDefault();
        }
    }

    function restrictInputforcno(event) {
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
        if (inputlength.value.length >= 50) {
            inputlength.value = inputlength.value.slice(0, 50);
            event.preventDefault();
        }
    }

    function clearform() {
        document.getElementById("aashraycontactform").reset();
    }
</script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Send'])) {
    if (!isset($conn)) {
    } else {
        $firstname = trim($_POST['cfirstname'] ?? '');
        $lastname = trim($_POST['clastname'] ?? '');
        $contactno = trim($_POST['ccontactnumber'] ?? '');
        $emailid = filter_var(trim($_POST['cemailid'] ?? ''), FILTER_SANITIZE_EMAIL);
        $subject = trim($_POST['csubject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $message = str_replace(["\r\n", "\r"], "\n", $message);
        $stmt = $conn->prepare("INSERT INTO `contact_us`(`first_name`, `last_name`, `contact_no`, `email_id`, `subject`, `message`, `contact_request_date`) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param('ssssss', $firstname, $lastname, $contactno, $emailid, $subject, $message);
            if ($stmt->execute()) {
                echo <<<EOD
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        <script>
            Swal.fire({
              title: 'Aashray',
              text: 'Your Contact Request Submitted successfully!!',
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
              text: 'Failed to submit your request. Please try again later !!',
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
    }
}
?>