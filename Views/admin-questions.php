<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-questionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-questionsModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['questionsLink'] = "";
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
				<div class= "title"> MAINTENANCE - Questions </div>
				<table id = actionTable>
					<col width = "620">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div id='addQuestion' class = "sortBtn" onclick='addQuestionModal()'><img class = "iconAction" src="Resources/addIcon.png">ADD</div>
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
	      										<a href="index?route=questions&sort=questionID<?php echo $optionSearch.$search;?>">ID</a>
	      										<a href="index?route=questions&sort=question<?php echo $optionSearch.$search;?>">Questions</a>
	      										<a href="index?route=questions&sort=dateModified<?php echo $optionSearch.$search;?>">Date Modified</a>
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
										<a href = "index?route=questions"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'questionID'){
														echo " selected ";
													}
												}
											?>value="questionID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'question'){
														echo " selected ";
													}
												}
											?>
											value="question">Question</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'dateModified'){
														echo " selected ";
													}
												}
											?>
											value="dateModified">Date Modified</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value = "questions">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = questionTable>
					<col width = "50">
					<col width = "570">
					<col width = "230">
					<thead>
						<tr>
							<th> ID </th>
							<th> Question </th>
							<th> Date Modified </th>
							<th colspan="2"> Action </th>
						</tr>				
					</thead>
					<tbody>
						<?php
							$sqlQuestion = "SELECT questionID, question, DATE_FORMAT(dateModified, '%b %d, %Y %r') AS dateModified, flag FROM question WHERE flag = 1";
							if(isset($_GET['optionSearch'])){
								//echo "<script type='text/javascript'>alert('" . $_GET['sort'] . "')</script>";
		    					$sqlQuestion .= " AND " . $_GET['optionSearch'] . " LIKE '%".$_GET['search']."%'";
							}

							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'questionID'){
		    						$sqlQuestion .= " ORDER BY questionID";
		    						$_SESSION['questionsLink'] = "questionID";
								}
								elseif ($_GET['sort'] == 'question')
								{
								    $sqlQuestion .= " ORDER BY question";
								    $_SESSION['questionsLink'] = "question";
								}
								elseif ($_GET['sort'] == 'dateModified')
								{
								    $sqlQuestion .= " ORDER BY dateModified";
								    $_SESSION['questionsLink'] = "dateModified";
								}
							}

							$stmt = $con->prepare($sqlQuestion);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowQuestion){
								$questionQuestionID = $rowQuestion["questionID"];
			    				$questionQuestion = $rowQuestion["question"];
			    				$questionDateModified = $rowQuestion["dateModified"];
			    				$questionFlag = $rowQuestion["flag"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $questionQuestionID; ?></td>
						        	<td><?php echo $questionQuestion; ?></td>
						        	<td><?php echo $questionDateModified; ?></td>
						        	<td><button id='edit' class = 'edit' onclick='viewEditModal()'>Edit</button></td>
						        	<td><input type = 'submit' class = 'delete' onclick = 'viewYesNo()' value = 'Delete'></td>
					       		</tr>
							<?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan =	4> No results. </td>";
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
						<div class = "titleDetails"><b>Add Question</b></div>
						<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Question</td>
							    <td class = "tdInput"><textarea class = "textAreaDesc" name="queDesc" placeholder="Add question here..." maxlength="200" required></textarea></td>
							</tr>
						</table>
						<div class = buttonSubmit>
  							<button type = "submit" class="addSubmit" name = "addQuestion"> ADD </button>
  						</div>
					</div>
				</div>
				<?php
					if(isset($_POST['addQuestion'])){
						$questionAdd = $_POST['queDesc'];
						$dateToday = date('Y-m-d h:i:s A');

						$sqlQuestionAddCheck = "SELECT questionID, flag FROM question WHERE question = (:questionAdd)";
						$stmt = $con->prepare($sqlQuestionAddCheck);
						$stmt->bindParam(':questionAdd', $questionAdd, PDO::PARAM_STR);
						$stmt->execute();
						$rowQuestionFound = $stmt->fetch();
						$rowCount = $stmt->rowCount();

						$questionQuestionIDFound = $rowQuestionFound["questionID"];
		    			$questionFlagFound = $rowQuestionFound["flag"];

		    			if(($rowCount >= 1)&&($questionFlagFound == 0)){
		    				$sqlQuestionFoundUpdate = "UPDATE question
													SET flag = 1
													WHERE questionID = :questionQuestionIDFound";

							$stmt = $con->prepare($sqlQuestionFoundUpdate);
							$stmt->bindParam(':questionQuestionIDFound', $questionQuestionIDFound, PDO::PARAM_INT);
							$stmt->execute();

							echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';</script>";
		    			}
		    			elseif(($rowCount > 0) && ($questionFlagFound == 1)){
		    				echo "
		    					<script>
		    						alert('There is already existing question.');
		    						// Get the modal
								    var modal = document.getElementById('viewAddModal');

								    // Get the button that opens the modal
								    var btn = document.getElementById('addQuestion');

								    // Get the <span> element that closes the modal
								    var span = document.getElementsByClassName('viewAddClose')[0];

								    // When the user clicks the button, open the modal 
								    modal.style.display = 'block';
								    // When the user clicks on <span> (x), close the modal
								    span.onclick = function() {
								    	window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';
								        modal.style.display = 'none';
								    }

								    // When the user clicks anywhere outside of the modal, close it
								    window.onclick = function(event) {
								        if (event.target == modal) {
								        	window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';
								            modal.style.display = 'none';
								        }
								    }
		    					</script>
		    				";
		    			}
		    			elseif($rowCount == 0){
		    				$sqlAddQuestion = "INSERT INTO question (question, dateModified)values(:questionAdd, NOW())";
							
							$stmt = $con->prepare($sqlAddQuestion);
							$stmt->bindParam(':questionAdd', $questionAdd, PDO::PARAM_STR);
							$stmt->execute();

							echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';</script>";
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
    					if((isset($_GET['questionID']))&&($_GET['yesno'] == 'delete')){
	    					$sqlYesNo = "SELECT questionID, question, flag FROM question WHERE questionID = (:questionID)";

	    					$stmt = $con->prepare($sqlYesNo);
							$stmt->bindParam(':questionID', $_GET['questionID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowYesNo = $stmt->fetch();

							$modalYesNoQuestionID = $rowYesNo["questionID"];
							$modalYesNoQuestion = $rowYesNo["question"];
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
										<th> Question </th>
									</tr>				
								</thead>
		    					<tbody>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoQuestionID; ?></td>
							        	<td><?php echo $modalYesNoQuestion; ?></td>
							        </tr>
								</tbody>
							</table>
							<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php
					if(isset($_POST['Yes'])&&($_GET['yesno'] == "delete")){
						$sqlActiveModal = "UPDATE question
											SET flag = 0
											WHERE questionID = :modalYesNoQuestionID";
						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':modalYesNoQuestionID', $modalYesNoQuestionID, PDO::PARAM_INT);
						$stmt->execute();

						echo "<script type='text/javascript'>alert('Record was deleted.');window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';</script>";
					}
					?>
	  			</div>
			</div>
		</div>

		<div id="viewModalArchived" class="viewModalArchived">
  			<div class="viewModalArchived-content">
    			<span class="viewCloseArchived">&times;</span>
    			<div class = "details">
	    			<div class = "titleDetails"><b>Deleted questions</b></div>
	    			<table id = userTableArchived>
	    				<col width="50">
	    				<col width="600">	
						<thead>
							<tr>
								<th> ID </th>
								<th> Question </th>
								<th> Actions </th>
							</tr>				
						</thead>
						<tbody>
							<?php
								$sqlQuestionDeleted = "SELECT questionID, question, flag FROM question WHERE flag = 0";

								$stmt = $con->prepare($sqlQuestionDeleted);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();

								foreach($results as $rowQuestionDeleted){
									$questionIDDeleted = $rowQuestionDeleted["questionID"];
				    				$questionNameDeleted = $rowQuestionDeleted["question"];
				    				?>
				    				<tr class = "tableContent">
							        	<td><?php echo $questionIDDeleted; ?></td>
							        	<td><?php echo $questionNameDeleted; ?></td>
							        	<td><button onclick = 'restore()' class="restore"> Restore </button></td>
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

		<div id="viewModalYesNoArchived" class="viewModalYesNoArchived">
  			<div class="viewModalYesnoArchived-content">
    			<span class="viewCloseYesNoArchived">&times;</span>
    			<div class = "details">
    				<?php
    					if((isset($_GET['questionID']))&&($_GET['yesno'] == 'archived')){
	    					$sqlYesNo = "SELECT questionID, question, flag FROM question WHERE questionID = (:questionID)";

	    					$stmt = $con->prepare($sqlYesNo);
							$stmt->bindParam(':questionID', $_GET['questionID'], PDO::PARAM_INT);
							$stmt->execute();
							$rowYesNo = $stmt->fetch();

							$modalYesNoQuestionID = $rowYesNo["questionID"];
							$modalYesNoQuestion = $rowYesNo["question"];
				    		$modalYesNoFlag = $rowYesNo["flag"];

						}
    				?>
	    			<div class = "titleDetails"><b>Are you sure to restore?</b></div>
	    			<div class = "profilePicDivision">
	    				<form method="post">
    						<table class = "tableYesNo">
	    						<col width = "120">
		    					<thead class = "tableYesNoHead">
									<tr>
										<th> Question No. </th>
										<th> Question </th>
									</tr>				
								</thead>
		    					<tbody>
				    				<tr class = "tableContentYesNo">
							        	<td><?php echo $modalYesNoQuestionID; ?></td>
							        	<td><?php echo $modalYesNoQuestion; ?></td>
							        </tr>
								</tbody>
							</table>
							<div class = "YesNo">
	    						<button name="Yes" class = "yesButton"> YES </button><button name="No" class = "noButton"> NO </button>
	    					</div>
	    				</form>
					</div>
					<?php
					if(isset($_POST['Yes'])&&($_GET['yesno'] == "archived")){
						$sqlActiveModal = "UPDATE question
											SET flag = 1, dateModified = NOW()
											WHERE questionID = :modalYesNoQuestionID";
						$stmt = $con->prepare($sqlActiveModal);
						$stmt->bindParam(':modalYesNoQuestionID', $modalYesNoQuestionID, PDO::PARAM_INT);
						$stmt->execute();
						?>
						<script type='text/javascript'>
							alert('Record was restored.');
							var linkSort = '<?php if(isset($_SESSION['questionsLink'])){echo '&sort=' . $_SESSION['questionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							var yesno = '<?php echo '&yesno=delete';?>'
							location.replace('index?route=questions'+linkSort+search+optionSearch+yesno);
							localStorage.setItem('viewModalArchived',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						</script>
						<?php
					}
					?>
	  			</div>
			</div>
		</div>

		<form method="post">
			<div id="viewEditModal" class="viewEditModal">
				<div class="viewEditModal-content">
					<span class="viewEditClose">&times;</span>
					<?php
						$sqlQuestionEdit = "SELECT questionID, question FROM question WHERE questionID = (:questionID)";

						$stmt = $con->prepare($sqlQuestionEdit);
						$stmt->bindParam(':questionID', $_GET['questionID'], PDO::PARAM_INT);
						$stmt->execute();
						$rowQuestionEdit = $stmt->fetch();

						$questionIDEdit = $rowQuestionEdit["questionID"];
			    		$questionQuestionEdit = $rowQuestionEdit["question"];
					?>
					<div class = "details">
						<div class = "titleDetails"><b>Edit Question</b></div>
						<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Question</td>
							    <td class = "tdInput"><textarea class = "textAreaDesc" name="queDescUpdate" placeholder="Add question here..." maxlength="200" required><?php echo $questionQuestionEdit; ?></textarea></td>
							</tr>
						</table>
						<div class = buttonSubmit>
  							<button type = "submit" class="addSubmit" name = "updateQuestion"> UPDATE </button>
  						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
			if(isset($_POST['updateQuestion'])){
				$queDescUpdate = $_POST['queDescUpdate'];
				$dateToday = date('Y-m-d h:i:s A');

				$sqlQuestionUpdateCheck = "SELECT questionID, flag FROM question WHERE question = (:queDescUpdate) AND questionID <> (:questionIDEdit)";

				$stmt = $con->prepare($sqlQuestionUpdateCheck);
				$stmt->bindParam(':questionIDEdit', $questionIDEdit, PDO::PARAM_STR);
				$stmt->bindParam(':queDescUpdate', $queDescUpdate, PDO::PARAM_STR);
				
				$stmt->execute();
				$rowQuestionUpdateFound = $stmt->fetch();
				$rowCount = $stmt->rowCount();

				$questionQuestionIDUpdateFound = $rowQuestionUpdateFound["questionID"];
    			$questionFlagUpdateFound = $rowQuestionUpdateFound["flag"];

    			if($rowCount > 0){
    				echo "<script>alert('There is already existing question.');localStorage.setItem('viewEditModal',true);</script>";
    			}
    			else{
    				$sqlQuestion = "UPDATE question
								SET question = (:queDescUpdate), dateModified = (NOW())
								WHERE questionID = '$questionIDEdit'";

					$stmt = $con->prepare($sqlQuestion);
					$stmt->bindParam(':queDescUpdate', $queDescUpdate, PDO::PARAM_STR);
					$stmt->execute();

					echo "<script type='text/javascript'>alert('Updated successfully.');window.location.href='index?route=questions&sort=".$_SESSION['questionsLink'].$optionSearch.$search."';</script>";
    			}
			}
		?>

		<script type = 'text/javascript'>
			function addQuestionModal(){
			    // Get the modal
			    var modal = document.getElementById('viewAddModal');

			    // Get the button that opens the modal
			    var btn = document.getElementById('addQuestion');

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

			function viewEditModal(){
				var table = document.getElementById('questionTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var questionID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['questionsLink'])){echo '&sort=' . $_SESSION['questionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							location.replace("index?route=questions&questionID="+questionID+linkSort+search+optionSearch);
							localStorage.setItem('viewEditModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function restore(){
				var table = document.getElementById('userTableArchived');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var questionID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['questionsLink'])){echo '&sort=' . $_SESSION['questionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							var yesno = '<?php echo '&yesno=archived';?>'
							location.replace("index?route=questions&questionID="+questionID+linkSort+search+optionSearch+yesno);
							localStorage.setItem('viewModalYesNoArchived',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function viewYesNo(){
				var table = document.getElementById('questionTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var questionID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['questionsLink'])){echo '&sort=' . $_SESSION['questionsLink'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
							var yesno = '<?php echo '&yesno=delete';?>'
							location.replace("index?route=questions&questionID="+questionID+linkSort+search+optionSearch+yesno);
							localStorage.setItem('viewModalYesNo',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);
						}						
					}
				}
			}

			function viewArchived(){
					var linkSort = '<?php if(isset($_SESSION['questionsLink'])){echo '&sort=' . $_SESSION['questionsLink'];}?>'
					var search = '<?php if(isset($_GET['search'])){echo '&search='. $_GET['search'];}?>'
					var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch='. $_GET['optionSearch'];}?>'
					var yesno = '<?php echo '&yesno=delete';?>'
					location.replace("index?route=questions"+linkSort+search+optionSearch+yesno);
					localStorage.setItem('viewModalArchived',true);
					var top = window.scrollY;
					localStorage.setItem('y',top);	
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

				var x = localStorage.getItem('viewModalYesNoArchived');
				if (x == 'true'){
					document.getElementById('viewModalYesNoArchived').style.display = "block";
				}
				localStorage.setItem('viewModalYesNoArchived',false)

				var x = localStorage.getItem('viewModalArchived');
				if (x == 'true'){
					document.getElementById('viewModalArchived').style.display = "block";
				}
				localStorage.setItem('viewModalArchived',false)
			}

			var editClose = document.getElementsByClassName("viewEditClose")[0];
			editClose.onclick = function() {
				document.getElementById('viewEditModal').style.display = "none";
			}

			var spanYesNo = document.getElementsByClassName("viewCloseYesNo")[0];
			spanYesNo.onclick = function() {
				document.getElementById('viewModalYesNo').style.display = "none";
			}

			var spanArchived = document.getElementsByClassName("viewCloseArchived")[0];
			spanArchived.onclick = function() {
				document.getElementById('viewModalArchived').style.display = "none";
			}

			var spanYesNoArchived = document.getElementsByClassName("viewCloseYesNoArchived")[0];
			spanYesNoArchived.onclick = function() {
				document.getElementById('viewModalYesNoArchived').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModalArchived')) {
		            document.getElementById('viewModalArchived').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewEditModal')) {
		            document.getElementById('viewEditModal').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNo')) {
		            document.getElementById('viewModalYesNo').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalYesNoArchived')) {
		            document.getElementById('viewModalYesNoArchived').style.display = "none";
		        }
		        if (event.target == document.getElementById('viewModalArchived')) {
		            document.getElementById('viewModalArchived').style.display = "none";
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