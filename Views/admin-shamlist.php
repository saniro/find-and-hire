<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-shamlistStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-shamlistModalStyles.css">

		<script class="jsbin" src="Libraries/jquery/jquery.min.js"></script>
		<script class="jsbin" src="Libraries/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript">
			function validateDate(){
				var date = document.getElementById("expirationDate").value;
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();

				if(dd<10) {
				    dd = '0'+dd
				} 

				if(mm<10) {
				    mm = '0'+mm
				} 

				today = yyyy + '-' + mm + '-' + dd;
				if(date <= today){
					alert('Expiration date already passed.');
					return false;
				}
				else{
					return true;
				}
			}
		</script>
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkCustomer'] = "";
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
				<div class= "title"> SHAMS - No. of consecutive cancel</div>

				<table id = actionTable>
					<col width = "590">
					<thead class = "actions">	
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
	    											if(isset($_GET['optionSearch'])){
	    												$optionSearch = "&optionSearch=" . $_GET['optionSearch'];
	    											}
	    											if(isset($_GET['search'])){
	    												$search = "&search=" . $_GET['search'];
	    											}
	    										?>
	      										<a href="index?route=sham&sort=userID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=sham&sort=customerName<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=sham&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=sham&sort=count<?php echo $optionSearch . $search; ?>">Count</a>
	      										<a href="index?route=sham&sort=user_type<?php echo $optionSearch . $search; ?>">Type</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=sham"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'userID'){
														echo " selected ";
													}
												}
											?>value="userID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'customerName'){
														echo " selected ";
													}
												}
											?>
											value="customerName">Name</option>
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
													if($_GET['optionSearch'] == 'count'){
														echo " selected ";
													}
												}
											?>
											value="count">Count</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'user_type'){
														echo " selected ";
													}
												}
											?>
											value="user_type">Type</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type="hidden" name = "route" value = "sham">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				
				<table id = userTable>
					<col width = "50">
					<col width = "320">
					<col width = "320">
					<col width = "95">
					<col width = "75">
					<thead>
						<tr>
							<th> ID </th>
							<th class = name> Name </th>
							<th> Email Address </th>
							<th> Count </th>
							<th> Type </th>
							<th colspan = 3> Action </th>
						</tr>			
					</thead>
					<tbody>
						<?php
							$sqlInfo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS customerName, email, count_cancelled, CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END AS type, verifiedFlag, flag FROM users WHERE count_cancelled > 2";

							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
								if($_GET['optionSearch'] == 'userID'){
									$optionSearchVar = "userID";
								}
								elseif($_GET['optionSearch'] == 'customerName'){
									$optionSearchVar = "concat(lastName, ', ', firstName, ' ', middleName)";
								}
								elseif($_GET['optionSearch'] == 'email'){
									$optionSearchVar = "email";
								}
								elseif($_GET['optionSearch'] == 'count'){
									$optionSearchVar = "count_cancelled";
								}
								elseif($_GET['optionSearch'] == 'user_type'){
									$optionSearchVar = "(CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END)";
								}

		    					$sqlInfo .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'userID'){
		    						$sqlInfo .= " ORDER BY userID";
		    						$_SESSION['linkCustomer'] = "userID";
								}
								elseif ($_GET['sort'] == 'customerName')
								{
								    $sqlInfo .= " ORDER BY customerName";
								    $_SESSION['linkCustomer'] = "customerName";
								}
								elseif ($_GET['sort'] == 'email')
								{
								    $sqlInfo .= " ORDER BY email";
								    $_SESSION['linkCustomer'] = "email";
								}
								elseif ($_GET['sort'] == 'count_cancelled')
								{
								    $sqlInfo .= " ORDER BY count_cancelled";
								    $_SESSION['linkCustomer'] = "count_cancelled";
								}
								elseif ($_GET['sort'] == 'user_type')
								{
								    $sqlInfo .= " ORDER BY (CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END)";
								    $_SESSION['linkCustomer'] = "user_type";
								}
							}
							$stmt = $con->prepare($sqlInfo);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowInfo){
								$infoUserID = $rowInfo["userID"];
			    				$infoCustomerName = $rowInfo["customerName"];
			    				$infoEmail = $rowInfo["email"];
			    				$infoReportNo = $rowInfo["count_cancelled"];
			    				$infoType = $rowInfo["type"];
			    				$infoVerifiedFlag = $rowInfo["verifiedFlag"];
			    				$infoFlag = $rowInfo["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $infoUserID; ?></td>
						        	<td><?php echo $infoCustomerName; ?></td>
						        	<td class = "max word-wrap"><?php echo $infoEmail; ?></td>
						        	<td><?php echo $infoReportNo; ?></td>
						        	<td><?php echo $infoType;?></td>
						        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
						        	<td>
						        		<?php
						        			if ($infoFlag == 1){
						        			echo "<button id = 'deacBtn' class = 'deactivate' onclick='viewYesNo()'>Deactivate</button>";
						        			}
						        			else{
						        				$sqlCheckCanBeActivate = "
													SELECT count(requirementID) AS reqCount 
													FROM requirements 
													WHERE userID = (:infoUserID) AND submitted = 1";
												$stmt = $con->prepare($sqlCheckCanBeActivate);
												$stmt->bindParam(':infoUserID', $infoUserID, PDO::PARAM_INT);
												$stmt->execute();
												$rowCheckCanBeActivate = $stmt->fetch();
												if($rowCheckCanBeActivate['reqCount'] == 0){
													echo "<button id = 'actBtn' class = 'viewDisabled' disabled>Activate</button>";
												}
												else{
													echo "<button id = 'actBtn' class = 'view' onclick='viewYesNo()'>Activate</button>";
												}
						        			}
						        		?>
						        	</td>
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
		<div id="viewModal" class="viewModal">
  				<div class="viewModal-content">
    				<span class="viewClose">&times;</span>
    				<div class = "details">
    				<div class = "titleDetails"><b>View Full Details</b></div>
    				<div class = "profilePicDivision">
    					<?php
    						if(isset($_GET['userID'])){
    							$sqlInfoModal = "SELECT userID, 
    							profilepicture, 
    							concat (lastName, ', ', firstName, ' ', middleName) AS name,
    							concat(houseNo, ', ', street, ', ', barangay, ', ', city) AS address, 
    							(CASE WHEN gender = 1 THEN 'Male' WHEN gender = 0 THEN 'Female' END) AS gender, DATE_FORMAT(birthDate,'%b %d, %Y') AS birthDate, email, contact, (SELECT count(reportsID) FROM reports WHERE reportedID = userID) AS reportNo FROM users AS US, address AS AD WHERE (US.addressID = AD.addressID) AND userID = (:userID)";

    							$stmt = $con->prepare($sqlInfoModal);
								$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowInfoModal = $stmt->fetch();
								
								$modalInfoUserID = $rowInfoModal["userID"];
			    				if (empty($rowInfoModal["profilepicture"])){
			    					$modalInfoProfilePicture = "ProfilePictures/userIcon.png";
			    				}
			    				else{
			    					$modalInfoProfilePicture = $rowInfoModal["profilepicture"];
			    				}
			    				$modalInfoName = $rowInfoModal["name"];
			    				$modalInfoAddress = $rowInfoModal["address"];
			    				$modalInfoGender = $rowInfoModal["gender"];
			    				$modalInfoBirthdate = $rowInfoModal["birthDate"];
			    				$modalInfoAge = date_diff(date_create($modalInfoBirthdate), date_create('now'))->y;
			    				$modalInfoEmail = $rowInfoModal["email"];
			    				$modalInfoContact = $rowInfoModal["contact"];
			    				$modalInfoReportNo = $rowInfoModal["reportNo"];
						}
    					?>
    					<div class = "profilePicContent">
    						<img class = "profilePic" src="<?php echo $modalInfoProfilePicture;?>">
    					</div>
    				</div>
    				<table class = "tableInputs">
						<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">User Number</td>
						    <td class = "tdInput"><?php echo $modalInfoUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalInfoName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Accused Times</td>
						    <td class = "tdInput"><?php echo $modalInfoReportNo; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Address</td>
						    <td class = "tdInput"><?php echo $modalInfoAddress;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalInfoGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Birthdate</td>
						    <td class = "tdInput"><?php echo $modalInfoBirthdate; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Age</td>
						    <td class = "tdInput"><?php echo $modalInfoAge; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email Address</td>
						    <td class = "tdInput max"><?php echo $modalInfoEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact Number</td>
						    <td class = "tdInput"><?php echo $modalInfoContact; ?></td>
						</tr>
					</table>
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
    					<table class = "tableYesNo">
    						<col width = "50">
	    					<thead class = "tableYesNoHead">
								<tr>
									<th> ID </th>
									<th> Name </th>
								</tr>				
							</thead>
	    					<tbody>
								<?php
								if(isset($_GET['userID'])&&($_GET['type']=="yesno")){
									$sqlYesNo = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name, Flag FROM users WHERE userID = (:userID)";
									
									$stmt = $con->prepare($sqlYesNo);
									$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
									$stmt->execute();
									$rowYesNoModal = $stmt->fetch();

									$modalYesNoUserID = $rowYesNoModal["userID"];
									$modalInfoAccountID = $rowYesNoModal["name"];
									$modalYesNoFlag	= $rowYesNoModal["Flag"];

								}
					    		?>
			    				<tr class = "tableContentYesNo">
						        	<td><?php echo $modalYesNoUserID; ?></td>
						        	<td><?php echo $modalInfoAccountID; ?></td>
						        </tr>
							</tbody>
						</table>
    					<div class = "YesNo">
    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
    					</div>
    				</form>
				</div>
				<?php
					if(isset($_POST['Yes'])&&($_GET['type']=="yesno")){
						if($modalYesNoFlag == 1){
							$sqlActiveModal = "UPDATE users
												SET Flag = 0
												WHERE userID = (:modalYesNoUserID);";
							$stmt = $con->prepare($sqlActiveModal);
							$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
							$stmt->execute();
						}
						elseif($modalYesNoFlag == 0){
							$sqlDeactiveModal = "UPDATE users
												SET Flag = 1
												WHERE userID = (:modalYesNoUserID);";
							$stmt = $con->prepare($sqlDeactiveModal);
							$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
							$stmt->execute();
						}
						echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=sham&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
					}
				?>
  			</div>
		</div>
	</div>
		<script src="Script/viewModal.js" type="text/javascript"></script>
		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=sham&userID="+userID+linkSort+search+optionSearch
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
							var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var type = '&type=yesno'
							var url = "index?route=sham&userID="+userID+linkSort+search+optionSearch+type
							location.replace(url);
							localStorage.setItem('viewModalYesNo',true);
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

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
		        }
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