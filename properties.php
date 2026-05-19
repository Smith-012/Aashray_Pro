<?php
include 'header.php';
include 'connection.php';
$searchArea = isset($_GET['area']) ? $conn->real_escape_string($_GET['area']) : '';
$searchCity = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : '';
$searchState = isset($_GET['state']) ? $conn->real_escape_string($_GET['state']) : '';
$searchType = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';
$whereClauses = [];
if ($searchArea !== '') {
  $whereClauses[] = "area LIKE '%$searchArea%'";
}
if ($searchCity !== '') {
  $whereClauses[] = "city LIKE '%$searchCity%'";
}
if ($searchState !== '') {
  $whereClauses[] = "state LIKE '%$searchState%'";
}
if ($searchType !== '') {
  $whereClauses[] = "property_type = '$searchType'";
}
$whereSql = '';
if (count($whereClauses) > 0) {
  $whereSql = "WHERE " . implode(' AND ', $whereClauses);
}
?>
<main class="main">
  <br><br><br><br><br>
  <form method="GET" action="" class="airbnb-search-form" role="search" aria-label="Search properties">
    <div class="airbnb-search-bar">
      <div class="search-section position-relative">
        <label for="area" class="search-label">Area</label>
        <input
          type="text"
          name="area"
          id="area"
          placeholder="Enter area"
          value="<?php echo isset($_GET['area']) ? htmlspecialchars($_GET['area']) : ''; ?>"
          autocomplete="off"
          class="search-input">
        <span class="clear-input" data-target="area">&times;</span>
      </div>
      <div class="search-section position-relative">
        <label for="city" class="search-label">City</label>
        <input
          type="text"
          name="city"
          id="city"
          placeholder="Enter city"
          value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>"
          autocomplete="off"
          class="search-input">
        <span class="clear-input" data-target="city">&times;</span>
      </div>
      <div class="search-section position-relative">
        <label for="state" class="search-label">State</label>
        <input
          type="text"
          name="state"
          id="state"
          placeholder="Enter state"
          value="<?php echo isset($_GET['state']) ? htmlspecialchars($_GET['state']) : ''; ?>"
          autocomplete="off"
          class="search-input">
        <span class="clear-input" data-target="state">&times;</span>
      </div>
      <div class="search-section">
        <label for="type" class="search-label">Property Type</label>
        <select name="type" id="type" class="search-input">
          <option value="">Select Property Type</option>
          <?php
          $types_dir = realpath(__DIR__ . '/assets/img/Z Property Images');
          if ($types_dir !== false) {
            $dirs = glob($types_dir . '/*', GLOB_ONLYDIR);
            if ($dirs !== false) {
              foreach ($dirs as $d) {
                $typeName = basename($d);
                $selected = ($searchType === $typeName) ? ' selected' : '';
                echo "<option value='" . htmlspecialchars($typeName, ENT_QUOTES) . "'{$selected}>" . htmlspecialchars($typeName) . "</option>\n";
              }
            }
          }
          ?>
        </select>
      </div>
      <div class="search-button-container">
        <button type="submit" class="search-button" aria-label="Search">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </div>
  </form>
  <section id="real-estate" class="real-estate section">
    <div class="container">
      <div class="row gy-4">
        <?php
        $limit = 9;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $totalSql = "SELECT COUNT(*) AS total FROM properties $whereSql";
        $totalResult = $conn->query($totalSql);
        $totalRow = $totalResult->fetch_assoc();
        $totalProperties = $totalRow['total'];
        $totalPages = ceil($totalProperties / $limit);
        $sql = "SELECT * FROM properties $whereSql ORDER BY property_listing_dt DESC LIMIT $limit OFFSET $offset";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $photos = json_decode($row['property_photos'], true);
            $imagePath = !empty($photos) ? $photos[0] : "assets/img/properties/default.jpg";
            $propertyId = htmlspecialchars($row['property_sr_no']);
            $propertyNo = htmlspecialchars($row['property_no']);
            $address = htmlspecialchars($row['property_address']);
            $price = htmlspecialchars($row['rent_amount']);
            $status = htmlspecialchars($row['booking']);
            $area = htmlspecialchars($row['area']);
            $city = htmlspecialchars($row['city']);
            $pincode = htmlspecialchars($row['pincode']);
            $state = htmlspecialchars($row['state']);
            $badgeClass = ($status === 'Available') ? 'badge-available' : 'badge-booked';
        ?>
            <div class="col-xl-4 col-md-6" data-aos="fade-up">
              <div class="card position-relative">
                <div class="status-badge <?php echo $badgeClass; ?>">
                  <?php echo $status; ?>
                </div>
                <img src="<?php echo $imagePath; ?>" alt="Property Image" class="img-fluid">
                <div class="card-body">
                  <span class="sale-rent"><?php echo $price; ?></span>
                  <h3>
                    <a href="property-single.php?id=<?php echo $propertyId; ?>" class="stretched-link">
                      <?php echo $propertyNo . " , " . $address; ?>
                    </a>
                  </h3>
                  <div class="card-content d-flex flex-column justify-content-center text-center">
                    <div class="row propery-info">
                      <div class="col">Area</div>
                      <div class="col">City</div>
                      <div class="col">Pincode</div>
                      <div class="col">State</div>
                    </div>
                    <div class="row">
                      <div class="col"><?php echo $area; ?></div>
                      <div class="col"><?php echo $city; ?></div>
                      <div class="col"><?php echo $pincode; ?></div>
                      <div class="col"><?php echo $state; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }
        } else {
          echo "
    <div class='d-flex justify-content-center align-items-center min-vh-50'>
      <p class='text-center m-0'>No properties available at the moment.</p>
    </div>
  ";
        }
        ?>
      </div>
      <?php if ($totalPages > 1): ?>
        <div class="center mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
              <a href="<?php
                        echo ($page <= 1) ? '#' : '?page=' . ($page - 1)
                          . ($searchArea ? '&area=' . urlencode($searchArea) : '')
                          . ($searchCity ? '&city=' . urlencode($searchCity) : '')
                          . ($searchState ? '&state=' . urlencode($searchState) : '')
                          . ($searchType ? '&type=' . urlencode($searchType) : '');
                        ?>" class="page-link">Prev</a>
            </li>
            <li class="page-item active">
              <a href="#" class="page-link"><?php echo $page; ?></a>
            </li>
            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
              <a href="<?php
                        echo ($page >= $totalPages) ? '#' : '?page=' . ($page + 1)
                          . ($searchArea ? '&area=' . urlencode($searchArea) : '')
                          . ($searchCity ? '&city=' . urlencode($searchCity) : '')
                          . ($searchState ? '&state=' . urlencode($searchState) : '')
                          . ($searchType ? '&type=' . urlencode($searchType) : '');
                        ?>" class="page-link">Next</a>
            </li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.real-estate .row');
    const links = document.querySelectorAll('.pagination a');
    links.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        container.style.opacity = 0;
        setTimeout(() => {
          window.location.href = url;
        }, 300);
      });
    });
    container.style.opacity = 0;
    setTimeout(() => {
      container.style.transition = 'opacity 0.5s ease-in-out';
      container.style.opacity = 1;
    }, 100);
  });
  document.addEventListener('DOMContentLoaded', function() {
    const clearIcons = document.querySelectorAll('.clear-input');
    clearIcons.forEach(icon => {
      icon.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        input.value = '';
        input.focus();
        this.style.display = 'none';
      });
    });
    const inputs = document.querySelectorAll('.search-input');
    inputs.forEach(input => {
      input.addEventListener('input', function() {
        const icon = this.parentElement.querySelector('.clear-input');
        if (this.value.length > 0) {
          icon.style.display = 'block';
        } else {
          icon.style.display = 'none';
        }
      });
      input.dispatchEvent(new Event('input'));
    });
  });
</script>
<?php include 'footer.php'; ?>