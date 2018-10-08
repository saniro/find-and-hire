<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-notificationsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-notificationsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['notificationLink'] = "";
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
				<div class= "title"> MAINTENANCE - Notifications </div>
				<table id = actionTable>
					<col width = "620">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='addNotif' class = "sortBtn" onclick='addNotifModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
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
	      										<a href="index?route=notifications&sort=notifTypeID<?php echo $optionSearch.$search;?>">ID</a>
	      										<a href="index?route=notifications&sort=message<?php echo $optionSearch.$search;?>">Message</a>
	      										<a href="index?route=notifications&sort=type<?php echo $optionSearch.$search;?>">Type</a>
	      										<a href="index?route=notifications&sort=dateModified<?php echo $optionSearch.$search;?>">Date Modified</a>
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
										<a href = "index?route=notifications&sort=notifTypeID"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'notifTypeID'){
														echo " selected ";
													}
												}
											?>value="notifTypeID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'message'){
														echo " selected ";
													}
												}
											?>
											value="message">Message</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'type'){
														echo " selected ";
													}
												}
											?>
											value="type">Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'dateModified'){
														echo " selected ";
													}
												}
											?>
											value="dateModified">Date Modified</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "notifications">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = notificationTable>
					<col width = "50">
					<col width = "480">
					<col width = "160">
					<col width = "160">
					<thead>
						<tr>
							<th> ID </th>
							<th> Message </th>
							<th> Type </th>
							<th> Date Modified </th>
							<th colspan="2"> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlNotification = "SELECT notifTypeID, message, (SELECT CASE WHEN type =  0 THEN 'Verification' WHEN type = 1 THEN 'Report' WHEN type = 2 THEN 'Penalty' WHEN type = 3 THEN 'Top Up' END FROM notificationtype AS NE WHERE NE.notifTypeID = NT.notifTypeID) AS type, DATE_FORMAT(dateModified,'%b %d, %Y') AS dateModified, flag FROM notificationtype AS NT WHERE flag = 1";
							if(isset($_GET['optionSearch'])){
		    					$sqlNotification .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'notifTypeID'){
		    						$sqlNotification .= " ORDER BY notifTypeID";
		    						$_SESSION['notificationLink'] = "notifTypeID";
								}
								elseif ($_GET['sort'] == 'message')
								{
								    $sqlNotification .= " ORDER BY message";
								    $_SESSION['notificationLink'] = "message";
								}
								elseif ($_GET['sort'] == 'type')
								{
								    $sqlNotification .= " ORDER BY type";
								    $_SESSION['notificationLink'] = "type";
								}
								elseif ($_GET['sort'] == 'dateModified')
								{
								    $sqlNotification .= " ORDER BY dateModified";
								    $_SESSION['notificationLink'] = "dateModified";
								}
							}

							$stmt = $con->prepare($sqlNotification);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowNotification){
								$notificationTypeID = $rowNotification["notifTypeID"];
			    				$notificationMessage = $rowNotification["message"];
			    				$notificationType = $rowNotification["type"];
			    				$notificationDateModified = $rowNotification["dateModified"];
			    				$notificationFlag = $rowNotification["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $notificationTypeID; ?></td>
						        	<td><?php echo $notificationMessage; ?></td>
						        	<td><?php echo $notificationType; ?></td>
						        	<td><?php echo $notificationDateModified; ?></td>
						        	<td><button id='edit' class = 'edit' onclick='viewEditModal()'>Edit</button></td>
						        	<td><input type = 'submit' class = 'delete' onclick = 'viewYesNo()' value = 'Delete'></td>
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
		<form method="post">
		<div id="viewAddModal" class="viewAddModal">
			<div class="viewAddModal-content">
				<span class="viewAddClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Add Notification</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Message</td>
						    <td class = "tdInput"><textarea class = "textAreaDesc" name="notifMessage" placeholder="Add message here..." maxlength="150" required></textarea></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Message Type</td>
						    <td class = "tdInput">
						    	<select class = "dropdownType" name = "notifMessageType">
									<option value="0">Verification</option>
									<option value="1">Report</option>
									<option value="2">Penalty</option>
									<option value="3">Top Up</option>
								</select>
							</td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "addNotif"> ADD </button>
					</div>
				</div>
			</div>
			<?php
				if(isset($_POST['addNotif'])){
					$notifMessageAdd = $_POST['notifMessage'];
					$notifMessageType = $_POST['notifMessageType'];
					$dateToday = date('Y-m-d');

					$sqlNotifAddCheck = "SELECT notifTypeID, flag FROM notificationtype WHERE message = (:notifMessageAdd) AND type = (:notifMessageType)";
					$stmt = $con->prepare($sqlNotifAddCheck);
					$stmt->bindParam(':notifMessageAdd', $notifMessageAdd, PDO::PARAM_STR);
					$stmt->bindParam(':notifMessageType', $notifMessageType, PDO::PARAM_STR);
					$stmt->execute();
					$rowNotifAddCheck = $stmt->fetch();
					$rowCount = $stmt->rowCount();

					$notificationNotifIDFound = $rowNotifAddCheck["notifTypeID"];
	    			$notificationFlagFound = $rowNotifAddCheck["flag"];
	    			if(($rowCount >= 1)&&($notificationFlagFound == 0)){
	    				$sqlNotificationFoundUpdate = "UPDATE notificationtype
													SET flag = 1
													WHERE notifTypeID = :notificationNotifIDFound";

						$stmt = $con->prepare($sqlNotificationFoundUpdate);
						$stmt->bindParam(':notificationNotifIDFound', $notificationNotifIDFound, PDO::PARAM_INT);
						$stmt->execute();
						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
	    			}
	    			elseif(($rowCount > 0) && ($notificationFlagFound == 1)){
	    				echo "<script>
	    					alert('There is already existing notification.');
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
						        window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';
						    }

						    // When the user clicks anywhere outside of the modal, close it
						    window.onclick = function(event) {
						        if (event.target == modal) {
						            viewAddModal.style.display = 'none';
						            window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';
						        }
						    }
	    				</script>";
	    			}
	    			elseif($rowCount == 0){
	    				$sqlAddNotif = "INSERT INTO notificationtype (type, message, dateModified, flag)values(:notifMessageType, :notifMessageAdd, NOW(), 1)";

						$stmt = $con->prepare($sqlAddNotif);
						$stmt->bindParam(':notifMessageType', $notifMessageType, PDO::PARAM_INT);
						$stmt->bindParam(':notifMessageAdd', $notifMessageAdd, PDO::PARAM_STR);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
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
					if(isset($_GET['notifTypeID'])){
						$sqlNotifEdit = "SELECT notifTypeID, type, message, dateModified, flag FROM notificationtype WHERE flag = 1 AND notifTypeID = (:notifTypeID)";

						$stmt = $con->prepare($sqlNotifEdit);
						$stmt->bindParam(':notifTypeID', $_GET['notifTypeID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowNotifEdit = $stmt->fetch();

						$notifTypeIDEdit = $rowNotifEdit["notifTypeID"];
	    				$notifTypeEdit = $rowNotifEdit["type"];
	    				$notifMessageEdit = $rowNotifEdit["message"];

		    		}
				?>
				<div class = "details">
					<div class = "titleDetails"><b>Edit Notification</b></div>
					<table class = "tableInputs">
    					<col width="170">
						<tr class = "trInputs">
						    <td class = "tdName">Message</td>
						    <td class = "tdInput"><textarea class = "textAreaDesc" name="notifMessageEdit" placeholder="Add message here..." maxlength="150" required><?php echo $notifMessageEdit; ?></textarea></td>
						</tr>
						<tr class = "trInputs">
						    <td class = "tdName">Message Type</td>
						    <td class = "tdInput">
						    	<select class = "dropdownType" name = "notifMessageTypeEdit">
									<option
									<?php
										if($notifTypeEdit == 0){
											echo " selected ";
										}
									?>value="0">Verification</option>
									<option
									<?php
										if($notifTypeEdit == 1){
											echo " selected ";
										}
									?>
									value="1">Report</option>
									<option
									<?php
										if($notifTypeEdit == 2){
											echo " selected ";
										}
									?>
									value="2">Penalty</option>
								</select>
							</td>
						</tr>
					</table>
					<div class = buttonSubmit>
						<button type = "submit" class="addSubmit" name = "updateNotif"> UPDATE </button>
					</div>
				</div>
			</div>
		</div>
		</form>

		<div id="viewModalYesNo" class="viewModalYesNo">
  			<div class="viewModalYesno-content">
    			<span class="viewCloseYesNo">&times;</span>
    			<?php
    				if(isset($_GET['notifTypeID'])){
						$sqlNotifDelete = "SELECT notifTypeID, message FROM notificationtype WHERE notifTypeID = (:notifTypeID)";

						$stmt = $con->prepare($sqlNotifDelete);
						$stmt->bindParam(':notifTypeID', $_GET['notifTypeID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowNotifDelete = $stmt->fetch();

						$notifMessageDelete = $rowNotifDelete["message"];
						$notifTypeIDDelete = $rowNotifDelete["notifTypeID"];
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
									<th> Message </th>
								</tr>				
							</thead>
	    					<tbody>
			    				<tr class = "tableContentYesNo">
						        	<td><?php echo $notifTypeIDDelete; ?></td>
						        	<td><?php echo $notifMessageDelete; ?></td>
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
					$sqlActiveModal = "UPDATE notificationtype
										SET flag = 0
										WHERE notifTypeID = :notifTypeIDDelete";
					$stmt = $con->prepare($sqlActiveModal);
					$stmt->bindParam(':notifTypeIDDelete', $notifTypeIDDelete, PDO::PARAM_INT);
					$stmt->execute();
					echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
				}
				?>
  			</div>
		</div>
	</div>

	<div id="viewModalArchived" class="viewModalArchived">
  			<div class="viewModalArchived-content">
    			<span class="viewCloseArchived">&times;</span>
    			<div class = "details">
    			<div class = "titleDetails"><b>Deleted notifications</b></div>
    			<table id = userTableArchived>
    				<col width = "50">
					<col width = "400">
					<col width = "170">
					<thead>
						<tr>
							<th> ID </th>
							<th> Message </th>
							<th> Date Modified </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlNotificationDeleted = "SELECT notifTypeID, message, dateModified, flag FROM notificationtype WHERE flag = 0";

							$stmt = $con->prepare($sqlNotificationDeleted);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowNotificationDeleted){
								$notificationTypeIDDeleted = $rowNotificationDeleted["notifTypeID"];
			    				$notificationMessageDeleted = $rowNotificationDeleted["message"];
			    				$notificationDateModifiedDeleted = $rowNotificationDeleted["dateModified"];
			    				$notificationFlagDeleted = $rowNotificationDeleted["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $notificationTypeIDDeleted; ?></td>
						        	<td><?php echo $notificationMessageDeleted; ?></td>
						        	<td><?php echo $notificationDateModifiedDeleted; ?></td>
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
			if(isset($_POST['updateNotif'])){
				$notifMessageTypeEdit = $_POST["notifMessageTypeEdit"];
				$notifMessageEdit = $_POST["notifMessageEdit"];
				$dateToday = date('Y-m-d');

				$sqlNotificationUpdateCheck = "SELECT notifTypeID, flag FROM notificationtype WHERE message = (:notifMessageEdit) AND notifTypeID <> (:notifTypeID)";

				$stmt = $con->prepare($sqlNotificationUpdateCheck);
				$stmt->bindParam(':notifTypeID', $notifTypeIDEdit, PDO::PARAM_INT);
				$stmt->bindParam(':notifMessageEdit', $notifMessageEdit, PDO::PARAM_STR);
				
				$stmt->execute();
				$rowNotifUpdateFound = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				$notificationNotifIDUpdateFound = $rowNotifUpdateFound["notifTypeID"];
    			$notificationFlagUpdateFound = $rowNotifUpdateFound["flag"];

    			if($rowCount > 0){
    				echo "<script>alert('There is already existing notification.');localStorage.setItem('viewEditModal',true);</script>";
    			}
    			else{
    				$sqlNotifUpdate = "UPDATE notificationtype
								SET type = (:notifMessageTypeEdit), message = (:notifMessageEdit), dateModified = NOW()
								WHERE notifTypeID = (:notifTypeIDEdit)";
					$stmt = $con->prepare($sqlNotifUpdate);
					$stmt->bindParam(':notifMessageTypeEdit', $_POST["notifMessageTypeEdit"], PDO::PARAM_INT);
					$stmt->bindParam(':notifMessageEdit', $_POST["notifMessageEdit"], PDO::PARAM_STR);
					$stmt->bindParam(':notifTypeIDEdit', $notifTypeIDEdit, PDO::PARAM_INT);
					$stmt->execute();

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
    			}
			}
		?>
		<script type="text/javascript">
			function addNotifModal(){
			    // Get the modal
			    var modal = document.getElementById('viewAddModal');

			    // Get the button that opens the modal
			    var btn = document.getElementById('addNotif');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName("viewAddClose")[0];

			    // When the user clicks the button, open the modal 
			    viewAddModal.style.display = "block";
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			        viewAddModal.style.display = "none";
			    }

			    // When the user clicks anywhere outside of the modal, close it
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
							var notifTypeID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['notificationLink'])){echo '&sort=' . $_SESSION['notificationLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=notifications&notifTypeID="+notifTypeID+linkSort+search+optionSearch);
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
							var notifTypeID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['notificationLink'])){echo '&sort=' . $_SESSION['notificationLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=notifications&notifTypeID="+notifTypeID+linkSort+search+optionSearch);
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