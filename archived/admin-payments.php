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
				$_SESSION['paymentsLink'] = "";
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
				<div class= "title"> CURRENT PAYMENTS - Handyman </div>
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
	      										<a href="index?route=payments&sort=paymentID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=payments&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=payments&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=payments&sort=amount<?php echo $optionSearch . $search; ?>">Amount</a>
	      										<a href="index?route=payments&sort=dueDate<?php echo $optionSearch . $search; ?>">Due Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=payments"><img class = "resetAction" src="Resources/reset.png"></a>
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
													if($_GET['optionSearch'] == 'dueDate'){
														echo " selected ";
													}
												}
											?>
											value="dueDate">Due Date</option>

										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="payments">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
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
							<th> Due Date </th>
							<th colspan = 1> Action </th>
						</tr>				
					</thead>
						<tbody>
							<?php
								$sqlPayments = "SELECT paymentID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PT.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = PT.userID) AS email, amount, datePaid, dueDate FROM payment AS PT WHERE flag = 1 AND dueDate > NOW()";
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
									elseif($_GET['optionSearch'] == 'dueDate'){
										$optionSearchVar = "dueDate";
									}
			    					$sqlPayments .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
								}

								if(isset($_GET['sort'])){
									if ($_GET['sort'] == 'paymentID'){
			    						$sqlPayments .= " ORDER BY paymentID";
			    						$_SESSION['paymentsLink'] = "paymentID";
									}
									elseif ($_GET['sort'] == 'name')
									{
									    $sqlPayments .= " ORDER BY name";
									    $_SESSION['paymentsLink'] = "name";
									}
									elseif ($_GET['sort'] == 'email')
									{
									    $sqlPayments .= " ORDER BY email";
									    $_SESSION['paymentsLink'] = "email";
									}
									elseif ($_GET['sort'] == 'amount')
									{
									    $sqlPayments .= " ORDER BY amount";
									    $_SESSION['paymentsLink'] = "amount";
									}
									elseif ($_GET['sort'] == 'dueDate')
									{
									    $sqlPayments .= " ORDER BY dueDate DESC";
									    $_SESSION['paymentsLink'] = "dueDate";
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
				    				$paymentDueDate = $rowPayment["dueDate"];
				    				
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $paymentUserID; ?></td>
							        	<td><?php echo $paymentHandymanName; ?></td>
							        	<td><?php echo $paymentHandymanEmail; ?></td>
							        	<td><?php echo $paymentAmount; ?></td>
							        	<td><?php echo $paymentDueDate; ?></td>
							        	
							        	<td><button id='viewBtn' class = 'view' onclick="viewYesNo()">Pay</button></td>
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

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
	    			<div class = "infoDetails"><b>Are you sure?</b></div>
	    			<div class = "profilePicDivision">
	    				<?php
		    				if(isset($_GET['paymentID'])){
		    					$sqlYesNo = "SELECT userID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PT.userID) AS name, amount, flag FROM payment AS PT WHERE (flag = 1 AND dueDate > NOW()) AND paymentID = (:paymentID)";

		    					$stmt = $con->prepare($sqlYesNo);
								$stmt->bindParam(':paymentID', $_GET['paymentID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowYesNo = $stmt->fetch();

								$modalYesNoUserID = $rowYesNo["userID"];
								$modalYesNoName = $rowYesNo["name"];
								$modalYesNoAmount = $rowYesNo["amount"];
				    			$modalYesNoFlag = $rowYesNo["flag"];
							}
						?>
	    				<form method="post">
	    					<table class = "tableYesNo">
	    						<col width = "150">
		    					<thead class = "tableYesNoHead">
									<tr>
										<th> ID </th>
										<th> Name </th>
										<th> Amount </th>
									</tr>				
								</thead>
		    					<tbody>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoUserID; ?></td>
							        	<td><?php echo $modalYesNoName; ?></td>
							        	<td><?php echo $modalYesNoFlag; ?></td>
							        	
							        </tr>
								</tbody>
							</table>
	    					<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php
						if(isset($_POST['Yes'])){
							if($modalYesNoFlag == 1){
								$sqlActiveModal = "UPDATE payment
													SET flag = 0, datePaid = NOW()
													WHERE userID = (:modalYesNoUserID)";
								$stmt = $con->prepare($sqlActiveModal);
								$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
								$stmt->execute();
								
								$date = date("Y-m-d");
								$newdate = strtotime('+1 month', strtotime($date));
								$newdate = date('Y-m-d', $newdate);
								
								$sqlMakePayment = "INSERT INTO payment (userID, amount, dueDate)values(:modalUserID, '1000', :newdate)";
								$stmt = $con->prepare($sqlMakePayment);
								$stmt->bindParam(':modalUserID', $modalYesNoUserID, PDO::PARAM_INT);
								$stmt->bindParam(':newdate', $newdate, PDO::PARAM_STR);
								$stmt->execute();

							//echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=payments&sort=".$_SESSION['paymentsLink'].$optionSearch.$search."';</script>";
						}
					}
					?>
	  			</div>
			</div>
		</div>

		<script type="text/javascript">
			function viewYesNo(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var paymentID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['paymentsLink'])){echo '&sort=' . $_SESSION['paymentsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=payments&paymentID="+paymentID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			window.onload = function(){
				var y = localStorage.getItem('viewModalYesNo');
				if (y == 'true'){
					document.getElementById('viewModalYesNo').style.display = "block";
				}
				localStorage.setItem('viewModalYesNo',false)
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
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