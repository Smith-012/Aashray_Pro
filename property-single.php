<?php
include 'header.php';
include 'connection.php';
if (isset($_GET['id'])) {
  $property_id = intval($_GET['id']);
  $sql = "SELECT * FROM properties WHERE property_sr_no = $property_id";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    $property = $result->fetch_assoc();
  } else {
    echo "<h2 class='text-center'>Property not found!</h2>";
    include 'footer.php';
    exit;
  }
} else {
  echo "<h2 class='text-center'>No property selected!</h2>";
  include 'footer.php';
  exit;
}
?>
<main class="main">
  <br><br><br><br><br>
  <section id="real-estate-2" class="real-estate-2 section">
    <div class="container" data-aos="fade-up">
      <div class="portfolio-details-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {
            "loop": true,
            "speed": 600,
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": "auto",
            "navigation": {
              "nextEl": ".swiper-button-next",
              "prevEl": ".swiper-button-prev"
            },
            "pagination": {
              "el": ".swiper-pagination",
              "type": "bullets",
              "clickable": true
            }
          }
        </script>
        <div class="swiper-wrapper align-items-center">
          <?php
          if (!empty($property['property_photos'])) {
            $images = json_decode($property['property_photos'], true);
            foreach ($images as $img) {
              echo '<div class="swiper-slide"><img src="' . htmlspecialchars($img) . '" alt=""></div>';
            }
          } else {
            echo '<div class="swiper-slide"><img src="assets/img/property-slide/property-slide-1.jpg" alt=""></div>';
          }
          ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="row justify-content-between gy-4 mt-4">
        <div class="col-lg-5" data-aos="fade-up">
          <div class="portfolio-description">
            <h2><?php echo htmlspecialchars($property['property_type']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
            <div class="testimonial-item mt-4 p-3" style="background:#f9f9f9; border-radius:8px;">
              <div class="d-flex align-items-center">
                <div class="profile-avatar"
                  style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; border-radius: 50%; font-size: 50px; color: #6c757d; flex-shrink: 0;">
                  <i class="bi bi-person-circle"></i>
                </div>
                <div class="ms-4">
                  <h3 style="margin:0; font-size:20px; font-weight:700; color:#212529;">
                    Owner Details :-
                  </h3>
                  <p style="margin:3px 0; font-size:16px; color:#333;">
                    <strong>Name :</strong>
                    <?php echo !empty($property['owner_name'])
                      ? htmlspecialchars($property['owner_name'])
                      : "Not Available"; ?>
                  </p>
                  <p style="margin:3px 0; font-size:16px; color:#333;">
                    <strong>Contact :</strong>
                    <?php echo !empty($property['owner_contact_no'])
                      ? htmlspecialchars($property['owner_contact_no'])
                      : "Not Available"; ?>
                  </p>
                  <p style="margin:3px 0; font-size:16px; color:#333;">
                    <strong>E-mail :</strong>
                    <?php echo !empty($property['owner_email_id'])
                      ? htmlspecialchars($property['owner_email_id'])
                      : "Not Available"; ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
          <div class="portfolio-info">
            <h3>Quick Summary</h3>
            <div class="row">
              <div class="col-md-6">
                <ul>
                  <li><strong>Property Number :</strong> <?php echo $property['property_no']; ?></li>
                  <li><strong>Property Type :</strong> <?php echo htmlspecialchars($property['property_type']); ?></li>
                  <li><strong>Address :</strong> <?php echo htmlspecialchars($property['property_address']); ?></li>
                  <li><strong>Area :</strong> <?php echo htmlspecialchars($property['area']); ?></li>
                  <li><strong>City :</strong> <?php echo htmlspecialchars($property['city']); ?></li>
                  <li><strong>Pincode :</strong> <?php echo htmlspecialchars($property['pincode']); ?></li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul>
                  <li><strong>State :</strong> <?php echo htmlspecialchars($property['state']); ?></li>
                  <li><strong>Status :</strong> <?php echo htmlspecialchars($property['booking']); ?> for booking</li>
                  <li><strong>Area :</strong> <?php echo htmlspecialchars($property['area']); ?></li>
                  <li><strong>Rent Amount :</strong> <?php echo htmlspecialchars($property['rent_amount']); ?></li>
                  <li>
                    <strong><i class="fa fa-exclamation-triangle" style="color:orange; margin-right:5px;"></i>
                      No Cancelation Available</strong>
                  </li>
                  <?php if (isset($property['booking']) && strtolower($property['booking']) === 'available') : ?>
                    <form action="apply.php" method="post" class="mt-3">
                      <input type="hidden" name="property_number" value="<?php echo $property['property_no']; ?>">
                      <input type="hidden" name="rent_amount" value="<?php echo $property['rent_amount']; ?>">
                      <button type="submit" name="submit" id="submit" class="btn btn-primary">
                        <i class="bi bi-calendar-check-fill me-2"></i>Book Now
                      </button>
                    </form>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>