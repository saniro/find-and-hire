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
		<link rel="stylesheet" type="text/css" href="Styles/admin-requestStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-requestModalStyles.css">

		<script class="jsbin" src="Libraries/jquery/jquery.min.js"></script>
		<script class="jsbin" src="Libraries/jquery/jquery-ui.min.js"></script>
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkRequest'] = "";
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
				<div class= "title"> ACCOUNTS - Request </div>

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
	      										<a href="index?route=request&sort=userID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=request&sort=name<?php echo $optionSearch . $search; ?>">Name</a>
	      										<a href="index?route=request&sort=email<?php echo $optionSearch . $search; ?>">Email Address</a>
	    									</div>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=request"><img class = "resetAction" src="Resources/reset.png"></a>
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
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type="hidden" name = "route" value = "request">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				
				<table id = userTable>
					<col width = "50">
					<col width = "320">
					<col width = "330">
					<col width = "95">
					<thead>
						<tr>
							<th> ID </th>
							<th class = name> Name </th>
							<th> Email Address </th>
							<th colspan = 3> Action </th>
						</tr>			
					</thead>
					<tbody>
						<?php
							$sqlRequest = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name, email, (SELECT count(reportsID) FROM reports WHERE reportedID = userID) AS reportNo, flag FROM users WHERE (type = 1 OR type = 2) AND (requestPic != '')";

							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
								if($_GET['optionSearch'] == 'userID'){
									$optionSearchVar = "userID";
								}
								elseif($_GET['optionSearch'] == 'name'){
									$optionSearchVar = "concat(lastName, ', ', firstName, ' ', middleName)";
								}
								elseif($_GET['optionSearch'] == 'email'){
									$optionSearchVar = "email";
								}
								elseif($_GET['optionSearch'] == 'reportNo'){
									$optionSearchVar = "(SELECT count(reportsID) FROM reports WHERE reportedID = userID)";
								}

		    					$sqlRequest .= " AND " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'userID'){
		    						$sqlRequest .= " ORDER BY userID";
		    						$_SESSION['linkRequest'] = "userID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlRequest .= " ORDER BY name";
								    $_SESSION['linkRequest'] = "name";
								}
								elseif ($_GET['sort'] == 'email')
								{
								    $sqlRequest .= " ORDER BY email";
								    $_SESSION['linkRequest'] = "email";
								}
							}
							$stmt = $con->prepare($sqlRequest);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowRequest){
								$infoUserID = $rowRequest["userID"];
			    				$infoName = $rowRequest["name"];
			    				$infoEmail = $rowRequest["email"];
			    				$infoReportNo = $rowRequest["reportNo"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $infoUserID; ?></td>
						        	<td><?php echo $infoName; ?></td>
						        	<td><?php echo $infoEmail; ?></td>
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
    						if(isset($_GET['userID'])){
    							$sqlRequestModal = "SELECT userID, concat(lastName, ', ', firstName, ' ', middleName) AS name, (CASE WHEN gender = 1 THEN 'Male' WHEN gender = 0 THEN 'Female' END) AS gender, DATE_FORMAT(birthDate,'%b %d, %Y') AS birthDate, email, contact, profilepicture, requestPic FROM users WHERE userID = (:userID)";

    							$stmt = $con->prepare($sqlRequestModal);
								$stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_INT);
								$stmt->execute();
								$rowRequestModal = $stmt->fetch();

								$modalRequestUserID = $rowRequestModal["userID"];
			    				$modalRequestName = $rowRequestModal["name"];
			    				$modalRequestGender = $rowRequestModal["gender"];
			    				$modalRequestBirthdate = $rowRequestModal["birthDate"];
			    				$modalRequestEmail = $rowRequestModal["email"];
			    				$modalRequestContact = $rowRequestModal["contact"];
			    				if (empty($rowRequestModal["profilepicture"])){
			    					$modalRequestProfilePic = "ProfilePictures/userIcon.png";
			    				}
			    				else{
			    					$modalRequestProfilePic = $rowRequestModal["profilepicture"];
			    				}

			    				$modalRequestReqProfilePic = $rowRequestModal["requestPic"];
						}
    					?>
    				<table class = "tableInputs">
						<col width="170">
						<tr>
							<td class = "tdName" colspan="2">
								Current Profile
							</td>
						</tr>
						<tr>
							<td class = "tdInput" colspan="2">
								<div class = "profilePicContent">
    								<img class = "profilePic" src="<?php echo $modalRequestProfilePic;?>">
    							</div>
							</td>
						</tr>
						<tr>
							<td class = "tdName" colspan="2">
								Requested Profile
							</td>
						</tr>
						<tr>
							<td class = "tdInput" colspan="2">
								<div class = "profilePicContent">
    								<img class = "profilePic" src="<?php echo $modalRequestReqProfilePic; ?>">
    							</div>
							</td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">User Number</td>
						    <td class = "tdInput"><?php echo $modalRequestUserID; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Name</td>
						    <td class = "tdInput"><?php echo $modalRequestName; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Gender</td>
						    <td class = "tdInput"><?php echo $modalRequestGender; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Birthdate</td>
						    <td class = "tdInput"><?php echo $modalRequestBirthdate; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Email Address</td>
						    <td class = "tdInput"><?php echo $modalRequestEmail; ?></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Contact Number</td>
						    <td class = "tdInput"><?php echo $modalRequestContact; ?></td>
						</tr>
					</table>
					<form method="post">
						<div class = "transactionAndReportBtn">
							<button name = "acceptRequest" class = "updateBig">ACCEPT REQUEST</button>
						</div>
					</form>
				</div>
  			</div>
		</div>
		<?php
			if(isset($_POST['acceptRequest'])){
				$sqlAcceptModal = "UPDATE users
									SET profilepicture = :profilepicture, requestPic = ''
									WHERE userID = :modalRequestUserID";
				$stmt = $con->prepare($sqlAcceptModal);
				$stmt->bindParam(':modalRequestUserID', $modalRequestUserID, PDO::PARAM_INT);
				$stmt->bindParam(':profilepicture', $modalRequestReqProfilePic, PDO::PARAM_STR);
				$stmt->execute();
				echo "<script type='text/javascript'>alert('Request Accepted.');window.location.href='index?route=request&sort=".$_SESSION['linkRequest'].$optionSearch.$search."';</script>";
			}
		?>
		<script src="Script/viewModal.js" type="text/javascript"></script>
		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var userID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkRequest'])){echo '&sort=' . $_SESSION['linkRequest'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=request&userID="+userID+linkSort+search+optionSearch
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