<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-requirementTypesStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-requirementTypesModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['requirementLink'] = "";
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
				<div class= "title"> MAINTENANCE - Requirements </div>
				<table id = actionTable>
					<col width = "620">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='addReq' class = "sortBtn" onclick='addRequirementsModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
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
	      										<a href="index?route=requirementTypes&sort=requirementTypeID<?php echo $optionSearch.$search;?>">ID</a>
	      										<a href="index?route=requirementTypes&sort=name<?php echo $optionSearch.$search;?>">Name</a>
	      										<a href="index?route=requirementTypes&sort=lastModified<?php echo $optionSearch.$search;?>">Last Modified</a>
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
										<a href = "index?route=requirementTypes&sort=requirementTypeID"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'requirementTypeID'){
														echo " selected ";
													}
												}
											?>value="requirementTypeID">ID</option>
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
													if($_GET['optionSearch'] == 'lastModified'){
														echo " selected ";
													}
												}
											?>
											value="lastModified">Last Modified</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "requirementTypes">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = notificationTable>
					<col width = "50">
					<col width = "580">
					<col width = "220">
					<thead>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Last Modified </th>
							<th colspan="2"> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlRequirementTypes = "SELECT requirementTypeID, name, DATE_FORMAT(lastModified,'%b %d, %Y') AS lastModified, flag FROM requirementtype WHERE flag = 1";
							if(isset($_GET['optionSearch'])){
		    					$sqlRequirementTypes .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'requirementTypeID'){
		    						$sqlRequirementTypes .= " ORDER BY requirementTypeID";
		    						$_SESSION['requirementLink'] = "requirementTypeID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlRequirementTypes .= " ORDER BY name";
								    $_SESSION['requirementLink'] = "name";
								}
								elseif ($_GET['sort'] == 'lastModified')
								{
								    $sqlRequirementTypes .= " ORDER BY lastModified";
								    $_SESSION['requirementLink'] = "lastModified";
								}
							}

							$stmt = $con->prepare($sqlRequirementTypes);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowRequirementTypes){
								$requirementTypesID = $rowRequirementTypes["requirementTypeID"];
			    				$requirementTypesName = $rowRequirementTypes["name"];
			    				$requirementTypesLastModified = $rowRequirementTypes["lastModified"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $requirementTypesID; ?></td>
						        	<td><?php echo $requirementTypesName; ?></td>
						        	<td><?php echo $requirementTypesLastModified; ?></td>
						        	<td><button id='edit' class = 'edit' onclick='viewEditModal()'>Edit</button></td>
						        	<td><input type = 'submit' class = 'delete' onclick = 'viewYesNo()' value = 'Delete'></td>
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
		<form method="post">
		<div id="viewAddModal" class="viewAddModal">
			<div class="viewAddModal-content">
				<span class="viewAddClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Add Requirement Types</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Requirement Name</td>
							<td class = "tdInput"><input type="text" name="reqName" placeholder="Enter service name here..." maxlength="60" pattern = "[a-zA-Z0-9 ]+" title="Must only contain letters, and numbers" required></td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "addReq"> ADD </button>
					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['addReq'])){
					$reqNameAdd = $_POST['reqName'];
					$dateToday = date('Y-m-d');
					$sqlReqAddCheck = "SELECT requirementTypeID, flag FROM requirementtype WHERE name = (:reqNameAdd)";
					$stmt = $con->prepare($sqlReqAddCheck);
					$stmt->bindParam(':reqNameAdd', $reqNameAdd, PDO::PARAM_STR);
					$stmt->execute();
					$rowReqAddCheck = $stmt->fetch();
					$rowCount = $stmt->rowCount();

					$requirementReqIDFound = $rowReqAddCheck["requirementTypeID"];
	    			$requirementFlagFound = $rowReqAddCheck["flag"];

	    			if(($rowCount >= 1)&&($requirementFlagFound == 0)){
	    				$sqlReqFoundUpdate = "UPDATE requirementtype
													SET flag = 1
													WHERE requirementTypeID = :requirementReqIDFound";
						$stmt = $con->prepare($sqlReqFoundUpdate);
						$stmt->bindParam(':requirementReqIDFound', $requirementReqIDFound, PDO::PARAM_INT);
						$stmt->execute();
						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';</script>";
	    			}
	    			elseif(($rowCount > 0) && ($requirementFlagFound == 1)){
	    				echo "<script>
	    					alert('There is already existing requirement type.');
	    					// Get the modal
						    var modal = document.getElementById('viewAddModal');

						    // Get the button that opens the modal
						    var btn = document.getElementById('addNotif');

						    // Get the <span> element that closes the modal
						    var span = document.getElementsByClassName('viewAddClose')[0];

						    // When the user clicks the button, open the modal 
						    viewAddModal.style.display = 'block';
						    // When the user clicks on <span> (x), close the modal
						    span.onclick = function() {
						        viewAddModal.style.display = 'none';
						        window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';
						    }

						    // When the user clicks anywhere outside of the modal, close it
						    window.onclick = function(event) {
						        if (event.target == modal) {
						            viewAddModal.style.display = 'none';
						            window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';
						        }
						    }
	    				</script>";
	    			}
	    			elseif($rowCount == 0){
	    				$sqlAddReq = "INSERT INTO requirementtype (name, lastModified)values(:reqNameAdd, :dateToday)";

						$stmt = $con->prepare($sqlAddReq);
						$stmt->bindParam(':reqNameAdd', $reqNameAdd, PDO::PARAM_STR);
						$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';</script>";
	    			}		
				}
			?>
		</div>
		</form>

		<form method="post">
		<div id="viewEditModal" class="viewEditModal">
			<div class="viewEditModal-content">
				<span class="viewEditClose">&times;</span>
				<?php
					if(isset($_GET['reqTypeID'])){
						$sqlReqEdit = "SELECT requirementTypeID, name, lastModified FROM requirementtype WHERE flag = 1 AND requirementTypeID = (:reqTypeID)";

						$stmt = $con->prepare($sqlReqEdit);
						$stmt->bindParam(':reqTypeID', $_GET['reqTypeID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowReqEdit = $stmt->fetch();

						$reqIDEdit = $rowReqEdit["requirementTypeID"];
	    				$reqNameEdit = $rowReqEdit["name"];
		    		}
				?>
				<div class = "details">
					<div class = "titleDetails"><b>Edit Requirement Type</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Requirement Name</td>
						    <td class = "tdInput"><input type="text"  maxlength="60" name="reqNameEdit" placeholder="Enter service name here..." value = "<?php echo $reqNameEdit; ?>" required pattern = "[a-zA-Z0-9 ]+" title="Must only contain letters, and numbers"></td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "updateReq"> UPDATE </button>
					</div>
				</div>
			</div>
		</div>
		</form>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<?php
    				if(isset($_GET['reqTypeID'])){
						$sqlReqDelete = "SELECT requirementTypeID, name FROM requirementtype WHERE requirementTypeID = (:reqTypeID)";

						$stmt = $con->prepare($sqlReqDelete);
						$stmt->bindParam(':reqTypeID', $_GET['reqTypeID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowReqDelete = $stmt->fetch();

						$rowReqIDDelete = $rowReqDelete["requirementTypeID"];
						$rowReqNameDelete = $rowReqDelete["name"];
					}
				?>
    			<div class = "details">
    			<div class = "titleDetails"><b>Are you sure?</b></div>
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
			    				<tr class = "tableContentYesNo">
						        	<td><?php echo $rowReqIDDelete; ?></td>
						        	<td><?php echo $rowReqNameDelete; ?></td>
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
					$sqlActiveModal = "UPDATE requirementtype
										SET flag = 0
										WHERE requirementTypeID = :rowReqIDDelete";
					$stmt = $con->prepare($sqlActiveModal);
					$stmt->bindParam(':rowReqIDDelete', $rowReqIDDelete, PDO::PARAM_INT);
					$stmt->execute();
					echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';</script>";
				}
				?>
  			</div>
		</div>
	</div>

	<div id="viewModalArchived" class="viewModalArchived">
  			<div class="viewModalArchived-content">
    			<span class="viewCloseArchived">&times;</span>
    			<div class = "details">
    			<div class = "titleDetails"><b>Deleted requirement types</b></div>
    			<table id = userTableArchived>
    				<col width = "50">
					<col width = "400">
					<col width = "170">
					<thead>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Date Modified </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlRequirementTypeDeleted = "SELECT requirementTypeID, name, DATE_FORMAT(lastModified, '%M %d, %Y') AS lastModified FROM requirementtype WHERE flag = 0";

							$stmt = $con->prepare($sqlRequirementTypeDeleted);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowRequirementTypeDeleted){
								$requirementTypeIDDeleted = $rowRequirementTypeDeleted["requirementTypeID"];
			    				$requirementNameDeleted = $rowRequirementTypeDeleted["name"];
			    				$requirementLastModifiedDeleted = $rowRequirementTypeDeleted["lastModified"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $requirementTypeIDDeleted; ?></td>
						        	<td><?php echo $requirementNameDeleted; ?></td>
						        	<td><?php echo $requirementLastModifiedDeleted; ?></td>
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
	</div>

		<?php
			if(isset($_POST['updateReq'])){
				$reqNameEdit = $_POST["reqNameEdit"];
				$dateToday = date('Y-m-d');
				$sqlReqUpdateCheck = "SELECT requirementTypeID, flag FROM requirementtype WHERE name = (:reqNameEdit) AND requirementTypeID <> (:reqIDEdit)";

				$stmt = $con->prepare($sqlReqUpdateCheck);
				$stmt->bindParam(':reqIDEdit', $reqIDEdit, PDO::PARAM_INT);
				$stmt->bindParam(':reqNameEdit', $reqNameEdit, PDO::PARAM_STR);
				$stmt->execute();
				$rowReqUpdateFound = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				$reqReqIDUpdateFound = $rowReqUpdateFound["requirementTypeID"];
    			$reqFlagUpdateFound = $rowReqUpdateFound["flag"];

    			if($rowCount > 0){
    				echo "<script>alert('There is already existing requirement type.');localStorage.setItem('viewEditModal',true);</script>";
    			}
    			else{
    				$sqlReqUpdate = "UPDATE requirementtype
									SET name = (:reqNameEdit), lastModified = (:dateToday)
									WHERE requirementTypeID = (:reqIDEdit)";
					$stmt = $con->prepare($sqlReqUpdate);
					$stmt->bindParam(':reqIDEdit', $reqIDEdit, PDO::PARAM_INT);
					$stmt->bindParam(':reqNameEdit', $reqNameEdit, PDO::PARAM_STR);
					$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
					$stmt->execute();

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=requirementTypes&sort=".$_SESSION['requirementLink'].$optionSearch.$search."';</script>";
    			}
			}
		?>
		<script type="text/javascript">
			function addRequirementsModal(){
			    var modal = document.getElementById('viewAddModal');
			    var btn = document.getElementById('addReq');
			    var span = document.getElementsByClassName("viewAddClose")[0];
			    viewAddModal.style.display = "block";
			    span.onclick = function() {
			        viewAddModal.style.display = "none";
			    }
			    window.onclick = function(event) {
			        if (event.target == modal) {
			            viewAddModal.style.display = "none";
			        }
			    }
			}
			
			function viewEditModal(){
				var table = document.getElementById('notificationTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var requirementTypeID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['requirementLink'])){echo '&sort=' . $_SESSION['requirementLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=requirementTypes&reqTypeID="+requirementTypeID+linkSort+search+optionSearch);
							localStorage.setItem('viewEditModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function viewYesNo(){
				var table = document.getElementById('notificationTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var requirementTypeID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['requirementLink'])){echo '&sort=' . $_SESSION['requirementLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=requirementTypes&reqTypeID="+requirementTypeID+linkSort+search+optionSearch);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function viewArchived(){
			    var modal = document.getElementById('viewModalArchived');
			    var btn = document.getElementById('viewArchived');
			    var span = document.getElementsByClassName("viewCloseArchived")[0];
				modal.style.display = "block";
			    span.onclick = function() {
			        viewModalArchived.style.display = "none";
			    }
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

			var span = document.getElementsByClassName("viewEditClose")[0];
			span.onclick = function() {
				document.getElementById('viewEditModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewModalYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewEditModal')) {
		            document.getElementById('viewEditModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
		        }
		    }
		</script>
		<script src="Script/addModal.js" type="text/javascript"></script>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>