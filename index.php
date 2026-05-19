<?php include 'header.php';
include 'connection.php';
?>
<main class="main">
  <section id="hero" class="hero section dark-background">
    <div id="hero-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <?php
      $sql = "SELECT * FROM properties ORDER BY property_listing_dt DESC LIMIT 3";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        $isActive = true;
        while ($row = $result->fetch_assoc()) {
          $photos = json_decode($row['property_photos'], true);
          $imagePath = !empty($photos) ? $photos[0] : "assets/img/hero-carousel/default.jpg";
          $rentAmount = htmlspecialchars($row['rent_amount']);
      ?>
          <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
            <img src="<?php echo $imagePath; ?>" alt="Property Image">
            <div class="carousel-container">
              <div>
                <p><?php echo htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['state']) . "   (" . htmlspecialchars($row['property_type']) . ")"; ?></p>
                <h2><span><?php echo htmlspecialchars($row['property_no']); ?></span> <?php echo htmlspecialchars($row['property_address']); ?></h2>
                <a href="property-single.php?id=<?php echo $row['property_sr_no']; ?>" class="btn-get-started">
                  <?php echo $rentAmount; ?>
                </a>
              </div>
            </div>
          </div>
      <?php
          $isActive = false;
        }
      } else {
        echo '<p class="text-center text-light">No properties available right now.</p>';
      }
      ?>
      <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
      </a>
      <ol class="carousel-indicators"></ol>
    </div>
  </section>
  <?php include __DIR__ . '/partials/about_section.php'; ?>
  <?php include __DIR__ . '/partials/services_section.php'; ?>
  <section id="testimonials" class="testimonials section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Testimonials</h2>
      <p>Thousands of happy customers have found their perfect stay through our platform. Here's what they have to say.</p>
    </div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {
            "loop": true,
            "speed": 500,
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": "auto",
            "pagination": {
              "el": ".swiper-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": ".swiper-button-next",
              "prevEl": ".swiper-button-prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 1,
                "spaceBetween": 40
              },
              "1200": {
                "slidesPerView": 3,
                "spaceBetween": 1
              }
            }
          }
        </script>
        <div class="swiper-wrapper">
          <?php
          $sql = "SELECT first_name, last_name, occupation, feedback_text, rating 
        FROM feedback 
        ORDER BY feedback_id DESC";
          $result = mysqli_query($conn, $sql);
          if ($result && mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
              $fullname = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
              $occupation = htmlspecialchars($row['occupation']);
              $feedback = htmlspecialchars($row['feedback_text']);
              $rating = (int)$row['rating'];
          ?>
              <div class="swiper-slide">
                <div class="testimonial-item">
                  <div class="stars">
                    <?php
                    for ($i = 0; $i < $rating; $i++) {
                      echo '<i class="bi bi-star-fill"></i>';
                    }
                    for ($i = $rating; $i < 5; $i++) {
                      echo '<i class="bi bi-star"></i>';
                    }
                    ?>
                  </div>
                  <p>"<?= $feedback ?>"</p>
                  <div class="profile mt-auto">
                    <div class="profile-avatar" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; border-radius: 50%; font-size: 48px; color: #6c757d; margin: 0 auto 15px auto;">
                      <i class="bi bi-person-circle"></i>
                    </div>
                    <h3><?= $fullname ?></h3>
                    <h4><?= $occupation ?></h4>
                  </div>
                </div>
              </div>
          <?php
            endwhile;
          else:
            echo "<div class='swiper-slide'><p class='text-center'>No feedback available yet.</p></div>";
          endif;
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php include __DIR__ . '/partials/contact_section.php'; ?>
</main>
<?php include 'footer.php'; ?>