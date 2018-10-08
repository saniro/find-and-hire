<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-handymanAccountsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/modal/admin-handymanAccountsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['paymentHistoLink'] = "";
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
				<div class= "title"> PAYMENTS HISTORIES - Handyman </div>
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
	      										<a href="index?route=paymentHisto&sort=paymentID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=paymentHisto&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=paymentHisto&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=paymentHisto&sort=amount<?php echo $optionSearch . $search; ?>">Amount</a>
	      										<a href="index?route=paymentHisto&sort=datePaid<?php echo $optionSearch . $search; ?>">Date Paid</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=paymentHisto"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'paymentID'){
														echo " selected ";
													}
												}
											?>value="paymentID">ID</option>
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
													if($_GET['optionSearch'] == 'amount'){
														echo " selected ";
													}
												}
											?>
											value="amount">Amount</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'datePaid'){
														echo " selected ";
													}
												}
											?>
											value="datePaid">Date Paid</option>

										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="paymentHisto">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
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
							<th> Date Paid </th>
						</tr>				
					</thead>
						<tbody>
							<?php
								$sqlPayments = "SELECT paymentID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PT.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = PT.userID) AS email, amount, datePaid, dueDate FROM payment AS PT WHERE flag = 0";
								if(isset($_GET['optionSearch'])){
									//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
									if($_GET['optionSearch'] == 'paymentID'){
										$optionSearchVar = "paymentID";
									}
									elseif($_GET['optionSearch'] == 'name'){
										$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PT.userID)";
									}
									elseif($_GET['optionSearch'] == 'email'){
										$optionSearchVar = "(SELECT email FROM users AS US WHERE US.userID = PT.userID)";
									}
									elseif($_GET['optionSearch'] == 'amount'){
										$optionSearchVar = "amount";
									}
									elseif($_GET['optionSearch'] == 'datePaid'){
										$optionSearchVar = "datePaid";
									}
			    					$sqlPayments .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}

								if(isset($_GET['sort'])){
									if ($_GET['sort'] == 'paymentID'){
			    						$sqlPayments .= " ORDER BY paymentID";
			    						$_SESSION['paymentHistoLink'] = "paymentID";
									}
									elseif ($_GET['sort'] == 'name')
									{
									    $sqlPayments .= " ORDER BY name";
									    $_SESSION['paymentHistoLink'] = "name";
									}
									elseif ($_GET['sort'] == 'email')
									{
									    $sqlPayments .= " ORDER BY email";
									    $_SESSION['paymentHistoLink'] = "email";
									}
									elseif ($_GET['sort'] == 'amount')
									{
									    $sqlPayments .= " ORDER BY amount";
									    $_SESSION['paymentHistoLink'] = "amount";
									}
									elseif ($_GET['sort'] == 'datePaid')
									{
									    $sqlPayments .= " ORDER BY datePaid DESC";
									    $_SESSION['paymentHistoLink'] = "datePaid";
									}
								}

								$stmt = $con->prepare($sqlPayments);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach($results as $rowPayment){
									$paymentUserID = $rowPayment["paymentID"];
				    				$paymentHandymanName = $rowPayment["name"];
				    				$paymentHandymanEmail = $rowPayment["email"];
				    				$paymentAmount = $rowPayment["amount"];
				    				$paymentDatePaid = $rowPayment["datePaid"];
				    				
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $paymentUserID; ?></td>
							        	<td><?php echo $paymentHandymanName; ?></td>
							        	<td><?php echo $paymentHandymanEmail; ?></td>
							        	<td><?php echo $paymentAmount; ?></td>
							        	<td><?php echo $paymentDatePaid; ?></td>
							        </tr>
							<?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = '5'> No results. </td>";
								}
							?>	
						</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>