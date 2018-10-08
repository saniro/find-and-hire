<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 10px 16px;
    transition: 0.3s;
    font-size: 16px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

.active {
    display: block;
}
</style>
</head>
<body>

<p>Click on the buttons inside the tabbed menu:</p>

<div class="tab">
  <button class="tablinks" onclick="tabAddAdmin(event, 'adminInfo')">London</button>
  <button class="tablinks" onclick="tabAddAdmin(event, 'adminAddress')">Paris</button>
  <button class="tablinks" onclick="tabAddAdmin(event, 'adminUserPassword')">Tokyo</button>
</div>

<div id="adminInfo" class="tabcontent active">
  <div class = "inputs">
    <div class = name>
      <b>Name: </b><br>
      <input type="text" name="lastname" placeholder="Enter your last name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-." value = "<?php if(isset($_SESSION['addAdminLastname'])){echo $_SESSION['addAdminLastname'];}?>">
      <input type="text" name="firstname" placeholder="Enter your first name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-." value = "<?php if(isset($_SESSION['addAdminFirstname'])){echo $_SESSION['addAdminFirstname'];}?>"><br>
      <input type="text" name="middlename" placeholder="Enter your middle name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-." value = "<?php if(isset($_SESSION['addAdminMiddlename'])){echo $_SESSION['addAdminMiddlename'];}?>">
    </div>
    <div class = gender>
      <b>Gender: </b><br>
        <?php if(isset($_SESSION['addAdminGender'])){
          if($_SESSION['addAdminGender'] == 1){
            ?>
            <input type='radio' name='gender' value='male' checked> Male
            <input type='radio' name='gender' value='female'> Female
            <?php
          }
          elseif($_SESSION['addAdminGender'] == 0){
            ?>
            <input type='radio' name='gender' value='male'> Male
              <input type='radio' name='gender' value='female' checked> Female
            <?php
          }
        }?>
      </div>
      <div class = birthdate>
        <b>Birthdate: </b><br>
          <input type="date" name="birthdate" required value="<?php if(isset($_SESSION['addAdminBirthdate'])){echo $_SESSION['addAdminBirthdate'];}?>"><br>
      </div>
      <div class = contactno>
        <b>Contact Number: </b><br>
        <input type="text" name="contactno" placeholder="Enter your contact number here." minlength = "11" maxlength = "11" required pattern = "[0-9].{10,10}" title="Must only contain numbers, and must be 11 digits." value="<?php if(isset($_SESSION['addAdminContactno'])){echo $_SESSION['addAdminContactno'];}?>"><br>
      </div>
      <div class = buttonSubmit>
        <button id = "addAdminContinue" class="addSubmit" name = "addAdminContinue"> CONTINUE... </button>
      </div>
    </div>
</div>

<div id="adminAddress" class="tabcontent">
  <div class = "inputs">
    <div class = houseno>
      <b>House Number: </b><br>
      <input type="text" name="houseno" placeholder="Enter your house number here." required pattern = "\d*" title="Must only contain numbers." value="<?php if(isset($_SESSION['addAdminHouseno'])){echo $_SESSION['addAdminHouseno'];}?>"><br>
    </div>
    <div class = streetno>
      <b>Street Number: </b><br>
      <input type="text" name="streetno" placeholder="Enter your street number here." required pattern = "\d*" title="Must only contain numbers."><br>
    </div>
    <div class = barangay>
      <b>Barangay: </b><br>
      <input type="text" name="barangay" placeholder="Enter your barangay here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."><br>
    </div>
    <div class = city>
      <b>City: </b><br>
      <input type="text" name="city" placeholder="Enter your city here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."><br>
    </div>
    <div class = buttonSubmit>
      <button id = "backBtn" onclick = "viewAddInfoModal()" class="backBtn" name = "backBtn"><img class = "buttonBack" src="Resources/back-white.png"></button>
        <button id = "twoAddAdminContinue" type = "submit" class="addSubmitWBack" name = "twoAddAdminContinue"> CONTINUE... </button>
      </div>
  </div>
</div>

<div id="adminUserPassword" class="tabcontent">
  <div class = "inputs">
    <div class = email>
      <b>Email Address: </b><br>
      <input type="email" name="email" placeholder="Enter your email address here." required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "Must only contain letters, numbers, and ._%+-. Format e.g. aaa@gmail.com."><br>
    </div>
    <div class = pword>
      <b>Password: </b><br>
      <input type="password" name="password" placeholder="Enter your password here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."><br>
    </div>
    <div class = confirmpword>
      <b>Confirm Password: </b><br>
      <input type="password" name="confirmPassword" placeholder="Enter your password here again." value = "<?php if(isset($_SESSION['addAdminConfirmPassword'])){echo $_SESSION['addAdminConfirmPassword']; } ?>" required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."><br>
    </div>
    <div class = buttonSubmit>
        <button id = "createAdmin" type = "submit" class="addSubmit" name = "createAdmin"> CREATE </button>
      </div>
  </div>
</div>

<script>
function tabAddAdmin(modal, modalName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(modalName).style.display = "block";
    modal.currentTarget.className += " active";
}
</script>
     
</body>
</html> 