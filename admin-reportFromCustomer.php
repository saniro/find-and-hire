<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		 <link rel="stylesheet" type="text/css" href="Styles/admin-reportFromCustomerStyles.css">
		 <link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		 <style>
		 	.viewModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 30px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 60%;
			    margin-bottom: 60px;
			}

			/* The Close Button */
			.viewClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewClose:hover,
			.viewClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewReviewModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 100px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(0,0,0); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewReviewModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewReviewClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewReviewClose:hover,
			.viewReviewClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}
		</style>
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<?php
			require("admin-header.php");
			if(!isset($_SESSION['linkCustomer'])){
				$_SESSION['linkReportCustomer'] = "";
			}
		?>
		<div class = wrapper>
			<div class= "title"> REPORTS - Customer </div>
			<table id = actionTable>
				<col width = "430">
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
    										<a href="admin-reportFromCustomer.php?sort=reportsID<?php echo $optionSearch . $search; ?>">Report No.</a>
      										<a href="admin-reportFromCustomer.php?sort=customerName<?php echo $optionSearch . $search; ?>">Customer's Name</a>
      										<a href="admin-reportFromCustomer.php?sort=reportType<?php echo $optionSearch . $search; ?>">Report Type</a>
      										<a href="admin-reportFromCustomer.php?sort=date<?php echo $optionSearch . $search; ?>">Date</a>
    									</div>
  									</li>
  									
								</ul>
							</div>
						</th>
						<th class = "searchCol" colspan="2">
							<form method = "get">
							<div class = "searchClass">
									<a href = "admin-reportFromCustomer.php"><img class = "resetAction" src="Resources/reset.png"></a>
									<select class = "optionSearch" name = "optionSearch">
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'reportsID'){
													echo " selected ";
												}
											}
										?>value="reportsID">Report No.</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'customerName'){
													echo " selected ";
												}
											}
										?>
										value="customerName">Customer's Name</option>
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
				<col width = 130>
				<col width = 280>
				<col width = 160>
				<col width = 180>
				<thead>
					<tr>
						<th> Report No. </th>
						<th> Reporter </th>
						<th> Report Type </th>
						<th> Date </th>
						<th> Action </th>
					</tr>				
				</thead>
				<tbody>
				<?php
						$sqlReport = "SELECT reportsID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RS.reporterID) AS customerName, (SELECT RT.name from reporttype AS RT WHERE RS.reportTypeID = RT.reportTypeID) AS reportType, date, readFlag FROM reports AS RS WHERE (SELECT Type FROM users AS US WHERE RS.reporterID = US.userID) = 1";
						if(isset($_GET['optionSearch'])){
							if($_GET['optionSearch'] == 'reportsID'){
								$optionSearchVar = "reportsID";
							}
							elseif($_GET['optionSearch'] == 'customerName'){
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
	    						$_SESSION['linkReportCustomer'] = "reportsID";
							}
							elseif ($_GET['sort'] == 'customerName')
							{
							    $sqlReport .= " ORDER BY customerName, reportsID";
							    $_SESSION['linkReportCustomer'] = "customerName";
							}
							elseif ($_GET['sort'] == 'handymanName')
							{
							    $sqlReport .= " ORDER BY handymanName, reportsID";
							    $_SESSION['linkReportCustomer'] = "handymanName";
							}
							elseif ($_GET['sort'] == 'serviceType')
							{
							    $sqlReport .= " ORDER BY serviceName, reportsID";
							    $_SESSION['linkReportCustomer'] = "serviceType";
							}
							elseif ($_GET['sort'] == 'date')
							{
							    $sqlReport .= " ORDER BY date DESC";
							    $_SESSION['linkReportCustomer'] = "date";
							}
						}

						$resultReport = $con->query($sqlReport);

						if ($resultReport->num_rows > 0) {
			    		// output data of each row
		    			while($rowReport = $resultReport->fetch_assoc()) {
		    				$reportID = $rowReport["reportsID"];
		    				$reportCustomerName = $rowReport["customerName"];
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
				        	<td><?php echo $reportCustomerName; ?></td>
				        	<td><?php echo $reportReportType; ?></td>
				        	<td><?php echo $reportDate; ?></td>

				        	<td><button id='viewBtn' class = 'view' onclick='viewModal()'>Read</button></td>
				        </tr>
				        <?php
				    			}
							} else {
				    			echo "<td>No result.</td>";
							}
						?>
				</tbody>
			</table>
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
    						(SELECT name FROM reporttype AS RE WHERE RE.reportTypeID = RT.reportTypeID) AS reportType, 
    						(SELECT description FROM reporttype AS RE WHERE RE.reportTypeID = RT.reportTypeID) AS reportDesc, 
    						date, 
    						comment, 
    						reporterID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = RT.reporterID) AS customerName, 
    						(SELECT email FROM users AS US WHERE US.userID = RT.reporterID) AS customerEmail, 
    						(SELECT contact FROM users AS US WHERE US.userID = RT.reporterID) AS customerContactNo, 
    						(SELECT handymanID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID) AS handymanID, 
    						(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = (SELECT handymanID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID)) AS handymanName, 
    						(SELECT email FROM users AS US WHERE US.userID = (SELECT handymanID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID)) AS handymanEmail, 
    						(SELECT contact FROM users AS US WHERE US.userID = (SELECT handymanID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID)) AS handymanContactNo,
    						transactionID,
    						(SELECT name FROM services WHERE serviceID = (SELECT serviceID FROM options WHERE optionsID = (SELECT optionsID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID))) AS transactionServiceView,
    						(SELECT description FROM options WHERE optionsID = (SELECT optionsID FROM transaction AS TN WHERE TN.transactionID = RT.transactionID)) AS transactionServiceDescView,
    						(SELECT date FROM transaction AS TN WHERE TN.transactionID = RT.transactionID) AS transactionDateView,
    						(SELECT amount FROM transaction AS TN WHERE TN.transactionID = RT.transactionID) AS transactionAmountView,
    						(SELECT remarks FROM transaction AS TN WHERE TN.transactionID = RT.transactionID) AS transactionRemarksView
    						FROM reports AS RT WHERE reportsID = '$reportIDVar'";

							$resultReportView = $con->query($sqlReportView);

							if ($resultReportView->num_rows > 0) {
			    			// output data of each row
			    				while($rowReportView = $resultReportView->fetch_assoc()) {
				    				$reportIDView = $rowReportView["reportsID"];
				    				$reportTypeView = $rowReportView["reportType"];
				    				$reportDescView = $rowReportView["reportDesc"];
				    				$reportDateView = $rowReportView["date"];
				    				$reportCommentView = $rowReportView["comment"];
				    				$_SESSION["reportCustomerIDView"] = $reportCustomerIDView = $rowReportView["reporterID"];
				    				$reportCustomerNameView = $rowReportView["customerName"];
				    				$reportCustomerEmailView = $rowReportView["customerEmail"];
				    				$reportCustomerContactView = $rowReportView["customerContactNo"];
				    				$reportTransactionIDView = $rowReportView["transactionID"];
				    				$reportHandymanIDView = $rowReportView["handymanID"];
				    				$reportHandymanNameView = $rowReportView["handymanName"];
				    				$reportHandymanEmailView = $rowReportView["handymanEmail"];
				    				$reportHandymanContactView = $rowReportView["handymanContactNo"];
				    				$reportTransactionServiceView = $rowReportView["transactionServiceView"];
				    				$reportServiceDescriptionsView = $rowReportView["transactionServiceDescView"];
				    				$reportTransactionDateView = $rowReportView["transactionDateView"];
				    				$reportTransactionAmountView = $rowReportView["transactionAmountView"];
				    				$reportRemarksView = $rowReportView["transactionRemarksView"];
						    	}
						    	$sqlreportsRead = "UPDATE reports
									SET readFlag = 1	
									WHERE reportsID = '$reportIDView'";
									if ($con->query($sqlreportsRead) === TRUE) {
									}
							}
						}
					?>
    				<div class = "wholeModal">
    					<div class = "transactionID">
	    					<div id = number class = customerDetails>
								<b>Report ID: </b><?php echo $reportIDView;?>
							</div>
							<div id = number class = customerDetails>
								<b>Report Type: </b><?php echo $reportTypeView;?>
							</div>
							<div id = number class = customerDetails>
								<b>Description: </b><?php echo $reportDescView;?>
							</div>
							<div id = number class = customerDetails>
								<b>Date Reported: </b><?php echo $reportDateView;?>
							</div>
							<div id = number class = customerDetails>
								<b>Comment: </b><?php echo $reportCommentView;?>
							</div>
						</div>
					    <div class = "customerWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Reporter Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<div id = number class = customerDetails>
								<b>User no: </b><?php echo $reportCustomerIDView;?>
							</div>
							<div id = name class = customerDetails>
								<b>Name: </b><?php echo $reportCustomerNameView;?>
							</div>
							<div id = email class = customerDetails>
								<b>Email Address: </b><?php echo $reportCustomerEmailView;?>
							</div>
							<div id = contactno class = customerDetails>
								<b>Contact Number: </b><?php echo $reportCustomerContactView;?>
							</div>
					    </div>
					    <div class = "handymanWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Handyman Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
    						</div>
    						<div id = number class = customerDetails>
								<b>User no: </b><?php echo $reportHandymanIDView;?>
							</div>
							<div id = name class = customerDetails>
								<b>Name: </b><?php echo $reportHandymanNameView;?>
							</div>
							<div id = email class = customerDetails>
								<b>Email Address: </b><?php echo $reportHandymanEmailView;?>
							</div>
							<div id = contactno class = customerDetails>
								<b>Contact Number: </b><?php echo $reportHandymanContactView;?>
							</div>
					    </div>
					</div>
					<div class = "transactionWholeInfo">
						<div id = number class = customerDetailsTitle>
							<b>Transaction Information </b>
						</div>
						<div class = "transactionInfo">
							<div class = "transactionWholeInfoOne">
								<div id = number class = customerDetails>
									<b>Transaction No: </b><?php echo $reportTransactionIDView;?>
								</div>
						    	<div id = number class = customerDetails>
									<b>Service Type: </b><?php echo $reportTransactionServiceView;?>
								</div>
								<div id = number class = customerDetails>
									<b>Description: </b><?php echo $reportServiceDescriptionsView;?>
								</div>
							</div>
							<div class = "transactionWholeInfoTwo">
								<div id = number class = customerDetails>
									<b>Date and Time: </b><?php echo $reportTransactionDateView;?>
								</div>
								<div id = number class = customerDetails>
									<b>Amount: </b><?php echo $reportTransactionAmountView;?>
								</div>
								<div id = number class = customerDetails>
									<b>Remarks: </b><?php echo $reportRemarksView;?>
								</div>
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
					<div class = "inputs">
						<div id = remarksType class = notificationsRemarks>
							<b>Notification: </b><br>
							<select class = "notifRemarksType" name = "notifRemarksType">
								<?php
									$sqlNotificationType = "SELECT notifTypeID, message FROM notificationtype WHERE flag = 1 AND type = 1";
									$resultNotificationType = $con->query($sqlNotificationType);

									if ($resultNotificationType->num_rows > 0) {
					    			// output data of each row
					    				while($rowNotificationType = $resultNotificationType->fetch_assoc()) {
						    				$notificationTypeID = $rowNotificationType["notifTypeID"];
						    				$notificationTypeMessage = $rowNotificationType["message"];
						    				?>
						    				<option value="<?php if(isset($notificationTypeID)){echo $notificationTypeID;}?>"><?php if(isset($notificationTypeMessage)){echo $notificationTypeMessage;}?></option>
						    				<?php
								    	}
									}
								?>
							</select>
						</div>
						<div id = remarks class = remarksDetails>
							<b>Remarks: </b><br>
							<textarea class = "remarksMessage" name="notifRemarks" placeholder="Add remarks here..." required></textarea>
						</div>
						<div class = buttonSubmit>
  							<button type = "submit" class="addSubmit" name = "sendNotif"> SEND </button>
  						</div>
					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['sendNotif'])){
					$sendUserID = $_SESSION["reportCustomerIDView"];
					$sendRemarksType = $_POST['notifRemarksType'];
					$sendRemarks = $_POST['notifRemarks'];
					$dateToday = date('Y-m-d');
					mysqli_query($con, "INSERT INTO notification (userID, notifTypeID, remarks, dateReceive)values('$sendUserID', '$sendRemarksType', '$sendRemarks', '$dateToday')");
					$_SESSION["reportCustomerIDView"] = "";
					echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='admin-reportFromCustomer.php?sort=".$_SESSION['linkReportCustomer'].$optionSearch.$search."';</script>";
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
			        modal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
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
							var linkSort = '<?php if(isset($_SESSION['linkReportCustomer'])){echo '&sort=' . $_SESSION['linkReportCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "admin-reportFromCustomer.php?reportID="+reportID+linkSort+search+optionSearch
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