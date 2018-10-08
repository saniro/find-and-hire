<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminAccountID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		 <link rel="stylesheet" type="text/css" href="Styles/admin-transactionsStyles.css">
		 <link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<?php
			require("admin-header.php");
		?>
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
    										<a href="admin-transactions.php?sort=accountID<?php echo $optionSearch . $search; ?>">Transaction No.</a>
      										<a href="admin-transactions.php?sort=accountID<?php echo $optionSearch . $search; ?>">Customer's Lastname</a>
      										<a href="admin-transactions.php?sort=lastName<?php echo $optionSearch . $search; ?>">Handyman's Lastname</a>
      										<a href="admin-transactions.php?sort=lastName<?php echo $optionSearch . $search; ?>">Service Type</a>
      										<a href="admin-transactions.php?sort=lastName<?php echo $optionSearch . $search; ?>">Date</a>
    									</div>
  									</li>
  									
								</ul>
							</div>
						</th>
						<th class = "searchCol" colspan="2">
							<form method = "get">
							<div class = "searchClass">
									<a href = "admin-adminAccounts.php"><img class = "resetAction" src="Resources/reset.png"></a>
									<select class = "optionSearch" name = "optionSearch">
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'PI.accountID'){
													echo " selected ";
												}
											}
										?>value="PI.accountID">Transaction No.</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'firstName'){
													echo " selected ";
												}
											}
										?>
										value="firstName">Customer's Lastname</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'lastName'){
													echo " selected ";
												}
											}
										?>
										value="lastName">Handyman's Lastname</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'lastName'){
													echo " selected ";
												}
											}
										?>
										value="lastName">Service Type</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'lastName'){
													echo " selected ";
												}
											}
										?>
										value="lastName">Date</option>
									</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here...">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
							</div>
						</form>
						</th>
					</tr>
				</thead>
			</table>
			<table id = userTable>
				<col width = 150>
				<col width = 230>
				<col width = 230>
				<col width = 150>
				<col width = 150>
				<thead>
					<tr>
						<th> Transaction No. </th>
						<th> Customer Name </th>
						<th> Handyman Name </th>
						<th> Service Type </th>
						<th> Date </th>
						<th> Action </th>
					</tr>				
				</thead>
				<tbody>
				<?php
						$sqlTransaction = "SELECT transactionID, customerID, handymanID, optionsID, date, amount, remarks, status FROM transaction";
						/*if(isset($_GET['searchInput'])){
	    					$sqlInfo .= " WHERE " . $_POST;
						}
						if(isset($_GET['sort'])){
							if ($_GET['sort'] == 'accountID'){
	    						$sqlInfo .= " ORDER BY accountID";
	    						$_SESSION['link'] = "admin-customerAccounts.php?sort=accountID";
							}
							elseif ($_GET['sort'] == 'lastname')
							{
							    $sqlInfo .= " ORDER BY lastName";
							    $_SESSION['link'] = "admin-customerAccounts.php?sort=lastname";
							}
						}*/

						$resultTransaction = $con->query($sqlTransaction);

						if ($resultTransaction->num_rows > 0) {
			    		// output data of each row
		    			while($rowTransaction = $resultTransaction->fetch_assoc()) {
		    				$transactionID = $rowTransaction["transactionID"];
		    				$transactionCustomerID = $rowTransaction["customerID"];
		    				$transactionHandymanID = $rowTransaction["handymanID"];
		    				$transactionOptionsID = $rowTransaction["optionsID"];
		    				$transactionDate = $rowTransaction["date"];
		    				$transactionAmount = $rowTransaction["amount"];
		    				$transactionRemarks = $rowTransaction["remarks"];
		    				$transactionStatus = $rowTransaction["status"];

		    				$sqlCustomer = "SELECT firstName, middleName, lastName FROM personalinfo WHERE personalID = '$transactionCustomerID'";
		    				$resultCustomer = $con->query($sqlCustomer);
							if ($resultCustomer->num_rows > 0) {
				    		// output data of each row
				    			while($rowCustomer = $resultCustomer->fetch_assoc()) {
				    				$customerFirstName = $rowCustomer["firstName"];
		    						$customerMiddleName = $rowCustomer["middleName"];
		    						$customerLastName = $rowCustomer["lastName"];
				    			}
				    		}

				    		$sqlHandyman = "SELECT firstName, middleName, lastName FROM personalinfo WHERE personalID = '$transactionHandymanID'";
		    				$resultHandyman = $con->query($sqlHandyman);
							if ($resultHandyman->num_rows > 0) {
				    		// output data of each row
				    			while($rowHandyman = $resultHandyman->fetch_assoc()) {
				    				$handymanFirstName = $rowHandyman["firstName"];
		    						$handymanMiddleName = $rowHandyman["middleName"];
		    						$handymanLastName = $rowHandyman["lastName"];
				    			}
				    		}

				    		$sqlOptionService = "SELECT SS.name FROM transaction AS TN, options AS OS, services AS SS WHERE TN.optionsID = '$transactionOptionsID' AND (TN.optionsID = OS.optionsID AND OS.serviceID = SS.serviceID)";
		    				$resultOptionService = $con->query($sqlOptionService);
							if ($resultOptionService->num_rows > 0) {
				    		// output data of each row
				    			while($rowOptionService = $resultOptionService->fetch_assoc()) {
				    				$optionServiceName = $rowOptionService["name"];
				    			}
				    		}
				    	?>
				      	<tr class = "tableContent">
				        	<td><?php echo $transactionID; ?></td>
				        	<td><?php echo $customerLastName . ", " . $customerFirstName . " " . $customerMiddleName; ?></td>
				        	<td><?php echo $handymanLastName . ", " . $handymanFirstName . " " . $handymanMiddleName; ?></td>
				        	<td><?php echo $optionServiceName; ?></td>
				        	<td><?php echo $transactionDate; ?></td>
				        	<td><button id='viewBtn' class = 'view' onclick='reply_click(this.id)''>View</button></td>
				        </tr>
				        <?php
				    			}
							} else {
				    			echo "0 results";
							}
						?>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>