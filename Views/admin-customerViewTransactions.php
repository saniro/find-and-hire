<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerViewTransactionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerViewTransactionsModalStyles.css">
	</head>
	<body>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<?php
			if(!isset($_SESSION['customerViewTransactionsLink'])){
				$_SESSION['customerViewTransactionsLink'] = "";
			}

			if(isset($_GET['customerID'])){
				$_SESSION['customerIDSet'] = $_GET['customerID'];

			}

			if(isset($_SESSION['customerIDSet'])){
				$customerIDSet = "&customerID=" . $_SESSION['customerIDSet'];
			}

			$sqlCustomerInfo = "SELECT firstName, middleName, lastName, gender, birthDate, email, contact FROM users WHERE userID = (:customerIDSet)";

			$stmt = $con->prepare($sqlCustomerInfo);
			$stmt->bindParam(':customerIDSet', $_SESSION['customerIDSet'], PDO::PARAM_INT);
			$stmt->execute();
			$rowCustomerInfo = $stmt->fetch();

			$customerFirstName = $rowCustomerInfo["firstName"];
			$customerMiddleName = $rowCustomerInfo["middleName"];
			$customerLastName = $rowCustomerInfo["lastName"];
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
							    <td class = "tdName">Customer No</td>
							    <td class = "tdInput"><?php echo $_SESSION['customerIDSet']; ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Name</td>
							    <td class = "tdInput"><?php echo $customerLastName . ", " . $customerFirstName . " " . $customerMiddleName; ?></td>
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
													<a href="index?route=customerViewTransactions&sort=transactionID<?php echo $customerIDSet.$optionSearch.$search;?>">ID</a>
		      										<a href="index?route=customerViewTransactions&sort=handymanName<?php echo $customerIDSet.$optionSearch.$search;?>">Handyman</a>
		      										<a href="index?route=customerViewTransactions&sort=serviceName<?php echo $customerIDSet.$optionSearch.$search;?>">Service</a>
		      										<a href=index?route=customerViewTransactions&sort=date<?php echo $customerIDSet.$optionSearch.$search;?>>Date</a>
		    									</div>
		    									</form>
		  									</li>
										</ul>
									</div>
								</th>
								<th class = "searchCol" colspan="2">
									<form method = "get">
									<div class = "searchClass">
											<a href = "index?route=customerViewTransactions<?php echo $customerIDSet; ?>&sort=transactionID"><img class = "resetAction" src="Resources/reset.png"></a>
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
														if($_GET['optionSearch'] == 'lastName'){
															echo " selected ";
														}
													}
												?>
												value="lastName">Handyman</option>
												<option
												<?php
													if(isset($_GET['optionSearch'])){
														if($_GET['optionSearch'] == 'name'){
															echo " selected ";
														}
													}
												?>
												value="name">Service Name</option>
												<option
												<?php
													if(isset($_GET['optionSearch'])){
														if($_GET['optionSearch'] == 'date'){
															echo " selected ";
														}
													}
												?>
												value="date">Date</option>
											</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..."><input type = "hidden" name = "route" value = "customerViewTransactions">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
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
								<th> Handyman </th>
								<th> Service </th>
								<th> Date </th>
								<th colspan="1" class = 'actionStyle'> Action </th>
							</tr>				
						</thead>
						<tbody>
							<?php
								$sqlCustomerTransaction = "SELECT transactionID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = TN.handymanID) AS handymanName, (SELECT SS.name FROM services AS SS WHERE SS.serviceID = (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID)) AS serviceName, (SELECT date FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date FROM transaction AS TN WHERE (SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :customerIDSet";
								if(isset($_GET['optionSearch'])){
									//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
			    					$sqlCustomerTransaction .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
								}

								if(isset($_GET['sort'])){
									if ($_GET['sort'] == 'transactionID'){
			    						$sqlCustomerTransaction .= " ORDER BY transactionID";
			    						$_SESSION['customerViewTransactionsLink'] = "transactionID";
									}
									elseif ($_GET['sort'] == 'handymanName'){
									    $sqlCustomerTransaction .= " ORDER BY handymanName";
									    $_SESSION['customerViewTransactionsLink'] = "handymanName";
									}
									elseif ($_GET['sort'] == 'serviceName'){
									    $sqlCustomerTransaction .= " ORDER BY serviceName";
									    $_SESSION['customerViewTransactionsLink'] = "serviceName";
									}
									elseif ($_GET['sort'] == 'date'){
									    $sqlCustomerTransaction .= " ORDER BY date desc";
									    $_SESSION['customerViewTransactionsLink'] = "date";
									}
								}
								$stmt = $con->prepare($sqlCustomerTransaction);
								$stmt->bindParam(':customerIDSet', $_SESSION['customerIDSet'], PDO::PARAM_INT);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach($results as $rowCustomerTransaction){
									$customerTransactionID = $rowCustomerTransaction["transactionID"];
				    				$customerTransactionHandymanName = $rowCustomerTransaction["handymanName"];
				    				$customerTransactionServiceName = $rowCustomerTransaction["serviceName"];
				    				$customerTransactionDate = $rowCustomerTransaction["date"];
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $customerTransactionID; ?></td>
							        	<td><?php echo $customerTransactionHandymanName; ?></td>
							        	<td><?php echo $customerTransactionServiceName; ?></td>
							        	<td><?php echo $customerTransactionDate; ?></td>
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
    							handymanID, 
    							(SELECT profilepicture FROM users AS US WHERE US.userID = TN.handymanID) AS profilepicture, 
    							(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = TN.handymanID) AS handymanName, 
    							(SELECT email FROM users AS US WHERE US.userID = TN.handymanID) AS handymanEmail, (SELECT contact FROM users AS US WHERE US.userID = TN.handymanID) AS handymanContactNo, 
    							(SELECT SS.name FROM services AS SS WHERE SS.serviceID = (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID)) AS serviceName, 
    							(SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS groupChoicesID, 
    							(SELECT DATE_FORMAT(date,'%b %d, %Y %r') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS date, 
    							(SELECT amount FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS amount, 
    							(SELECT remarks FROM booking AS BG WHERE BG.bookingID = TN.bookingID) AS remarks, timeIn, timeOut
    							FROM transaction AS TN WHERE transactionID = (:transactionID) AND (SELECT customerID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = (:customerIDSet)";

    							$stmt = $con->prepare($sqlTransactionModal);
								$stmt->bindParam(':transactionID', $_GET['transactionID'], PDO::PARAM_INT);
								$stmt->bindParam(':customerIDSet', $_SESSION['customerIDSet'], PDO::PARAM_INT);
								$stmt->execute();
								$rowTransactionModal = $stmt->fetch();

								$transactionID = $rowTransactionModal["transactionID"];
								if(empty($rowTransactionModal["profilepicture"])){
									$transactionProfilePicture = "Resources/userIcon.png";
								}
								else{
									$transactionProfilePicture = $rowTransactionModal["profilepicture"];
								}
								$transactionHandymanID = $rowTransactionModal["handymanID"];
			    				$transactionHandymanName = $rowTransactionModal["handymanName"];
			    				$transactionHandymanEmail = $rowTransactionModal["handymanEmail"];
			    				$transactionHandymanContact = $rowTransactionModal["handymanContactNo"];
			    				$transactionServiceName = $rowTransactionModal["serviceName"];
			    				$transactionGroupChoicesID = $rowTransactionModal["groupChoicesID"];
			    				$transactionDate = $rowTransactionModal["date"];
			    				$transactionAmount = $rowTransactionModal["amount"];
			    				$transactionRemarks = $rowTransactionModal["remarks"];
			    				$transactionRemarks = $rowTransactionModal["timeIn"];
			    				$transactionRemarks = $rowTransactionModal["timeOut"];
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
								<b>Handyman Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="<?php echo $transactionProfilePicture; ?>">
    						</div>

    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User ID</td>
								    <td class = "tdInput"><?php echo $transactionHandymanID;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $transactionHandymanName;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><?php echo $transactionHandymanEmail;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $transactionHandymanContact;?></td>
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
							var customerID = '<?php if(isset($_SESSION['customerIDSet'])){echo '&serviceID=' . $_SESSION['customerIDSet'];}?>'
							var linkSort = '<?php if(isset($_SESSION['customerViewTransactionsLink'])){echo '&sort=' . $_SESSION['customerViewTransactionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=customerViewTransactions&transactionID="+transactionID+customerID+linkSort+search+optionSearch);
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