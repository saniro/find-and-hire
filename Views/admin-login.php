<?php
	require("sessionStart.php");
	if(!isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<!-- <link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css"> -->
		<link rel="stylesheet" type="text/css" href="Styles/admin-loginStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<?php
			require("connection.php");
		?>
		<div class = wrapper>
			<div class = content>
				<form method = "post">
				<div class = hometitle> 
					<center><b>Welcome</b></center>
				</div>
				<div class = inputs>
					<div class = username>
						<b>Email: </b><br>
						<input type="email" name="email" placeholder="Email" required><br>
					</div>
					<div class = password>
						<b>Password: </b><br>
						<input type="password" name="passWord" placeholder="Password" required><br>
					</div>
					<div class = buttonSubmit>
  						<button type = "submit" class="loginBtn" name = "login">Log In</button>
  					</div>
				</div>
  			</form>
			</div>
			<?php
				if(isset($_POST['login'])){
					$rowCount = 0;
					$emailAdminLog = emptyCheck($_POST['email'], $loginErrors, "Email", "true");
					$passwordAdminLog = emptyCheck($_POST['passWord'], $loginErrors, "Password", "true");
					if(count($loginErrors) == 0){
						$sqlAdminLog = "SELECT userID, firstName, middleName, lastName FROM users WHERE email = (:emailAdminLog) AND password = (:passwordAdminLog) AND type = 0 AND flag = 1";
						#$pdo = ($host , $user, $dbname, $pass) 
						$stmt = $con->prepare($sqlAdminLog);
						$stmt->bindParam(':emailAdminLog', $emailAdminLog, PDO::PARAM_STR);
						$stmt->bindParam(':passwordAdminLog', $passwordAdminLog, PDO::PARAM_STR);
						$stmt->execute();
						$results = $stmt->fetchAll();
						#get affected rows
						#$rowCount = $stmt->rowCount();
						foreach($results as $rowAdminLog){
							$_SESSION['adminUserID'] = $rowAdminLog["userID"];
							$adminFirstname = $rowAdminLog["firstName"];
							$adminMiddlename = $rowAdminLog["middleName"];
							$adminLastname = $rowAdminLog["lastName"];

							$dateToday = date('Y-m-d h:i:sa');
	        				$sqllastLog = "UPDATE users
											SET lastLogin = '$dateToday'
											WHERE userID = '$_SESSION[adminUserID]'";
							if ($con->query($sqllastLog) === TRUE) {
							}
							echo "<script type='text/javascript'>alert('Welcome " . $adminFirstname . " " . $adminMiddlename . " " . $adminLastname . ".');window.location.href='index?route=dashboard';</script>";
						}
						if($rowCount == 0){
							echo "<script type='text/javascript'>alert('Username and password does not match.');</script>";
						}
					}
					else {
						echo "<script>alert('";
						foreach ($loginErrors as $error) {
							echo $error.'\n';
						}
						echo $emailAdminLog."')</script>";
					
					}
				}
			?>
		</div>
	</body>
</html>
<?php
	}
	else
		echo "<script type='text/javascript'>window.location.href='index?route=dashboard';</script>";
?>