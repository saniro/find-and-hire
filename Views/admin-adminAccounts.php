<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-adminAccountsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-adminAccountsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkAdmin'] = "";
			}
		?>
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
				<div class= "title"> ACCOUNTS - Admin </div>

				<table id = actionTable>
					<thead class = "actions">
						<col width = 620>	
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
	      										<a href="index?route=adminAccounts&sort=userID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=adminAccounts&sort=adminName<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=adminAccounts&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	    									</div>
	  									</li>
	  									
									</ul>
								</div>
							</th>
							<th colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=adminAccounts"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'userID'){
														echo " selected ";
													}
												}
											?>value="userID">ID</option>
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
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "index?route=adminAccounts">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				
				<table id = userTable>
					<col width = "50">
					<col width = "390">
					<col width = "400">
					<thead>
						<tr>
							<th> ID </th>
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
						        			echo "<button id = 'deacBtn' class = 'deactivate' onclick='viewYesNo()'>Deactivate</button>";
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
								echo "<td colspan = 4> No results. </td>";
							}
						?>
					</tbody>
				</table>
			</div>

		</div>
		<div id="viewModal" class="viewModal">
  			<div class="viewModal-content">
    			<span class="viewClose">&times;</span>
    				<div class = "details">
    				<div class = "titleDetails"><b>View Full Details</b></div>
    				<div class = "profilePicDivision">
    					<?php
    						if(isset($_GET['userID'])){
    							$sqlInfoModal = "SELECT userID, firstName, middleName, lastName, houseNo, street, barangay, city, gender, DATE_FORMAT(birthDate,'%b %d, %Y') AS birthDate, email, contact FROM users AS US, address AS AD WHERE (US.addressID = AD.addressID) AND userID = (:userID)";

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
    				<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Admin Number</td>
						    <td class = "tdInput"><?php echo $modalInfoAccountID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalInfoLastName . ", " . $modalInfoFirstName . " " . $modalInfoMiddleName; ?></td>
						</tr>
					  	<tr class = "trInputs">
						    <td class = "tdName">Address</td>
						    <td class = "tdInput"><?php echo $modalInfoHouseNo . ", " . $modalInfoStreet . ", " . $modalInfoBarangay . ", " . $modalInfoCity;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalInfoGender;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Birthdate</td>
						    <td class = "tdInput"><?php echo $modalInfoBirthdate;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email Address</td>
						    <td class = "tdInput"><?php echo $modalInfoEmail;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact Number</td>
						    <td class = "tdInput"><?php echo $modalInfoContact;?></td>
						</tr>
					</table>
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
	    					<table class = "tableYesNo">
	    						<col width = "120">
		    					<thead class = "tableYesNoHead">
									<tr>
										<th> Admin No. </th>
										<th> Name </th>
									</tr>				
								</thead>
		    					<tbody>
									<?php
										$sqlYesNo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users WHERE userID = (:userID)";
										
										$stmt = $con->prepare($sqlYesNo);
										$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
										$stmt->execute();
										$rowYesNoModal = $stmt->fetch();

										$modalYesNoUserID = $rowYesNoModal["userID"];
										$modalInfoAccountID = $rowYesNoModal["name"];
						    		?>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoUserID; ?></td>
							        	<td><?php echo $modalInfoAccountID; ?></td>
							        </tr>
								</tbody>
							</table>
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
							echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=adminAccounts&sort=".$_SESSION['linkAdmin'].$optionSearch.$search."';</script>";
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
						<div class="tab">
						  <button class="tablinks" onclick="tabAddAdmin(event, 'adminInfo')">Info</button>
						  <button class="tablinks" onclick="tabAddAdmin(event, 'adminAddress')">Address</button>
						  <button class="tablinks" onclick="tabAddAdmin(event, 'adminUserPassword')">Email And Password</button>
						</div>
						<div id="adminInfo" class="tabcontent active">
							<table class = "tableInputs">
		    					<col width="170">
								<tr class = "trInputs">
								    <td class = "tdName">Last Name</td>
								    <td class = "tdInput"> <input type="text" name="lastname" placeholder="Enter your last name here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">First Name</td>
								    <td class = "tdInput"><input type="text" name="firstname" placeholder="Enter your first name here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
							  	<tr class = "trInputs">
								    <td class = "tdName">Middle Name</td>
								    <td class = "tdInput"><input type="text" name="middlename" placeholder="Enter your middle name here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Gender</td>
								    <td class = "tdInput"><input type='radio' name='gender' value='male' checked> Male
								        <input type='radio' name='gender' value='female'> Female</td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Birthdate</td>
								    <td class = "tdInput"><input type="date" name="birthdate" required></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact Number</td>
								    <td class = "tdInput"><input type="text" name="contactno" placeholder="Enter your contact number here." minlength = "11" maxlength = "11" required pattern = "[0-9]{11,11}" title="Must only contain numbers, and must be 11 digits."></td>
								</tr>
							</table>
						</div>

						<div id="adminAddress" class="tabcontent">
							<table class = "tableInputs">
		    					<col width="170">
								<tr class = "trInputs">
								    <td class = "tdName">House Number</td>
								    <td class = "tdInput"><input type="text" name="houseno" placeholder="Enter your house number here." required pattern = "[a-zA-Z0-9#._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Street Number</td>
								    <td class = "tdInput"><input type="text" name="streetno" placeholder="Enter your street number here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Barangay</td>
								    <td class = "tdInput"><input type="text" name="barangay" placeholder="Enter your barangay here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">City</td>
								    <td class = "tdInput"><input type="text" name="city" placeholder="Enter your city here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
							</table>
						</div>

						<div id="adminUserPassword" class="tabcontent">
							<table class = "tableInputs">
		    					<col width="170">
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><input type="email" name="email" placeholder="Enter your email address here." required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "Must only contain letters, numbers, and ._%+-. Format e.g. aaa@gmail.com."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Password</td>
								    <td class = "tdInput"><input type="password" name="password" placeholder="Enter your password here." required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Confirm Password</td>
								    <td class = "tdInput"><input type="password" name="confirmPassword" placeholder="Enter your password here again." value = "<?php if(isset($_SESSION['addAdminConfirmPassword'])){echo $_SESSION['addAdminConfirmPassword']; } ?>" required pattern = "[a-zA-Z0-9._%+-]{0,}" title="Must only contain letters, numbers, period(.), underscore(_), percentage(%), plus(+), minus(-)."></td>
								</tr>
							</table>
						    <div class = buttonSubmit>
						        <button id = "createAdmin" type = "submit" class="addSubmit" name = "createAdmin"> CREATE </button>
						    </div>
						  </div>
					</form>
				</div>
  			</div>
		</div>
		<?php

			if(isset($_POST['createAdmin'])){
				if ($_POST['password'] == $_POST['confirmPassword']){
					$email = $_POST['email'];
					$password = $_POST['password'];
					$confirmPassword = $_POST['confirmPassword'];

					$addAdminLastname = $_POST['lastname'];
					$addAdminFirstname = $_POST['firstname'];
					$addAdminMiddlename = $_POST['middlename'];
					if($_POST['gender'] == 'male'){
						$addAdminGender = 1;
					}
					elseif($_POST['gender'] == 'female'){
						$addAdminGender = 0;
					}
					$addAdminBirthdate = $_POST['birthdate'];
					$addAdminContactno = $_POST['contactno'];

					$addAdminHouseno = $_POST['houseno'];
					$addAdminStreetno = $_POST['streetno'];
					$addAdminBarangay = $_POST['barangay'];
					$addAdminCity = $_POST['city'];

					$addAdminEmail = $email;
					$addAdminPassword = $password;
					$addAdminConfirmPassword = $confirmPassword;

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
						$stmt->bindParam(':addAdminHouseno', $addAdminHouseno, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminStreetno', $addAdminStreetno, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminBarangay', $addAdminBarangay, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminCity', $addAdminCity, PDO::PARAM_STR);
						$stmt->execute();
						$addressID = $con->lastInsertId();

						$sqlCreateAdminUser = "INSERT INTO users (firstName, middleName, lastName, addressID, gender, birthDate, email, contact, password, type)values(:addAdminFirstname, :addAdminMiddlename, :addAdminLastname, :addressID, :addAdminGender, :addAdminBirthdate, :addAdminEmail, :addAdminContactno, :addAdminPassword, 0)";

						$stmt = $con->prepare($sqlCreateAdminUser);
						$stmt->bindParam(':addAdminFirstname', $addAdminFirstname, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminMiddlename', $addAdminMiddlename, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminLastname', $addAdminLastname, PDO::PARAM_STR);
						$stmt->bindParam(':addressID', $addressID, PDO::PARAM_INT);
						$stmt->bindParam(':addAdminGender', $addAdminGender, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminBirthdate', $addAdminBirthdate, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminEmail', $addAdminEmail, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminContactno', $addAdminContactno, PDO::PARAM_STR);
						$stmt->bindParam(':addAdminPassword', $addAdminPassword, PDO::PARAM_STR);
						$stmt->execute();

						$_SESSION['addAdminPassword'] = "";
						$_SESSION['addAdminConfirmPassword'] = "";
						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=adminAccounts&sort=".$_SESSION['linkAdmin'].$optionSearch.$search."';</script>";
						//include("page1.php");
					}
				}
				else{
					echo "	<script type='text/javascript'>
								alert('Password does not match. Please try again.');
								// Get the modal
							    var modal = document.getElementById('viewAddInfoModal');

							    // Get the <span> element that closes the modal
							    var span = document.getElementsByClassName('viewAddInfoClose')[0];

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
			function tabAddAdmin(modal, modalName) {
			    var i, tabcontent, tablinks;
			    tabcontent = document.getElementsByClassName("tabcontent");
			    for (i = 0; i < tabcontent.length; i++) {
			        tabcontent[i].style.display = "none";
			    }
			    tablinks = document.getElementsByClassName("tablinks");
			    for (i = 0; i < tablinks.length; i++) {
			        tablinks[i].className = tablinks[i].className.replace(" active", "");
			    }
			    document.getElementById(modalName).style.display = "block";
			    modal.currentTarget.className += " active";
			}

			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkAdmin'])){ echo '&sort=' . $_SESSION['linkAdmin'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=adminAccounts&userID="+userID+linkSort+search+optionSearch
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
							var url = "index?route=adminAccounts&userID="+userID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}
					}
				}
			}

			function viewAddInfoModal(){
				var linkSort = '<?php if(isset($_SESSION['linkAdmin'])){echo '&sort=' . $_SESSION['linkAdmin'];}?>'
				var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
				var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
				var url = "index?route=adminAccounts"+linkSort+search+optionSearch
				location.replace(url);
				localStorage.setItem('viewAddInfoModal',true);
				var top = window.scrollY;
				localStorage.setItem('y',top);	
			}

			// function viewAddInfoModal(){
			//     // Get the modal
			//     var modalInfo = document.getElementById('viewAddInfoModal');

			//     // Get the button that opens the modal
			//     var btn = document.getElementById('addAdmin');

			//     // Get the <span> element that closes the modal
			//     var span = document.getElementsByClassName("viewAddInfoClose")[0];

			//     // When the user clicks the button, open the modal 
			//   	modalInfo.style.display = "block";
			//     // When the user clicks on <span> (x), close the modal
			//    	span.onclick = function() {
			//         modalInfo.style.display = "none";
			//     }

			//     // When the user clicks anywhere outside of the modal, close it
			//     window.onclick = function(event) {
			//         if (event.target == modalInfo) {
			//            modalInfo.style.display = "none";
			//         }
			//     }
			// }

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

				var addInfo = localStorage.getItem('viewAddInfoModal');
				if (addInfo == 'true'){
					document.getElementById('viewAddInfoModal').style.display = "block";
				}
				localStorage.setItem('viewAddInfoModal',false)

			}

			var spanView = document.getElementsByClassName("viewClose")[0];
			spanView.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			var spanAdd = document.getElementsByClassName("viewAddInfoClose")[0];
			spanAdd.onclick = function() {
				document.getElementById('viewAddInfoModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewAddInfoModal')) {
		            document.getElementById('viewAddInfoModal').style.display = "none";
		        }
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