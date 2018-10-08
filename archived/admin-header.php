<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/admin-headerStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-headerUserStyles.css">
	</head>
	<body>
		<div class = navWrapper>
			<div class = navContent>
				<?php
					require("connection.php");
					$adminAccountID = $_SESSION['adminAccountID'];
					$sqlAdminInfo = "SELECT PI.firstName, PI.middleName, PI.lastName, PI.addressID, PI.gender, PI.birthDate, PI.email, PI.contact, AC.username, AC.password, AD.houseNo, AD.street, AD.barangay, AD.city FROM personalInfo AS PI, account AS AC, address AS AD WHERE (PI.accountID = '$adminAccountID') AND (PI.accountID = AC.accountID) AND (PI.addressID = AD.addressID)";
					$resultAdminInfo = $con->query($sqlAdminInfo);
					if ($resultAdminInfo->num_rows > 0) {
						if($rowAdminInfo = $resultAdminInfo->fetch_assoc()) {
							$_SESSION['adminUsername'] = $rowAdminInfo["username"];
        					$_SESSION['adminFirstName'] = $firstName = $rowAdminInfo["firstName"];
        					$_SESSION['adminMiddleName'] = $middleName = $rowAdminInfo["middleName"];
        					$_SESSION['adminLastName'] = $lastName = $rowAdminInfo["lastName"];
        					$_SESSION['adminAddressID'] = $rowAdminInfo["addressID"];
        					$_SESSION['adminHouseNo'] = $rowAdminInfo["houseNo"];
        					$_SESSION['adminStreet'] = $rowAdminInfo["street"];
        					$_SESSION['adminBarangay'] = $rowAdminInfo["barangay"];
        					$_SESSION['adminCity'] = $rowAdminInfo["city"];
        					$_SESSION['adminGender'] = $rowAdminInfo["gender"];
        					$_SESSION['adminBirthdate'] = $rowAdminInfo["birthDate"];
        					$_SESSION['adminEmail'] = $rowAdminInfo["email"];
        					$_SESSION['adminContact'] = $rowAdminInfo["contact"];
    					}
					}
				?>

				<div class = navHometitle> 
					<b>Find and Hire!</b>
				</div>
			</div>
			<div class = user>
				Good Day!<br><?php echo $firstName . " " . $middleName . " " . $lastName; ?>
			</div>
			<div class = userPic>
				<div class = "dropdownUser">
					<img class = "dropbtnUser" src="Resources/userIcon.png" alt="Paris">
					<div class="dropdownUser-content">
						<a href="admin-profile.php">UPDATE PROFILE</a>
						<a href="admin-changePassword.php">CHANGE PASSWORD</a>
						<a href="admin-changeAddress.php">CHANGE ADDRESS</a>
						<form method = "post">
							<button type = "submit" class = "log-out" name = "logout">LOGOUT</button>
						</form>
					</div>	
				</div>
			</div>
			<center>
					<div class = links>
						<ul class = dropdownLinks>
  							<li class="dropdown"><a href="admin-dashboard.php"><b>DASHBOARD</b></a></li>
  							<li class="dropdown">
    							<a href="javascript:void(0)" class="dropbtn"><b>ACCOUNTS</b></a>
    							<div class="dropdown-content">
    								<a href="admin-adminAccounts.php">ADMIN</a>
      								<a href="admin-customerAccounts.php">CUSTOMER</a>
      								<a href="admin-handymanAccounts.php">HANDYMAN</a>
    							</div>
  							</li>
  							<li class="dropdown"><a href="admin-transactions.php"><b>TRANSACTION</b></a></li>
							<li class="dropdown">
								<a href="javascript:void(0)" class="dropbtn"><b>MAINTENANCE</b></a>
								<div class="dropdown-content">
      								<a href="admin-service.php">SERVICES</a>
      								<a href="admin-violations.php">VIOLATIONS</a>
      								<a href="admin-notifications.php">NOTIFICATIONS</a>
    							</div>
							</li>
							<li class="dropdown">
								<a href="javascript:void(0)" class="dropbtn"><b>REPORT</b></a>
								<div class="dropdown-content">
      								<a href="#">CUSTOMER</a>
      								<a href="#">HANDYMAN</a>
    							</div>
							</li>
						</ul>
					</div>
			</center>
		</div>
		<?php
			if(isset($_POST['logout'])){
				session_destroy();
				echo "<script type='text/javascript'>window.location.href='admin-login.php';</script>";
			}
		?>
	</body>
</html>