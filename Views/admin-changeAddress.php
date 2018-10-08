<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-changeAddressStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-changeAddressModalStyles.css">
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
					Change Address
				</div>
				<div class = content>
					<form method = "post">
					<div class = inputs>
						<table class = "tableInputs">
							<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">House Number</td>
							    <td class = "tdInput"><input type="text" name="houseno" placeholder="Enter your house number here." value = "<?php if(isset($_SESSION['adminHouseNo'])){echo $_SESSION['adminHouseNo'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-), hash(#)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Street Number</td>
							    <td class = "tdInput"><input type="text" name="streetno" placeholder="Enter your street number here." value = "<?php if(isset($_SESSION['adminStreet'])){echo $_SESSION['adminStreet'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Barangay</td>
							    <td class = "tdInput"><input type="text" name="barangay" placeholder="Enter your barangay here." value = "<?php if(isset($_SESSION['adminBarangay'])){echo $_SESSION['adminBarangay'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">City</td>
							    <td class = "tdInput"><input type="text" name="city" placeholder="Enter your city here." value = "<?php if(isset($_SESSION['adminCity'])){echo $_SESSION['adminCity'];} ?>" pattern = "[a-zA-Z0-9._%+- ]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
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
						<div class = "titleDetails"><b>Update Address</b></div>
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
			$_SESSION['tempHouseno'] = $_POST['houseno'];
			$_SESSION['tempStreetno'] = $_POST['streetno'];
			$_SESSION['tempBarangay'] = $_POST['barangay'];
			$_SESSION['tempCity'] = $_POST['city'];

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
					        window.location.href='index?route=changeAddress';
					    }

					    // When the user clicks anywhere outside of the modal, close it
					    window.onclick = function(event) {
					        if (event.target == modal) {
					            modal.style.display = 'none';
					            window.location.href='index?route=changeAddress';
					        }
					    }
					</script>";
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
					$sqlAddress = "UPDATE address
									SET houseNo = :tempHouseno, street = :tempStreetno, barangay = :tempBarangay, city = :tempCity
									WHERE addressID = :adminAddressID";

					$stmt = $con->prepare($sqlAddress);
					$stmt->bindParam(':tempHouseno', $_SESSION['tempHouseno'], PDO::PARAM_STR);
					$stmt->bindParam(':tempStreetno', $_SESSION['tempStreetno'], PDO::PARAM_STR);
					$stmt->bindParam(':tempBarangay', $_SESSION['tempBarangay'], PDO::PARAM_STR);
					$stmt->bindParam(':tempCity', $_SESSION['tempCity'], PDO::PARAM_STR);
					$stmt->bindParam(':adminAddressID', $_SESSION['adminAddressID'], PDO::PARAM_STR);
					$stmt->execute();

					unset($_SESSION['tempHouseno']);
					unset($_SESSION['tempStreetno']);
					unset($_SESSION['tempBarangay']);
					unset($_SESSION['tempCity']);
				    echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=changeAddress';</script>";
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