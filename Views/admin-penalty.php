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
		<link rel="stylesheet" type="text/css" href="Styles/admin-penaltyStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-penaltyModalStyles.css">

		<script class="jsbin" src="Libraries/jquery/jquery.min.js"></script>
		<script class="jsbin" src="Libraries/jquery/jquery-ui.min.js"></script>
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkPenalty'] = "";
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
				<div class= "title"> PENALTIES </div>

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
	      										<a href="index?route=penalty&sort=penaltyID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=penalty&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=penalty&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=penalty&sort=type<?php echo $optionSearch . $search; ?>">User Type</a>
	      										<a href="index?route=penalty&sort=status<?php echo $optionSearch . $search; ?>">Status</a>
	      										<a href="index?route=penalty&sort=date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=penalty"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'penaltyID'){
														echo " selected ";
													}
												}
											?>value="penaltyID">ID</option>
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
													if($_GET['optionSearch'] == 'type'){
														echo " selected ";
													}
												}
											?>
											value="type">User Type</option>
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
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type="hidden" name = "route" value = "penalty">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
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
					<col width = "100">
					<col width = "140">
					<thead>
						<tr>
							<th> ID </th>
							<th class = name> Name </th>
							<th> Email Address </th>
							<th> User Type </th>
							<th> Status </th>
							<th> Date </th>
							<th> Action </th>
						</tr>			
					</thead>
					<tbody>
						<?php
							$sqlPenalty = "SELECT penaltyID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PY.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = PY.userID) AS email, (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = PY.userID) AS type, (CASE WHEN status = 1 THEN 'Not paid' WHEN status = 0 THEN 'Paid' END) AS status, DATE_FORMAT(date,'%b %d, %Y') AS date FROM penalty AS PY";

							if(isset($_GET['optionSearch'])){
								if($_GET['optionSearch'] == 'penaltyID'){
									$optionSearchVar = "penaltyID";
								}
								elseif($_GET['optionSearch'] == 'name'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PY.userID)";
								}
								elseif($_GET['optionSearch'] == 'email'){
									$optionSearchVar = "(SELECT email FROM users AS US WHERE US.userID = PY.userID)";
								}
								elseif($_GET['optionSearch'] == 'type'){
									$optionSearchVar = "(SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = PY.userID)";
								}
								elseif($_GET['optionSearch'] == 'status'){
									$optionSearchVar = "(CASE WHEN status = 1 THEN 'Not paid' WHEN status = 0 THEN 'Paid' END)";
								}
								elseif($_GET['optionSearch'] == 'date'){
									$optionSearchVar = "DATE_FORMAT(date,'%b %d, %Y')";
								}

		    					$sqlPenalty .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'penaltyID'){
		    						$sqlPenalty .= " ORDER BY penaltyID";
		    						$_SESSION['linkPenalty'] = "penaltyID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlPenalty .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PY.userID)";
								    $_SESSION['linkPenalty'] = "name";
								}
								elseif ($_GET['sort'] == 'email')
								{
								    $sqlPenalty .= " ORDER BY (SELECT email FROM users AS US WHERE US.userID = PY.userID)";
								    $_SESSION['linkPenalty'] = "email";
								}
								elseif ($_GET['sort'] == 'type')
								{
								    $sqlPenalty .= " ORDER BY (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = PY.userID)";
								    $_SESSION['linkPenalty'] = "type";
								}
								elseif ($_GET['sort'] == 'status')
								{
								    $sqlPenalty .= " ORDER BY (CASE WHEN status = 1 THEN 'Not paid' WHEN status = 0 THEN 'Paid' END)";
								    $_SESSION['linkPenalty'] = "status";
								}
								elseif ($_GET['sort'] == 'date')
								{
								    $sqlPenalty .= " ORDER BY DATE_FORMAT(date,'%b %d, %Y')";
								    $_SESSION['linkPenalty'] = "date";
								}
							}
							$stmt = $con->prepare($sqlPenalty);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowPenalty){
								$penaltyUserID = $rowPenalty["penaltyID"];
			    				$penaltyName = $rowPenalty["name"];
			    				$penaltyEmail = $rowPenalty["email"];
			    				$penaltyType = $rowPenalty["type"];
			    				$penaltyStatus = $rowPenalty["status"];
			    				$penaltyDate = $rowPenalty["date"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $penaltyUserID; ?></td>
						        	<td><?php echo $penaltyName; ?></td>
						        	<td><?php echo $penaltyEmail; ?></td>
						        	<td><?php echo $penaltyType; ?></td>
						        	<td><?php echo $penaltyStatus; ?></td>
						        	<td><?php echo $penaltyDate; ?></td>
						        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
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
    				<?php
						if(isset($_GET['penaltyID'])){
							$sqlPenaltyModal = "SELECT penaltyID, userID, (SELECT profilepicture FROM users AS US WHERE US.userID = PY.userID) AS profilepicture, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = PY.userID) AS name, (SELECT CASE WHEN gender = 0 THEN 'Female' WHEN gender = 1 THEN 'Male' END FROM users AS US WHERE US.userID = PY.userID) AS gender, (SELECT email FROM users AS US WHERE US.userID = PY.userID) AS email, (SELECT contact FROM users AS US WHERE US.userID = PY.userID) AS contact, (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = PY.userID) AS type, amount, (CASE WHEN status = 1 THEN 'Not paid' WHEN status = 0 THEN 'Paid' END) AS status, DATE_FORMAT(date,'%b %d, %Y') AS date FROM penalty AS PY WHERE penaltyID = :penaltyID";

							$stmt = $con->prepare($sqlPenaltyModal);
							$stmt->bindParam(':penaltyID', $_GET['penaltyID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowPenaltyModal = $stmt->fetch();

							$modalPenaltyID = $rowPenaltyModal["penaltyID"];
							$modalPenaltyUserID = $rowPenaltyModal["userID"];
							if(empty($rowPenaltyModal["profilepicture"])){
		    					$modalPenaltyPic = 'Resources/userIcon.png';
		    				}
		    				else{
		    					$modalPenaltyPic = $rowPenaltyModal["profilepicture"];
		    				}
		    				$modalPenaltyName = $rowPenaltyModal["name"];
		    				$modalPenaltyEmail = $rowPenaltyModal["email"];
		    				$modalPenaltyContact = $rowPenaltyModal["contact"];
		    				$modalPenaltyGender = $rowPenaltyModal["gender"];	
		    				$modalPenaltyType = $rowPenaltyModal["type"];
		    				$modalPenaltyAmount = $rowPenaltyModal["amount"];
		    				$modalPenaltyStatus = $rowPenaltyModal["status"];
		    				$modalPenaltyDate = $rowPenaltyModal["date"];
							}
					?>
    				<table class = "tableInputs">
						<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Penalty ID</td>
						    <td class = "tdInput"><?php echo $modalPenaltyID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Amount</td>
						    <td class = "tdInput"><?php echo $modalPenaltyAmount; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Status</td>
						    <td class = "tdInput"><?php echo $modalPenaltyStatus; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Date</td>
						    <td class = "tdInput"><?php echo $modalPenaltyDate; ?></td>
						</tr>
						<tr>
							<td class = "tdInput" colspan="2">
			    				<div class = "profilePicDivision">
			    					<div class = "profilePicContent">
			    						<img class = "profilePic" src="<?php echo $modalPenaltyPic; ?>">
			    					</div>
			    				</div>
			    			</td>
			    		</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User ID</td>
						    <td class = "tdInput"><?php echo $modalPenaltyUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalPenaltyName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email</td>
						    <td class = "tdInput max"><?php echo $modalPenaltyEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact No.</td>
						    <td class = "tdInput"><?php echo $modalPenaltyContact;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalPenaltyGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User Type</td>
						    <td class = "tdInput"><?php echo $modalPenaltyType; ?></td>
						</tr>
					</table>
				</div>
  			</div>
		</div>

		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var penaltyID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkPenalty'])){echo '&sort=' . $_SESSION['linkPenalty'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=penalty&penaltyID="+penaltyID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
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
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
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