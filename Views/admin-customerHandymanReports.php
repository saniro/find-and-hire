<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerViewReportsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerViewReportsModalStyles.css">
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<?php
			require("admin-header.php");
			if(!isset($_SESSION['customerViewSpeReportsLink'])){
				$_SESSION['customerViewSpeReportsLink'] = "";
			}

			if(isset($_GET['customerID'])){
				$_SESSION['customerIDSpeReportSet'] = $_GET['customerID'];

			}

			if(isset($_SESSION['customerIDSpeReportSet'])){
				$customerIDSet = "&customerID=" . $_SESSION['customerIDSpeReportSet'];
			}

			$sqlCustomerInfo = "SELECT concat (firstName, ', ', middleName, ' ', lastName) AS name, (SELECT count(reportsID) FROM reports WHERE reportedID = userID) AS reportNo, gender, birthDate, email, contact FROM users WHERE userID = (:customerIDSet)";

			$stmt = $con->prepare($sqlCustomerInfo);
			$stmt->bindParam(':customerIDSet', $_SESSION['customerIDSpeReportSet'], PDO::PARAM_INT);
			$stmt->execute();
			$rowCustomerInfo = $stmt->fetch();

			$customerName = $rowCustomerInfo["name"];
			$customerReportNo = $rowCustomerInfo["reportNo"];
			if ($rowCustomerInfo["gender"] == 1){
				$customerGender = "Male";
			}
			elseif ($rowCustomerInfo["gender"] == 0){
				$customerGender = "Female";
			}
			$customerBirthdate = $rowCustomerInfo["birthDate"];
			$customerEmail = $rowCustomerInfo["email"];
			$customerContact = $rowCustomerInfo["contact"];
		?>

		<div class = wrapper>
			<div class = "customerDesc">
				<div class = "customerDescContents">
					<form method="post">
						<div class = "backBtn">
							<button type = "submit" class="backButton" name = "backButton"> Go Back </button>
						</div>
					</form>
					<table class = "tableInputs">
    					<col width="130">
						<tr class = "trInputs">
						    <td class = "tdName">Customer No</td>
						    <td class = "tdInput"><?php echo $_SESSION['customerIDSpeReportSet']; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $customerName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Reported Times</td>
						    <td class = "tdInput"><?php echo $customerReportNo; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $customerGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Birthdate</td>
						    <td class = "tdInput"><?php echo $customerBirthdate; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email</td>
						    <td class = "tdInput"><?php echo $customerEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact No</td>
						    <td class = "tdInput"><?php echo $customerContact; ?></td>
						</tr>
					</table>
				</div>
			</div>
			<?php
				if(isset($_POST['backButton'])){
					echo "<script type='text/javascript'>window.location.href='index?route=customerAccounts';</script>";
				}
			?>
			<div class = "contents">
				<table id = actionTable>
					<col width = "390">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li id = "dropdownSortID" class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div class = "sortBtn"><img class = "iconAction" src="Resources/sort-by-attributes.png">SORT</div>
	    									</a>
	    									<form method="get">
	    									<div class="dropdown-content">
	    										<?php
	    											$optionSearch = "";
	    											$search = "";
	    											$optionID = "";
													if(isset($_GET['optionSearch'])){
														$optionSearch = "&optionSearch=" . $_GET['optionSearch'];
													}
													if(isset($_GET['search'])){
														$search = "&search=" . $_GET['search'];
													}
												?>
												<a href="index?route=customerViewReports&sort=reportsID<?php echo $customerIDSet.$optionSearch.$search;?>">Report No.</a>
	      										<a href="index?route=customerViewReports&sort=handyman<?php echo $customerIDSet.$optionSearch.$search;?>">Handyman Name</a>
	      										<a href="index?route=customerViewReports&sort=reporttype<?php echo $customerIDSet.$optionSearch.$search;?>">Report Type</a>
	      										<a href="index?route=customerViewReports&sort=reportNo<?php echo $customerIDSet.$optionSearch.$search;?>">Reports</a>
	      										<a href="index?route=customerViewReports&sort=date<?php echo $customerIDSet.$optionSearch.$search;?>">Date</a>
	    									</div>
	    									</form>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=customerViewReports"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportsID'){
														echo " selected ";
													}
												}
											?>value="reportsID">Report ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'handyman'){
														echo " selected ";
													}
												}
											?>
											value="handyman">Handyman Name</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reporttype'){
														echo " selected ";
													}
												}
											?>
											value="reporttype">Report Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportNo'){
														echo " selected ";
													}
												}
											?>
											value="reportNo">Reports</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'date'){
														echo " selected ";
													}
												}
											?>
											value="date">Date</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..."><input type = "hidden" name = "route" value = "customerViewReports">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>

				<table id = transactionTable>
					<col width = "130">
					<col width = "200">
					<col width = "170">
					<col width = "120">
					<thead>
						<tr>
							<th> Report No. </th>
							<th> Handyman Name </th>
							<th> Report Type </th>
							<th> Reports </th>
							<th> Date </th>
							<th> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlCustomerViewReport = "SELECT 
								reportsID, 
								(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reportedID) AS handymanName, 
								(SELECT count(reportsID) FROM reports WHERE reportedID = (SELECT userID FROM users AS US WHERE US.userID = RS.reportedID)) AS reportNo, 
								(SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID) AS reportType, 
								date, 
								readFlag FROM reports AS RS 
								WHERE (SELECT Type FROM users AS US WHERE RS.reporterID = US.userID) AND reporterID = (:customerIDSet)";
							if(isset($_GET['optionSearch'])){
								if ($_GET['optionSearch'] == 'reportsID'){
		    						$optionSearchVar = "reportsID";
								}
								elseif ($_GET['optionSearch'] == 'handyman'){
		    						$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reportedID)";
								}
								elseif ($_GET['optionSearch'] == 'reporttype'){
		    						$optionSearchVar = "(SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID)";
								}
								elseif ($_GET['optionSearch'] == 'reportNo'){
		    						$optionSearchVar = "(SELECT count(reportsID) FROM reports WHERE reportedID = (SELECT userID FROM users AS US WHERE US.userID = RS.reportedID))";
								}
								elseif ($_GET['optionSearch'] == 'date'){
		    						$optionSearchVar = "date";
								}
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
		    					$sqlCustomerViewReport .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'reportsID'){
		    						$sqlCustomerViewReport .= " ORDER BY reportsID";
		    						$_SESSION['customerViewSpeReportsLink'] = "reportsID";
								}
								elseif ($_GET['sort'] == 'handyman')
								{
								    $sqlCustomerViewReport .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reportedID)";
								    $_SESSION['customerViewSpeReportsLink'] = "handyman";
								}
								elseif ($_GET['sort'] == 'reporttype')
								{
								    $sqlCustomerViewReport .= " ORDER BY (SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID)";
								    $_SESSION['customerViewSpeReportsLink'] = "reporttype";
								}
								elseif ($_GET['sort'] == 'reportNo')
								{
								    $sqlCustomerViewReport .= " ORDER BY reportNo desc";
								    $_SESSION['customerViewSpeReportsLink'] = "reportNo";
								}
								elseif ($_GET['sort'] == 'date')
								{
								    $sqlCustomerViewReport .= " ORDER BY date desc";
								    $_SESSION['customerViewSpeReportsLink'] = "date";
								}
							}
							$stmt = $con->prepare($sqlCustomerViewReport);
							$stmt->bindParam(':customerIDSet', $_SESSION['customerIDSpeReportSet'], PDO::PARAM_INT);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowCustomerViewReport){
								$customerReportID = $rowCustomerViewReport["reportsID"];
			    				$customerReportCustName = $rowCustomerViewReport["handymanName"];
			    				$customerReportType = $rowCustomerViewReport["reportType"];
			    				$customerReportNo = $rowCustomerViewReport["reportNo"];
			    				$customerReportDate = $rowCustomerViewReport["date"];
			    				$customerReportFlag = $rowCustomerViewReport["readFlag"];
			    				?>
			    				<?php
						    		if($customerReportFlag == 1){
						    			echo "<tr class = 'tableContent'>";
						    		}else if($customerReportFlag == 0){
						    			echo "<tr class = 'tableContentNot'>";
						    		}
						    	?>
						        	<td><?php echo $customerReportID; ?></td>
						        	<td><?php echo $customerReportCustName; ?></td>
						        	<td><?php echo $customerReportType; ?></td>
						        	<td><?php echo $customerReportNo; ?></td>
						        	<td><?php echo $customerReportDate; ?></td>
						        	<td><button id='edit' class = 'view' onclick='viewModal()'>View</button></td></td>
						        </tr>
						<?php
				    		}
					    	if($rowCount == 0){
								echo "<td> No results. </td>";
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
    						date, 
    						comment, 
    						readFlag, 
    						reporterID AS customerID, 
    						reportedID AS handymanID, 
    						(SELECT count(reportsID) FROM reports WHERE reportedID = (SELECT userID FROM users AS US WHERE US.userID = RS.reportedID)) AS reportNo, 
    						(SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users WHERE userID = reportedID) AS handymanName, 
    						(SELECT email FROM users WHERE userID = reportedID) AS handymanEmail, 
    						(SELECT contact FROM users WHERE userID = reportedID) AS handymanContactNo, 
    						(SELECT name FROM reporttype AS RE WHERE RE.reportTypeID = RS.reportTypeID) AS reportType, 
    						(SELECT description FROM reporttype AS RE WHERE RE.reportTypeID = RS.reportTypeID) AS reportDesc, 
    						transactionID, 
							(SELECT name FROM services AS SS WHERE serviceID = (SELECT serviceID FROM options AS OS WHERE OS.optionsID = (SELECT optionsID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID))) AS transactionServiceView, 
    						(SELECT description FROM options AS OS WHERE OS.optionsID = (SELECT optionsID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) AS transactionServiceDescView, 
    						(SELECT date FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionDateView, 
    						(SELECT amount FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionAmountView, 
    						(SELECT remarks FROM transaction AS TN WHERE TN.transactionID = RS.transactionID) AS transactionRemarksView
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
		    				$reportTransactionIDView = $rowReportView["transactionID"];
		    				$reportCustomerIDView = $rowReportView["customerID"];
		    				$reportHandymanIDView = $rowReportView["handymanID"];
		    				$reportHandymanNameView = $rowReportView["handymanName"];
		    				$reportHandymanReportNo = $rowReportView["reportNo"];
		    				$reportHandymanEmailView = $rowReportView["handymanEmail"];
		    				$reportHandymanContactView = $rowReportView["handymanContactNo"];
		    				$reportTransactionServiceView = $rowReportView["transactionServiceView"];
		    				$reportServiceDescriptionsView = $rowReportView["transactionServiceDescView"];
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
							    <td class = "tdName">Report ID</td>
							    <td class = "tdInput"><?php echo $reportIDView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Report Type</td>
							    <td class = "tdInput"><?php echo $reportTypeView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Description</td>
							    <td class = "tdInput"><?php echo $reportDescView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Date Reported</td>
							    <td class = "tdInput"><?php echo $reportDateView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Comment</td>
							    <td class = "tdInput"><?php echo $reportCommentView;?></td>
							</tr>
						</table>
					    <div class = "handymanWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Handyman Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<table class = "tableInputs">
		    					<col width="150">
								<tr class = "trInputs">
								    <td class = "tdName">User no</td>
								    <td class = "tdInput"><?php echo $reportHandymanIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $reportHandymanNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Reported Times</td>
								    <td class = "tdInput"><?php echo $reportHandymanReportNo;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><?php echo $reportHandymanEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $reportHandymanContactView;?></td>
								</tr>
							</table>
					    </div>
					</div>
					<div class = "transactionWholeInfo">
						<div id = number class = customerDetailsTitle>
							<b>Transaction Information </b>
						</div>
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
									    <td class = "tdName">Description</td>
									    <td class = "tdInput"><?php echo $reportServiceDescriptionsView;?></td>
									</tr>
								</table>
							</div>
							<div class = "transactionWholeInfoTwo">
								<table class = "tableInputs">
			    					<col width="130">
									<tr class = "trInputs">
									    <td class = "tdName">Date and Time</td>
									    <td class = "tdInput"><?php echo $reportTransactionDateView;?></td>
									</tr>
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
					<button id='proceedBtn' class = 'proceedBig' onclick='viewReview()'> Proceed... </button>
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

									foreach($results as $rowNotificationType){
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
					$sendUserID = $_SESSION['customerIDSpeReportSet'];
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

					echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=customerViewReports&sort=".$_SESSION['customerViewSpeReportsLink']."&customerID=".$_SESSION['customerIDSpeReportSet'].$optionSearch.$search."';</script>";
				}
			?>
		</div>
		</form>

		<script>
			function viewReview(){
			    // Get the modal
			    var modal = document.getElementById('viewReviewModal');
			    var modalClose = document.getElementById('viewModal');
			    // Get the button that opens the modal
			    var btn = document.getElementById('proceedBtn');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName("viewReviewClose")[0];

			    // When the user clicks the button, open the modal 
			   	modal.style.display = "block";
			   	modalClose.style.display = "none";
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			    	var customerID = '<?php if(isset($_SESSION['customerIDSpeReportSet'])){echo '&customerID=' . $_SESSION['customerIDSpeReportSet'];}?>'
			    	var linkSort = '<?php if(isset($_SESSION['customerViewSpeReportsLink'])){echo '&sort=' . $_SESSION['customerViewSpeReportsLink'];}?>'
					var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
					var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
					var url = "index?route=customerViewReports"+customerID+linkSort+search+optionSearch
					location.replace(url);
			        modal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			        	var customerID = '<?php if(isset($_SESSION['customerIDSpeReportSet'])){echo '&customerID=' . $_SESSION['customerIDSpeReportSet'];}?>'
				    	var linkSort = '<?php if(isset($_SESSION['customerViewSpeReportsLink'])){echo '&sort=' . $_SESSION['customerViewSpeReportsLink'];}?>'
						var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
						var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
						var url = "index?route=customerViewReports"+customerID+linkSort+search+optionSearch
						location.replace(url);
				        modal.style.display = "none";
			        }
			    }
			}

			function viewModal(){
				var table = document.getElementById('transactionTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var reportID = this.cells[0].innerHTML;
							var customerID = '<?php if(isset($_SESSION['customerIDSpeReportSet'])){echo '&serviceID=' . $_SESSION['customerIDSpeReportSet'];}?>'
							var linkSort = '<?php if(isset($_SESSION['customerViewSpeReportsLink'])){echo '&sort=' . $_SESSION['customerViewSpeReportsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=customerViewReports&reportID="+reportID+customerID+linkSort+search+optionSearch);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			window.onload = function(){

				var view = localStorage.getItem('viewModal');
				if (view == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				var customerID = '<?php if(isset($_SESSION['customerIDSpeReportSet'])){echo '&serviceID=' . $_SESSION['customerIDSpeReportSet'];}?>'
				var linkSort = '<?php if(isset($_SESSION['customerViewSpeReportsLink'])){echo '&sort=' . $_SESSION['customerViewSpeReportsLink'];}?>'
				var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
				var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
				var url = 'index?route=customerViewReports'+customerID+linkSort+search+optionSearch
				location.replace(url);
				document.getElementById('viewModal').style.display = "none";
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