<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-serviceStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-serviceModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['serviceLink'] = "";
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
				<div class= "title"> MAINTENANCE - Services </div>
				<table id = actionTable>
					<col width = "600">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='addViolation' class = "sortBtn" onclick='addServiceModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
	    									</a>
	  									</li>
	  									<li id = "dropdownSortID" class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div class = "sortBtn"><img class = "iconAction" src="Resources/sort-by-attributes.png">SORT</div>
	    									</a>
	    									<form method="get">
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
	      										<a href="index?route=service&sort=serviceID<?php echo $optionSearch.$search;?>">ID</a>
	      										<a href="index?route=service&sort=name<?php echo $optionSearch.$search;?>">Service Name</a>
	    									</div>
	    									</form>
	  									</li>
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='viewArchived' class = "viewArchived" onclick='viewArchived()'><img class = "iconAction" src="Resources/archived.png">ARCHIVED</div>
	    									</a>
	  									</li>
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=service"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'serviceID'){
														echo " selected ";
													}
												}
											?>value="serviceID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'name'){
														echo " selected ";
													}
												}
											?>
											value="name">Service Name</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "service">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = serviceTable>
					<col width = "50">
					<col width = "680">
					<thead>
						<tr>
							<th> ID </th>
							<th> Service Name </th>
							<th colspan="3"> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlService = "SELECT serviceID, name, flag FROM services WHERE flag = 1";
							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
		    					$sqlService .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'serviceID'){
		    						$sqlService .= " ORDER BY serviceID";
		    						$_SESSION['serviceLink'] = "serviceID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlService .= " ORDER BY name";
								    $_SESSION['serviceLink'] = "name";
								}
							}

							$stmt = $con->prepare($sqlService);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowService){
								$serviceServiceID = $rowService["serviceID"];
			    				$serviceName = $rowService["name"];
			    				$serviceFlag = $rowService["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $serviceServiceID; ?></td>
						        	<td><?php echo $serviceName; ?></td>
						        	<td><button id='viewBtn' class = 'view' onclick="viewModal()">View</button></td>
						        	<td><button id='edit' class = 'edit' onclick='viewEditModal()'>Edit</button></td>
						        	<td><input type = 'submit' class = 'delete' onclick = 'viewYesNo()' value = 'Delete'></td>
					        	</tr>
				        <?php
					    	}
					    	if($rowCount == 0){
								echo "<td colspan = 3> No results. </td>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<form method="post">
			<div id="viewAddModal" class="viewAddModal">
				<div class="viewAddModal-content">
					<span class="viewAddClose">&times;</span>
					<div class = "details">
						<div class = "titleDetails"><b>Add Service</b></div>
						<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Service Name</td>
							    <td class = "tdInput"><input type="text" name="serviceName" placeholder="Enter service name here..."  maxlength="60" pattern = "[a-zA-Z0-9 ]+" title="Must only contain letters, and numbers" required></td>
							</tr>
						</table>
						<div class = buttonSubmit>
  							<button type = "submit" class="addSubmit" name = "addService"> ADD </button>
  						</div>
					</div>
				</div>
				<?php
					if(isset($_POST['addService'])){
						$serviceNameAdd = $_POST['serviceName'];

						$sqlServiceNameAddCheck = "SELECT serviceID, flag FROM services WHERE name = (:serviceName)";
						$stmt = $con->prepare($sqlServiceNameAddCheck);
						$stmt->bindParam(':serviceName', $serviceNameAdd, PDO::PARAM_STR);
						$stmt->execute();
						$rowServiceFound = $stmt->fetch();
						$rowCount = $stmt->rowCount();

						$serviceServiceIDFound = $rowServiceFound["serviceID"];
		    			$serviceFlagFound = $rowServiceFound["flag"];

		    			if(($rowCount >= 1)&&($serviceFlagFound == 0)){
		    				$sqlServiceFoundUpdate = "UPDATE services
													SET flag = 1
													WHERE serviceID = :serviceServiceIDFound";

							$stmt = $con->prepare($sqlServiceFoundUpdate);
							$stmt->bindParam(':serviceServiceIDFound', $serviceServiceIDFound, PDO::PARAM_INT);
							$stmt->execute();
							echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=serviceOptions&serviceID=".$serviceServiceIDFound."';</script>";
		    			}
		    			elseif(($rowCount > 0) && ($serviceFlag == 1)){
		    				echo "
		    					<script>
		    					alert('There is already existing service.');
						    	// Get the modal
							    var modal = document.getElementById('viewAddModal');

							    // Get the button that opens the modal
							    var btn = document.getElementById('addViolation');

							    // Get the <span> element that closes the modal
							    var span = document.getElementsByClassName('viewAddClose')[0];

							    // When the user clicks the button, open the modal 
							    modal.style.display = 'block';
							    // When the user clicks on <span> (x), close the modal
							    span.onclick = function() {
							        modal.style.display = 'none';
							        window.location.href='index?route=service&sort=".$_SESSION['serviceLink'].$optionSearch.$search."';
							    }

							    // When the user clicks anywhere outside of the modal, close it
							    window.onclick = function(event) {
							        if (event.target == modal) {
							            modal.style.display = 'none';
							            window.location.href='index?route=service&sort=".$_SESSION['serviceLink'].$optionSearch.$search."';
							        }
							    }
							    </script>";
		    			}
		    			elseif($rowCount == 0){
		    				$sqlAddService = "INSERT INTO services (name, flag)values(:serviceNameAdd, 1)";

							$stmt = $con->prepare($sqlAddService);
							$stmt->bindParam(':serviceNameAdd', $serviceNameAdd, PDO::PARAM_STR);
							$stmt->execute();
							$lastServiceID = $con->lastInsertId();
							echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=serviceOptions&serviceID=".$lastServiceID."';</script>";
		    			}
					}
				?>
			</div>
		</form>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
    				<?php
    					if(isset($_GET['serviceID'])){
	    					$sqlYesNo = "SELECT serviceID, name, flag FROM services WHERE serviceID = (:serviceID)";

	    					$stmt = $con->prepare($sqlYesNo);
	    					$stmt->bindParam(':serviceID', $_GET['serviceID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowYesNo = $stmt->fetch();

							$modalYesNoServiceID = $rowYesNo["serviceID"];
							$modalYesNoName = $rowYesNo["name"];
				    		$modalYesNoFlag = $rowYesNo["flag"];
						}
    				?>
	    			<div class = "titleDetails"><b>Are you sure?</b></div>
	    			<div class = "profilePicDivision">
	    				<form method="post">
	    					<table class = "tableYesNo">
	    						<col width = "50">
		    					<thead class = "tableYesNoHead">
									<tr>
										<th> ID </th>
										<th> Service </th>
									</tr>
								</thead>
		    					<tbody>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoServiceID; ?></td>
							        	<td><?php echo $modalYesNoName; ?></td>
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
							$sqlActiveModal = "UPDATE services
												SET flag = 0
												WHERE serviceID = :modalYesNoServiceID";

							$stmt = $con->prepare($sqlActiveModal);
							$stmt->bindParam(':modalYesNoServiceID', $modalYesNoServiceID, PDO::PARAM_INT);
							$stmt->execute();

							echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='index?route=service&sort=".$_SESSION['serviceLink'].$optionSearch.$search."';</script>";
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
	    				<col width= "50">
						<thead>
							<tr>
								<th> ID </th>
								<th> Service Name </th>
							</tr>				
						</thead>
						<tbody>
							<?php
								$sqlServiceDeleted = "SELECT serviceID, name, flag FROM services WHERE flag = 0";

								$stmt = $con->prepare($sqlServiceDeleted);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach($results as $rowServiceDeleted){
									$serviceIDDeleted = $rowServiceDeleted["serviceID"];
				    				$serviceNameDeleted = $rowServiceDeleted["name"];
				    				?>
									<tr class = "tableContent">
							        	<td><?php echo $serviceIDDeleted; ?></td>
							        	<td><?php echo $serviceNameDeleted; ?></td>
							        </tr>
							<?php
						    	}
						    	if($rowCount == 0){
									echo "<td colspan = 2> No results. </td>";
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
						if(isset($_GET['serviceID'])){
							$sqlServiceEdit = "SELECT serviceID, name FROM services WHERE serviceID = (:serviceID)";

							$stmt = $con->prepare($sqlServiceEdit);
							$stmt->bindParam(':serviceID', $_GET['serviceID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowServiceEdit = $stmt->fetch();

							$serviceIDEdit = $rowServiceEdit["serviceID"];
				    		$serviceNameEdit = $rowServiceEdit["name"];
				    	}
					?>
					<div class = "details">
						<div class = "titleDetails"><b>Edit Service</b></div>
						<table class = "tableInputs">
	    					<col width="130">
							<tr class = "trInputs">
							    <td class = "tdName">Service Name</td>
							    <td class = "tdInput"><input type="text" name="serviceNameUpdate" placeholder="Enter service name here..." pattern = "[a-zA-Z0-9 ]+" title="Must only contain letters, and numbers" value = "<?php echo $serviceNameEdit ?>" required></td>
							</tr>
						</table>
						<div class = buttonSubmit>
  							<button type = "submit" class="addSubmit" name = "updateService"> UPDATE </button>
  						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
			if(isset($_POST['updateService'])){
				$serviceNameUpdate = $_POST['serviceNameUpdate'];

				$sqlServiceNameUpdateCheck = "SELECT serviceID, flag FROM services WHERE name = (:serviceName) AND serviceID <> (:serviceIDEdit)";

				$stmt = $con->prepare($sqlServiceNameUpdateCheck);
				$stmt->bindParam(':serviceIDEdit', $serviceIDEdit, PDO::PARAM_STR);
				$stmt->bindParam(':serviceName', $serviceNameUpdate, PDO::PARAM_STR);
				$stmt->execute();
				$rowServiceUpdateFound = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				$serviceServiceIDUpdateFound = $rowServiceUpdateFound["serviceID"];
    			$serviceFlagUpdateFound = $rowServiceUpdateFound["flag"];

    			if($rowCount > 0){
    				echo "<script>alert('There is already existing service.');localStorage.setItem('viewEditModal',true);</script>";
    			}
    			else{
    				$sqlServices = "UPDATE services
								SET name = :serviceNameUpdate
								WHERE serviceID = '$serviceIDEdit'";
					$stmt = $con->prepare($sqlServices);
					$stmt->bindParam(':serviceNameUpdate', $serviceNameUpdate, PDO::PARAM_STR);
					$stmt->execute();

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=service&sort=".$_SESSION['serviceLink'].$optionSearch.$search."';</script>";
    			}
			}
		?>

		<script type = 'text/javascript'>
			function addServiceModal(){
			    // Get the modal
			    var modal = document.getElementById('viewAddModal');

			    // Get the button that opens the modal
			    var btn = document.getElementById('addViolation');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName("viewAddClose")[0];

			    // When the user clicks the button, open the modal 
			    modal.style.display = "block";
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			        modal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			            modal.style.display = "none";
			        }
			    }
			}

			function viewModal(){
				var table = document.getElementById('serviceTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var serviceID = this.cells[0].innerHTML;
							location.replace("index?route=serviceOptions&serviceID="+serviceID);
						}						
					}
				}
			}

			function viewEditModal(){
				var table = document.getElementById('serviceTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var serviceID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['serviceLink'])){echo '&sort=' . $_SESSION['serviceLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=service&serviceID="+serviceID+linkSort+search+optionSearch);
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
							var serviceID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['serviceLink'])){echo '&sort=' . $_SESSION['serviceLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=service&serviceID="+serviceID+linkSort+search+optionSearch);
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

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewEditModal')) {
		            document.getElementById('viewEditModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewCloseArchived')) {
		            document.getElementById('viewCloseArchived').style.display = "none";
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