<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-profileStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-profileModalStyles.css">

	</head>
	<body>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<div>
			<div class = "sideNavigation">
				<?php
					require("admin-sidebar.php");
				?>
			</div>
			<div class = wrapper>
				<div class = myProfile> 
					Change Profile
				</div>
				<div class = content>
					<form method = "post">
					<div class = inputs>
						<table class = "tableInputs">
							<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Lastname</td>
							    <td class = "tdInput"><input type="text" name="lastname" placeholder="Enter your last name here." value = "<?php if(isset($_SESSION['adminLastName'])){echo $_SESSION['adminLastName'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Firstname</td>
							    <td class = "tdInput"><input type="text" name="firstname" placeholder="Enter your first name here." value = "<?php if(isset($_SESSION['adminFirstName'])){echo $_SESSION['adminFirstName'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Middlename</td>
							    <td class = "tdInput"><input type="text" name="middlename" placeholder="Enter your middle name here." value = "<?php if(isset($_SESSION['adminMiddleName'])){echo $_SESSION['adminMiddleName'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Gender</td>
							    <td class = "tdInput">
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
								</td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Birthdate</td>
							    <td class = "tdInput"><input type="date" name="birthdate" value = "<?php if(isset($_SESSION['adminBirthdate'])){echo $_SESSION['adminBirthdate'];} ?>" required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Email Address</td>
							    <td class = "tdInput"><input type="email" name="email" placeholder="Enter your email address here." value = "<?php if(isset($_SESSION['adminEmail'])){echo $_SESSION['adminEmail'];} ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "Must only contain letters, numbers, and ._%+-. Format e.g. aaa@gmail.com." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Contact Number</td>
							    <td class = "tdInput"><input type="text" name="contactno" placeholder="Enter your contact number here." value = "<?php if(isset($_SESSION['adminContact'])){echo $_SESSION['adminContact'];} ?>" pattern = "[0-9]{11,11}" title="Must only contain numbers, and must be 11 digits."required></td>
							</tr>
						</table>
						<div class = buttonSubmit>
	  						<button type = "submit" class="updateBig" name = update><b> NEXT </b></button>
	  					</div>
					</div>
	  			</form>
				</div>
			</div>
		</div>
		<form method="post">
			<div id="viewConfirmPass" class="viewConfirmPass">
				<div class="viewConfirmPass-content">
					<span class="viewConfirmPassClose">&times;</span>
					<div class = "details">
						<div class = "titleDetails"><b>Update Profile</b></div>
						<table class = "tableInputs">
							<col width="160">
							<tr class = "trInputs">
							    <td class = "tdName">Confirm Password</td>
							    <td class = "tdInput"><input type="password" name="confirmPassword" placeholder="Enter password here..." required></td>
							</tr>
						</table>
						<div class = buttonSubmit>
	  						<button type = "submit" class="updateBig" name = "updateConfirm"> UPDATE </button>
	  					</div>
					</div>
				</div>
			</div>
		</form>
		<?php
			if(isset($_POST['update'])){
				$sqlCheckEmail = "SELECT email from users WHERE email = (:email)";

				$stmt = $con->prepare($sqlCheckEmail);
				$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
				$stmt->execute();
				$rowCount = $stmt->rowCount();

				if ($_POST['gender'] == 'male'){
					$gender = 1;
				}
				elseif ($_POST['gender'] == 'female'){
					$gender = 0;
				}
				$_SESSION['tempLastname'] = $_POST['lastname'];
				$_SESSION['tempFirstname'] = $_POST['firstname'];
				$_SESSION['tempMiddlename'] = $_POST['middlename'];
				$_SESSION['tempGender'] = $gender;
				$_SESSION['tempBirthdate'] = $_POST['birthdate'];
				$_SESSION['tempEmail'] = $_POST['email'];
				$_SESSION['tempContactno'] = $_POST['contactno'];

				if ($_POST['email'] == $_SESSION['adminEmail']){
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
							        window.location.href='index?route=profile';
							    }

							    // When the user clicks anywhere outside of the modal, close it
							    window.onclick = function(event) {
							        if (event.target == modal) {
							            modal.style.display = 'none';
							            window.location.href='index?route=profile';
							        }
							    }
							</script>";
				}
				else{
					if ($rowCount > 0) {
						echo "<script type='text/javascript'>alert('Email is already used. Please choose another.');</script>";
					}
					else{
						if ($_POST['gender'] == 'male'){
							$gender = 1;
						}
						elseif ($_POST['gender'] == 'female'){
							$gender = 0;
						}
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
								        window.location.href='index?route=profile';
								    }

								    // When the user clicks anywhere outside of the modal, close it
								    window.onclick = function(event) {
								        if (event.target == modal) {
								            modal.style.display = 'none';
								            window.location.href='index?route=profile';
								        }
								    }
								</script>";
					}
	  			}
			}

			if(isset($_POST['updateConfirm'])){
				$sqlPassword = "SELECT password FROM users WHERE userID = (:adminUserID)";

				$stmt = $con->prepare($sqlPassword);
				$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_INT);
				$stmt->execute();
				$rowPassword = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				if ($rowCount > 0) {
					$confirmPassword = $rowPassword["password"];
				}
				else {
					$confirmPassword = "";
				}

				if($confirmPassword == $_POST['confirmPassword']){

					$sqlPersonalInfo = "UPDATE users
										SET firstName = :tempFirstname, middleName = :tempMiddlename, lastName = :tempLastname, gender = :tempGender, birthDate = :tempBirthdate, email = :tempEmail, contact = :tempContactno
										WHERE userID = :adminUserID";

					$stmt = $con->prepare($sqlPersonalInfo);
					$stmt->bindParam(':tempFirstname', $_SESSION['tempFirstname'], PDO::PARAM_STR);
					$stmt->bindParam(':tempMiddlename', $_SESSION['tempMiddlename'], PDO::PARAM_STR);
					$stmt->bindParam(':tempLastname', $_SESSION['tempLastname'], PDO::PARAM_STR);
					$stmt->bindParam(':tempGender', $_SESSION['tempGender'], PDO::PARAM_INT);
					$stmt->bindParam(':tempBirthdate', $_SESSION['tempBirthdate'], PDO::PARAM_STR);
					$stmt->bindParam(':tempEmail', $_SESSION['tempEmail'], PDO::PARAM_STR);
					$stmt->bindParam(':tempContactno', $_SESSION['tempContactno'], PDO::PARAM_STR);
					$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_INT);
					$stmt->execute();

						//$sqlProf = "UPDATE account AS AC, personalinfo AS PI, address AS AD 
									//SET AC.username = '$_POST[userName]', firstName = '$_POST[firstname]', middleName = '$_POST[middlename]', lastName = '$_POST[lastname]'
									//WHERE (accountID = '$_SESSION[accountID]') AND (AC.accountID = PI.accountID) AND (PI.addressID = AD.addressID)";
					unset($_SESSION['tempLastname']);
					unset($_SESSION['tempFirstname']);
					unset($_SESSION['tempMiddlename']);
					unset($_SESSION['tempGender']);
					unset($_SESSION['tempBirthdate']);
					unset($_SESSION['tempEmail']);
					unset($_SESSION['tempContactno']);

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=profile';</script>";
				}
				else{
					echo "	<script>
								alert('Wrong Password.');

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
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>