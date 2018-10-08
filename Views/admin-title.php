<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/admin-titleStyles.css">
	</head>
	<body>
		<?php
			date_default_timezone_set('Asia/Manila');
			require("connection.php");
			$adminUserID = $_SESSION['adminUserID'];
			$sqlAdminInfo = "SELECT profilepicture, US.firstName, US.middleName, US.lastName, US.addressID, US.gender, US.birthDate, US.email, US.contact, US.password, AD.houseNo, AD.street, AD.barangay, AD.city FROM users AS US, address AS AD WHERE (US.userID = (:adminUserID)) AND (US.addressID = AD.addressID)";

			$stmt = $con->prepare($sqlAdminInfo);
			$stmt->bindParam(':adminUserID', $adminUserID, PDO::PARAM_INT);
			$stmt->execute();
			$rowAdminInfo = $stmt->fetch();
			if(empty($rowAdminInfo["profilepicture"])){
				$_SESSION['adminProfilePic'] = $profilePic = "Resources/userIcon.png";
			}
			else{
				$_SESSION['adminProfilePic'] = $profilePic = $rowAdminInfo["profilepicture"];
			}
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

			//Update of Requirements
			$dateToday = date("Y-m-d");
			$sqlReqCheckExpiration = "SELECT requirementID, expirationDate FROM requirements WHERE submitted = 1";

			$stmt = $con->prepare($sqlReqCheckExpiration);
			$stmt->execute();
			$results = $stmt->fetchAll();
			$rowCount = $stmt->rowCount();

			foreach ($results as $rowReqCheckExpiration) {
				$requirementID = $rowReqCheckExpiration['requirementID'];
				$expirationDate = $rowReqCheckExpiration['expirationDate'];
				if($expirationDate > $dateToday){
				}
				else{
					$sqlUpdateRequirements = "	UPDATE requirements
												SET submitted = 0
												WHERE requirementID = (:requirementID)";
					$stmt = $con->prepare($sqlUpdateRequirements);
					$stmt->bindParam(':requirementID', $requirementID, PDO::PARAM_INT);
					$stmt->execute();

				}
			}

			//If user have requirements
			$sqlCheckVerification = "
				SELECT userID 
				FROM users 
				WHERE (type = 2 OR type = 1)";

			$stmt = $con->prepare($sqlCheckVerification);
			//$stmt->bindParam(':handymanID', $_GET['handymanID'], PDO::PARAM_INT);
			$stmt->execute();
			$results = $stmt->fetchAll();
			$rowCount = $stmt->rowCount();

			foreach ($results as $rowCheckVerification) {
				$checkVerificationUserID = $rowCheckVerification["userID"];
				$sqlCheckRequirements = "
					SELECT count(requirementID) AS reqCount 
					FROM requirements 
					WHERE userID = (:checkVerificationUserID) AND submitted = 1";
				$stmt = $con->prepare($sqlCheckRequirements);
				$stmt->bindParam(':checkVerificationUserID', $checkVerificationUserID, PDO::PARAM_INT);
				$stmt->execute();
				$rowCheckRequirements = $stmt->fetch();
				if($rowCheckRequirements['reqCount'] == 0){
					$sqlCheckUsersUpdate = "UPDATE users
											SET flag = 0
											WHERE userID = (:checkVerificationUserID)";
					$stmt = $con->prepare($sqlCheckUsersUpdate);
					$stmt->bindParam(':checkVerificationUserID', $checkVerificationUserID, PDO::PARAM_INT);
					$stmt->execute();
				}
			}

			//If user have requirements
			// $sqlCheckPayment = "
			// 	SELECT userID 
			// 	FROM users 
			// 	WHERE (type = 2 OR type = 1)";

			// $stmt = $con->prepare($sqlCheckPayment);
			// //$stmt->bindParam(':handymanID', $_GET['handymanID'], PDO::PARAM_INT);
			// $stmt->execute();
			// $results = $stmt->fetchAll();
			// $rowCount = $stmt->rowCount();

			// foreach ($results as $rowCheckPayment) {
			// 	$dateToday = date("Y-m-d");
			// 	$checkPaymentUserID = $rowCheckPayment["userID"];
			// 	$sqlCheckRequirements = "
			// 		SELECT count(paymentID) AS paymentCount 
			// 		FROM payment 
			// 		WHERE userID = (:checkPaymentUserID) AND flag = 1 AND dueDate < :dateToday";
			// 	$stmt = $con->prepare($sqlCheckRequirements);
			// 	$stmt->bindParam(':checkPaymentUserID', $checkPaymentUserID, PDO::PARAM_INT);
			// 	$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_INT);
			// 	$stmt->execute();
			// 	$rowCheckPayment = $stmt->fetch();
			// 	if($rowCheckPayment['paymentCount'] > 0){
			// 		$sqlCheckPaymentUsersUpdate = "UPDATE users
			// 								SET flag = 0
			// 								WHERE userID = (:checkPaymentUserID)";
			// 		$stmt = $con->prepare($sqlCheckPaymentUsersUpdate);
			// 		$stmt->bindParam(':checkPaymentUserID', $checkPaymentUserID, PDO::PARAM_INT);
			// 		$stmt->execute();
			// 	}
			// }
		?>
		<div class = webTitle>
			<ul class = webTitleUL>
			  <li class = "webTitleList"><div class = "webTitleText">Find and Hire</div></li>
			  <li class = "webTitleListLeft">
			  	<div class = "webProfile">
			  		<ul class = webTitleUL>
			  			<li class = "webTitleList">
				  			<div class="webProfilePicture">
				  				<img class = "dropbtnUser" src="<?php echo $profilePic; ?>">
				  			</div>
				  		</li>
				  		<li class = "webTitleList">
				  			<div class = "webProfileName"><?php echo $firstName;?></div>
				  		</li>
				  		<li class = "webTitleList">
				  			<div class = "webProfileOptions">
								<div class="dropDownBtn">
									<button class="dropBtnOptions">
										<i class="dropDown"></i>
									</button>
									<div class="dropDownBtnContent">
										<a href="index?route=picture">UPDATE PROFILE PIC</a>
										<a href="index?route=profile">UPDATE PROFILE</a>
										<a href="index?route=changePassword">CHANGE PASSWORD</a>
										<a href="index?route=changeAddress">CHANGE ADDRESS</a>
										<form method="post">
											<button class="logoutBtn" name="logout">
												LOGOUT
											</button>
										</form>
									</div>
								</div> 
				  				
				  			</div>
				  		</li>
			  		</ul>
			  	</div>
			 	</li>
			</ul>	
		</div>
		<?php
			if(isset($_POST['logout'])){
				session_destroy();
				echo "<script type='text/javascript'>window.location.href='index';</script>";
			}
		?>
	</body>
</html>