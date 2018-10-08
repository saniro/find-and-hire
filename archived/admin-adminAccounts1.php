<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-adminAccountsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<style>
			.viewModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 30px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewClose:hover,
			.viewClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewAddInfoModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 30px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewAddInfoModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewAddInfoClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewAddInfoClose:hover,
			.viewAddInfoClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewModalYesNo {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 100px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewModalYesNo-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 17%;
			    padding-bottom: 0px;
			}

			/* The Close Button */
			.viewCloseYesNo {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewCloseYesNo:hover,
			.viewCloseYesNo:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewAddAddressModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 30px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewAddAddressModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewAddAddressClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewAddAddressClose:hover,
			.viewAddAddressClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewUserPassModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 30px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewUserPassModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewUserPassClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewUserPassClose:hover,
			.viewUserPassClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.details div{
				margin-bottom: 10px;
			}
			.titleDetails{
				margin-bottom: 20px;
				font-size: 24px;
			}
			.infoDetails{
				margin-bottom: 20px;
				font-size: 20px;
			}
			.noButton{
				margin-left: 40px;
			}
			.profilePic{
				width: 150px;
				height: 150px;
			}
			.profilePicDivision{
				padding-top: 15px;
				padding-bottom: 15px;
			}
			.profilePicContent{
				padding-left: 33%;
				padding-left: 33%;
			}
		</style>
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
				<?php
					require("admin-header.php");
					if(!isset($_GET['sort'])){
						$_SESSION['linkAdmin'] = "";
					}
				?>
		<div class = wrapper>
			<div class= "title"> ACCOUNTS - Admin </div>

			<table id = actionTable>
				<thead class = "actions">
					<col width = "380">	
					<tr>
						<th class = "addButton" colspan="1">
							<div class = linkSort>
								<ul class = "nothing">
  									<li class="dropdownSort">
    									<a href="javascript:void(0)" class="dropbtnSort">
    										<div id='addAdmin' class = "sortBtn" onclick='viewAddInfoModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
    									</a>
  									</li>
  									<li id = "dropdownSortID" class="dropdownSort">
    									<a href="javascript:void(0)" class="dropbtnSort">
    										<div class = "sortBtn"><img class = "iconAction" src="Resources/sort-by-attributes.png">SORT</div>
    									</a>
    									<div class="dropdown-content">
    										<?php
    											$optionSearch = "";
    											$search = "";
    											if(isset($_GET['optionSearch'])){
    												$optionSearch = "&optionSearch=" . $_GET['optionSearch'];
    											}
    											if(isset($_GET['search'])){
    												$search = "&search=" . $_GET['search'];
    											}
    										?>
      										<a href="index.php?route=adminAccounts&sort=userID<?php echo $optionSearch . $search; ?>">Admin No.</a>
      										<a href="index.php?route=adminAccounts&sort=adminName<?php echo $optionSearch . $search; ?>">Name</a>
      										<a href="index.php?route=adminAccounts&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
    									</div>
  									</li>
  									
								</ul>
							</div>
						</th>
						<th colspan="2">
							<form method = "get">
							<div class = "searchClass">
									<a href = "index.php?route=adminAccounts"><img class = "resetAction" src="Resources/reset.png"></a>
									<select class = "optionSearch" name = "optionSearch">
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'userID'){
													echo " selected ";
												}
											}
										?>value="userID">Admin No.</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'adminName'){
													echo " selected ";
												}
											}
										?>
										value="adminName">Name</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'email'){
													echo " selected ";
												}
											}
										?>
										value="email">Email Address</option>
									</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "adminAccounts">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
							</div>
						</form>
						</th>
					</tr>
				</thead>
			</table>
			
			<table id = userTable>
				<col width = "110">
				<col width = "280">
				<col width = "250">
				<thead>
					<tr>
						<th> Admin No. </th>
						<th> Name </th>
						<th> Email Address </th>
						<th colspan = 3> Action </th>
					</tr>				
				</thead>
				<tbody>
					<?php
						$sqlInfo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS adminName, addressID, gender, birthDate, email, contact, flag FROM users WHERE userID != (:adminUserID) AND type = 0";
						if(isset($_GET['optionSearch'])){
							//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
							if($_GET['optionSearch'] == 'userID'){
								$optionSearchVar = "userID";
							}
							elseif($_GET['optionSearch'] == 'adminName'){
								$optionSearchVar = "concat(lastName, ', ', firstName, ' ', middleName)";
							}
							elseif($_GET['optionSearch'] == 'email'){
								$optionSearchVar = "email";
							}

	    					$sqlInfo .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
						}

						if(isset($_GET['sort'])){
							if ($_GET['sort'] == 'userID'){
	    						$sqlInfo .= " ORDER BY userID";
	    						$_SESSION['linkAdmin'] = "userID";
							}
							elseif ($_GET['sort'] == 'adminName')
							{
							    $sqlInfo .= " ORDER BY adminName";
							    $_SESSION['linkAdmin'] = "adminName";
							}
							elseif ($_GET['sort'] == 'email')
							{
							    $sqlInfo .= " ORDER BY email";
							    $_SESSION['linkAdmin'] = "email";
							}
						}

						$stmt = $con->prepare($sqlInfo);
						$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_INT);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$rowCount = $stmt->rowCount();

						foreach($results as $rowInfo){
							$infoUserID = $rowInfo["userID"];
		    				$infoAdminName = $rowInfo["adminName"];
		    				$infoEmail = $rowInfo["email"];
		    				$infoFlag = $rowInfo["flag"];
		    				?>
		    				<tr class = "tableContent">
					        	<td><?php echo $infoUserID; ?></td>
					        	<td><?php echo $infoAdminName; ?></td>
					        	<td><?php echo $infoEmail; ?></td>
					        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
					        	<td>
					        		<?php
					        			if ($infoFlag == 1){
					        			echo "<button id = 'deacBtn' class = 'delete' onclick='viewYesNo()'>Deactivate</button>";
					        			}
					        			else{
					        			echo "<button id = 'actBtn' class = 'view' onclick='viewYesNo()'>Activate</button>";
					        			}
					        		?>
					        	</td>
					        </tr>
					<?php
				    	}
				    	if($rowCount == 0){
							echo "<td> No results. </td>";
						}
					?>
				</tbody>
			</table>
		</div>

		<div id="viewModal" class="viewModal">
  			<div class="viewModal-content">
    			<span class="viewClose">&times;</span>
    				<div class = "details">
    				<div class = "titleDetails"><b>View Full Details</b></div>
    				<div class = "profilePicDivision">
    					<?php
    						if(isset($_GET['userID'])){
    							$sqlInfoModal = "SELECT userID, firstName, middleName, lastName, houseNo, street, barangay, city, gender, birthDate, email, contact FROM users AS US, address AS AD WHERE (US.addressID = AD.addressID) AND userID = (:userID)";

    							$stmt = $con->prepare($sqlInfoModal);
								$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowInfoModal = $stmt->fetch();

								$modalInfoAccountID = $rowInfoModal["userID"];
			    				$modalInfoLastName = $rowInfoModal["lastName"];
			    				$modalInfoMiddleName = $rowInfoModal["middleName"];
			    				$modalInfoFirstName = $rowInfoModal["firstName"];
			    				$modalInfoHouseNo = $rowInfoModal["houseNo"];
			    				$modalInfoStreet = $rowInfoModal["street"];
			    				$modalInfoBarangay = $rowInfoModal["barangay"];
			    				$modalInfoCity = $rowInfoModal["city"];
			    				if($rowInfoModal["gender"] == 1){
			    					$modalInfoGender = 'Male';
			    				}elseif($rowInfoModal["gender"] == 0){
			    					$modalInfoGender = 'Female';
			    				}
			    				$modalInfoBirthdate = $rowInfoModal["birthDate"];
			    				$modalInfoEmail = $rowInfoModal["email"];
			    				$modalInfoContact = $rowInfoModal["contact"];
							}
    					?>
    					<div class = "profilePicContent">
    						<img class = "profilePic" src="ProfilePictures/userIcon.png">
    					</div>
    				</div>
    				<div id = number class = customerDetails>
						<b>Admin no: </b><?php echo $modalInfoAccountID; ?>
					</div>
					<div id = name class = customerDetails>
						<b>Name: </b><?php echo $modalInfoLastName . ", " . $modalInfoFirstName . " " . $modalInfoMiddleName; ?>
					</div>
					<div id = address class = customerDetails>
						<b>Address: </b><?php echo $modalInfoHouseNo . ", " . $modalInfoStreet . ", " . $modalInfoBarangay . ", " . $modalInfoCity;?>
					</div>
					<div id = gender class = customerDetails>
						<b>Gender: </b><?php echo $modalInfoGender ?>
					</div>
					<div id = birthdate class = customerDetails>
						<b>Birthdate: </b><?php echo $modalInfoBirthdate ?>
					</div>
					<div id = email class = customerDetails>
						<b>Email Address: </b><?php echo $modalInfoEmail ?>
					</div>
					<div id = contactno class = customerDetails>
						<b>Contact Number: </b><?php echo $modalInfoContact ?>
					</div>
				</div>
  			</div>
		</div>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
	    			<div class = "infoDetails"><b>Are you sure?</b></div>
	    			<div class = "profilePicDivision">
	    				<form method="post">
	    					<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php
						if(isset($_GET['userID'])){
	    					$sqlYesNo = "SELECT userID, flag FROM users WHERE userID = (:userID)";

	    					$stmt = $con->prepare($sqlYesNo);
							$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowYesNo = $stmt->fetch();

							$modalYesNoUserID = $rowYesNo["userID"];
				    		$modalYesNoFlag = $rowYesNo["flag"];
						}

						if(isset($_POST['Yes'])){
							if($modalYesNoFlag == 1){
								$sqlActiveModal = "UPDATE users
													SET flag = 0
													WHERE userID = (:modalYesNoUserID)";
								$stmt = $con->prepare($sqlActiveModal);
								$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
								$stmt->execute();
							}
							elseif($modalYesNoFlag == 0){
								$sqlDeactiveModal = "UPDATE users
													SET flag = 1
													WHERE userID = (:modalYesNoUserID)";
								$stmt = $con->prepare($sqlDeactiveModal);
								$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
								$stmt->execute();
							}
							echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index.php?route=adminAccounts&sort=".$_SESSION['linkAdmin'].$optionSearch.$search."';</script>";
						}
					?>
		  		</div>
		  	</div>
		</div>

  		<div id="viewAddInfoModal" class="viewAddInfoModal">
  			<div class="viewAddInfoModal-content">
    			<span class = "viewAddInfoClose">&times;</span>
    			<div class = "details">
    				<div class = "titleDetails"><b>Add Admin</b></div>
    				<form method = "post">
						<div class = "inputs">
							<div class = name>
								<b>Name: </b><br>

								<input type="text" name="lastname" placeholder="Enter your last name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-." value = "<?php if(isset($_SESSION['addAdminLastname'])){echo $_SESSION['addAdminLastname'];}?>">
								<input type="text" name="firstname" placeholder="Enter your first name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."><br>
								<input type="text" name="middlename" placeholder="Enter your middle name here." required pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-.">
							</div>
							<div class = gender>
								<b>Gender: </b><br>
								<input type='radio' name='gender' value='male' checked> Male
		  						<input type='radio' name='gender' value='female'> Female
							</div>
							<div class = birthdate>
								<b>Birthdate: </b><br>
								<input type="date" name="birthdate" required><br>
							</div>
							<div class = contactno>
								<b>Contact Number: </b><br>
								<input type="text" name="contactno" placeholder="Enter your contact number here." minlength = "11" maxlength = "11" required pattern = "[0-9].{10,10}" title="Must only contain numbers, and must be 11 digits."><br>
							</div>
							<div class = buttonSubmit>
	  							<button id = "addAdminContinue" type = "submit" class="addSubmit" name = "addAdminContinue"> CONTINUE... </button>
	  						</div>
						</div>
					</form>
				</div>
  			</div>
		</div>

		<div id="viewAddAddressModal" class="viewAddAddressModal">
  			<div class="viewAddAddressModal-content">
    			<span class="viewAddAddressClose">&times;</span>
    			<div class = "details">
    				<div class = "titleDetails"><b>Add Admin</b></div>
    				<form method = "post">
						<div class = "inputs">
							<div class = houseno>
								<b>House Number: </b><br>
								<input type="text" name="houseno" placeholder="Enter your house number here." required pattern = "\d*" title="Must only contain numbers."><br>
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
					</form>
				</div>
  			</div>
		</div>

		<div id="viewUserPassModal" class="viewUserPassModal">
  			<div class="viewUserPassModal-content">
    			<span class="viewUserPassClose">&times;</span>
    			<div class = "details">
    				<div class = "titleDetails"><b>Add Admin</b></div>
    				<form method = "post">
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
					</form>
				</div>
  			</div>
		</div>
		<?php
			if (isset($_POST['lastname'])){
				$_SESSION['addAdminLastname'] = $_POST['lastname'];
			}
			if (isset($_POST['firstname']))
			if(isset($_POST['addAdminContinue'])){
				$_SESSION['addAdminFirstname'] = ;
				$_SESSION['addAdminMiddlename'] = $_POST['middlename'];
				if($_POST['gender'] == 'male'){
					$_SESSION['addAdminGender'] = 1;
				}
				elseif($_POST['gender'] == 'female'){
					$_SESSION['addAdminGender'] = 0;
				}
				$_SESSION['addAdminBirthdate'] = $_POST['birthdate'];
				$_SESSION['addAdminContactno'] = $_POST['contactno'];
				echo "
						<script>
						    // Get the modal
						    var modal = document.getElementById('viewAddAddressModal');

						    // Get the button that opens the modal
						    var btn = document.getElementById('addAdminContinue');

						    // Get the <span> element that closes the modal
						    var span = document.getElementsByClassName('viewAddAddressClose')[0];

						    // When the user clicks the button, open the modal 
						   modal.style.display = 'block';
						    // When the user clicks on <span> (x), close the modal
						   	span.onclick = function() {
						        modal.style.display = 'none';
						    }

						    // When the user clicks anywhere outside of the modal, close it
						    window.onclick = function(event) {
						        if (event.target == modal) {
						           modal.style.display = 'none';
						        }
						    }
						</script>
				";
			}

			if(isset($_POST['twoAddAdminContinue'])){
				$_SESSION['addAdminHouseno'] = $_POST['houseno'];
				$_SESSION['addAdminStreetno'] = $_POST['streetno'];
				$_SESSION['addAdminBarangay'] = $_POST['barangay'];
				$_SESSION['addAdminCity'] = $_POST['city'];
				echo "
						<script>
						    // Get the modal
						    var modal = document.getElementById('viewUserPassModal');

						    // Get the button that opens the modal
						    var btn = document.getElementById('twoAddAdminContinue');

						    // Get the <span> element that closes the modal
						    var span = document.getElementsByClassName('viewUserPassClose')[0];

						    // When the user clicks the button, open the modal 
						   modal.style.display = 'block';
						    // When the user clicks on <span> (x), close the modal
						   	span.onclick = function() {
						        modal.style.display = 'none';
						    }

						    // When the user clicks anywhere outside of the modal, close it
						    window.onclick = function(event) {
						        if (event.target == modal) {
						           modal.style.display = 'none';
						        }
						    }
						</script>
				";
			}

			if(isset($_POST['createAdmin'])){
				if ($_POST['password'] == $_POST['confirmPassword']){
					$email = $_POST['email'];
					$password = $_POST['password'];
					$confirmPassword = $_POST['confirmPassword'];

					$_SESSION['addAdminEmail'] = $email;
					$_SESSION['addAdminPassword'] = $password;
					$_SESSION['addAdminConfirmPassword'] = $confirmPassword;

					$sqlAddAdmin = "SELECT email FROM users WHERE email = (:email)";

					$stmt = $con->prepare($sqlAddAdmin);
					$stmt->bindParam(':email', $email, PDO::PARAM_INT);
					$stmt->execute();
					$rowCount = $stmt->rowCount();

					if ($rowCount > 0) {
						echo "	<script type='text/javascript'>
									alert('Email is already used. Please choose another.');
									// Get the modal
								    var modal = document.getElementById('viewUserPassModal');

								    // Get the <span> element that closes the modal
								    var span = document.getElementsByClassName('viewUserPassClose')[0];

								    // When the user clicks the button, open the modal 
								   modal.style.display = 'block';
								    // When the user clicks on <span> (x), close the modal
								   	span.onclick = function() {
								        modal.style.display = 'none';
								    }

								    // When the user clicks anywhere outside of the modal, close it
								    window.onclick = function(event) {
								        if (event.target == modal) {
								           modal.style.display = 'none';
								        }
								    }
								</script>";
					} else {
						$sqlCreateAdminAddress = "INSERT INTO address (houseNo, street, barangay, city)values(:addAdminHouseno, :addAdminStreetno, :addAdminBarangay, :addAdminCity)";

						$stmt = $con->prepare($sqlCreateAdminAddress);
						$stmt->bindParam(':addAdminHouseno', $_SESSION['addAdminHouseno'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminStreetno', $_SESSION['addAdminStreetno'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminBarangay', $_SESSION['addAdminBarangay'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminCity', $_SESSION['addAdminCity'], PDO::PARAM_STR);
						$stmt->execute();
						$addressID = $con->lastInsertId();

						$sqlCreateAdminUser = "INSERT INTO users (firstName, middleName, lastName, addressID, gender, birthDate, email, contact, password, type)values(:addAdminFirstname, :addAdminMiddlename, :addAdminLastname, :addressID, :addAdminGender, :addAdminBirthdate, :addAdminEmail, :addAdminContactno, :addAdminPassword, 0)";

						$stmt = $con->prepare($sqlCreateAdminUser);
						$stmt->bindParam(':addAdminFirstname', $_SESSION['addAdminFirstname'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminMiddlename', $_SESSION['addAdminMiddlename'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminLastname', $_SESSION['addAdminLastname'], PDO::PARAM_STR);
						$stmt->bindParam(':addressID', $addressID, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminGender', $_SESSION['addAdminGender'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminBirthdate', $_SESSION['addAdminBirthdate'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminEmail', $_SESSION['addAdminEmail'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminContactno', $_SESSION['addAdminContactno'], PDO::PARAM_STR);
						$stmt->bindParam(':addAdminPassword', $_SESSION['addAdminPassword'], PDO::PARAM_STR);
						$stmt->execute();

						$_SESSION['addAdminPassword'] = "";
						$_SESSION['addAdminConfirmPassword'] = "";
						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index.php?route=adminAccounts&sort=".$_SESSION['linkAdmin'].$optionSearch.$search."';</script>";
						//include("page1.php");
					}
				}
				else{
					echo "	<script type='text/javascript'>
								alert('Password does not match. Please try again.');
								// Get the modal
							    var modal = document.getElementById('viewUserPassModal');

							    // Get the <span> element that closes the modal
							    var span = document.getElementsByClassName('viewUserPassClose')[0];

							    // When the user clicks the button, open the modal 
							   modal.style.display = 'block';
							    // When the user clicks on <span> (x), close the modal
							   	span.onclick = function() {
							        modal.style.display = 'none';
							    }

							    // When the user clicks anywhere outside of the modal, close it
							    window.onclick = function(event) {
							        if (event.target == modal) {
							           modal.style.display = 'none';
							        }
							    }
							</script>";
				}
			}
		?>
		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkAdmin'])){ echo '&sort=' . $_SESSION['linkAdmin'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index.php?route=adminAccounts&userID="+userID+linkSort+search+optionSearch
							alert('<?php echo $_SESSION['linkAdmin']; ?>');
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function viewYesNo(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkAdmin'])){echo '&sort=' . $_SESSION['linkAdmin'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index.php?route=adminAccounts&userID="+userID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}
					}
				}
			}

			function viewAddInfoModal(){
			    // Get the modal
			    var modalInfo = document.getElementById('viewAddInfoModal');
			    var modalAddress = document.getElementById('viewAddAddressModal');
			    var modalPassword = document.getElementById('viewUserPassModal');

			    // Get the button that opens the modal
			    var btn = document.getElementById('addAdmin');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName("viewAddInfoClose")[0];

			    // When the user clicks the button, open the modal 
			  	modalInfo.style.display = "block";
			  	modalAddress.style.display = "none";
			  	modalPassword.style.display = "none";
			    // When the user clicks on <span> (x), close the modal
			   	span.onclick = function() {
			        modalInfo.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			           modalInfo.style.display = "none";
			        }
			    }
			}

			

			window.onload = function(){
				var x = localStorage.getItem('viewModal');
				if (x == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)

				var yesno = localStorage.getItem('viewModalYesNo');
				if (yesno == 'true'){
					document.getElementById('viewModalYesNo').style.display = "block";
				}
				localStorage.setItem('viewModalYesNo',false)

			}

			var spanView = document.getElementsByClassName("viewClose")[0];
			spanView.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

		</script>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>