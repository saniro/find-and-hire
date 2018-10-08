<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-topupPendingStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-topupPendingModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['pendingLink'] = "";
			}

			require_once('admin-functionMail.php');
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
				<div class= "title"> TOP UP - Pending </div>
				<table id = actionTable>
					<thead class = "actions">
						<col width = "600">
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
	    											$sort = "";
	    											if(isset($_GET['optionSearch'])){
	    												$optionSearch = "&optionSearch=" . $_GET['optionSearch'];
	    											}
	    											if(isset($_GET['search'])){
	    												$search = "&search=" . $_GET['search'];
	    											}
	    										?>
	      										<a href="index?route=pending&sort=topupHistoryID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=pending&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=pending&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=pending&sort=value<?php echo $optionSearch . $search; ?>">Amount</a>
	      										<a href="index?route=pending&sort=Date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=pending"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'topupHistoryID'){
														echo " selected ";
													}
												}
											?>value="topupHistoryID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'name'){
														echo " selected ";
													}
												}
											?>
											value="name">Name</option>
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
													if($_GET['optionSearch'] == 'value'){
														echo " selected ";
													}
												}
											?>
											value="value">Amount</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'Date'){
														echo " selected ";
													}
												}
											?>
											value="Date">Date</option>

										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="pending">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = "50">
					<col width = "300">
					<col width = "300">
					<col width = "130">
					<col width = "180">
					<thead>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Email Address </th>
							<th> Amount </th>
							<th> Due Date </th>
							<th colspan = 1> Action </th>
						</tr>				
					</thead>
						<tbody>
							<?php
								$sqlPending = "SELECT topupHistoryID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = TH.userID) AS email, value, DATE_FORMAT(Date, '%M %d, %Y %r') AS Date FROM topuphistory AS TH WHERE status = 0";
								if(isset($_GET['optionSearch'])){
									//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
									if($_GET['optionSearch'] == 'topupHistoryID'){
										$optionSearchVar = "topupHistoryID";
									}
									elseif($_GET['optionSearch'] == 'name'){
										$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID)";
									}
									elseif($_GET['optionSearch'] == 'email'){
										$optionSearchVar = "(SELECT email FROM users AS US WHERE US.userID = TH.userID)";
									}
									elseif($_GET['optionSearch'] == 'value'){
										$optionSearchVar = "value";
									}
									elseif($_GET['optionSearch'] == 'Date'){
										$optionSearchVar = "Date";
									}
			    					$sqlPending .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}

								if(isset($_GET['sort'])){
									if ($_GET['sort'] == 'topupHistoryID'){
			    						$sqlPending .= " ORDER BY topupHistoryID";
			    						$_SESSION['pendingLink'] = "topupHistoryID";
									}
									elseif ($_GET['sort'] == 'name')
									{
									    $sqlPending .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID)";
									    $_SESSION['pendingLink'] = "name";
									}
									elseif ($_GET['sort'] == 'email')
									{
									    $sqlPending .= " ORDER BY (SELECT email FROM users AS US WHERE US.userID = TH.userID)";
									    $_SESSION['pendingLink'] = "email";
									}
									elseif ($_GET['sort'] == 'value')
									{
									    $sqlPending .= " ORDER BY value";
									    $_SESSION['pendingLink'] = "value";
									}
									elseif ($_GET['sort'] == 'Date')
									{
									    $sqlPending .= " ORDER BY Date DESC";
									    $_SESSION['pendingLink'] = "Date";
									}
								}

								$stmt = $con->prepare($sqlPending);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach($results as $rowPending){
									$pendingUserID = $rowPending["topupHistoryID"];
				    				$pendingHandymanName = $rowPending["name"];
				    				$pendingHandymanEmail = $rowPending["email"];
				    				$pendingValue = $rowPending["value"];
				    				$pendingDate = $rowPending["Date"];
				    				
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $pendingUserID; ?></td>
							        	<td><?php echo $pendingHandymanName; ?></td>
							        	<td><?php echo $pendingHandymanEmail; ?></td>
							        	<td><?php echo $pendingValue; ?></td>
							        	<td><?php echo $pendingDate; ?></td>
							        	
							        	<td><button id='viewBtn' class = 'view' onclick="viewTopUp()">Accepted</button></td>
							        </tr>
							<?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = '6'> No results. </td>";
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
					<?php
						if(isset($_GET['pendingID'])){
							$sqlPendingModal = "
							SELECT topupHistoryID, 
							userID, 
							(SELECT profilepicture FROM users AS US WHERE US.userID = TY.userID) AS profilepicture, 
							(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TY.userID) AS name, 
							(SELECT CASE WHEN gender = 1 THEN 'Male' WHEN gender = 0 THEN 'Female' END FROM users AS US WHERE US.userID = TY.userID) AS gender, 
							(SELECT email FROM users AS US WHERE US.userID = TY.userID) AS email, 
							(SELECT contact FROM users AS US WHERE US.userID = TY.userID) AS contact, 
							Value, 
							paymentPic, 
							Date, 
							status 
							FROM topuphistory AS TY 
							WHERE topupHistoryID = (:pendingID)";

							$stmt = $con->prepare($sqlPendingModal);
							$stmt->bindParam(':pendingID', $_GET['pendingID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowPendingModal = $stmt->fetch();

							$modalPendingTopUpHistoryID = $rowPendingModal["topupHistoryID"];
		    				$modalPendingUserID = $rowPendingModal["userID"];
		    				if (empty($rowPendingModal["profilepicture"])){
		    					$modalPendingProfilePicture = "ProfilePictures/userIcon.png";
		    				}
		    				else{
		    					$modalPendingProfilePicture = $rowPendingModal["profilepicture"];
		    				}
		    				$modalPendingName = $rowPendingModal["name"];
		    				$modalPendingGender = $rowPendingModal["gender"];
		    				$modalPendingEmail = $rowPendingModal["email"];
		    				$modalPendingContact = $rowPendingModal["contact"];
		    				$modalPendingValue = $rowPendingModal["Value"];
		    				if(empty($rowPendingModal["paymentPic"])){
		    					$modalPendingPaymentPic = "Resources/noimage.png";
		    				}
		    				else{
		    					$modalPendingPaymentPic = $rowPendingModal["paymentPic"];
		    				}
		    				$modalPendingDate = $rowPendingModal["Date"];
		    				$modalPendingFlag = $rowPendingModal["status"];
						}
					?>
				<div class = customerDetailsWhole>
					<form method="post"> 
					<table class = "tableInputs">
						<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Top Up ID</td>
						    <td class = "tdInput"><?php echo $modalPendingTopUpHistoryID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdInput" colspan="2">
						    	<div class = "profilePicContent">
						    		<img class = "profilePic" src="<?php echo $modalPendingProfilePicture; ?>">
						    	</div>
						    </td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User Number</td>
						    <td class = "tdInput"><?php echo $modalPendingUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalPendingName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalPendingGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email Address</td>
						    <td class = "tdInput max"><?php echo $modalPendingEmail;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact</td>
						    <td class = "tdInput"><?php echo $modalPendingContact; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Value</td>
						    <td class = "tdInput"><?php echo $modalPendingValue; ?></td>
						</tr>
						<tr class = "trInputs">
							<td class = "tdName" colspan="1">
								<td class = "tdName">Payment Picture</td>
						    </td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdInput" colspan="2">
						    	<div class = "paymentContent">
						    		<img class = "paymentPic" src="<?php echo $modalPendingPaymentPic; ?>">
						    	</div>
						    </td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Date</td>
						    <td class = "tdInput"><?php echo $modalPendingDate; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Notification</td>
						    <td class = "tdInput">
						    	<?php 
						    		$sqlNotif = "SELECT notifTypeID, message FROM notificationtype WHERE flag = 1 AND type = 3";

									$stmt = $con->prepare($sqlNotif);
									$stmt->execute();
									$rowCount = $stmt->rowCount();
									$results = $stmt->fetchAll();
						    	?>
						    	<select class = "dropdownType" name = "notification" required>
						    		<?php
						    			foreach ($results as $notif) {
						    				?>
						    				<option value = "<?php echo $notif['notifTypeID']; ?>"><?php echo $notif['message']; ?></option>
						    				<?php	
						    			}
						    			if($rowCount == 0){
						    				echo "<option>-Nothing-</option>";
						    			}
						    		?>
						    	</select>
						    </td>
						</tr>
						<tr class = "trInputs">
							<td class = "tdName">Remarks</td>
							<td class="tdInput">
								<textarea class = "textAreaDesc" name="remarks" placeholder="Add remarks here..." required></textarea>
							</td>
						</tr>
					</table>
						<div class = "YesNo">
	    					<button name="Yes" class = "yesButton"> YES </button>
	    					<button name="No" class = "noButton"> NO </button>
	    				</div>
	    			</form>
    				<?php
						if(isset($_POST['Yes'])){
							if($modalPendingFlag == 0){
								$sqlInsertNotif = "INSERT INTO notification (userID, notifTypeID, remarks, dateReceive)values(:modalPendingUserID, :notifTypeID, :remarks, NOW())";
								$stmt = $con->prepare($sqlInsertNotif);
								$stmt->bindParam(':modalPendingUserID', $modalPendingUserID, PDO::PARAM_INT);
								$stmt->bindParam(':notifTypeID', $_POST['notification'], PDO::PARAM_INT);
								$stmt->bindParam(':remarks', $_POST['remarks'], PDO::PARAM_STR);
								$stmt->execute();

								$sqlActiveModal = "UPDATE topuphistory
													SET status = 1
													WHERE topupHistoryID = (:modalPendingTopUpHistoryID)";
								$stmt = $con->prepare($sqlActiveModal);
								$stmt->bindParam(':modalPendingTopUpHistoryID', $modalPendingTopUpHistoryID, PDO::PARAM_INT);
								$stmt->execute();
								
								$sqlSelectPoints = "SELECT points, (SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users AS US WHERE US.userID = HS.userID) AS name, (SELECT email FROM users AS US WHERE HS.userID = US.userID) AS email FROM handymanpoints AS HS WHERE userID = (:modalPendingUserID)";
								$stmt = $con->prepare($sqlSelectPoints);
								$stmt->bindParam(':modalPendingUserID', $modalPendingUserID, PDO::PARAM_INT);
								$stmt->execute();
								$rowSelectPoints = $stmt->fetch();
								$selectPoints = $rowSelectPoints["points"];
								$selectPointsName = $rowSelectPoints["name"];
								$selectPointsEmail = $rowSelectPoints["email"];

								$insertPoints = $selectPoints + $modalPendingValue;

								$sqlInsertPoints = "UPDATE handymanpoints
													SET points = :points
													WHERE userID = (:modalPendingUserID)";
								$stmt = $con->prepare($sqlInsertPoints);
								$stmt->bindParam(':modalPendingUserID', $modalPendingUserID, PDO::PARAM_INT);
								$stmt->bindParam(':points', $insertPoints, PDO::PARAM_INT);
								$stmt->execute();

								$sqlNotifType = "SELECT message FROM notificationtype WHERE notifTypeID = :remarksType";
								$stmt = $con->prepare($sqlNotifType);
								$stmt->bindParam(':remarksType', $_POST['notification'], PDO::PARAM_INT);
								$stmt->execute();
								$rowNotifType = $stmt->fetch();
								$notifType = $rowNotifType["message"];

								$message = $notifType . ' ' . $modalPendingValue . '<br>Current balance : ' . $insertPoints . '<br><br>Remarks : <br>' . $_POST['remarks'];
								sendMail($rowSelectPoints["email"], $selectPointsName, 'Top Up', $message);

								echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=pending&sort=".$_SESSION['pendingLink'].$optionSearch.$search."';</script>";
						}
					}
					if(isset($_POST['No'])){
						$sqlActiveModal = "UPDATE topuphistory
											SET status = 2
											WHERE topupHistoryID = (:topupHistoryID)";
						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':topupHistoryID', $modalPendingTopUpHistoryID, PDO::PARAM_INT);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=pending&sort=".$_SESSION['pendingLink'].$optionSearch.$search."';</script>";
					}
					?>
				</div>
  			</div>
		</div>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
	    			<div class = "infoDetails"><b>Are you sure?</b></div>
	    			<div class = "profilePicDivision">
	    				<?php
		    				if(isset($_GET['pendingID'])){
		    					$sqlYesNo = "SELECT topupHistoryID, userID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = TH.userID) AS email, value, Date, status FROM topuphistory AS TH WHERE topupHistoryID = (:topupHistoryID) AND status = 0";

		    					$stmt = $con->prepare($sqlYesNo);
								$stmt->bindParam(':topupHistoryID', $_GET['pendingID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowYesNo = $stmt->fetch();

								$modalYesNoUserID = $rowYesNo["userID"];
								$modalYesNoName = $rowYesNo["name"];
								$modalYesNoEmail = $rowYesNo["email"];
								$modalYesNoValue = $rowYesNo["value"];
				    			$modalYesNoStatus = $rowYesNo["status"];
							}
						?>
	    				<form method="post">
	    					<table class = "tableYesNo">
	    						<col width = "50">
	    						<col width = "270">
	    						<col width = "270">
		    					<thead class = "tableYesNoHead">
									<tr>
										<th> ID </th>
										<th> Name </th>
										<th> Email </th>
										<th> Value </th>
									</tr>				
								</thead>
		    					<tbody>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoUserID; ?></td>
							        	<td><?php echo $modalYesNoName; ?></td>
							        	<td><?php echo $modalYesNoEmail; ?></td>
							        	<td><?php echo $modalYesNoValue; ?></td>
							        	
							        </tr>
								</tbody>
							</table>
	    					<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php
					// 	if(isset($_POST['Yes'])){
					// 		if($modalYesNoFlag == 0){
					// 			$sqlActiveModal = "UPDATE topuphistory
					// 								SET status = 1
					// 								WHERE userID = (:modalYesNoUserID)";
					// 			$stmt = $con->prepare($sqlActiveModal);
					// 			$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
					// 			$stmt->execute();
								
					// 			$sqlSelectPoints = "SELECT points FROM handymanpoints WHERE userID = (:modalYesNoUserID)";
					// 			$stmt = $con->prepare($sqlSelectPoints);
					// 			$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
					// 			$stmt->execute();
					// 			$rowSelectPoints = $stmt->fetch();
					// 			$selectPoints = $rowSelectPoints["points"];
					// 			$insertPoints = $selectPoints + $modalYesNoValue;

					// 			$sqlInsertPoints = "UPDATE handymanpoints
					// 								SET points = :points
					// 								WHERE userID = (:modalYesNoUserID)";
					// 			$stmt = $con->prepare($sqlInsertPoints);
					// 			$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
					// 			$stmt->bindParam(':points', $insertPoints, PDO::PARAM_INT);
					// 			$stmt->execute();

					// 		echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=pending&sort=".$_SESSION['pendingLink'].$optionSearch.$search."';</script>";
					// 	}
					// }
					?>
	  			</div>
			</div>
		</div>

		<script type="text/javascript">
			function viewTopUp(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var pendingID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['pendingLink'])){echo '&sort=' . $_SESSION['pendingLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=pending&pendingID="+pendingID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			window.onload = function(){
				var y = localStorage.getItem('viewModal');
				if (y == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
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