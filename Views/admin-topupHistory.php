<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-topupHistoryStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-topupHistoryModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['historyLink'] = "";
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
				<div class= "title"> TOP UP - History </div>
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
	      										<a href="index?route=history&sort=topupHistoryID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=history&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=history&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=history&sort=value<?php echo $optionSearch . $search; ?>">Amount</a>
	      										<a href="index?route=history&sort=Date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=history"><img class = "resetAction" src="Resources/reset.png"></a>
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

										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="history">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = "50">
					<col width = "380">
					<col width = "380">
					<col width = "130">
					<thead>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Email Address </th>
							<th> Amount </th>
							<th> Status </th>
						</tr>				
					</thead>
						<tbody>
							<?php
								$sqlPending = "SELECT topupHistoryID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = TH.userID) AS email, value, DATE_FORMAT(Date, '%M %d, %Y %r') AS Date, (CASE WHEN status = 1 THEN 'Paid' WHEN status = 2 THEN 'Rejected' END) AS status FROM topuphistory AS TH WHERE (status = 1 OR status = 2)";
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
			    						$_SESSION['historyLink'] = "topupHistoryID";
									}
									elseif ($_GET['sort'] == 'name')
									{
									    $sqlPending .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = TH.userID)";
									    $_SESSION['historyLink'] = "name";
									}
									elseif ($_GET['sort'] == 'email')
									{
									    $sqlPending .= " ORDER BY (SELECT email FROM users AS US WHERE US.userID = TH.userID)";
									    $_SESSION['historyLink'] = "email";
									}
									elseif ($_GET['sort'] == 'value')
									{
									    $sqlPending .= " ORDER BY value";
									    $_SESSION['historyLink'] = "value";
									}
									elseif ($_GET['sort'] == 'Date')
									{
									    $sqlPending .= " ORDER BY Date DESC";
									    $_SESSION['historyLink'] = "Date";
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
				    				$pendingStatus = $rowPending["status"];
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $pendingUserID; ?></td>
							        	<td><?php echo $pendingHandymanName; ?></td>
							        	<td><?php echo $pendingHandymanEmail; ?></td>
							        	<td><?php echo $pendingValue; ?></td>
							        	<td><?php echo $pendingStatus; ?></td>
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
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>