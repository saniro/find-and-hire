<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-reportFromHandymanStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-reportFromHandymanModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkReportHandyman'] = "";
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
				<div class= "title"> REPORTS - Handyman </div>
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
	    										<a href="index?route=reportFromHandyman&sort=reportsID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=reportFromHandyman&sort=handymanName<?php echo $optionSearch . $search; ?>">Handyman Name</a>
	      										<a href="index?route=reportFromHandyman&sort=reportType<?php echo $optionSearch . $search; ?>">Report Type</a>
	      										<a href="index?route=reportFromHandyman&sort=date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=reportFromHandyman"><img class = "resetAction" src="Resources/reset.png"></a>
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
													if($_GET['optionSearch'] == 'handymanName'){
														echo " selected ";
													}
												}
											?>
											value="handymanName">Handyman Name</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportType'){
														echo " selected ";
													}
												}
											?>
											value="reportType">Report Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'date'){
														echo " selected ";
													}
												}
											?>
											value="date">Date</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations.">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = 50>
					<col width = 420>
					<col width = 220>
					<col width = 220>
					<thead>
						<tr>
							<th> ID </th>
							<th> Handyman Name </th>
							<th> Report Type </th>
							<th> Date </th>
							<th> Action </th>
						</tr>				
					</thead>
					<tbody>
					<?php
							$sqlReport = "SELECT reportsID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reporterID) AS handymanName, (SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID) AS reportType, date, readFlag FROM reports AS RS WHERE (SELECT Type FROM users AS US WHERE RS.reporterID = US.userID) = 2";
							if(isset($_GET['optionSearch'])){
								if($_GET['optionSearch'] == 'reportsID'){
									$optionSearchVar = "reportsID";
								}
								elseif($_GET['optionSearch'] == 'handymanName'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reporterID)";
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
								elseif ($_GET['sort'] == 'customerName')
								{
								    $sqlReport .= " ORDER BY customerName, reportsID";
								    $_SESSION['linkReportHandyman'] = "customerName";
								}
								elseif ($_GET['sort'] == 'handymanName')
								{
								    $sqlReport .= " ORDER BY handymanName, reportsID";
								    $_SESSION['linkReportHandyman'] = "handymanName";
								}
								elseif ($_GET['sort'] == 'serviceType')
								{
								    $sqlReport .= " ORDER BY serviceName, reportsID";
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
			    				$reportHandymanName = $rowReport["handymanName"];
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
						        	<td><?php echo $reportReportType; ?></td>
						        	<td><?php echo $reportDate; ?></td>

						        	<td><button id='viewBtn' class = 'view' onclick='viewModal()'> Read </button></td>
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
    						reporterID AS handymanID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users WHERE userID = reporterID) AS handymanName, 
    						(SELECT email FROM users WHERE userID = reporterID) AS handymanEmail, 
    						(SELECT contact FROM users WHERE userID = reporterID) AS handymanContactNo, 
    						reportedID AS customerID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', lastName) FROM users WHERE userID = reportedID) AS customerName, 
    						(SELECT email FROM users WHERE userID = reportedID) AS customerEmail, 
    						(SELECT contact FROM users WHERE userID = reportedID) AS customerContactNo, 
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
		    				$_SESSION["reportHandymanIDView"] = $reportHandymanIDView = $rowReportView["handymanID"];
		    				$reportHandymanNameView = $rowReportView["handymanName"];
		    				$reportHandymanEmailView = $rowReportView["handymanEmail"];
		    				$reportHandymanContactView = $rowReportView["handymanContactNo"];
		    				$reportTransactionIDView = $rowReportView["transactionID"];
		    				$reportCustomerIDView = $rowReportView["customerID"];
		    				$reportCustomerNameView = $rowReportView["customerName"];
		    				$reportCustomerEmailView = $rowReportView["customerEmail"];
		    				$reportCustomerContactView = $rowReportView["customerContactNo"];
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
					    	<div id = number class = handymanDetails>
								<b>Reporter Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User no</td>
								    <td class = "tdInput"><?php echo $reportHandymanIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $reportHandymanNameView;?></td>
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
					    <div class = "customerWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Customer Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User no</td>
								    <td class = "tdInput"><?php echo $reportCustomerIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $reportCustomerNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><?php echo $reportCustomerEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $reportCustomerContactView;?></td>
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
					$sendUserID = $_SESSION["reportHandymanIDView"];
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

					$_SESSION["reportHandymanIDView"] = "";
					echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=reportFromHandyman&sort=".$_SESSION['linkReportHandyman'].$optionSearch.$search."';</script>";
				}
			?>
		</div>
		</form>

		<script type="text/javascript">
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
			    	var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportCustomer'];}?>'
			    	var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
					var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
					var url = "index?route=reportFromHandyman"+linkSort+search+optionSearch
					location.replace(url);
			        modal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			        	var linkSort = '<?php if(isset($_SESSION['linkReportHandyman'])){echo '&sort=' . $_SESSION['linkReportHandyman'];}?>'
						var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
						var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
						var url = "index?route=reportFromHandyman"+linkSort+search+optionSearch
						location.replace(url);
			            modal.style.display = "none";
			        }
			    }
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
							var url = "index?route=reportFromHandyman&reportID="+reportID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
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
				var url = "index?route=reportFromHandyman"+linkSort+search+optionSearch
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