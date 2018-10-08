<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/admin-headerStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-headerUserStyles.css">
	</head>
	<body>
		<div class = navWrapper>
			<div class = navContent>
				<?php
					require("connection.php");
					$adminUserID = $_SESSION['adminUserID'];
					$sqlAdminInfo = "SELECT US.firstName, US.middleName, US.lastName, US.addressID, US.gender, US.birthDate, US.email, US.contact, US.password, AD.houseNo, AD.street, AD.barangay, AD.city FROM users AS US, address AS AD WHERE (US.userID = (:adminUserID)) AND (US.addressID = AD.addressID)";

					$stmt = $con->prepare($sqlAdminInfo);
					$stmt->bindParam(':adminUserID', $adminUserID, PDO::PARAM_INT);
					$stmt->execute();
					$rowAdminInfo = $stmt->fetch();
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

    				$sqlReadReports = "SELECT count(reportsID) AS count FROM reports WHERE readFlag = 0";

					$stmt = $con->prepare($sqlReadReports);
					$stmt->execute();
					$rowReadReports = $stmt->fetch();
					$readReports = $rowReadReports["count"];
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
					<img class = "dropbtnUser" src="Resources/userIcon.png">
					<div class="dropdownUser-content">
						<a href="index?route=profile">UPDATE PROFILE</a>
						<a href="index?route=changePassword">CHANGE PASSWORD</a>
						<a href="index?route=changeAddress">CHANGE ADDRESS</a>
						<form method = "post">
							<button type = "submit" class = "log-out" name = "logout">LOGOUT</button>
						</form>
					</div>	
				</div>
			</div>
			<center>
					<div class = links>
						<ul class = dropdownLinks>
  							<li class="dropdown"><a href="index?route=dashboard"><b>DASHBOARD</b></a></li>
  							<li class="dropdown">
    							<a href="javascript:void(0)" class="dropbtn"><b>ACCOUNTS</b></a>
    							<div class="dropdown-content">
    								<a href="index?route=adminAccounts">ADMIN</a>
      								<a href="index?route=customerAccounts">CUSTOMER</a>
      								<a href="index?route=handymanAccounts">HANDYMAN</a>
    							</div>
  							</li>
  							<li class="dropdown"><a href="index?route=transactions"><b>TRANSACTION</b></a></li>
							<li class="dropdown">
								<a href="javascript:void(0)" class="dropbtn"><b>MAINTENANCE</b></a>
								<div class="dropdown-content">
      								<a href="index?route=notifications">NOTIFICATIONS</a>
      								<a href="index?route=questions">QUESTIONS</a>
      								<a href="index?route=service">SERVICES</a>
      								<a href="index?route=violations">VIOLATIONS</a>
    							</div>
							</li>
							<li class="dropdown">
								<div class = "btnLinks">
									<a href="javascript:void(0)" class="dropbtn"><b>REPORT</b>
										<?php if($readReports != 0){
											?>
											<span class="badge">
												<?php echo $readReports;?>
											</span>
										<?php
										}
										?>
									</a>
								</div>
								<div class="dropdown-content">
      								<a href="index?route=reportFromCustomer">CUSTOMER</a>
      								<a href="index?route=reportFromHandyman">HANDYMAN</a>
    							</div>
							</li>
						</ul>
					</div>
			</center>
		</div>
		<?php
			if(isset($_POST['logout'])){
				session_destroy();
				echo "<script type='text/javascript'>window.location.href='index';</script>";
			}
		?>
	</body>
</html>