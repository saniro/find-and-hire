<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-handymanViewTransactionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-handymanViewTransactionsModalStyles.css">
	</head>
	<body>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<?php
			if(!isset($_SESSION['handymanViewTransactionsLink'])){
				$_SESSION['handymanViewTransactionsLink'] = "";
			}

			if(isset($_GET['handymanID'])){
				$_SESSION['handymanIDSet'] = $_GET['handymanID'];

			}

			if(isset($_SESSION['handymanIDSet'])){
				$handymanIDSet = "&handymanID=" . $_SESSION['handymanIDSet'];
			}

			$sqlHandymanInfo = "SELECT firstName, middleName, lastName, gender, birthDate, email, contact FROM users WHERE userID = (:handymanIDSet)";

			$stmt = $con->prepare($sqlHandymanInfo);
			$stmt->bindParam(':handymanIDSet', $_SESSION['handymanIDSet'], PDO::PARAM_INT);
			$stmt->execute();
			$rowHandymanInfo = $stmt->fetch();

			$handymanUserID = $rowHandymanInfo["userID"];
			$handymanFirstName = $rowHandymanInfo["firstName"];
			$handymanMiddleName = $rowHandymanInfo["middleName"];
			$handymanLastName = $rowHandymanInfo["lastName"];
			if ($rowHandymanInfo["gender"] == 1){
				$handymanGender = "Male";
			}
			elseif ($rowHandymanInfo["gender"] == 0){
				$handymanGender = "Female";
			}
			$handymanBirthdate = $rowHandymanInfo["birthDate"];
			$handymanEmail = $rowHandymanInfo["email"];
			$handymanContact = $rowHandymanInfo["contact"];
		?>
		<div>
			<div class = "sideNavigation">
				<?php
					require("admin-sidebar.php");
				?>
			</div>
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
							    <td class = "tdName">Handyman No</td>
							    <td class = "tdInput"><?php echo $_SESSION['handymanIDSet']; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Name</td>
							    <td class = "tdInput"><?php echo $handymanLastName . ", " . $handymanFirstName . " " . $handymanMiddleName; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Gender</td>
							    <td class = "tdInput"><?php echo $handymanGender; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Birthdate</td>
							    <td class = "tdInput"><?php echo $handymanBirthdate; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Email</td>
							    <td class = "tdInput max"><?php echo $handymanEmail; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Contact No</td>
							    <td class = "tdInput"><?php echo $handymanContact; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<?php
					if(isset($_POST['backButton'])){
						echo "<script type='text/javascript'>window.location.href='index?route=handymanAccounts';</script>";
					}
				?>
				<div class = "contents">
					<table id = actionTable>
						<col width = "250">
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
													<a href="index?route=handymanViewTransactions&sort=transactionID<?php echo $handymanIDSet.$optionSearch.$search;?>">ID</a>
		      										<a href="index?route=handymanViewTransactions&sort=customerName<?php echo $handymanIDSet.$optionSearch.$search;?>">Customer</a>
		      										<a href="index?route=handymanViewTransactions&sort=serviceName<?php echo $handymanIDSet.$optionSearch.$search;?>">Service</a>
		      										<a href="index?route=handymanViewTransactions&sort=date<?php echo $handymanIDSet.$optionSearch.$search;?>">Date</a>
		    									</div>
		    									</form>
		  									</li>
										</ul>
									</div>
								</th>
								<th class = "searchCol" colspan="2">
									<form method = "get">
									<div class = "searchClass">
											<a href = "index?route=handymanViewTransactions<?php echo $handymanIDSet; ?>&sort=transactionID"><img class = "resetAction" src="Resources/reset.png"></a>
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
												value="customerName">Customer</option>
												<option
												<?php
													if(isset($_GET['optionSearch'])){
														if($_GET['optionSearch'] == 'serviceName'){
															echo " selected ";
														}
													}
												?>
												value="serviceName">Service Name</option>
												<option
												<?php
													if(isset($_GET['optionSearch'])){
														if($_GET['optionSearch'] == 'date'){
															echo " selected ";
														}
													}
												?>
												value="date">Date</option>
											</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..."><input type = "hidden" name = "route" value = "handymanViewTransactions">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
									</div>
								</form>
								</th>
							</tr>
						</thead>
					</table>

					<table id = transactionTable>
						<col width = "50">
						<col width = "210">
						<col width = "100">
						<col width = "110">
						<col width = "40">
						<thead>
							<tr>
								<th> ID </th>
								<th> Customer </th>
								<th> Service </th>
								<th> Date </th>
								<th colspan="1" class = 'actionStyle'> Action </th>
							</tr>				
						</thead>
						<tbody>
							<?php
								$sqlHandymanTransaction = "SELECT transactionID, (SELECT (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerName, (SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS serviceName, (SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, (CASE WHEN status = 0 THEN 'Cancelled' WHEN status = 1 THEN 'Accepted' ELSE 'None' END) AS status FROM transaction AS TN WHERE handymanID = (:handymanUserID)";
								if(isset($_GET['optionSearch'])){
									//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
			    					$sqlHandymanTransaction .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
								}

								if(isset($_GET['sort'])){
									if ($_GET['sort'] == 'transactionID'){
			    						$sqlHandymanTransaction .= " ORDER BY transactionID";
			    						$_SESSION['customerViewTransactionsLink'] = "transactionID";
									}
									elseif ($_GET['sort'] == 'lastName')
									{
									    $sqlHandymanTransaction .= " ORDER BY lastName";
									    $_SESSION['customerViewTransactionsLink'] = "lastName";
									}
									elseif ($_GET['sort'] == 'serviceName')
									{
									    $sqlHandymanTransaction .= " ORDER BY serviceName";
									    $_SESSION['customerViewTransactionsLink'] = "serviceName";
									}
									elseif ($_GET['sort'] == 'date')
									{
									    $sqlHandymanTransaction .= " ORDER BY date desc";
									    $_SESSION['customerViewTransactionsLink'] = "date";
									}
								}

								$stmt = $con->prepare($sqlHandymanTransaction);
								$stmt->bindParam(':handymanUserID', $_SESSION['handymanIDSet'], PDO::PARAM_INT);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach ($results as $rowHandymanTransaction) {
									$handymanTransactionID = $rowHandymanTransaction["transactionID"];
				    				$handymanTransactionCustomerName = $rowHandymanTransaction["customerName"];
				    				$handymanTransactionServiceName = $rowHandymanTransaction["serviceName"];
				    				$handymanTransactionDate = $rowHandymanTransaction["date"];
									?>
									<tr class = "tableContent">
							        	<td><?php echo $handymanTransactionID; ?></td>
							        	<td><?php echo $handymanTransactionCustomerName; ?></td>
							        	<td><?php echo $handymanTransactionServiceName; ?></td>
							        	<td><?php echo $handymanTransactionDate; ?></td>
							        	<td><button id='view' class = 'view' onclick='viewModal()'>View</button></td></td>
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
		</div>
		<div id="viewModal" class="viewModal">
  			<div class="viewModal-content">
    			<span class="viewClose">&times;</span>
    				<div class = "details">
    				<div class = "titleDetails"><b>View Full Details</b></div>
					<?php
						if(isset($_GET['transactionID'])){
							$sqlTransactionModal = "SELECT 
							transactionID, 
							(SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerID, 
							(SELECT (SELECT profilepicture FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS profilepicture, 
							(SELECT (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerName, 
							(SELECT (SELECT email FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerEmail, 
							(SELECT (SELECT contact FROM users AS US WHERE US.userID = BG.customerID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS customerContactNo, 
							(SELECT (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS serviceName, 
							(SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS groupChoicesID, 
    						(SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, 
    						(SELECT amount FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS amount, 
    						(SELECT remarks FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS remarks, timeIn, timeOut 
							FROM transaction AS TN WHERE transactionID = (:transactionID) AND handymanID = (:handymanUserID)";

							$stmt = $con->prepare($sqlTransactionModal);
							$stmt->bindParam(':transactionID', $_GET['transactionID'], PDO::PARAM_INT);
							$stmt->bindParam(':handymanUserID', $_SESSION['handymanIDSet'], PDO::PARAM_INT);
							$stmt->execute();
							$rowTransactionModal = $stmt->fetch();

							$transactionID = $rowTransactionModal["transactionID"];
							$transactionCustomerID = $rowTransactionModal["customerID"];
							if(empty($rowTransactionModal["profilepicture"])){
								$transactionProfilePicture = "Resources/userIcon.png";
							}
							else{
								$transactionProfilePicture = $rowTransactionModal["profilepicture"];
							}
		    				$transactionCustomerName = $rowTransactionModal["customerName"];
		    				$transactionCustomerEmail = $rowTransactionModal["customerEmail"];
		    				$transactionCustomerContact = $rowTransactionModal["customerContactNo"];
		    				$transactionServiceName = $rowTransactionModal["serviceName"];
		    				$transactionGroupChoicesID = $rowTransactionModal["groupChoicesID"];
		    				$transactionDate = $rowTransactionModal["date"];
		    				$transactionTimeIn = $rowTransactionModal["timeIn"];
		    				$transactionTimeOut = $rowTransactionModal["timeOut"];
		    				$transactionAmount = $rowTransactionModal["amount"];
		    				$transactionRemarks = $rowTransactionModal["remarks"];
						}
					?>
    				<div class = "wholeModal">
    					<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Transaction ID</td>
							    <td class = "tdInput"><?php echo $transactionID;?></td>
							</tr>
						</table>
					    <div class = "handymanWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Customer Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="<?php echo $transactionProfilePicture; ?>">
    						</div>

    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User ID</td>
								    <td class = "tdInput"><?php echo $transactionCustomerID;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $transactionCustomerName;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><?php echo $transactionCustomerEmail;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $transactionCustomerContact;?></td>
								</tr>
							</table>
					    </div>
					</div>
					<div class = "transactionInfo">
						<table class = "tableInputs">
	    					<col width="130">
							<tr class = "trInputs">
							    <td class = "tdName">Service Type</td>
							    <td class = "tdInput"><?php echo $transactionServiceName;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Description</td>
							    <td class = "tdInput">
							   	<?php 
						    		$sqlSelectedTitle = "SELECT (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS titleID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS title FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :transactionID)) GROUP BY (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID))";

									$stmt = $con->prepare($sqlSelectedTitle);
									$stmt->bindParam(':transactionID', $transactionID, PDO::PARAM_INT);
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
										$sqlSelectedChoices = "SELECT (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS amount FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction WHERE transactionID = :transactionID)) AND (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) = :selectedTitleID";

										$stmt = $con->prepare($sqlSelectedChoices);
										$stmt->bindParam(':transactionID', $transactionID, PDO::PARAM_INT);
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
							    <td class = "tdInput"><?php echo $transactionDate;?></td>
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
							    <td class = "tdInput"><?php echo $transactionAmount;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Remarks</td>
							    <td class = "tdInput"><?php echo $transactionRemarks;?></td>
							</tr>
						</table>
					</div>
				</div>
  			</div>
		</div>
	
		<script>
			function viewModal(){
				var table = document.getElementById('transactionTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var transactionID = this.cells[0].innerHTML;
							var handymanID = '<?php if(isset($_SESSION['handymanIDSet'])){echo '&serviceID=' . $_SESSION['handymanIDSet'];}?>'
							var linkSort = '<?php if(isset($_SESSION['handymanViewTransactionsLink'])){echo '&sort=' . $_SESSION['handymanViewTransactionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=handymanViewTransactions&transactionID="+transactionID+handymanID+linkSort+search+optionSearch);
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