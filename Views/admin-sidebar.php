<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Styles/sideNav.css">
    <link rel="stylesheet" href="Styles/admin-sidebarStyles.css">
  </head>
<body>

<div class="sidenav">
  <a href="index?route=dashboard">DASHBOARD</a>
  <button class="sub-menu">ACCOUNTS 
    <i class="iconDown"></i>
  </button>
  <div class="submenu-container">
    <a href="index?route=customerAccounts">CUSTOMER</a>
    <a href="index?route=handymanAccounts">HANDYMAN</a>
    <!-- <a href="index?route=request">REQUEST PROFILE PICTURE</a> -->
    <a href="index?route=accept_requirements">REQUIREMENTS</a>
  </div>
  <a href="index?route=sham">
    <?php 
      $sqlCountCancelled = "SELECT count(userID) AS count FROM users WHERE count_cancelled > 2";
      $stmt = $con->prepare($sqlCountCancelled);
      $stmt->execute();
      $resultsCount = $stmt->fetch();
      $count_cancelled = $resultsCount['count'];

      if(($count_cancelled > 0)&&($count_cancelled < 101)){
        ?>
        <span class="badge">
          <?php echo $count_cancelled;?>
        </span>
      <?php
      }
      elseif($count_cancelled > 100){
        ?>
        <span class="badge">
          <?php echo "100+";?>
        </span>
      <?php
      }
      ?>SHAM LIST
  </a>
  <a href="index?route=booking">BOOKING</a>
  <a href="index?route=transactions">TRANSACTION</a>
  <button class="sub-menu">MAINTENANCE 
    <i class="iconDown"></i>
  </button>
  <div class="submenu-container">
    <a href="index?route=notifications">NOTIFICATIONS</a>
    <a href="index?route=questions">QUESTIONS</a>
    <a href="index?route=requirementTypes">REQUIREMENTS</a>
    <a href="index?route=service">SERVICES</a>
    <a href="index?route=violations">VIOLATIONS</a>
  </div>
  <a href="index?route=complaints">
  <?php if(($readReports > 0)&&($readReports < 101)){
        ?>
        <span class="badge">
          <?php echo $readReports;?>
        </span>
      <?php
      }
      elseif($readReports > 100){
        ?>
        <span class="badge">
          <?php echo "100+";?>
        </span>
      <?php
      }
      ?>
    COMPLAINTS
  </a>
  <button class="sub-menu">TOP UP 
    <i class="iconDown"></i>
  </button>
  <div class="submenu-container">
    <a href="index?route=pending">PENDING</a>
    <a href="index?route=history">HISTORY</a>
  </div>
  <a href="index?route=penalty">PENALTIES</a>
  <button class="sub-menu">REPORTS 
    <i class="iconDown"></i>
  </button>
  <div class="submenu-container">
    <a href="index?route=generateReports">GRAPHICAL REPORTS</a>
    <a href="index?route=writtenReports">WRITTEN REPORTS</a>
    <?php
      $sqlService = "SELECT serviceID, name FROM services LIMIT 1";
      $stmt = $con->prepare($sqlService);
      $stmt->execute();
      $results = $stmt->fetch();
      $ctrService = 0;
      $reportServiceID = $results['serviceID'];
    ?>
    <a href="index?route=serviceReports&service=<?php echo $reportServiceID; ?>">SERVICE REPORTS</a>
  </div>
</div>
<script>
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("sub-menu");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>

</body>
</html> 