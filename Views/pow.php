<div id="viewAddAddressModal" class="viewAddAddressModal">
  			<div class="viewAddAddressModal-content">
    			<span class="viewAddAddressClose">&times;</span>
    			<div class = "details">
    				<div class = "titleDetails"><b>Add Admin</b></div>
    				<form method = "post">
						<div class = "inputs">
							<div class = name>
								<b>Name: </b><br>
								<input type="text" name="lastname" placeholder="Enter your last name here." required>
								<input type="text" name="firstname" placeholder="Enter your first name here." required><br>
								<input type="text" name="middlename" placeholder="Enter your middle name here." required>
							</div>
							<div class = gender>
								<b>Gender: </b><br>
								<input type="radio" name="gender" value="male" checked> Male
		  						<input type="radio" name="gender" value="female"> Female
							</div>
							<div class = birthdate>
								<b>Birthdate: </b><br>
								<input type="date" name="birthdate" required><br>
							</div>
							<div class = email>
								<b>Email Address: </b><br>
								<input type="email" name="email" placeholder="Enter your email address here." required><br>
							</div>
							<div class = contactno>
								<b>Contact Number: </b><br>
								<input type="text" name="contactno" placeholder="Enter your contact number here." required><br>
							</div>
							<div class = buttonSubmit>
	  							<button id = "addAdminContinue2" type = "submit" class="addSubmit" name = "addAdminContinue" onclick=''> Create </button>
	  						</div>
						</div>
					</form>
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

								<input type="text" name="lastname" placeholder="Enter your last name here." value = "<?php if(isset($_SESSION['addAdminLastname'])){echo $_SESSION['addAdminLastname'];}?>" required>
								<input type="text" name="firstname" placeholder="Enter your first name here." value = "<?php if(isset($_SESSION['addAdminFirstname'])){echo $_SESSION['addAdminFirstname'];}?>" required><br>
								<input type="text" name="middlename" placeholder="Enter your middle name here." value = "<?php if(isset($_SESSION['addAdminMiddlename'])){echo $_SESSION['addAdminMiddlename'];}?>" required>
							</div>
							<div class = gender>
								<b>Gender: </b><br>
								<?php if(isset($_SESSION['addAdminGender'])){
									if($_SESSION['addAdminGender'] == 1){
										echo "
										<input type='radio' name='gender' value='male' checked> Male
		  								<input type='radio' name='gender' value='female'> Female
		  								";
									} elseif($_SESSION['addAdminGender'] == 0){
										echo "
										<input type='radio' name='gender' value='male'> Male
		  								<input type='radio' name='gender' value='female' checked> Female
		  								";
									}
								}
								else echo "	<input type='radio' name='gender' value='male' checked> Male
		  									<input type='radio' name='gender' value='female'> Female";
								?>
							</div>
							<div class = birthdate>
								<b>Birthdate: </b><br>
								<input type="date" name="birthdate" value = "<?php if(isset($_SESSION['addAdminBirthdate'])){echo $_SESSION['addAdminBirthdate'];}?>" required><br>
							</div>
							<div class = email>
								<b>Email Address: </b><br>
								<input type="email" name="email" placeholder="Enter your email address here." value = "<?php if(isset($_SESSION['addAdminEmail'])){echo $_SESSION['addAdminEmail'];}?>" required><br>
							</div>
							<div class = contactno>
								<b>Contact Number: </b><br>
								<input type="text" name="contactno" placeholder="Enter your contact number here." value = "<?php if(isset($_SESSION['addAdminContactno'])){echo $_SESSION['addAdminContactno'];}?>" required><br>
							</div>
							<div class = buttonSubmit>
	  							<button id = "addAdminContinue" type = "submit" class="addSubmit" name = "addAdminContinue" onclick=''> CONTINUE... </button>
	  						</div>
						</div>
					</form>
					<?php
						if(isset($_POST['addAdminContinue'])){
							$_SESSION['addAdminLastname'] = $_POST['lastname'];
							$_SESSION['addAdminFirstname'] = $_POST['firstname'];
							$_SESSION['addAdminMiddlename'] = $_POST['middlename'];
							if($_POST['gender'] = 'male'){
								$_SESSION['addAdminGender'] = 1;
							}
							elseif($_POST['gender'] = 'female'){
								$_SESSION['addAdminGender'] = 0;
							}
							$_SESSION['addAdminBirthdate'] = $_POST['birthdate'];
							$_SESSION['addAdminEmail'] = $_POST['email'];
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
					?>
				</div>
  			</div>
		</div>



		<?php
			if(isset($_POST['createAdmin'])){
				if ($_POST['password'] == $_POST['confirmPassword']){
					$username = $_POST['username'];
					$password = $_POST['password'];
					$confirmPassword = $_POST['confirmPassword'];
					if($_POST['gender'] == male){
						$gender = 1;
					}elseif($_POST['gender'] == female){
						$gender = 0;
					}
					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					$_SESSION['confirmPassword'] = $confirmPassword;
					$sqlUser = "SELECT username FROM account WHERE username = '$username'";
					$resultUser = $con->query($sqlUser);
					if ($resultUser->num_rows > 0) {
						echo "<script type='text/javascript'>alert('Username is already used. Please choose another.');</script>";
						$_SESSION['username'] = "";
					} else {
						mysqli_query($con, "INSERT INTO account (username, password, type)values('$_POST[username]', '$_POST[password]', 1)");
						$accountID = mysqli_insert_id($con);
						mysqli_query($con, "INSERT INTO personalinfo (accountID, firstName, middleName, lastName, gender, birthDate, email, contact)values('$accountID', '$_POST[firstname]', '$_POST[middlename]','$_POST[lastname]', '$gender', '$_POST[birthdate]', '$_POST[email]', '$_POST[contactno]')");
						$_SESSION['password'] = "";
						$_SESSION['confirmPassword'] = "";
						$_SESSION['username'] = "";
						//echo "<script type='text/javascript'>alert('Submitted successfully.');window.location='admin-adminAccounts.php';</script>";
						//include("page1.php");
					}
				}
				else{
					echo "<script type='text/javascript'>alert('Password does not match. Please try again.');</script>";
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['password'] = $_POST['password'];
					$_SESSION['confirmPassword'] = "";
				}
			}
		?>





















		<div id="viewModalConfirm" class="viewModalConfirm">
  			<div class="viewModalConfirm-content">
    			<span class="viewCloseConfirm">&times;</span>
    			<div class = "details">
	    			<div class = "infoDetails"><b>Enter password to update</b></div>
	    			<div class = "profilePicDivision">
	    				<form method="post">
	    					<div class = pword>
								<input type="password" name="password" placeholder="Enter your password here." required><br>
							</div>
	    					<div class = buttonSubmit>
	  							<button id = "updateInfo" type = "submit" class="updateInfo" name = "updateInfo"> UPDATE PROFILE </button>
	  						</div>
	    				</form>
					</div>
		  		</div>
		  	</div>
		</div>

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

				if ($_POST['userName'] == $_SESSION['adminUsername']){
				echo "
						<script type='text/javascript'>
							// Get the modal
						    var modal = document.getElementById('viewModalConfirm');

						    // Get the button that opens the modal
						    var btn = document.getElementById('update');

						    // Get the <span> element that closes the modal
						    var span = document.getElementsByClassName('viewCloseConfirm')[0];

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
					echo "
						<script type='text/javascript'>
							// Get the modal
						    var modal = document.getElementById('viewModalConfirm');

						    // Get the button that opens the modal
						    var btn = document.getElementById('update');

						    // Get the <span> element that closes the modal
						    var span = document.getElementsByClassName('viewCloseConfirm')[0];

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
				}
			}


			if(isset($_POST['updateInfo'])){
				$sqlCheckPass = "SELECT password from account WHERE accountID = '$_SESSION[adminAccountID]'";
				$resultCheckPass = $con->query($sqlCheckPass);

				if ($resultCheckPass->num_rows > 0) {
	    		// output data of each row
	    			while($rowCheckPass = $resultCheckPass->fetch_assoc()) {
	    				$adminPasswordCheck = $rowCheckPass["password"];
					}
				}
				echo ("<script>alert(". $_SESSION[adminAccountID] .");</script>");
				if($adminPasswordCheck == $_POST['password']){
					$sqlAccount = "UPDATE account
							SET username = '$_POST[userName]'
							WHERE accountID = '$_SESSION[adminAccountID]'";

					$sqlPersonalInfo = "UPDATE personalinfo
								SET firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]', gender = '$gender', birthDate = '$_POST[birthdate]', email = '$_POST[email]', contact = '$_POST[contactno]'
								WHERE accountID = '$_SESSION[adminAccountID]'";

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
					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='admin-profile.php';</script>";
					}
				else{
					echo "
					<script type='text/javascript'>
						// Get the modal
					    var modal = document.getElementById('viewModalConfirm');

					    // Get the button that opens the modal
					    var btn = document.getElementById('update');

					    // Get the <span> element that closes the modal
					    var span = document.getElementsByClassName('viewCloseConfirm')[0];

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
			}
		?>




































		<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminAccountID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-profileStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<style>
			.viewConfirmPass {
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
			.viewConfirmPass-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewConfirmPassClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewConfirmPassClose:hover,
			.viewConfirmPassClose:focus {
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
		</style>
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<div class = heading>
				<?php
					require("admin-header.php");
				?>
			</div>
		<div class = wrapper>
			<div class = content>

				<form method = "post">
				<div class = myProfile> 
					<center><b>MY PROFILE</b></center>
				</div>
				<div class = inputs>
					<div class = username>
						<b>Username: </b><br>
						<input type="text" name="userName" placeholder="Username" value = "<?php if(isset($_SESSION['adminUsername'])){echo $_SESSION['adminUsername'];} ?>" required><br>
					</div>
					<div class = fullName>
						<b>Name: </b><br>
						<input type="text" name="lastname" placeholder="Enter your last name here." value = "<?php if(isset($_SESSION['adminLastName'])){echo $_SESSION['adminLastName'];} ?>"required>
						<input type="text" name="firstname" placeholder="Enter your first name here." value = "<?php if(isset($_SESSION['adminFirstName'])){echo $_SESSION['adminFirstName'];} ?>"required><br>
						<input type="text" name="middlename" placeholder="Enter your middle name here." value = "<?php if(isset($_SESSION['adminMiddleName'])){echo $_SESSION['adminMiddleName'];} ?>"required>
					</div>
					<div class = gender>
						<b>Gender: </b><br>
						<?php
							if($_SESSION['adminGender'] == 1){
								echo "
								<input type='radio' name='gender' value='male' checked> Male
  								<input type='radio' name='gender' value='female'> Female
  								";
							} elseif($_SESSION['adminGender'] == 0){
								echo "
								<input type='radio' name='gender' value='male'> Male
  								<input type='radio' name='gender' value='female' checked> Female
  								";
							}
						?>
					</div>
					<div class = birthdate>
						<b>Birthdate: </b><br>
						<input type="date" name="birthdate" value = "<?php if(isset($_SESSION['adminBirthdate'])){echo $_SESSION['adminBirthdate'];} ?>" required><br>
					</div>
					<div class = email>
						<b>Email Address: </b><br>
						<input type="email" name="email" placeholder="Enter your email address here." value = "<?php if(isset($_SESSION['adminEmail'])){echo $_SESSION['adminEmail'];} ?>" required><br>
					</div>
					<div class = contactno>
						<b>Contact Number: </b><br>
						<input type="text" name="contactno" placeholder="Enter your contact number here." value = "<?php if(isset($_SESSION['adminContact'])){echo $_SESSION['adminContact'];} ?>" required><br>
					</div>
					<div class = buttonSubmit>
  						<button type = "submit" class="updateBig" name = update><b>UPDATE PROFILE</b></button>
  					</div>
				</div>
  			</form>
			</div>
		</div>
		<form method="post">
			<div id="viewConfirmPass" class="viewConfirmPass">
				<div class="viewConfirmPass-content">
					<span class="viewConfirmPassClose">&times;</span>
					<div class = "details">
						<div class = "titleDetails"><b>Update Profile</b></div>
						<div class = "confirmInputs">
							<div id = confirmPass class = confirmPass>
								<b>Confirm Password: </b><br>
								<input type="password" name="confirmPassword" placeholder="Enter password here..." required><br>
							</div>
							<div class = buttonSubmit>
	  							<button type = "submit" class="updateBig" name = "updateConfirm"> UPDATE </button>
	  						</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
			if(isset($_POST['update'])){
				/*$sqlCheckUser = "SELECT username from account WHERE username = '$_POST[userName]'";
				$resultCheckUser = $con->query($sqlCheckUser);

				if ($_POST['gender'] == 'male'){
				$gender = 1;
			}
			elseif ($_POST['gender'] == 'female'){
				$gender = 0;
			}

			if ($_POST['userName'] == $_SESSION['adminUsername']){
				$sqlPersonalInfo = "UPDATE personalinfo
							SET firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]', gender = '$gender', birthDate = '$_POST[birthdate]', email = '$_POST[email]', contact = '$_POST[contactno]'
							WHERE accountID = '$_SESSION[adminAccountID]'";

				if ($con->query($sqlPersonalInfo) === TRUE) {
				    echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='admin-profile.php';</script>";
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
					WHERE accountID = '$_SESSION[adminAccountID]'";

					$sqlPersonalInfo = "UPDATE personalinfo
								SET firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]', gender = '$gender', birthDate = '$_POST[birthdate]', email = '$_POST[email]', contact = '$_POST[contactno]'
								WHERE accountID = '$_SESSION[adminAccountID]'";

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
					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='admin-profile.php';</script>";
				}
  			}*/
			echo "	<script>
						// Get the modal
					    var modal = document.getElementById('viewConfirmPass');

					    // Get the button that opens the modal
					    var btn = document.getElementById('update');

					    // Get the <span> element that closes the modal
					    var span = document.getElementsByClassName('viewConfirmPassClose')[0];

					    // When the user clicks the button, open the modal 
					   modal.style.display = 'block';
					    // When the user clicks on <span> (x), close the modal
					    span.onclick = function() {
					        modal.style.display = 'none';
					        window.location.href='admin-profile.php';
					    }

					    // When the user clicks anywhere outside of the modal, close it
					    window.onclick = function(event) {
					        if (event.target == modal) {
					            modal.style.display = 'none';
					            window.location.href='profile.php';
					        }
					    }
					</script>";
			}

			if(isset($_POST['updateConfirm'])){
				
			}
  		?>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>





