<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		 <link rel="stylesheet" type="text/css" href="Styles/profileStyles.css">
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<div class = heading>
				<?php
					require("admin-header.php");
				?>
			</div>
		<div class = wrapper>
			<div class = content>
				<?php
					require("connection.php");
				?>

				<form method = "post">
				<div class = myProfile> 
					<center><b>MY PROFILE</b></center>
				</div>
				<div class = inputs>
					<div class = username>
						<b>Username: </b><br>
						<input type="text" name="userName" placeholder="Username" value = "<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?>" required><br>
					</div>
					<div class = fullName>
						<b>Name: </b><br>
						<input type="text" name="firstname" placeholder="Enter your last name here." value = "<?php if(isset($_SESSION['firstName'])){echo $_SESSION['firstName'];} ?>"required>
						<input type="text" name="lastname" placeholder="Enter your first name here." value = "<?php if(isset($_SESSION['lastName'])){echo $_SESSION['lastName'];} ?>"required><br>
						<input type="text" name="middlename" placeholder="Enter your middle name here." value = "<?php if(isset($_SESSION['middleName'])){echo $_SESSION['middleName'];} ?>"required>
					</div>
					<div class = houseno>
						<b>House Number: </b><br>
						<input type="text" name="houseno" placeholder="Enter your house number here." value = "<?php if(isset($_SESSION['houseNo'])){echo $_SESSION['houseNo'];} ?>" required><br>
					</div>
					<div class = streetno>
						<b>Street Number: </b><br>
						<input type="text" name="streetno" placeholder="Enter your street number here." value = "<?php if(isset($_SESSION['street'])){echo $_SESSION['street'];} ?>" required><br>
					</div>
					<div class = barangay>
						<b>Barangay: </b><br>
						<input type="text" name="barangay" placeholder="Enter your barangay here." value = "<?php if(isset($_SESSION['barangay'])){echo $_SESSION['barangay'];} ?>" required><br>
					</div>
					<div class = city>
						<b>City: </b><br>
						<input type="text" name="city" placeholder="Enter your city here." value = "<?php if(isset($_SESSION['city'])){echo $_SESSION['city'];} ?>" required><br>
					</div>
					<div class = gender>
						<b>Gender: </b><br>
						<?php
							if($_SESSION['gender'] == 1){
								echo "
								<input type='radio' name='gender' value='male' checked> Male
  								<input type='radio' name='gender' value='female'> Female
  								";
							} elseif($_SESSION['gender'] == 0){
								echo "
								<input type='radio' name='gender' value='male'> Male
  								<input type='radio' name='gender' value='female' checked> Female
  								";
							}
						?>
					</div>
					<div class = birthdate>
						<b>Birthdate: </b><br>
						<input type="date" name="birthdate" value = "<?php if(isset($_SESSION['birthDate'])){echo $_SESSION['birthDate'];} ?>" required><br>
					</div>
					<div class = email>
						<b>Email Address: </b><br>
						<input type="email" name="email" placeholder="Enter your email address here." value = "<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?>" required><br>
					</div>
					<div class = contactno>
						<b>Contact Number: </b><br>
						<input type="text" name="contactno" placeholder="Enter your contact number here." value = "<?php if(isset($_SESSION['contact'])){echo $_SESSION['contact'];} ?>" required><br>
					</div>
					<div class = buttonSubmit>
  						<button type = "submit" class="login" name = update><b>UPDATE PROFILE</b></button>
  					</div>
				</div>
  			</form>
  			<?php
  				if(isset($_POST['update'])){
  					$sqlCheckUser = "SELECT username from account WHERE username = '$_POST[userName]'";
  					$resultCheckUser = $con->query($sqlCheckUser);

  					if ($_POST['gender'] == 'male'){
						$gender = 1;
					}
					elseif ($_POST['gender'] == 'female'){
						$gender = 0;
					}

  					if ($_POST['userName'] == $_SESSION['username']){
						$sqlPersonalInfo = "UPDATE personalinfo
									SET firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]', gender = '$gender', birthDate = '$_POST[birthdate]', email = '$_POST[email]', contact = '$_POST[contactno]'
									WHERE accountID = '$_SESSION[accountID]'";

						$sqlAddress = "UPDATE address
									SET houseNo = '$_POST[houseno]', street = '$_POST[streetno]', barangay = '$_POST[barangay]', city = '$_POST[city]'
									WHERE addressID = '$_SESSION[addressID]'";
						if ($con->query($sqlPersonalInfo) === TRUE) {
						    echo "Record updated successfully";
						} else {
						    echo "Error updating record: " . $con->error;
						}
						if ($con->query($sqlAddress) === TRUE) {
						    echo "Record updated successfully";
						} else {
						    echo "Error updating record: " . $con->error;
						}
  					}
  					else{
  						if ($resultCheckUser->num_rows > 0) {
							echo "<script type='text/javascript'>alert('Username is already used. Please choose another.');</script>";
						}
						else{
		  					if ($_POST['gender'] == 'male'){
								$gender = 1;
							}
							elseif ($_POST['gender'] == 'female'){
								$gender = 0;
							}
		  					$sqlAccount = "UPDATE account
										SET username = '$_POST[userName]'
										WHERE accountID = '$_SESSION[accountID]'";

							$sqlPersonalInfo = "UPDATE personalinfo
										SET firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]', gender = '$gender', birthDate = '$_POST[birthdate]', email = '$_POST[email]', contact = '$_POST[contactno]'
										WHERE accountID = '$_SESSION[accountID]'";

							$sqlAddress = "UPDATE address
										SET houseNo = '$_POST[houseno]', street = '$_POST[streetno]', barangay = '$_POST[barangay]', city = '$_POST[city]'
										WHERE addressID = '$_SESSION[addressID]'";
							//$sqlProf = "UPDATE account AS AC, personalinfo AS PI, address AS AD 
										//SET AC.username = '$_POST[userName]', firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]'
										//WHERE (accountID = '$_SESSION[accountID]') AND (AC.accountID = PI.accountID) AND (PI.addressID = AD.addressID)";
							if ($con->query($sqlAccount) === TRUE) {
							    echo "Record updated successfully";
							} else {
							    echo "Error updating record: " . $con->error;
							}
							if ($con->query($sqlPersonalInfo) === TRUE) {
							    echo "Record updated successfully";
							} else {
							    echo "Error updating record: " . $con->error;
							}
							if ($con->query($sqlAddress) === TRUE) {
							    echo "Record updated successfully";
							} else {
							    echo "Error updating record: " . $con->error;
							}
						}
  					}
					header("Refresh:0");
				}
  			?>
			</div>
		</div>
	</body>
</html>