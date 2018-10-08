<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-changePasswordStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
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
					Change Account Password
				</div>
				<div class = content>
					<form method = "post">
					<div class = inputs>
						<table class = "tableInputs">
							<col width="190">
							<tr class = "trInputs">
							    <td class = "tdName">Old Password</td>
							    <td class = "tdInput"><input type="password" name="oldPword" placeholder="Enter old password here." pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">New Password</td>
							    <td class = "tdInput"><input type="password" name="newPword" placeholder="Enter new password here." pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Confirm New Password</td>
							    <td class = "tdInput"><input type="password" name="confirmNewPword" placeholder="Confirm new password here." pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)." required></td>
							</tr>
						</table>
						<div class = buttonSubmit>
	  						<button type = "submit" class="updateBig" name = changePass><b>CHANGE PASSWORD</b></button>
	  					</div>
					</div>
	  			</form>
	  			<?php
	  				if(isset($_POST['changePass'])){

	  					$sqlCheckPass = "SELECT password from users WHERE userID = (:adminUserID) AND password = (:oldPword)";

	  					$stmt = $con->prepare($sqlCheckPass);
						$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_INT);
						$stmt->bindParam(':oldPword', $_POST['oldPword'], PDO::PARAM_STR);
						$stmt->execute();
						$rowCount = $stmt->rowCount();

	  					if ($rowCount > 0) {
	  						if($_POST['newPword'] == $_POST['confirmNewPword']){
	  							if($_POST['newPword'] == $_POST['oldPword']){
	  								echo "<script type='text/javascript'>alert('Old password and new password are the same. Please use another password.');</script>";
	  							}else{
	  								$sqlPassword = "UPDATE users
										SET password = :newPword
										WHERE userID = :adminUserID";

										$stmt = $con->prepare($sqlPassword);
										$stmt->bindParam(':newPword', $_POST['newPword'], PDO::PARAM_STR);
										$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_STR);
										$stmt->execute();

										echo "<script type='text/javascript'>alert('Updated successfully.');</script>";

	  							}
	  						}else{
	  							echo "<script type='text/javascript'>alert('New password and confirm password does not match.');</script>";
	  						}
	  					}
	  					else{
	  						echo "<script type='text/javascript'>alert('Wrong password.');</script>";
	  					}
	  				}
	  			?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>