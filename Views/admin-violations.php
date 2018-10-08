<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-violationsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-violationsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['link'] = "";
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
				<div class= "title"> MAINTENANCE - Violations </div>
				<table id = actionTable>
					<col width = "630">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='addViolation' class = "sortBtn" onclick='viewAddModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
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
	      										<a href="index?route=violations&sort=reportTypeID<?php echo $optionSearch.$search;?>">ID</a>
	      										<a href="index?route=violations&sort=name<?php echo $optionSearch.$search;?>">Violation Name</a>
	      										<a href="index?route=violations&sort=description<?php echo $optionSearch.$search;?>">Description</a>
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
										<a href = "index?route=violations"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'reportTypeID'){
														echo " selected ";
													}
												}
											?>value="reportTypeID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'name'){
														echo " selected ";
													}
												}
											?>
											value="name">Violation Name</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'description'){
														echo " selected ";
													}
												}
											?>
											value="description">Description</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "violations">&nbsp<input type=submit name="searchBtn" class = "searchBtn" value = "SEARCH">
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = "50">
					<col width = "250">
					<col width = "550">
					<thead>
						<tr>
							<th> ID </th>
							<th> Violation Name </th>
							<th> Description </th>
							<th colspan="2"> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlReport = "SELECT reportTypeID, name, description, flag FROM reportType WHERE flag = 1";
							if(isset($_GET['optionSearch'])){
		    					$sqlReport .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'reportTypeID'){
		    						$sqlReport .= " ORDER BY reportTypeID";
		    						$_SESSION['link'] = "reportTypeID";
								}
								elseif ($_GET['sort'] == 'name')
								{
								    $sqlReport .= " ORDER BY name";
								    $_SESSION['link'] = "name";
								}
								elseif ($_GET['sort'] == 'description')
								{
								    $sqlReport .= " ORDER BY description";
								    $_SESSION['link'] = "description";
								}
							}

							$stmt = $con->prepare($sqlReport);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowReport){
								$reportTypeID = $rowReport["reportTypeID"];
			    				$reportName = $rowReport["name"];
			    				$reportDescription = $rowReport["description"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $reportTypeID; ?></td>
						        	<td><?php echo $reportName; ?></td>
						        	<td><?php echo $reportDescription; ?></td>
						        	<td><button id='edit' class = 'edit' onclick='viewEditModal()'>Edit</button></td>
						        	<td><input type = 'submit' class = 'delete' onclick = 'viewYesNo()' value = 'Delete'></td>
						        </tr>
						<?php
					    	}
				    		if($rowCount == 0){
								echo "<td> No results. </td>";
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
					<div class = "titleDetails"><b>Add Violations</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Violation Name</td>
						    <td class = "tdInput"><input type="text" name="vioName" placeholder="Enter violation name here..." required maxlength="150" pattern = "[a-zA-Z0-9._%+-].{0,}" title="Must only contain letters, numbers, and ._%+-."></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Description</td>
						    <td class = "tdInput"><textarea class = "textAreaDesc" maxlength = "100" name="vioDesc" placeholder="Add description here..." required></textarea></td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "addVio"> ADD </button>
					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['addVio'])){
					$vioNameAdd = $_POST['vioName'];
					$vioDescAdd = $_POST['vioDesc'];

					$sqlViolationNameAddCheck = "SELECT reportTypeID, flag FROM reporttype WHERE name = (:vioNameAdd)";

					$stmt = $con->prepare($sqlViolationNameAddCheck);
					$stmt->bindParam(':vioNameAdd', $vioNameAdd, PDO::PARAM_STR);
					$stmt->execute();
					$rowViolationAddFound = $stmt->fetch();
					$rowCount = $stmt->rowCount();

					$violationReportAddFound = $rowViolationAddFound["reportTypeID"];
	    			$violationFlagAddFound = $rowViolationAddFound["flag"];

	    			if(($rowCount >= 1)&&($violationFlagAddFound == 0)){
	    				$sqlReportFoundUpdate = "UPDATE reporttype
													SET flag = 1
													WHERE reportTypeID = :violationReportAddFound";

						$stmt = $con->prepare($sqlReportFoundUpdate);
						$stmt->bindParam(':violationReportAddFound',$violationReportAddFound, PDO::PARAM_INT);
						$stmt->execute();
						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';</script>";
	    			}
	    			elseif(($rowCount > 0) && ($violationFlagAddFound == 1)){
	    				echo "
	    					<script>
	    						alert('There is already existing violation.');
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
							    	window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';
							        modal.style.display = 'none';
							    }

							    // When the user clicks anywhere outside of the modal, close it
							    window.onclick = function(event) {
							        if (event.target == modal) {
							        	window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';
							            modal.style.display = 'none';
							        }
							    }
	    					</script>
	    				";
	    			}
	    			elseif($rowCount == 0){
	    				$sqlAddViolation = "INSERT INTO reporttype (name, description, flag)values(:vioNameAdd, :vioDescAdd, 1)";

						$stmt = $con->prepare($sqlAddViolation);
						$stmt->bindParam(':vioNameAdd', $vioNameAdd, PDO::PARAM_STR);
						$stmt->bindParam(':vioDescAdd', $vioDescAdd, PDO::PARAM_STR);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';</script>";
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
					if(isset($_GET['violationID'])){
						$sqlReport = "SELECT reportTypeID, name, description, flag FROM reportType WHERE reportTypeID = (:violationID)";

						$stmt = $con->prepare($sqlReport);
						$stmt->bindParam(':violationID', $_GET['violationID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowQuestionEdit = $stmt->fetch();

						$reportTypeIDEdit = $rowQuestionEdit["reportTypeID"];
			    		$reportNameEdit = $rowQuestionEdit["name"];
			    		$reportDescriptionEdit = $rowQuestionEdit["description"];
			    	}
				?>
				<div class = "details">
					<div class = "titleDetails"><b>Edit Violations</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Violation Name</td>
						    <td class = "tdInput"><input type="text" name="vioNameEdit" placeholder="Enter violation name here..." value = "<?php echo $reportNameEdit; ?>" maxlength = "150" pattern = "[a-zA-Z0-9._%+-].{0,}" required title="Must only contain letters, numbers, and ._%+-."></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Description</td>
						    <td class = "tdInput"><textarea class = "textAreaDesc" name="vioDescEdit" placeholder="Add description here..." maxlength="100" required><?php echo $reportDescriptionEdit; ?></textarea></td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "updateVio"> UPDATE </button>
					</div>
				</div>
			</div>
		</div>
		</form>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<div class = "details">
    			<?php
    				if(isset($_GET['violationID'])){
						$sqlYesNo = "SELECT reportTypeID, name, flag FROM reporttype WHERE reportTypeID = '$_GET[violationID]'";

						$stmt = $con->prepare($sqlYesNo);
						$stmt->execute();
						$rowYesNo = $stmt->fetch();

						$modalYesNoReportID = $rowYesNo["reportTypeID"];
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
									<th> Violation Name </th>
								</tr>				
							</thead>
	    					<tbody>
			    				<tr class = "tableContentYesNo">
						        	<td><?php echo $modalYesNoReportID; ?></td>
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
						$sqlActiveModal = "UPDATE reporttype
											SET flag = 0
											WHERE reportTypeID = :modalYesNoReportID";

						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':modalYesNoReportID', $modalYesNoReportID, PDO::PARAM_INT);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';</script>";
					}
				?>
  			</div>
		</div>
	</div>

	<div id="viewModalArchived" class="viewModalArchived">
  			<div class="viewModalArchived-content">
    			<span class="viewCloseArchived">&times;</span>
    			<div class = "details">
    			<div class = "titleDetails"><b>Deleted violations</b></div>
    			<table id = userTableArchived>
    				<col width = "50">
					<col width = "220">
					<thead>
						<tr>
							<th> ID </th>
							<th> Violation Name </th>
							<th> Description </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlReport = "SELECT reportTypeID, name, description, flag FROM reportType WHERE flag = 0";

							$stmt = $con->prepare($sqlReport);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach ($results as $rowReport) {
								$reportTypeID = $rowReport["reportTypeID"];
			    				$reportName = $rowReport["name"];
			    				$reportDescription = $rowReport["description"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $reportTypeID; ?></td>
						        	<td><?php echo $reportName; ?></td>
						        	<td><?php echo $reportDescription; ?></td>
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
	</div>
		<?php
			if(isset($_POST['updateVio'])){
				$vioNameEdit = $_POST['vioNameEdit'];
				$vioDescEdit = $_POST['vioDescEdit'];

				$sqlViolationUpdateCheck = "SELECT reportTypeID, flag FROM reporttype WHERE name = (:vioNameEdit) AND reportTypeID <> (:reportTypeIDEdit)";

				$stmt = $con->prepare($sqlViolationUpdateCheck);
				$stmt->bindParam(':vioNameEdit', $vioNameEdit, PDO::PARAM_STR);
				$stmt->bindParam(':reportTypeIDEdit', $reportTypeIDEdit, PDO::PARAM_STR);
				$stmt->execute();
				$rowViolationUpdateFound = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				$reporttypeViolationIDUpdateFound = $rowViolationUpdateFound["reportTypeID"];
    			$reporttypeFlagUpdateFound = $rowViolationUpdateFound["flag"];

    			if($rowCount > 0){
    				echo "<script>alert('There is already existing violation.');localStorage.setItem('viewEditModal',true);</script>";
    			}
    			else{
    				$sqlViolation = "UPDATE reporttype
								SET name = :vioNameEdit, description = :vioDescEdit
								WHERE reportTypeID = (:reportTypeIDEdit)";

					$stmt = $con->prepare($sqlViolation);
					$stmt->bindParam(':reportTypeIDEdit', $reportTypeIDEdit, PDO::PARAM_INT);
					$stmt->bindParam(':vioNameEdit', $vioNameEdit, PDO::PARAM_STR);
					$stmt->bindParam(':vioDescEdit', $vioDescEdit, PDO::PARAM_STR);
					$stmt->execute();

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=violations&sort=".$_SESSION['link'].$optionSearch.$search."';</script>";
    			}
			}
		?>
		<script type="text/javascript">
			function viewAddModal(){
			    var modal = document.getElementById('viewAddModal');
			    var btn = document.getElementById('addViolation');
			    var span = document.getElementsByClassName("viewAddClose")[0];
			    modal.style.display = "block";
			    span.onclick = function() {
			        modal.style.display = "none";
			    }
			    window.onclick = function(event) {
			        if (event.target == modal) {
			            modal.style.display = "none";
			        }
			    }
			}

			function viewEditModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var violationID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['link'])){echo '&sort=' . $_SESSION['link'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=violations&violationID="+violationID+linkSort+search+optionSearch);
							localStorage.setItem('viewEditModal',true);
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
							var violationID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['link'])){echo '&sort=' . $_SESSION['link'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=violations&violationID="+violationID+linkSort+search+optionSearch);
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
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>

<?php
	$flag = 1;
	$sqlReport = "SELECT reportTypeID, name, description, flag FROM reportType WHERE flag = (:flag)";
	$stmt = $con->prepare($sqlReport);
	$stmt->bindParam(':flag', $flag, PDO::PARAM_INT);
	$stmt->execute();
	$results = $stmt->fetchAll();
	$rowCount = $stmt->rowCount();

	foreach($results as $rowReport){
		$reportTypeID = $rowReport["reportTypeID"];
		$reportName = $rowReport["name"];
		$reportDescription = $rowReport["description"];
	}
?>