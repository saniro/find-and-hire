<?php
	require("sessionStart.php");
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-customerViewTransactionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<style>
			.viewAddModal {
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
			.viewAddModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewAddClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewAddClose:hover,
			.viewAddClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewModalYesno {
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
			.viewModalYesno-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 17%;
			}

			/* The Close Button */
			.viewCloseYesno {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewCloseYesno:hover,
			.viewCloseYesno:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewModalArchived {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 100px; /* Location of the box */
			    left: 0;
			    top: 0;
			    width: 100%; /* Full width */
			    height: 100%; /* Full height */
			    overflow: auto; /* Enable scroll if needed */
			    background-color: rgb(232, 232, 232); /* Fallback color */
			    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.viewModalArchived-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: rgb(232, 232, 232);
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 30%;
			}

			/* The Close Button */
			.viewCloseArchived {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewCloseArchived:hover,
			.viewCloseArchived:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.viewEditModal {
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
			.viewEditModal-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 35%;
			}

			/* The Close Button */
			.viewEditClose {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewEditClose:hover,
			.viewEditClose:focus {
			    color: #000;
			    text-decoration: none;
			    cursor: pointer;
			}

			.details div{
				margin-bottom: 10px;
			}

			.titleDetails{
				margin-bottom: 20px;
				font-size: 24px;
			}

			.noButton{
				margin-left: 40px;
			}
		</style>
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
				<?php
					require("admin-header.php");
					if(!isset($_SESSION['serviceOptionLink'])){
						$_SESSION['serviceOptionLink'] = "";
					}

					if(isset($_GET['customerID'])){
						$_SESSION['customerIDSet'] = $_GET['customerID'];

					}

					if(isset($_SESSION['customerIDSet'])){
						$customerIDSet = "&customerID=" . $_SESSION['customerIDSet'];
					}

					$sqlCustomerInfo = "SELECT firstName, middleName, lastName, gender, birthDate, email, contact FROM personalInfo WHERE accountID = '$_SESSION[customerIDSet]'";
					$resultCustomerInfo = $con->query($sqlCustomerInfo);
					if ($resultCustomerInfo->num_rows > 0) {
		    		// output data of each row
		    			while($rowCustomerInfo = $resultCustomerInfo->fetch_assoc()) {
		    				$customerFirstName = $rowCustomerInfo["firstName"];
		    				$customerMiddleName = $rowCustomerInfo["middleName"];
		    				$customerLastName = $rowCustomerInfo["lastName"];
		    				if ($rowCustomerInfo["gender"] = 1){
		    					$customerGender = "Male";
		    				}
		    				elseif ($rowCustomerInfo["gender"] = 0){
		    					$customerGender = "Female";
		    				}
		    				$customerBirthdate = $rowCustomerInfo["birthDate"];
		    				$customerEmail = $rowCustomerInfo["email"];
		    				$customerContact = $rowCustomerInfo["contact"];
			    		}
					} else {
			    		echo "<td>No result.</td>";
					}
				?>

		<div class = wrapper>
			<div class = "customerDesc">
				<div class = "customerDescContents">
					<form method="post">
						<div class = "backBtn">
							<button type = "submit" class="backButton" name = "backButton"> Go Back </button>
						</div>
					</form>
					<div class = "customerNumber">
						<div class = "titleOption">
							<b>Customer Number:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $_SESSION['customerIDSet']; ?>
						</div>
					</div>
					<div class = "customerName">
						<div class = "titleOption">
							<b>Name:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $customerLastName . ", " . $customerFirstName . " " . $customerMiddleName; ?>
						</div>
					</div>
					<div class = "customerGender">
						<div class = "titleOption">
							<b>Gender:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $customerGender; ?>
						</div>
					</div>
					<div class = "customerBirthdate">
						<div class = "titleOption">
							<b>Birthdate:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $customerBirthdate; ?>
						</div>
					</div>
					<div class = "customerEmail">
						<div class = "titleOption">
							<b>Email:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $customerEmail; ?>
						</div>
					</div>
					<div class = "customerContact">
						<div class = "titleOption">
							<b>Contact Number:</b>
						</div>
						<div class = "titleServiceDetails">
							<?php echo $customerContact; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['backButton'])){
					echo "<script type='text/javascript'>window.location.href='admin-customerAccounts.php';</script>";
				}
			?>
			<div class = "contents">
				<table id = actionTable>
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
												<a href="admin-customerViewTransactions.php?sort=transactionID<?php echo $customerIDSet.$optionSearch.$search;?>">Transaction No.</a>
	      										<a href="admin-customerViewTransactions.php?sort=transactionID<?php echo $customerIDSet.$optionSearch.$search;?>">Handyman's Lastname</a>
	      										<a href="admin-customerViewTransactions.php?sort=transactionID<?php echo $customerIDSet.$optionSearch.$search;?>">Options No.</a>
	    									</div>
	    									</form>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "admin-customerViewTransactions.php?<?php echo $customerIDSet; ?>"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'optionsID'){
														echo " selected ";
													}
												}
											?>value="optionsID">Options No.</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'description'){
														echo " selected ";
													}
												}
											?>
											value="description">Description</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here...">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>

				<table id = serviceTable>
					<thead>
						<tr>
							<th> Transaction ID </th>
							<th> Handyman </th>
							<th> Service </th>
							<th> Date </th>
							<th colspan="1" class = 'actionStyle'> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlCustomerTransaction = "SELECT TN.transactionID, PI.firstName, PI.middleName, PI.lastName, SS.name, TN.date, TN.remarks, TN.status FROM transaction AS TN, personalInfo AS PI, options AS OP, services AS SS WHERE customerID = $_SESSION[customerIDSet] AND TN.customerID = PI.accountID AND TN.optionsID = OP.optionsID AND OP.serviceID = SS.serviceID";
							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
		    					$sqlCustomerTransaction .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'optionsID'){
		    						$sqlCustomerTransaction .= " ORDER BY optionsID";
		    						$_SESSION['serviceOptionLink'] = "optionsID";
								}
								elseif ($_GET['sort'] == 'description')
								{
								    $sqlCustomerTransaction .= " ORDER BY description";
								    $_SESSION['serviceOptionLink'] = "description";
								}
							}

							$resultCustomerTransaction = $con->query($sqlCustomerTransaction);

							if ($resultCustomerTransaction->num_rows > 0) {
				    		// output data of each row
			    			while($rowCustomerTransaction = $resultCustomerTransaction->fetch_assoc()) {
			    				$customerTransactionID = $rowCustomerTransaction["transactionID"];
			    				$customerTransactionHandymanFname = $rowCustomerTransaction["firstName"];
			    				$customerTransactionHandymanMname = $rowCustomerTransaction["middleName"];
			    				$customerTransactionHandymanLname = $rowCustomerTransaction["lastName"];
			    				$customerTransactionServiceName = $rowCustomerTransaction["name"];
			    				$customerTransactionDate = $rowCustomerTransaction["date"];
					    	?>
					      	<tr class = "tableContent">
					        	<td><?php echo $customerTransactionID; ?></td>
					        	<td><?php echo $customerTransactionHandymanLname . ", " . $customerTransactionHandymanFname . " " . $customerTransactionHandymanMname; ?></td>
					        	<td><?php echo $customerTransactionServiceName; ?></td>
					        	<td><?php echo $customerTransactionDate; ?></td>
					        	<td><button id='edit' class = 'view' onclick='viewModal()'>View</button></td></td>
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
		</div>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
	    			<div class = "titleDetails"><b>Are you sure?</b></div>
	    			<div class = "profilePicDivision">
	    				<form method="post">
	    					<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php

					if(isset($_POST['Yes'])){
						$sqlActiveModal = "UPDATE options
											SET flag = 0
											WHERE optionsID = '$_GET[optionsID]'";
						if ($con->query($sqlActiveModal) === TRUE) {
							echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='admin-serviceOptions.php?sort=".$_SESSION['serviceOptionLink'].$serviceIDSet.$optionSearch.$search."';</script>";
						}
					}
					?>
	  			</div>
			</div>
		</div>

		<div id="viewModalArchived" class="viewModalArchived">
  			<div class="viewModalArchived-content">
    			<span class="viewCloseArchived">&times;</span>
    			<div class = "details">
	    			<div class = "titleDetails"><b>Deleted services</b></div>
	    			<table id = userTableArchived>
						<thead>
							<tr>
								<th> Option No. </th>
								<th> Description </th>
								<th> Price </th>
							</tr>				
						</thead>
						<tbody>
							<?php
								$sqlOptionsDeleted = "SELECT optionsID, description, amount, flag FROM options WHERE flag = 0 AND serviceID = '$_SESSION[serviceIDSet]'";
								$resultOptionsDeleted = $con->query($sqlOptionsDeleted);

								if ($resultOptionsDeleted->num_rows > 0) {
					    		// output data of each row
				    			while($rowOptionsDeleted = $resultOptionsDeleted->fetch_assoc()) {
				    				$optionsIDDeleted = $rowOptionsDeleted["optionsID"];
			    					$optionsDescDeleted = $rowOptionsDeleted["description"];
			    					$optionsPriceDeleted = $rowOptionsDeleted["amount"];
						    	?>
						      	<tr class = "tableContent">
						        	<td><?php echo $optionsIDDeleted; ?></td>
						        	<td><?php echo $optionsDescDeleted; ?></td>
						        	<td><?php echo $optionsPriceDeleted; ?> Php.</td>
						        </tr>
						        <?php
						    			}
									}
								?>
						</tbody>
					</table>
	  			</div>
			</div>
		</div>

		<form method="post">
			<div id="viewEditModal" class="viewEditModal">
				<div class="viewEditModal-content">
					<span class="viewEditClose">&times;</span>
					<?php
						$sqlOptionsEdit = "SELECT optionsID, description, amount FROM options WHERE optionsID = '$_GET[optionsID]'";
						$resultOptionsEdit = $con->query($sqlOptionsEdit);
							if ($resultOptionsEdit->num_rows > 0) {
				    		// output data of each row
			    			while($rowOptionsEdit = $resultOptionsEdit->fetch_assoc()) {
			    				$optionsIDEdit = $rowOptionsEdit["optionsID"];
			    				$optionsDescEdit = $rowOptionsEdit["description"];
			    				$optionsAmountEdit = $rowOptionsEdit["amount"];
			    			}
			    		}
					?>
					<div class = "details">
						<div class = "titleDetails"><b>Edit Service</b></div>
						<div class = "inputs">
							<div id = optionsDescUpdate class = optionsDescUpdate>
								<b>Description: </b><br>
								<textarea class = "optionDescInfo" name="optionDescInfoUpdate" placeholder="Add option description here..." required><?php echo $optionsDescEdit; ?></textarea>
							</div>
							<div id = serviceAmountUpdate class = serviceAmountUpdate>
								<b>Amount: </b><br>
								<input type="number" name="optionAmountInfoUpdate" placeholder="Enter option amount here..." value = "<?php echo $optionsAmountEdit; ?>" required><br>
							</div>
							<div class = buttonSubmit>
	  							<button type = "submit" class="addSubmit" name = "updateOptions"> UPDATE </button>
	  						</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<?php
			if(isset($_POST['updateOptions'])){
				$sqlOptionsUpdate = "UPDATE options
								SET description = '$_POST[optionDescInfoUpdate]', amount = '$_POST[optionAmountInfoUpdate]'
								WHERE optionsID = '$optionsIDEdit'";
				if ($con->query($sqlOptionsUpdate) === TRUE) {
					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='admin-serviceOptions.php?sort=".$_SESSION['serviceOptionLink'].$serviceIDSet.$optionSearch.$search."';</script>";
				}
			}
		?>

		<script>

			function viewEditModal(){
				var table = document.getElementById('serviceTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var optionsID = this.cells[0].innerHTML;
							var serviceID = '<?php if(isset($_SESSION['serviceID'])){echo '&serviceID=' . $_SESSION['serviceID'];}?>'
							var linkSort = '<?php if(isset($_SESSION['serviceOptionLink'])){echo '&sort=' . $_SESSION['serviceOptionLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("admin-serviceOptions.php?optionsID="+optionsID+serviceID+linkSort+search+optionSearch);
							localStorage.setItem('viewEditModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function viewYesNo(){
				var table = document.getElementById('serviceTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var optionsID = this.cells[0].innerHTML;
							var serviceID = '<?php if(isset($_SESSION['serviceID'])){echo '&serviceID=' . $_SESSION['serviceID'];}?>'
							var linkSort = '<?php if(isset($_SESSION['serviceOptionLink'])){echo '&sort=' . $_SESSION['serviceOptionLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("admin-serviceOptions.php?optionsID="+optionsID+serviceID+linkSort+search+optionSearch);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}
					}
				}
			}

			function viewArchived(){
			    // Get the modal
			    var modal = document.getElementById('viewModalArchived');

			    // Get the button that opens the modal
			    var btn = document.getElementById('viewArchived');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName("viewCloseArchived")[0];

			    // When the user clicks the button, open the modal 
			   modal.style.display = "block";
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			        viewModalArchived.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			            viewModalArchived.style.display = "none";
			        }
			    }
			}

			window.onload = function(){
				var x = localStorage.getItem('viewEditModal');
				if (x == 'true'){
					document.getElementById('viewEditModal').style.display = "block";
				}
				localStorage.setItem('viewEditModal',false)

				var yesno = localStorage.getItem('viewModalYesNo');
				if (yesno == 'true'){
					document.getElementById('viewModalYesNo').style.display = "block";
				}
				localStorage.setItem('viewModalYesNo',false)

				var archived = localStorage.getItem('viewModalArchived');
				if (archived == 'true'){
					document.getElementById('viewModalArchived').style.display = "block";
				}
				localStorage.setItem('viewModalArchived',false)
			}

			var editClose = document.getElementsByClassName("viewEditClose")[0];
			editClose.onclick = function() {
				document.getElementById('viewEditModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewModalYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			var spanArchived = document.getElementsByClassName("viewCloseArchived")[0];
			spanArchived.onclick = function() {
				document.getElementById('viewCloseArchived').style.display = "none";
			}
		</script>
	</body>
</html>