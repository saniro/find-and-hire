<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerAccountsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerAccountsModalStyles.css">

		<script class="jsbin" src="Libraries/jquery/jquery.min.js"></script>
		<script class="jsbin" src="Libraries/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript">
			function validateDate(){
				var date = document.getElementById("expirationDate").value;
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();

				if(dd<10) {
				    dd = '0'+dd
				} 

				if(mm<10) {
				    mm = '0'+mm
				} 

				today = yyyy + '-' + mm + '-' + dd;
				if(date <= today){
					alert('Expiration date already passed.');
					return false;
				}
				else{
					return true;
				}
			}
		</script>
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkCustomer'] = "";
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
				<div class= "title"> ACCOUNTS - Customer </div>

				<table id = actionTable>
					<col width = "590">
					<thead class = "actions">	
						<tr>
							<th colspan="2">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
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
	      										<a href="index?route=customerAccounts&sort=userID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=customerAccounts&sort=customerName<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=customerAccounts&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=customerAccounts&sort=reportNo<?php echo $optionSearch . $search; ?>">Reports</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=customerAccounts"><img class = "resetAction" src="Resources/reset.png"></a>
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
													if($_GET['optionSearch'] == 'customerName'){
														echo " selected ";
													}
												}
											?>
											value="customerName">Name</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'email'){
														echo " selected ";
													}
												}
											?>
											value="email">Email Address</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportNo'){
														echo " selected ";
													}
												}
											?>
											value="reportNo">Report No</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type="hidden" name = "route" value = "customerAccounts">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				
				<table id = userTable>
					<col width = "50">
					<col width = "320">
					<col width = "320">
					<col width = "95">
					<col width = "75">
					<thead>
						<tr>
							<th> ID </th>
							<th class = name> Name </th>
							<th> Email Address </th>
							<th> Accused </th>
							<th> Rating </th>
							<th colspan = 3> Action </th>
						</tr>			
					</thead>
					<tbody>
						<?php
							$sqlInfo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS customerName, addressID, gender, DATE_FORMAT(birthDate,'%b %d, %Y') AS birthDate, email, contact, (SELECT count(reportsID) FROM reports WHERE reportedID = userID) AS reportNo, verifiedFlag, flag FROM users WHERE type = 1";

							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
								if($_GET['optionSearch'] == 'userID'){
									$optionSearchVar = "userID";
								}
								elseif($_GET['optionSearch'] == 'customerName'){
									$optionSearchVar = "concat(lastName, ', ', firstName, ' ', middleName)";
								}
								elseif($_GET['optionSearch'] == 'email'){
									$optionSearchVar = "email";
								}
								elseif($_GET['optionSearch'] == 'reportNo'){
									$optionSearchVar = "(SELECT count(reportsID) FROM reports WHERE reportedID = userID)";
								}

		    					$sqlInfo .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'userID'){
		    						$sqlInfo .= " ORDER BY userID";
		    						$_SESSION['linkCustomer'] = "userID";
								}
								elseif ($_GET['sort'] == 'customerName')
								{
								    $sqlInfo .= " ORDER BY customerName";
								    $_SESSION['linkCustomer'] = "customerName";
								}
								elseif ($_GET['sort'] == 'email')
								{
								    $sqlInfo .= " ORDER BY email";
								    $_SESSION['linkCustomer'] = "email";
								}
								elseif ($_GET['sort'] == 'reportNo')
								{
								    $sqlInfo .= " ORDER BY reportNo DESC";
								    $_SESSION['linkCustomer'] = "reportNo";
								}
							}
							$stmt = $con->prepare($sqlInfo);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowInfo){
								$infoUserID = $rowInfo["userID"];
			    				$infoCustomerName = $rowInfo["customerName"];
			    				$infoEmail = $rowInfo["email"];
			    				$infoReportNo = $rowInfo["reportNo"];
			    				$infoVerifiedFlag = $rowInfo["verifiedFlag"];
			    				$infoFlag = $rowInfo["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $infoUserID; ?></td>
						        	<td><?php echo $infoCustomerName; ?></td>
						        	<td class = "max word-wrap"><?php echo $infoEmail; ?></td>
						        	<td><?php echo $infoReportNo; ?></td>
						        	<?php
						        		$sqlRating = "SELECT (SELECT (SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = FK.transactID) AS customer, transactID, userID, (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = FK.userID) AS rater, ROUND(AVG(rating),2) AS rating FROM feedback AS FK WHERE (SELECT Type FROM users AS US WHERE US.userID = FK.userID) = 2 AND (SELECT (SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = FK.transactID) = :customerID GROUP BY (SELECT (SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = FK.transactID)";
						        		$stmt = $con->prepare($sqlRating);
						        		$stmt->bindParam(':customerID', $infoUserID, PDO::PARAM_INT);
						        		$stmt->execute();
						        		$rowRating = $stmt->fetch();
						        		$infoRating = $rowRating['rating'];
						        	?>
						        	<td><?php echo $infoRating;?></td>
						        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
						        	<td>
						        		<?php
						        			if ($infoVerifiedFlag == 1){
						        			echo "<button id='viewBtn' class = 'verified' onclick = 'verify()'>Verified</button>";
						        			}
						        			else{
						        			echo "<button id='viewBtn' class = 'notVerified' onclick = 'verify()'>Verify</button>";
						        			}
						        		?>
						        	</td>
						        	<td>
						        		<?php
						        			if ($infoFlag == 1){
						        			echo "<button id = 'deacBtn' class = 'deactivate' onclick='viewYesNo()'>Deactivate</button>";
						        			}
						        			else{
						        				$sqlCheckCanBeActivate = "
													SELECT count(requirementID) AS reqCount 
													FROM requirements 
													WHERE userID = (:infoUserID) AND submitted = 1";
												$stmt = $con->prepare($sqlCheckCanBeActivate);
												$stmt->bindParam(':infoUserID', $infoUserID, PDO::PARAM_INT);
												$stmt->execute();
												$rowCheckCanBeActivate = $stmt->fetch();
												if($rowCheckCanBeActivate['reqCount'] == 0){
													echo "<button id = 'actBtn' class = 'viewDisabled' disabled>Activate</button>";
												}
												else{
													echo "<button id = 'actBtn' class = 'view' onclick='viewYesNo()'>Activate</button>";
												}
						        			}
						        		?>
						        	</td>
						        </tr>
							 <?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = 5> No results. </td>";
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
    							$sqlInfoModal = "SELECT userID, 
    							profilepicture, 
    							concat (lastName, ', ', firstName, ' ', middleName) AS name,
    							concat(houseNo, ', ', street, ', ', barangay, ', ', city) AS address, 
    							(CASE WHEN gender = 1 THEN 'Male' WHEN gender = 0 THEN 'Female' END) AS gender, DATE_FORMAT(birthDate,'%b %d, %Y') AS birthDate, email, contact, (SELECT count(reportsID) FROM reports WHERE reportedID = userID) AS reportNo FROM users AS US, address AS AD WHERE (US.addressID = AD.addressID) AND userID = (:userID)";

    							$stmt = $con->prepare($sqlInfoModal);
								$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowInfoModal = $stmt->fetch();
								
								$modalInfoUserID = $rowInfoModal["userID"];
			    				if (empty($rowInfoModal["profilepicture"])){
			    					$modalInfoProfilePicture = "ProfilePictures/userIcon.png";
			    				}
			    				else{
			    					$modalInfoProfilePicture = $rowInfoModal["profilepicture"];
			    				}
			    				$modalInfoName = $rowInfoModal["name"];
			    				$modalInfoAddress = $rowInfoModal["address"];
			    				$modalInfoGender = $rowInfoModal["gender"];
			    				$modalInfoBirthdate = $rowInfoModal["birthDate"];
			    				$modalInfoEmail = $rowInfoModal["email"];
			    				$modalInfoContact = $rowInfoModal["contact"];
			    				$modalInfoReportNo = $rowInfoModal["reportNo"];
						}
    					?>
    					<div class = "profilePicContent">
    						<img class = "profilePic" src="<?php echo $modalInfoProfilePicture;?>">
    					</div>
    				</div>
    				<table class = "tableInputs">
						<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">User Number</td>
						    <td class = "tdInput"><?php echo $modalInfoUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalInfoName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Accused Times</td>
						    <td class = "tdInput"><?php echo $modalInfoReportNo; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Address</td>
						    <td class = "tdInput"><?php echo $modalInfoAddress;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalInfoGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Birthdate</td>
						    <td class = "tdInput"><?php echo $modalInfoBirthdate; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email Address</td>
						    <td class = "tdInput max"><?php echo $modalInfoEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact Number</td>
						    <td class = "tdInput"><?php echo $modalInfoContact; ?></td>
						</tr>
					</table>
					<div class = "transactionAndReportBtn">
						<button name = "viewTransactionBtn" class = "viewTransactionBtn" onclick="viewTransactionBtn()">View Transactions</button><button name = "viewReportBtn" class = "viewReportBtn" onclick="viewReportsBtn()">View Complaints</button>
					</div>
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
    						<col width = "50">
	    					<thead class = "tableYesNoHead">
								<tr>
									<th> ID </th>
									<th> Name </th>
								</tr>				
							</thead>
	    					<tbody>
								<?php
								if(isset($_GET['userID'])&&($_GET['type']=="yesno")){
									$sqlYesNo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name, Flag FROM users WHERE userID = (:userID)";
									
									$stmt = $con->prepare($sqlYesNo);
									$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
									$stmt->execute();
									$rowYesNoModal = $stmt->fetch();

									$modalYesNoUserID = $rowYesNoModal["userID"];
									$modalInfoAccountID = $rowYesNoModal["name"];
									$modalYesNoFlag	= $rowYesNoModal["Flag"];

								}
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
					if(isset($_POST['Yes'])&&($_GET['type']=="yesno")){
						if($modalYesNoFlag == 1){
							$sqlActiveModal = "UPDATE users
												SET Flag = 0
												WHERE userID = (:modalYesNoUserID);";
							$stmt = $con->prepare($sqlActiveModal);
							$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
							$stmt->execute();
						}
						elseif($modalYesNoFlag == 0){
							$sqlDeactiveModal = "UPDATE users
												SET Flag = 1
												WHERE userID = (:modalYesNoUserID);";
							$stmt = $con->prepare($sqlDeactiveModal);
							$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
							$stmt->execute();
						}
						echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=customerAccounts&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
					}
				?>
  			</div>
		</div>
	</div>
	<div id="viewModalVerify" class="viewModalVerify">
  			<div class="viewModalVerify-content">
    			<span class="viewCloseVerify">&times;</span>
    			<div class = "details">
    			<div class = "infoDetails"><b>Verify</b></div>
    			<div class = "profilePicDivision">
    				<?php
    					if((isset($_GET['userID']))&&($_GET['type']=="verify")){
    						$sqlVerify = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name, gender, birthdate, email, verifiedFlag FROM  users WHERE type = 1 AND userID = (:userID)";

	    					$stmt = $con->prepare($sqlVerify);
							$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowVerify = $stmt->fetch();

							$modalVerifyUserID = $rowVerify["userID"];
							$modalVerifyName = $rowVerify["name"];
							if($rowVerify["gender"] == 1){
								$modalVerifyGender = "Male";
							}
							elseif($rowVerify["gender"] == 0){
								$modalVerifyGender = "Female";
							}
							$modalVerifyBirthdate = $rowVerify["birthdate"];
							$modalVerifyEmail = $rowVerify["email"];
			    			$modalVerifyVerifiedFlag = $rowVerify["verifiedFlag"];
						}
    				?>
    					<table class = "tableYesNo">
    						<col width = "50">
    						<col width = "240">
    						<col width = "130">
    						<col width = "130">
	    					<thead class = "tableYesNoHead">
								<tr>
									<th> ID </th>
									<th> Name </th>
									<th> Gender </th>
									<th> Birthdate </th>
									<th> Email </th>
								</tr>				
							</thead>
	    					<tbody>
			    				<tr class = "tableContentYesNo">
						        	<td><?php echo $modalVerifyUserID; ?></td>
						        	<td><?php echo $modalVerifyName; ?></td>
						        	<td><?php echo $modalVerifyGender; ?></td>
						        	<td><?php echo $modalVerifyBirthdate; ?></td>
						        	<td><?php echo $modalVerifyEmail; ?></td>
						        </tr>
							</tbody>
						</table>
						<?php
							if((isset($_GET['userID']))&&($_GET['type']=="verify")){
	    					$sqlRequirements = "SELECT requirementTypeID, name FROM requirementtype WHERE Flag = 1";

	    					$stmt = $con->prepare($sqlRequirements);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();
							foreach ($results as $rowRequirements) {
								$modalRequirementsID = $rowRequirements["requirementTypeID"];
								$modalRequirementsName = $rowRequirements["name"];

								$sqlRequirementsCheck = "SELECT RS.requirementTypeID, DATE_FORMAT(RS.expirationDate, '%M %d, %Y') AS expirationDate, RS.file AS file FROM requirements AS RS, requirementtype AS RE WHERE RS.requirementTypeID = (:modalRequirementsID) AND RS.requirementTypeID = RE.requirementTypeID AND RS.userID = (:modalVerifyUserID) AND submitted = 1";

		    					$stmt = $con->prepare($sqlRequirementsCheck);
		    					$stmt->bindParam(':modalVerifyUserID', $modalVerifyUserID, PDO::PARAM_INT);
		    					$stmt->bindParam(':modalRequirementsID', $modalRequirementsID, PDO::PARAM_INT);
		    					
								$stmt->execute();
								$rowFile = $stmt->fetch();
								$rowCount = $stmt->rowCount();
								//echo "<script>alert('" . $modalVerifyUserID . "');</script>";
								if($rowCount == 0){
								?>
								<br>
								<table class = "tableYesNo">
			    					<thead class = "tableYesNoHead">
										<tr>
											<th colspan="3"><?php echo $modalRequirementsName;?></th>
										</tr>				
									</thead>
			    					<tbody>
			    						<form method="post" enctype="multipart/form-data" onsubmit = "return validateDate();">
			    							<input type="hidden" name="requirementtypeID" value="<?php echo $modalRequirementsID;?>">
						    				<tr class = "tableContentYesNo">
						    					<td colspan="2"><input type='file' class = "inputfile" onchange="readURL<?php echo $modalRequirementsID;?>(this);" accept="image/gif, image/jpeg, image/png" name="myfile<?php echo $modalRequirementsID;?>" /></td>
						    					<td><center><img id="blah<?php echo $modalRequirementsID;?>" src="Resources/noimage.png" alt="your image" /></center></td>
						    					<script type="text/javascript">
						    					</script>
									        </tr>
									        <tr class = "tableContentYesNo">
									        	<td>Expiration Date</td>
									        	<td><input id = "expirationDate" type="date" name="expirationDate<?php echo $modalRequirementsID;?>"></td>
									        	<td><center><button class = "insert" name="insert<?php echo $modalRequirementsID;?>">Insert</button></center></td>
									        </tr>
								        </form>
								        <script type="text/javascript">
								        	function readURL<?php echo $modalRequirementsID;?>(input) {
										        if (input.files && input.files[0]) {
										            var reader = new FileReader();

										            reader.onload = function (e) {
										                $('#blah<?php echo $modalRequirementsID;?>')
										                    .attr('src', e.target.result)
										                    .width(150)
										                    .height(200);
										            };

										            reader.readAsDataURL(input.files[0]);
										        }
										    }
								        </script>
								        <?php
											if(isset($_POST["insert". $modalRequirementsID])){
												$dateToday = date('Y-m-d h:i:sa');
												if ($_POST["expirationDate" . $modalRequirementsID] <= $dateToday){
													echo "<script>alert('Expiration date already passed.');localStorage.setItem('viewModalVerify',true);</script>";
												}
												elseif(($_FILES['myfile'. $modalRequirementsID]['name']!="")&&($_POST['expirationDate' . $modalRequirementsID]!="")){
											   		$filetype = $_FILES['myfile' . $modalRequirementsID]['type'];
										    		if ($filetype == 'image/jpeg' or $filetype == 'image/png'){
														$target_dir = "Requirements/";
														$file = $_FILES['myfile'. $modalRequirementsID]['name'];
														$path = pathinfo($file);
														$filename = $path['filename'];
														$temp = explode(".", $_FILES['myfile'. $modalRequirementsID]['name']);
														$newfilename = round(microtime(true)) . '.' . end($temp);

														$ext = $path['extension'];
														$temp_name = $_FILES['myfile'. $modalRequirementsID]['tmp_name'];
														$path_filename_ext = $target_dir.$newfilename;
														$path_filename_for_database = "Requirements/".$newfilename;
														move_uploaded_file($temp_name,$path_filename_ext);

														$sqlAddRequirements = "INSERT INTO requirements (requirementTypeID, userID, file, submissionDate, expirationDate, submitted)values(:requirementtypeID, :modalVerifyUserID, :file, :dateToday, :expirationDate, 1)";

														$stmt = $con->prepare($sqlAddRequirements);
														$stmt->bindParam(':requirementtypeID', $_POST["requirementtypeID"], PDO::PARAM_STR);
														$stmt->bindParam(':modalVerifyUserID', $modalVerifyUserID, PDO::PARAM_INT);
														$stmt->bindParam(':file', $path_filename_for_database, PDO::PARAM_STR);
														$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
														$stmt->bindParam(':expirationDate', $_POST['expirationDate' . $modalRequirementsID], PDO::PARAM_STR);
														$stmt->execute();
													}
										   		?><script>
												alert('Success');
												var userID = '<?php if(isset($modalVerifyUserID)){ echo $modalVerifyUserID;}?>'
												var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
												var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
												var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
												var type = '&type=verify'
												var url = 'index?route=customerAccounts&userID='+userID+linkSort+search+optionSearch+type
												location.replace(url);
												localStorage.setItem('viewModalVerify',true);
												</script>";
												<?php
											}
											elseif ($_FILES['myfile'. $modalRequirementsID]['name'] == ""){
												echo "<script>alert('Nothing to be inserted.');localStorage.setItem('viewModalVerify',true);</script>";
											}
											elseif ($_POST["expirationDate" . $modalRequirementsID] == ""){
												echo "<script>alert('No expiration date.');localStorage.setItem('viewModalVerify',true);</script>";
											}
											else{
												echo "<script>alert('Nothing to be inserted.');</script>";
											}
										}
										?>
									</tbody>
								</table>
								<?php
								}
								else{
									?>
								<br>
								<table class = "tableYesNo">
			    					<thead class = "tableYesNoHead">
										<tr>
											<th colspan="2"><?php echo $modalRequirementsName;?></th>
										</tr>				
									</thead>
			    					<tbody>
			    						<form method="post" enctype="multipart/form-data">
			    							<input type="hidden" name="requirementtypeID" value="<?php echo $modalRequirementsID;?>">
						    				<tr class = "tableContentYesNo">
						    					<td>Already Passed</td>
						    					<td><img id="blah" src="<?php echo $rowFile['file'];?>" alt="your image" width = "150" height = "200"/></td>
									        </tr>
									        <tr class = "tableContentYesNo">
						    					<td>Expiration</td>
						    					<td><?php echo $rowFile['expirationDate'];?></td>
									        </tr>
								        </form>
								        <script type="text/javascript">
								        	function readURL(input) {
										        if (input.files && input.files[0]) {
										            var reader = new FileReader();

										            reader.onload = function (e) {
										                $('#blah')
										                    .attr('src', e.target.result)
										                    .width(150)
										                    .height(200);
										            };

										            reader.readAsDataURL(input.files[0]);
										        }
										    }
								        </script>
									</tbody>
								</table>
									<?php
								}
							}
						}
						?>
					<?php
						$sqlRequirements = "SELECT requirementTypeID, name FROM requirementtype WHERE Flag = 1";

	    					$stmt = $con->prepare($sqlRequirements);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();
					?>
    				<form method="post">
    					<div class = "YesNo">
    						<?php if($rowCount == 0){echo "<button class = 'yesButton' disabled>";}else { echo "<button name='Yes' class = 'yesButton'>";} ?> Verify </button><button name="No" class = "noButton"> Invalidate </button>
    					</div>
    				</form>
				</div>
				<?php
					if(isset($_POST['Yes'])&&($_GET['type']=="verify")){
						$sqlActiveModal = "UPDATE users
											SET verifiedFlag = 1
											WHERE userID = (:modalVerifyUserID)";
						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':modalVerifyUserID', $modalVerifyUserID, PDO::PARAM_INT);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=customerAccounts&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
					}
				?>
				<?php
					if(isset($_POST['No'])&&($_GET['type']=="verify")){
						$sqlActiveModal = "UPDATE users
											SET verifiedFlag = 0
											WHERE userID = (:modalVerifyUserID)";
						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':modalVerifyUserID', $modalVerifyUserID, PDO::PARAM_INT);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=customerAccounts&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
					}
				?>
  			</div>
		</div>
	</div>
		<script src="Script/viewModal.js" type="text/javascript"></script>
		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=customerAccounts&userID="+userID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function verify(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['link'])){echo '&sort=' . $_SESSION['link'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var type = '&type=verify'
							var url = "index?route=customerAccounts&userID="+userID+linkSort+search+optionSearch+type
							location.replace(url);
							localStorage.setItem('viewModalVerify',true);
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
							var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var type = '&type=yesno'
							var url = "index?route=customerAccounts&userID="+userID+linkSort+search+optionSearch+type
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function viewTransactionBtn(){
				var accountCustomerID = '<?php if(isset($_GET['userID'])){echo $_GET['userID'];}?>'
				var url = "index?route=customerViewTransactions&customerID="+accountCustomerID+"&sort=transactionID"
				location.replace(url);
			}

			function viewReportsBtn(){
				var accountCustomerID = '<?php if(isset($_GET['userID'])){echo $_GET['userID'];}?>'
				var url = "index?route=customerViewReports&customerID="+accountCustomerID+"&sort=reportsID"
				location.replace(url);
			}

			window.onload = function(){
				var x = localStorage.getItem('viewModal');
				if (x == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)

				var y = localStorage.getItem('viewModalYesNo');
				if (y == 'true'){
					document.getElementById('viewModalYesNo').style.display = "block";
				}
				localStorage.setItem('viewModalYesNo',false)

				var z = localStorage.getItem('viewModalVerify');
				if (z == 'true'){
					document.getElementById('viewModalVerify').style.display = "block";
				}
				localStorage.setItem('viewModalVerify',false)
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			var spanVerify = document.getElementsByClassName("viewCloseVerify")[0];
			spanVerify.onclick = function() {
				document.getElementById('viewModalVerify').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalVerify')) {
		            document.getElementById('viewModalVerify').style.display = "none";
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