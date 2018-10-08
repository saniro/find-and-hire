<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-complaintStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-complaintModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkReportHandyman'] = "";
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
				<div class= "title"> COMPLAINTS </div>
				<table id = actionTable>
					<col width = "620">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
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
	    										<a href="index?route=complaints&sort=reportsID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=complaints&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=complaints&sort=userType<?php echo $optionSearch . $search; ?>">User Type</a>
	      										<a href="index?route=complaints&sort=reportType<?php echo $optionSearch . $search; ?>">Complaint Type</a>
	      										<a href="index?route=complaints&sort=date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=complaints"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportsID'){
														echo " selected ";
													}
												}
											?>value="reportsID">ID</option>
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
													if($_GET['optionSearch'] == 'userType'){
														echo " selected ";
													}
												}
											?>
											value="userType">User Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportType'){
														echo " selected ";
													}
												}
											?>
											value="reportType">Complaint Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'date'){
														echo " selected ";
													}
												}
											?>
											value="date">Date</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "complaints">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = 50>
					<col width = 420>
					<col width = 170>
					<col width = 180>
					<col width = 140>
					<thead>
						<tr>
							<th> ID </th>
							<th> Complainant </th>
							<th> User Type </th>
							<th> Complain Type </th>
							<th> Date </th>
							<th> Action </th>
						</tr>				
					</thead>
					<tbody>
					<?php
						$sqlReport = "SELECT reportsID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reporterID) AS name, (SELECT Type FROM users AS US WHERE US.userID = RS.reporterID) AS userType, (SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID) AS reportType, DATE_FORMAT(date,'%b %d, %Y') AS date, readFlag FROM reports AS RS WHERE ((SELECT Type FROM users AS US WHERE RS.reporterID = US.userID) = 2 OR (SELECT Type FROM users AS US WHERE RS.reporterID = US.userID) = 1)";
							if(isset($_GET['optionSearch'])){
								if($_GET['optionSearch'] == 'reportsID'){
									$optionSearchVar = "reportsID";
								}
								elseif($_GET['optionSearch'] == 'name'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reporterID)";
								}
								elseif($_GET['optionSearch'] == 'userType'){
									$optionSearchVar = "(SELECT Type FROM users AS US WHERE US.userID = RS.reporterID)";
								}
								elseif($_GET['optionSearch'] == 'reportType'){
									$optionSearchVar = "(SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID)";
								}
								elseif($_GET['optionSearch'] == 'date'){
									$optionSearchVar = "date";
								}

		    					$sqlReport .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'reportsID'){
		    						$sqlReport .= " ORDER BY reportsID";
		    						$_SESSION['linkReportHandyman'] = "reportsID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlReport .= " ORDER BY name, reportsID";
								    $_SESSION['linkReportHandyman'] = "customerName";
								}
								elseif ($_GET['sort'] == 'userType')
								{
								    $sqlReport .= " ORDER BY userType, reportsID";
								    $_SESSION['linkReportHandyman'] = "handymanName";
								}
								elseif ($_GET['sort'] == 'reportType')
								{
								    $sqlReport .= " ORDER BY reportType, reportsID";
								    $_SESSION['linkReportHandyman'] = "serviceType";
								}
								elseif ($_GET['sort'] == 'date')
								{
								    $sqlReport .= " ORDER BY date DESC";
								    $_SESSION['linkReportHandyman'] = "date";
								}
							}

							$stmt = $con->prepare($sqlReport);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowReport){
								$reportID = $rowReport["reportsID"];
			    				$reportHandymanName = $rowReport["name"];
			    				if($rowReport["userType"] == 1){
			    					$reportUserType = "Customer";
			    				}
			    				elseif($rowReport["userType"] == 2){
			    					$reportUserType = "Handyman";
			    				}
			    				$reportReportType = $rowReport["reportType"];
			    				$reportDate = $rowReport["date"];
			    				$reportFlag = $rowReport["readFlag"];
			    				?>
							      	<?php
							    		if($reportFlag == 1){
							    			echo "<tr class = 'tableContent'>";
							    		}else if($reportFlag == 0){
							    			echo "<tr class = 'tableContentNot'>";
							    		}
							    	?>
						        	<td><?php echo $reportID; ?></td>
						        	<td><?php echo $reportHandymanName; ?></td>
						        	<td><?php echo $reportUserType; ?></td>
						        	<td><?php echo $reportReportType; ?></td>
						        	<td><?php echo $reportDate; ?></td>

						        	<td><button id='viewBtn' class = 'view' onclick='viewModal()'> Read </button></td>
						        </tr>
						<?php
					    	}
				    		if($rowCount == 0){
								echo "<td colspan = 6> No results. </td>";
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
    					if(isset($_GET['reportID'])){
    						$reportIDVar = $_GET['reportID'];
    						$sqlReportView = "SELECT 
    						reportsID, 
    						DATE_FORMAT(date,'%b %d, %Y') AS date, 
    						comment, 
    						readFlag, 
    						reporterID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users WHERE userID = reporterID) AS reporterName, 
    						(SELECT Type FROM users WHERE userID = reporterID) AS reporterUserType, 
    						(SELECT email FROM users WHERE userID = reporterID) AS reporterEmail, 
    						(SELECT contact FROM users WHERE userID = reporterID) AS reporterContactNo, 
    						reportedID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users WHERE userID = reportedID) AS reportedName, 
    						(SELECT Type FROM users WHERE userID = reportedID) AS reportedUserType, 
    						(SELECT email FROM users WHERE userID = reportedID) AS reportedEmail, 
    						(SELECT contact FROM users WHERE userID = reportedID) AS reportedContactNo, 
    						(SELECT name FROM reporttype AS RE WHERE RE.reportTypeID = RS.reportTypeID) AS reportType, 
    						(SELECT description FROM reporttype AS RE WHERE RE.reportTypeID = RS.reportTypeID) AS reportDesc, 
    						transactionID, 
    						(SELECT (SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionServiceView,
    						(SELECT (SELECT DATE_FORMAT(date,'%b %d, %Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionDateView, 
    						(SELECT (SELECT amount FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionAmountView, 
    						(SELECT (SELECT remarks FROM booking AS BG WHERE BG.bookingID = TN.bookingID) FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionRemarksView
    						FROM reports AS RS 
    						WHERE reportsID = (:reportID)";

    						$stmt = $con->prepare($sqlReportView);
    						$stmt->bindParam(':reportID', $reportIDVar, PDO::PARAM_INT);
							$stmt->execute();
							$rowReportView = $stmt->fetch();

							$reportIDView = $rowReportView["reportsID"];
		    				$reportTypeView = $rowReportView["reportType"];
		    				$reportDescView = $rowReportView["reportDesc"];
		    				$reportDateView = $rowReportView["date"];
		    				$reportCommentView = $rowReportView["comment"];

		    				$reportReportedIDView = $rowReportView["reportedID"];
		    				$reportReportedNameView = $rowReportView["reportedName"];
		    				if($rowReportView["reportedUserType"] == 1){
		    					$reportReportedUserTypeView = "Customer";
		    				}
		    				elseif($rowReportView["reportedUserType"] == 2){
		    					$reportReportedUserTypeView = "Handyman";
		    				}
		    				$reportReportedEmailView = $rowReportView["reportedEmail"];
		    				$reportReportedContactView = $rowReportView["reportedContactNo"];

		    				$_SESSION["reportReporterIDView"] = $reportReporterIDView = $rowReportView["reporterID"];
		    				$_SESSION["reportReporterNameView"] = $reportReporterNameView = $rowReportView["reporterName"];
		    				if($rowReportView["reporterUserType"] == 1){
		    					$reportReporterUserTypeView = "Customer";
		    				}
		    				elseif($rowReportView["reporterUserType"] == 2){
		    					$reportReporterUserTypeView = "Handyman";
		    				}
		    				$_SESSION["reportReporterEmailView"] = $reportReporterEmailView = $rowReportView["reporterEmail"];
		    				$reportReporterContactView = $rowReportView["reporterContactNo"];
		    				$reportTransactionIDView = $rowReportView["transactionID"];
		    				
		    				$reportTransactionServiceView = $rowReportView["transactionServiceView"];
		    				$reportTransactionDateView = $rowReportView["transactionDateView"];
		    				$reportTransactionAmountView = $rowReportView["transactionAmountView"];
		    				$reportRemarksView = $rowReportView["transactionRemarksView"];

					    	$sqlreportsRead = "UPDATE reports
												SET readFlag = 1
												WHERE reportsID = '$reportIDView'";
							$stmt = $con->prepare($sqlreportsRead);
							$stmt->bindParam(':reportTypeID', $reportIDView, PDO::PARAM_INT);
							$stmt->execute();
						}
					?>
    				<div class = "wholeModal">
    					<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Complaint ID</td>
							    <td class = "tdInput"><?php echo $reportIDView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Complaint Type</td>
							    <td class = "tdInput"><?php echo $reportTypeView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Description</td>
							    <td class = "tdInput"><?php echo $reportDescView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Date Complained</td>
							    <td class = "tdInput"><?php echo $reportDateView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Comment</td>
							    <td class = "tdInput"><?php echo $reportCommentView;?></td>
							</tr>
						</table>
					    <div class = "handymanWholeInfo">
					    	<div id = number class = handymanDetails>
								<b>Complainant</b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User no</td>
								    <td class = "tdInput"><?php echo $reportReporterIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName word-wrap">Name</td>
								    <td class = "tdInput"><?php echo $reportReporterNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">User Type</td>
								    <td class = "tdInput"><?php echo $reportReporterUserTypeView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput max"><?php echo $reportReporterEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $reportReporterContactView;?></td>
								</tr>
							</table>
					    </div>
					    <div class = "customerWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Accused</b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User no</td>
								    <td class = "tdInput"><?php echo $reportReportedIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $reportReportedNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">User Type</td>
								    <td class = "tdInput"><?php echo $reportReportedUserTypeView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput max"><?php echo $reportReportedEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $reportReportedContactView;?></td>
								</tr>
							</table>
					    </div>
					</div>
					<div class = "transactionWholeInfo">
						<div id = number class = customerDetailsTitle>
							<b>Transaction Information </b>
						</div>
						<div class="tab">
							<button class="tablinks" onclick="tabTransaction(event, 'transaction')">Information</button>
							<button class="tablinks" onclick="tabTransaction(event, 'description')">Service Description</button>
						</div>
						<div id = "transaction" class = "tabcontent active">
							<div class = "transactionInfo">
								<div class = "transactionWholeInfoOne">
									<table class = "tableInputs">
				    					<col width="130">
										<tr class = "trInputs">
										    <td class = "tdName">Transaction No</td>
										    <td class = "tdInput"><?php echo $reportTransactionIDView;?></td>
										</tr>
										<tr class = "trInputs">
										    <td class = "tdName">Service Type</td>
										    <td class = "tdInput"><?php echo $reportTransactionServiceView;?></td>
										</tr>
										<tr class = "trInputs">
										    <td class = "tdName">Date and Time</td>
										    <td class = "tdInput"><?php echo $reportTransactionDateView;?></td>
										</tr>
									</table>
								</div>
								<div class = "transactionWholeInfoTwo">
									<table class = "tableInputs">
				    					<col width="130">
										<tr class = "trInputs">
										    <td class = "tdName">Amount</td>
										    <td class = "tdInput"><?php echo $reportTransactionAmountView;?></td>
										</tr>
										<tr class = "trInputs">
										    <td class = "tdName">Remarks</td>
										    <td class = "tdInput"><?php echo $reportRemarksView;?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id = "description" class = "tabcontent">
							<div class = "transactionInfo">
								<table class = "tableInputs">
			    					<col width="130">
									<tr class = "trInputs">
									    <td class = "tdName">Description</td>
									    <td class = "tdInput">
			<?php 
				$sqlSelectedTitle = "SELECT (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS titleID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS title FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :reportTransactionIDView)) GROUP BY (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID))";

				$stmt = $con->prepare($sqlSelectedTitle);
				$stmt->bindParam(':reportTransactionIDView', $reportTransactionIDView, PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll();
				$rowCount = $stmt->rowCount();

				foreach($results as $rowSelectedTitle){
					echo "<div class = 'rowForm'>";
					echo "<table><col width = 100><col width = 550><tbody><tr>";
					$selectedTitleID = $rowSelectedTitle["titleID"];
					$selectedTitle = $rowSelectedTitle["title"];
					echo "<td class = 'tdName'>Title</td><td class = 'tdInput'>" .$selectedTitle . "</td>";
					echo "</tr>";
					echo "</tbody></table>";
					$sqlSelectedChoices = "SELECT (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS amount FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :reportTransactionIDView)) AND (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) = :selectedTitleID";

					$stmt = $con->prepare($sqlSelectedChoices);
					$stmt->bindParam(':reportTransactionIDView', $reportTransactionIDView, PDO::PARAM_INT);
					$stmt->bindParam(':selectedTitleID', $selectedTitleID, PDO::PARAM_INT);
					$stmt->execute();
					$results = $stmt->fetchAll();
					$rowCount = $stmt->rowCount();
					echo "<table><col width = 550><thead><tr><th class = 'tdName'>Options</th><th class = 'tdName'>Amount</th></tr></thead><tbody>";
					foreach($results as $rowSelectedChoices){
						$selectedChoicesDescription = $rowSelectedChoices["description"];
						$selectedChoicesAmount = $rowSelectedChoices["amount"];

						echo "<tr><td class = 'tdInput'>".$selectedChoicesDescription . "</td>";
						echo "<td class = 'tdInput'>".$selectedChoicesAmount . "</td></tr>";
					}
					echo "</tbody></table>";
					echo "</div>";	
		    	}
		    ?>
									    </td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<button id='proceedBtn' class = 'proceedBig' onclick='viewReview()'> NEXT </button>
				</div>
  			</div>
		</div>

		<form method="post">
		<div id="viewReviewModal" class="viewReviewModal">
			<div class="viewReviewModal-content">
				<span class="viewReviewClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Send Notification</b></div>
					<table class = "tableInputs">
    					<col width="130">
						<tr class = "trInputs">
						    <td class = "tdName">Notification</td>
						    <td class = "tdInput">
						    	<select class = "dropdownType" name = "notifRemarksType">
								<?php
									$sqlNotificationType = "SELECT notifTypeID, message FROM notificationtype WHERE flag = 1 AND type = 1";

									$stmt = $con->prepare($sqlNotificationType);
									$stmt->execute();
									$results = $stmt->fetchAll();
									$rowNotificationType = $stmt->rowCount();

									foreach ($results as $rowNotificationType) {
										$notificationTypeID = $rowNotificationType["notifTypeID"];
						    			$notificationTypeMessage = $rowNotificationType["message"];
						    			?>
						    			<option value="<?php if(isset($notificationTypeID)){echo $notificationTypeID;}?>"><?php if(isset($notificationTypeMessage)){echo $notificationTypeMessage;}?></option>
						    	<?php
								    }
								    if ($rowCount == 0){
								    	?>
								    	<option value="0" readonly>None</option>
								    	<?php
								    }
								?>
							</select>
						</td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Remarks</td>
						    <td class = "tdInput"><textarea class = "textAreaDesc" name="notifRemarks" placeholder="Add remarks here..." required></textarea></td>
						</tr>
					</table>
					<div class = buttonSubmit>
  						<button type = "submit" class="addSubmit" name = "sendNotif"> SEND </button>
  					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['sendNotif'])){
					$sendUserID = $_SESSION["reportReporterIDView"];
					$sendRemarksType = $_POST['notifRemarksType'];
					$sendRemarks = $_POST['notifRemarks'];
					$dateToday = date('Y-m-d');

					$sqlSendNotif = "INSERT INTO notification (userID, notifTypeID, remarks, dateReceive)values(:sendUserID, :sendRemarksType, :sendRemarks, :dateToday)";

					$stmt = $con->prepare($sqlSendNotif);
					$stmt->bindParam(':sendUserID', $sendUserID, PDO::PARAM_INT);
					$stmt->bindParam(':sendRemarksType', $sendRemarksType, PDO::PARAM_STR);
					$stmt->bindParam(':sendRemarks', $sendRemarks, PDO::PARAM_STR);
					$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
					$stmt->execute();

					$sqlNotifType = "SELECT message FROM notificationtype WHERE notifTypeID = :remarksType";
					$stmt = $con->prepare($sqlNotifType);
					$stmt->bindParam(':remarksType', $sendRemarksType, PDO::PARAM_INT);
					$stmt->execute();
					$rowNotifMessage = $stmt->fetch();
					$notifType = $rowNotifMessage['message'];

					$message = $notifType . '<br><br>Remarks : <br>' . $sendRemarks;
					sendMail($_SESSION["reportReporterEmailView"], $_SESSION["reportReporterNameView"], 'Your Complaint', $message);

					$_SESSION["reportReporterIDView"] = "";
					$_SESSION["reportReporterEmailView"] = "";
					$_SESSION["reportReporterNameView"] = "";
					
					echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=complaints&sort=".$_SESSION['linkReportHandyman'].$optionSearch.$search."';</script>";
				}
			?>
		</div>
		</form>

		<script type="text/javascript">
			function tabTransaction(modal, modalName) {
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
							var reportID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=complaints&reportID="+reportID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function viewReview(){
			    // Get the modal
			    var modal = document.getElementById('viewReviewModal');
			    var modalClose = document.getElementById('viewModal');
			    // Get the button that opens the modal
			    var btn = document.getElementById('proceedBtn');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName('viewReviewClose')[0];

			    // When the user clicks the button, open the modal 
			   	modal.style.display = "block";
			   	modalClose.style.display = "none";
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			    	var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
			    	var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
					var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
					var url = "index?route=complaints"+linkSort+search+optionSearch
					location.replace(url);
			        modal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			        	var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
						var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
						var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
						var url = "index?route=complaints"+linkSort+search+optionSearch
						location.replace(url);
			            modal.style.display = "none";
			        }
			    }
			}

			window.onload = function(){
				var x = localStorage.getItem('viewModal');
				if (x == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)

			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
				var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
				var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
				var url = "index?route=complaints"+linkSort+search+optionSearch
				location.replace(url);
				document.getElementById('viewModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		        	var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
					var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
					var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
					var url = "index?route=complaints"+linkSort+search+optionSearch
					location.replace(url);
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