<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-transactionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-transactionsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkTransactions'] = "";
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
				<div class= "title"> TRANSACTIONS </div>
				<table id = actionTable>
					<col width = "550">
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
	    										<a href="index?route=transactions&sort=transactionID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=transactions&sort=customerName<?php echo $optionSearch . $search; ?>">Customer Name</a>
	      										<a href="index?route=transactions&sort=handymanName<?php echo $optionSearch . $search; ?>">Handyman Name</a>
	      										<a href="index?route=transactions&sort=serviceType<?php echo $optionSearch . $search; ?>">Service Type</a>
	      										<a href="index?route=transactions&sort=date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
	  									
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=transactions"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'transactionID'){
														echo " selected ";
													}
												}
											?>value="transactionID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'customerName'){
														echo " selected ";
													}
												}
											?>
											value="customerName">Customer Name</option>
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
													if($_GET['optionSearch'] == 'serviceType'){
														echo " selected ";
													}
												}
											?>
											value="serviceType">Service Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'status'){
														echo " selected ";
													}
												}
											?>
											value="status">Status</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'date'){
														echo " selected ";
													}
												}
											?>
											value="date">Date</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="transactions">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = 50>
					<col width = 250>
					<col width = 250>
					<col width = 130>
					<col width = 100>
					<col width = 120>
					<thead>
						<tr>
							<th> ID </th>
							<th> Customer Name </th>
							<th> Handyman Name </th>
							<th> Service Type </th>
							<th> Status </th>
							<th> Date </th>
							<th colspan="2"> Action </th>
						</tr>
					</thead>
					<tbody>
					<?php
							$sqlTransaction = "SELECT transactionID, (SELECT (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerName, (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = TN.handymanID) AS handymanName, (SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS serviceName, (SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, 
							(CASE 
								WHEN status = 0 THEN 'Cancelled' 
								WHEN status = 1 THEN 'Accepted'
								ELSE 'None'
							END) AS status FROM transaction AS TN";
							if(isset($_GET['optionSearch'])){
								if($_GET['optionSearch'] == 'transactionID'){
									$optionSearchVar = "transactionID";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
								elseif($_GET['optionSearch'] == 'customerName'){
									$optionSearchVar = "(SELECT (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID)";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
								elseif($_GET['optionSearch'] == 'handymanName'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = TN.handymanID)";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
								elseif($_GET['optionSearch'] == 'serviceType'){
									$optionSearchVar = "(SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID)";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
								elseif($_GET['optionSearch'] == 'status'){
									$optionSearchVar = "(SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID)";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
								elseif($_GET['optionSearch'] == 'date'){
									$optionSearchVar = "(SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID)";
									$sqlTransaction .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'transactionID'){
		    						$sqlTransaction .= " ORDER BY transactionID";
		    						$_SESSION['linkTransactions'] = "transactionID";
								}
								elseif ($_GET['sort'] == 'customerName')
								{
								    $sqlTransaction .= " ORDER BY customerName, transactionID";
								    $_SESSION['linkTransactions'] = "customerName";
								}
								elseif ($_GET['sort'] == 'handymanName')
								{
								    $sqlTransaction .= " ORDER BY handymanName, transactionID";
								    $_SESSION['linkTransactions'] = "handymanName";
								}
								elseif ($_GET['sort'] == 'serviceType')
								{
								    $sqlTransaction .= " ORDER BY serviceName, transactionID";
								    $_SESSION['linkTransactions'] = "serviceType";
								}
								elseif ($_GET['sort'] == 'date')
								{
								    $sqlTransaction .= " ORDER BY date DESC";
								    $_SESSION['linkTransactions'] = "date";
								}
							}

							$stmt = $con->prepare($sqlTransaction);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowTransaction){
								$transactionID = $rowTransaction["transactionID"];
			    				$transactionCustomerName = $rowTransaction["customerName"];
			    				$transactionHandymanName = $rowTransaction["handymanName"];
			    				$transactionServiceName = $rowTransaction["serviceName"];
			    				$transactionDate = $rowTransaction["date"];
			    				$transactionStatus = $rowTransaction["status"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $transactionID; ?></td>
						        	<td><?php echo $transactionCustomerName; ?></td>
						        	<td><?php echo $transactionHandymanName; ?></td>
						        	<td><?php echo $transactionServiceName; ?></td>
						        	<td><?php echo $transactionStatus; ?></td>
						        	<td><?php echo $transactionDate; ?></td>

						        	<td><button id='viewBtn' class = 'view' onclick='viewModal()'>View</button></td>
						        	<td><button id='feedBtn' class = 'feedback' onclick='feedbackModal()'>Feedbacks</button></td>
						        </tr>
							<?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = '7'> No results. </td>";
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
    					if(isset($_GET['transactionID'])){
    						$transactionIDVar = $_GET['transactionID'];
    						$sqlTransactionView = "SELECT transactionID, 
    						(SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerID, 
    						(SELECT (SELECT profilepicture FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerPic,
    						(SELECT (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerName, 
    						(SELECT (SELECT email FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerEmail, 
    						(SELECT (SELECT contact FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerContactNo, 
    						handymanID, 
    						(SELECT profilepicture FROM users AS US WHERE US.userID = TN.handymanID) AS handymanPic, 
    						(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = TN.handymanID) AS handymanName, 
    						(SELECT email FROM users AS US WHERE US.userID = TN.handymanID) AS handymanEmail, 
    						(SELECT contact FROM users AS US WHERE US.userID = TN.handymanID) AS handymanContactNo, 
    						(SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS serviceName, 
    						(SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS groupChoicesID, 
    						(SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, 
    						(SELECT amount FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS amount, 
    						(SELECT remarks FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS remarks, timeIn, timeOut FROM transaction AS TN WHERE transactionID = (:transactionIDVar)";

    						$stmt = $con->prepare($sqlTransactionView);
    						$stmt->bindParam(':transactionIDVar', $transactionIDVar, PDO::PARAM_INT);
							$stmt->execute();
							$rowTransactionView = $stmt->fetch();

			    			// output data of each row
		    				$transactionIDView = $rowTransactionView["transactionID"];
		    				$transactionCustomerIDView = $rowTransactionView["customerID"];
		    				if(empty($rowTransactionView["customerPic"])){
		    					$transactionCustomerPicView = 'Resources/userIcon.png';
		    				}
		    				else{
		    					$transactionCustomerPicView = $rowTransactionView["customerPic"];
		    				}
		    				$transactionCustomerNameView = $rowTransactionView["customerName"];
		    				$transactionCustomerEmailView = $rowTransactionView["customerEmail"];
		    				$transactionCustomerContactView = $rowTransactionView["customerContactNo"];
		    				$transactionHandymanIDView = $rowTransactionView["handymanID"];
		    				if(empty($rowTransactionView["handymanPic"])){
		    					$transactionHandymanPicView = 'Resources/userIcon.png';
		    				}
		    				else{
		    					$transactionHandymanPicView = $rowTransactionView["handymanPic"];
		    				}
		    				$transactionHandymanNameView = $rowTransactionView["handymanName"];
		    				$transactionHandymanEmailView = $rowTransactionView["handymanEmail"];
		    				$transactionHandymanContactView = $rowTransactionView["handymanContactNo"];
		    				$transactionServiceNameView = $rowTransactionView["serviceName"];
		    				$transactionGroupChoicesID = $rowTransactionView["groupChoicesID"];
		    				$transactionDateView = $rowTransactionView["date"];
		    				$transactionAmountView = $rowTransactionView["amount"];
		    				$transactionRemarksView = $rowTransactionView["remarks"];
		    				$transactionTimeIn = $rowTransactionView["timeIn"];
		    				$transactionTimeOut = $rowTransactionView["timeOut"];
						}
					?>
    				<div class = "wholeModal">
    					<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Transaction ID</td>
							    <td class = "tdInput"><?php echo $transactionIDView;?></td>
							</tr>
						</table>
					    <div class = "customerWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Customer Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="<?php echo $transactionCustomerPicView;?>">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User ID</td>
								    <td class = "tdInput"><?php echo $transactionCustomerIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $transactionCustomerNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName max">Email Address</td>
								    <td class = "tdInput"><?php echo $transactionCustomerEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $transactionCustomerContactView;?></td>
								</tr>
							</table>
					    </div>
					    <div class = "handymanWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Handyman Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="<?php echo $transactionHandymanPicView;?>">
    						</div>

    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User ID</td>
								    <td class = "tdInput"><?php echo $transactionHandymanIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $transactionHandymanNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput max"><?php echo $transactionHandymanEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $transactionHandymanContactView;?></td>
								</tr>
							</table>
					    </div>
					</div>
					<div class = "transactionInfo">
						<table class = "tableInputs">
	    					<col width="130">
							<tr class = "trInputs">
							    <td class = "tdName">Service Type</td>
							    <td class = "tdInput"><?php echo $transactionServiceNameView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Description</td>
							    <td class = "tdInput">
							   	<?php 
						    		$sqlSelectedTitle = "SELECT (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS titleID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS title FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :transactionIDView)) GROUP BY (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID))";

									$stmt = $con->prepare($sqlSelectedTitle);
									$stmt->bindParam(':transactionIDView', $transactionIDView, PDO::PARAM_INT);
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
										$sqlSelectedChoices = "SELECT (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS amount FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :transactionIDView)) AND (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) = :selectedTitleID";

										$stmt = $con->prepare($sqlSelectedChoices);
										$stmt->bindParam(':transactionIDView', $transactionIDView, PDO::PARAM_INT);
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
							    ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Date and Time</td>
							    <td class = "tdInput"><?php echo $transactionDateView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Time In</td>
							    <td class = "tdInput"><?php echo $transactionTimeIn;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Time Out</td>
							    <td class = "tdInput"><?php echo $transactionTimeOut;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Amount</td>
							    <td class = "tdInput"><?php echo $transactionAmountView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Remarks</td>
							    <td class = "tdInput"><?php echo $transactionRemarksView;?></td>
							</tr>
						</table>
					</div>
				</div>
  			</div>
		</div>

		<div id="viewFeedbackModal" class="viewFeedbackModal">
  				<div class="viewFeedbackModal-content">
    				<span class="viewFeedbackClose">&times;</span>
    				<div class = "details">
    				<div class = "titleDetails"><b>Feedbacks</b></div>
    				<?php
    					if(isset($_GET['transactionID'])){
    						$feedBackTransactionIDVar = $_GET['transactionID'];
    						$sqlfeedBackTransactionView = "SELECT transactionID, (SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS serviceName, (SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, (SELECT amount FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS amount, (SELECT remarks FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS remarks, (SELECT count(feedbackID) FROM feedback WHERE transactID = transactionID) AS feedbackCount FROM transaction AS TN WHERE transactionID = (:transactionIDVar)";

    						$stmt = $con->prepare($sqlfeedBackTransactionView);
    						$stmt->bindParam(':transactionIDVar', $transactionIDVar, PDO::PARAM_INT);
							$stmt->execute();
							$rowTransactionFeedBackView = $stmt->fetch();

			    			// output data of each row
		    				$feedBackTransactionIDView = $rowTransactionFeedBackView["transactionID"];
		    				$feedBackTransactionServiceNameView = $rowTransactionFeedBackView["serviceName"];
		    				$feedBackTransactionDateView = $rowTransactionFeedBackView["date"];
		    				$feedBackTransactionAmountView = $rowTransactionFeedBackView["amount"];
		    				$feedBackTransactionRemarksView = $rowTransactionFeedBackView["remarks"];
		    				$feedBackCountView = $rowTransactionFeedBackView["feedbackCount"];

						}
					?>
    				<div class = "wholeModal">
    					<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Transaction ID</td>
							    <td class = "tdInput"><?php echo $feedBackTransactionIDView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Service Type</td>
							    <td class = "tdInput"><?php echo $feedBackTransactionServiceNameView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Date and Time</td>
							    <td class = "tdInput"><?php echo $feedBackTransactionDateView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Amount</td>
							    <td class = "tdInput"><?php echo $feedBackTransactionAmountView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Remarks</td>
							    <td class = "tdInput"><?php echo $feedBackTransactionRemarksView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Feedbacks</td>
							    <td class = "tdInput"><?php echo $feedBackCountView;?></td>
							</tr>
						</table>
					    
						<?php
							if(isset($_GET['transactionID'])){
	    						$feedBackIDVar = $_GET['transactionID'];
	    						$sqlfeedBackView = "SELECT feedbackID, userID, (SELECT Type FROM users AS SS WHERE SS.userID = FB.userID) AS userType,(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS SS WHERE SS.userID = FB.userID) AS name, comment, rating FROM feedback AS FB WHERE transactID = (:transactionIDVar)";

	    						$stmt = $con->prepare($sqlfeedBackView);
	    						$stmt->bindParam(':transactionIDVar', $transactionIDVar, PDO::PARAM_INT);
								$stmt->execute();
								$results = $stmt->fetchAll();

				    			foreach($results as $rowFeedBackView){
				    				$feedBackIDView = $rowFeedBackView["feedbackID"];
				    				$feedBackUserIDView = $rowFeedBackView["userID"];
				    				if($rowFeedBackView["userType"] == 1){
					    				$feedBackUserTypeView = "Customer";
					    			}
					    			else{
					    				$feedBackUserTypeView = "Handyman";
					    			}
				    				$feedBackNameView = $rowFeedBackView["name"];
				    				$feedBackCommentView = $rowFeedBackView["comment"];
				    				$feedBackRating = $rowFeedBackView["rating"];
				    				?>
				    				<div class = "feedBackInfo">
								    	<div id = number class = customerDetails>
											<b>Feedback Information </b>
										</div>
								    	<div class = "profilePicFeedback">
			    							<img class = "profilePic" src="ProfilePictures/userIcon.png">
			    						</div>

			    						<table class = "tableInputs">
					    					<col width="130">
											<tr class = "trInputs">
											    <td class = "tdName">User ID</td>
											    <td class = "tdInput"><?php echo $feedBackUserIDView;?></td>
											</tr>
											<tr class = "trInputs">
											    <td class = "tdName">User Type</td>
											    <td class = "tdInput"><?php echo $feedBackUserTypeView;?></td>
											</tr>
											<tr class = "trInputs">
											    <td class = "tdName">Name</td>
											    <td class = "tdInput"><?php echo $feedBackNameView;?></td>
											</tr>
											<tr class = "trInputs">
											    <td class = "tdName">Comment</td>
											    <td class = "tdInput"><?php echo $feedBackCommentView;?></td>
											</tr>
											<tr class = "trInputs">
											    <td class = "tdName">Rating</td>
											    <td class = "tdInput"><?php echo $feedBackRating;?></td>
											</tr>
											<tr>

											</tr>
										</table>
								    </div>
				    				<?php
				    			}
							}
						?>
					</div>
					<div class = "transactionInfo">
						<table class = "tableInputs">
	    					<col width="130">
							
						</table>
					</div>
				</div>
  			</div>
		</div>

		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var transactionID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkTransactions'])){echo '&sort=' . $_SESSION['linkTransactions'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=transactions&transactionID="+transactionID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function feedbackModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var transactionID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkTransactions'])){echo '&sort=' . $_SESSION['linkTransactions'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=transactions&transactionID="+transactionID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewFeedbackModal',true);
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

				var x = localStorage.getItem('viewFeedbackModal');
				if (x == 'true'){
					document.getElementById('viewFeedbackModal').style.display = "block";
				}
				localStorage.setItem('viewFeedbackModal',false)

			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			var feedbackSpan = document.getElementsByClassName("viewFeedbackClose")[0];
			feedbackSpan.onclick = function() {
				document.getElementById('viewFeedbackModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewFeedbackModal')) {
		            document.getElementById('viewFeedbackModal').style.display = "none";
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