<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-handymanAccountsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<style>
			 .viewModal {
			    display: none; /* Hidden by default */
			    position: fixed; /* Stay in place */
			    z-index: 1; /* Sit on top */
			    padding-top: 60px; /* Location of the box */
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
			    width: 35%;
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

			.viewModalYesNo {
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
			.viewModalYesNo-content {
				font-family: Century Gothic;
				font-size: 18px;
			    background-color: #fefefe;
			    margin: auto;
			    padding: 20px;
			    border: 1px solid #888;
			    width: 17%;
			    padding-bottom: 0px;
			}

			/* The Close Button */
			.viewCloseYesNo {
			    color: #aaaaaa;
			    float: right;
			    font-size: 28px;
			    font-weight: bold;
			}

			.viewCloseYesNo:hover,
			.viewCloseYesNo:focus {
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
			.infoDetails{
				margin-bottom: 20px;
				font-size: 20px;
			}
			.noButton{
				margin-left: 40px;
			}
			.profilePic{
				width: 150px;
				height: 150px;
			}
			.profilePicDivision{
				padding-top: 15px;
				padding-bottom: 15px;
			}
			.profilePicContent{
				padding-left: 33%;
				padding-left: 33%;
			}
		</style>
	</head>
	<body style="background-image: url(Resources/bg.jpg);">
		<?php
			require("admin-header.php");
			if(!isset($_SESSION['link'])){
				$_SESSION['link'] = "";
			}
		?>
		<div class = wrapper>
			<div class= "title"> ACCOUNTS - Handyman </div>
			<table id = actionTable>
				<thead class = "actions">
					<col width = "380">
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
      										<a href="admin-handymanAccounts.php?sort=userID<?php echo $optionSearch . $search; ?>">User No.</a>
      										<a href="admin-handymanAccounts.php?sort=lastName<?php echo $optionSearch . $search; ?>">Lastname</a>
    									</div>
  									</li>
								</ul>
							</div>
						</th>
						<th class = "searchCol" colspan="2">
							<form method = "get">
							<div class = "searchClass">
									<a href = "admin-handymanAccounts.php"><img class = "resetAction" src="Resources/reset.png"></a>
									<select class = "optionSearch" name = "optionSearch">
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'userID'){
													echo " selected ";
												}
											}
										?>value="userID">User No.</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'firstName'){
													echo " selected ";
												}
											}
										?>
										value="firstName">Firstname</option>
										<option
										<?php
											if(isset($_GET['optionSearch'])){
												if($_GET['optionSearch'] == 'lastName'){
													echo " selected ";
												}
											}
										?>
										value="lastName">Lastname</option>
									</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here...">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
							</div>
						</form>
						</th>
					</tr>
				</thead>
			</table>
			<table id = userTable>
				<col width = "110">
				<col width = "280">
				<col width = "250">
				<thead>
					<tr>
						<th> User No. </th>
						<th> Name </th>
						<th> Email Address </th>
						<th colspan = 2> Action </th>
					</tr>				
				</thead>
					<tbody>
						<?php
							$sqlInfo = "SELECT userID, firstName, middleName, lastName, addressID, gender, birthDate, email, contact, flag FROM users WHERE type = 2";
							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
		    					$sqlInfo .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'userID'){
		    						$sqlInfo .= " ORDER BY userID";
		    						$_SESSION['link'] = "userID";
								}
								elseif ($_GET['sort'] == 'lastName')
								{
								    $sqlInfo .= " ORDER BY lastName";
								    $_SESSION['link'] = "lastName";
								}
							}

							$resultInfo = $con->query($sqlInfo);

							if ($resultInfo->num_rows > 0) {
				    		// output data of each row
			    			while($rowInfo = $resultInfo->fetch_assoc()) {
			    				$infoUserID = $rowInfo["userID"];
			    				$infoLastName = $rowInfo["lastName"];
			    				$infoMiddleName = $rowInfo["middleName"];
			    				$infoFirstName = $rowInfo["firstName"];
			    				$infoEmail = $rowInfo["email"];
			    				$infoFlag = $rowInfo["flag"];
					    	?>
					      	<tr class = "tableContent">
					        	<td><?php echo $infoUserID; ?></td>
					        	<td><?php echo $infoLastName . ", " . " " . $infoFirstName . " " . $infoMiddleName; ?></td>
					        	<td><?php echo $infoEmail; ?></td>
					        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
					        	<td>
					        		<?php
					        			if ($infoFlag == 1){
					        			echo "<button id = 'deacBtn' class = 'delete' onclick='viewYesNo()'>Deactivate</button>";
					        			}
					        			else{
					        			echo "<button id = 'actBtn' class = 'view' onclick='viewYesNo()'>Activate</button>";
					        			}
					        		?>
					        	</td>
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
    				<div class = "profilePicDivision">
    					<?php
    						if(isset($_GET['userID'])){
    							$sqlInfoModal = "SELECT userID, firstName, middleName, lastName, houseNo, street, barangay, city, gender, birthDate, email, contact FROM users AS US, address AS AD WHERE (US.addressID = AD.addressID) AND userID = '$_GET[userID]'";

	    						$resultInfoModal = $con->query($sqlInfoModal);

								if ($resultInfoModal->num_rows > 0) {
					    		// output data of each row
				    			while($rowInfoModal = $resultInfoModal->fetch_assoc()) {
				    				$modalInfoUserID = $rowInfoModal["userID"];
				    				$modalInfoLastName = $rowInfoModal["lastName"];
				    				$modalInfoMiddleName = $rowInfoModal["middleName"];
				    				$modalInfoFirstName = $rowInfoModal["firstName"];
				    				$modalInfoHouseNo = $rowInfoModal["houseNo"];
				    				$modalInfoStreet = $rowInfoModal["street"];
				    				$modalInfoBarangay = $rowInfoModal["barangay"];
				    				$modalInfoCity = $rowInfoModal["city"];
				    				if($rowInfoModal["gender"] == 1){
				    					$modalInfoGender = 'Male';
				    				}elseif($rowInfoModal["gender"] == 0){
				    					$modalInfoGender = 'Female';
				    				}
				    				$modalInfoBirthdate = $rowInfoModal["birthDate"];
				    				$modalInfoEmail = $rowInfoModal["email"];
				    				$modalInfoContact = $rowInfoModal["contact"];
								}
							}
						}
    					?>
    					<div class = "profilePicContent">
    						<img class = "profilePic" src="ProfilePictures/userIcon.png">
    					</div>
    				</div>
    				<div id = number class = customerDetails>
						<b>User no: </b><?php echo $modalInfoUserID; ?>
					</div>
					<div id = name class = customerDetails>
						<b>Name: </b><?php echo $modalInfoLastName . ", " . $modalInfoFirstName . " " . $modalInfoMiddleName; ?>
					</div>
					<div id = address class = customerDetails>
						<b>Address: </b><?php echo $modalInfoHouseNo . ", " . $modalInfoStreet . ", " . $modalInfoBarangay . ", " . $modalInfoCity;?>
					</div>
					<div id = gender class = customerDetails>
						<b>Gender: </b><?php echo $modalInfoGender ?>
					</div>
					<div id = birthdate class = customerDetails>
						<b>Birthdate: </b><?php echo $modalInfoBirthdate ?>
					</div>
					<div id = email class = customerDetails>
						<b>Email Address: </b><?php echo $modalInfoEmail ?>
					</div>
					<div id = contactno class = customerDetails>
						<b>Contact Number: </b><?php echo $modalInfoContact ?>
					</div>
					<div class = "transactionAndReportBtn">
						<button name = "viewTransactionBtn" class = "viewTransactionBtn" onclick="viewTransactionBtn()">View Transactions</button><button name = "viewReportBtn" class = "viewReportBtn">View Reports</button>
					</div>
				</div>
  			</div>
		</div>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
    			<div class = "infoDetails"><b>Are you sure?</b></div>
    			<div class = "profilePicDivision">
    				<form method="post">
    					<div class = "YesNo">
    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
    					</div>
    				</form>
				</div>
				<?php
					if(isset($_GET['userID'])){
    					$sqlYesNo = "SELECT userID, flag FROM users WHERE userID = '$_GET[userID]'";

						$resultYesNo = $con->query($sqlYesNo);

						if ($resultYesNo->num_rows > 0) {
			    		// output data of each row
		    			while($rowYesNo = $resultYesNo->fetch_assoc()) {
		    				$modalYesNoUserID = $rowYesNo["userID"];
		    				$modalYesNoFlag = $rowYesNo["flag"];
						}
					}
				}

				if(isset($_POST['Yes'])){
					if($modalYesNoFlag == 1){
						$sqlActiveModal = "UPDATE users
											SET flag = 0
											WHERE userID = '$modalYesNoUserID'";
						if ($con->query($sqlActiveModal) === TRUE) {
							echo "Record updated successfully";
						}
					}
					elseif($modalYesNoFlag == 0){
						$sqlDeactiveModal = "UPDATE users
											SET flag = 1
											WHERE userID = '$modalYesNoUserID'";
						if ($con->query($sqlDeactiveModal) === TRUE) {
							echo "Record updated successfully";
						}
					}
					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='admin-handymanAccounts.php?sort=".$_SESSION['link'].$optionSearch.$search."';</script>";
				}
				?>
  			</div>
		</div>
	</div>

		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['link'])){echo '&sort=' . $_SESSION['link'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "admin-handymanAccounts.php?userID="+userID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function viewYesNo(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['link'])){echo '&sort=' . $_SESSION['link'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "admin-handymanAccounts.php?userID="+userID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			function viewTransactionBtn(){
				var accountHandymanID = '<?php if(isset($_GET['userID'])){echo $_GET['userID'];}?>'
				var url = "admin-handymanViewTransactions.php?handymanID="+accountHandymanID+"&sort=transactionID"
				location.replace(url);
			}

			window.onload = function(){
				var x = localStorage.getItem('viewModal');
				if (x == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)

				var y = localStorage.getItem('viewModalYesNo');
				if (y == 'true'){
					document.getElementById('viewModalYesNo').style.display = "block";
				}
				localStorage.setItem('viewModalYesNo',false)
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
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