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
		<link rel="stylesheet" type="text/css" href="Styles/admin-acceptRequirementsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-acceptRequirementsModalStyles.css">

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
				<div class= "title"> Accept Requirements </div>

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
	      										<a href="index?route=accept_requirements&sort=requirementID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=accept_requirements&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=accept_requirements&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	      										<a href="index?route=accept_requirements&sort=Type<?php echo $optionSearch . $search; ?>">User Type</a>
	      										<a href="index?route=accept_requirements&sort=requirementtype<?php echo $optionSearch . $search; ?>">Requirement</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=accept_requirements"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'requirementID'){
														echo " selected ";
													}
												}
											?>value="requirementID">ID</option>
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
													if($_GET['optionSearch'] == 'Type'){
														echo " selected ";
													}
												}
											?>
											value="Type">User Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'requirementtype'){
														echo " selected ";
													}
												}
											?>
											value="requirementtype">Requirement</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type="hidden" name = "route" value = "accept_requirements">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
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
					<!-- <col width = "90"> -->
					<thead>
						<tr>
							<th> ID </th>
							<th class = name> Name </th>
							<th> Email </th>
							<th> User Type </th>
							<th> Requirement </th>
							<th> Action </th>
						</tr>			
					</thead>
					<tbody>
						<?php
							$sqlRequirements = "SELECT requirementID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = RS.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = RS.userID) AS email, (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = RS.userID) AS user_type, (SELECT name FROM requirementtype AS RE WHERE RE.requirementTypeID = RS.requirementTypeID) AS requirement_type FROM requirements AS RS WHERE submitted = 0 AND expirationDate > CURDATE() AND (SELECT count(requirementID) FROM requirements AS RSS WHERE RSS.userID = RS.userID AND RSS.requirementTypeID = RS.requirementTypeID AND submitted = 1) < 1";

							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
								if($_GET['optionSearch'] == 'requirementID'){
									$optionSearchVar = "requirementID";
								}
								elseif($_GET['optionSearch'] == 'name'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = RS.userID)";
								}
								elseif($_GET['optionSearch'] == 'email'){
									$optionSearchVar = "(SELECT email FROM users AS US WHERE US.userID = RS.userID)";
								}
								elseif($_GET['optionSearch'] == 'Type'){
									$optionSearchVar = "(SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = RS.userID)";
								}
								elseif($_GET['optionSearch'] == 'requirementtype'){
									$optionSearchVar = "(SELECT name FROM requirementtype AS RE WHERE RE.requirementTypeID = RS.requirementTypeID)";
								}

		    					$sqlRequirements .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'requirementID'){
		    						$sqlRequirements .= " ORDER BY requirementID";
		    						$_SESSION['linkCustomer'] = "requirementID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlRequirements .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = RS.userID)";
								    $_SESSION['linkCustomer'] = "name";
								}
								elseif ($_GET['sort'] == 'email')
								{
								    $sqlRequirements .= " ORDER BY (SELECT email FROM users AS US WHERE US.userID = RS.userID)";
								    $_SESSION['linkCustomer'] = "email";
								}
								elseif ($_GET['sort'] == 'Type')
								{
								    $sqlRequirements .= " ORDER BY (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = RS.userID)";
								    $_SESSION['linkCustomer'] = "Type";
								}
								elseif ($_GET['sort'] == 'requirementtype')
								{
								    $sqlRequirements .= " ORDER BY (SELECT name FROM requirementtype AS RE WHERE RE.requirementTypeID = RS.requirementTypeID)";
								    $_SESSION['linkCustomer'] = "requirementtype";
								}
							}
							$stmt = $con->prepare($sqlRequirements);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowRequirements){
								$reqUserID = $rowRequirements["requirementID"];
			    				$reqName = $rowRequirements["name"];
			    				$reqEmail = $rowRequirements["email"];
			    				$reqUserType = $rowRequirements["user_type"];
			    				$reqType = $rowRequirements["requirement_type"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $reqUserID; ?></td>
						        	<td><?php echo $reqName; ?></td>
						        	<td class = "max word-wrap"><?php echo $reqEmail; ?></td>
						        	<td><?php echo $reqUserType; ?></td>
						        	<td><?php echo $reqType; ?></td>
						        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
						        </tr>
							 <?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = 4> No results. </td>";
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
    						if(isset($_GET['requirementID'])){
    							$sqlReqModal = "SELECT requirementID, userID, (SELECT profilepicture FROM users AS US WHERE US.userID = RS.userID) AS profilepicture, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = RS.userID) AS name, (SELECT email FROM users AS US WHERE US.userID = RS.userID) AS email, (SELECT CASE WHEN Type = 1 THEN 'Customer' WHEN Type = 2 THEN 'Handyman' END FROM users AS US WHERE US.userID = RS.userID) AS user_type, (SELECT name FROM requirementtype AS RE WHERE RE.requirementTypeID = RS.requirementTypeID) AS requirement_type, file, DATE_FORMAT(expirationDate, '%M %d, %Y') AS expirationDate FROM requirements AS RS WHERE submitted = 0 AND expirationDate > CURDATE() AND requirementID = :requirementID";

    							$stmt = $con->prepare($sqlReqModal);
								$stmt->bindParam(':requirementID', $_GET['requirementID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowReqModal = $stmt->fetch();
								
								$modalReqID = $rowReqModal["requirementID"];
			    				if (empty($rowReqModal["profilepicture"])){
			    					$modalReqProfilePicture = "ProfilePictures/userIcon.png";
			    				}
			    				else{
			    					$modalReqProfilePicture = $rowReqModal["profilepicture"];
			    				}
			    				$modalReqUserID = $rowReqModal["userID"];
			    				$modalReqName = $rowReqModal["name"];
			    				$modalReqEmail = $rowReqModal["email"];
			    				$modalReqUserType = $rowReqModal["user_type"];
			    				$modalReqType = $rowReqModal["requirement_type"];
			    				$modalReqFile = $rowReqModal["file"];
			    				$modalReqExpirationDate = $rowReqModal["expirationDate"];
						}
    					?>
    				</div>
    				<table class = "tableInputs">
						<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">ID</td>
						    <td class = "tdInput"><?php echo $modalReqID; ?></td>
						</tr>
						<tr class = "trInputs">
							<td class = "tdInput" colspan="2">
								<div class = "profilePicContent">
    								<img class = "profilePic" src="<?php echo $modalReqProfilePicture;?>">
    							</div>
							</td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User ID</td>
						    <td class = "tdInput"><?php echo $modalReqUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalReqName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email</td>
						    <td class = "tdInput"><?php echo $modalReqEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User Type</td>
						    <td class = "tdInput"><?php echo $modalReqUserType;?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Requirement</td>
						    <td class = "tdInput"><?php echo $modalReqType; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdInput" colspan="2">
						    	<img class = "reqPic" src="<?php echo $modalReqFile;?>">
						    </td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Expiration Address</td>
						    <td class = "tdInput max"><?php echo $modalReqExpirationDate; ?></td>
						</tr>
					</table>
					<div class = "transactionAndReportBtn">
						<form method="post">
						<div class = "YesNo">
	    					<button name="Yes" class = "yesButton"> ACCEPT </button>
	    					<button name="No" class = "noButton"> REJECT </button>
	    				</div>
	    				</form>
					</div>
					<?php
						if((isset($_POST['Yes']))&&(isset($_GET['requirementID']))){
							$sqlUpdateReq = "UPDATE requirements
												SET submitted = 1
												WHERE requirementID = (:requirementID);";
							$stmt = $con->prepare($sqlUpdateReq);
							$stmt->bindParam(':requirementID', $_GET['requirementID'], PDO::PARAM_INT);
							$stmt->execute();
							echo "<script type='text/javascript'>alert('Requirement accepted.');window.location.href='index?route=accept_requirements&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
						}

						if((isset($_POST['No']))&&(isset($_GET['requirementID']))){
							$sqlUpdateReq = "UPDATE requirements
												SET expirationDate = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
												WHERE requirementID = (:requirementID);";
							$stmt = $con->prepare($sqlUpdateReq);
							$stmt->bindParam(':requirementID', $_GET['requirementID'], PDO::PARAM_INT);
							$stmt->execute();
							echo "<script type='text/javascript'>alert('Requirement rejected.');window.location.href='index?route=accept_requirements&sort=".$_SESSION['linkCustomer'].$optionSearch.$search."';</script>";
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
							var requirementID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkCustomer'])){echo '&sort=' . $_SESSION['linkCustomer'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=accept_requirements&requirementID="+requirementID+linkSort+search+optionSearch
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