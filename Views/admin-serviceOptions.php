<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-serviceOptionsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-serviceOptionsModalStyles.css">

		<script type="text/javascript">
		var divID = 0;
		var inputID = 0;
		function addRow() {
    		var div = document.createElement('div');
    		div.className = 'rowForm';
    		div.id = 'form' + divID;

    		//content
    		var meas = 'meas'+ divID;
    		var name = 'input'+ divID;
    		var unit = 'unit'+ divID;
    		var amountName = 'amount'+divID;

		    div.innerHTML =
		        '<table>\
		        <col width	= 100>\
		        <col width = 800>\
		        <tbody>\
		        <tr>\
		        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "remove'+divID+'" onclick = "deleteThisRow('+divID+')"> &times</span></td>\
		        <tr>\
		        <tr>\
		        <td class = "tdName">Title : </td>\
		        <td class = "tdInput"><input type="text" name = "title'+divID+'" maxlength="100" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        </tr>\
		        <input name="counter'+divID+'" id="counter'+divID+'" type = "hidden" value = "1" readonly>\
		        <tr>\
		        <td class = "tdName">Component: </td>\
		        <td class = "tdInput">\
		        	<select id = "type'+divID+'" onchange = "selectChange('+divID+')" class = "dropdownType" name = "type'+divID+'">\
			        	<option value = "Dropdown">Drodown</option>\
				        <option value = "Checkbox">Checkbox</option>\
				        <option value = "RadioButton">Radio Button</option>\
			        </select>\
		        </td>\
		        </tr>\
		        <tr>\
		        <table>\
		        <col width = 40>\
		        <col width = 600>\
		        <tbody id = content'+ divID +'>\
		        <tr>\
		        	<th class = "tdName">Unit</th>\
		        	<th class = "tdName">Options</th>\
		        	<th class = "tdName">Metric</th>\
		        	<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input class = "check'+divID+'" type="checkbox" id = "'+meas+'-0" name="'+meas+'-0" onclick = "check(\''+divID+'\', \'0\')" disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="'+name+'-0" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        	<td class = "tdInput"><input type="text" id = "'+unit+'-0" name="'+unit+'-0" maxlength="10" pattern = "[a-zA-Z0-9._%+-].{0,}" required disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="'+amountName+'-0" maxlength="11" pattern = "[0-9].{0,}" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        <input type="button" class = "view" name="name" value="+" onclick = "addInput('+divID+');" />\
		        <input type="button" class = "delete" name="name" value="-" onclick = "minusInput('+divID+')" />\
		        </tr>\
		        </tbody>\
		        </table>\
		        ';
    		document.getElementById('content').appendChild(div);
    		divID++;
    		document.getElementById('rowCounter').value++;
		}

		function addMeasurement() {
    		var div = document.createElement('div');
    		div.className = 'rowForm';
    		div.id = 'form' + divID;

    		//content
    		var name = 'input'+ divID;
    		var amountName = 'amount'+divID;

    		div.innerHTML =
		        '<table>\
		        <col width	= 100>\
		        <col width = 800>\
		        <tbody>\
		        <tr>\
		        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "remove'+divID+'" onclick = "deleteMeasurement('+divID+')"> &times</span></td>\
		        </tr>\
		        <tr>\
		        <td class = "tdName">Title : </td>\
		        <td class = "tdInput"><input type="text" name = "title'+divID+'" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        </tr>\
		        <input name="counter'+divID+'" id="counter'+divID+'" type = "hidden" value = "1" readonly>\
		        <tr>\
		        <input type = "hidden" name = "type'+divID+'" value = "Textbox">\
		        </tr>\
		        <tr>\
		        <table>\
		        <col width = 800>\
		        <tbody id = content'+ divID +'>\
		        <tr>\
		        	<th class = "tdName">Metric</th>\
		       		<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input type="text" name="'+name+'-0" maxlength="10" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        	<td class = "tdInput"><input type="text" name="'+amountName+'-0" maxlength="11" pattern = "[0-9].{0,}" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        </tr>\
		        </tbody>\
		        </table>\
		        ';

    		document.getElementById('content').appendChild(div);
    		divID++;
    		document.getElementById('rowCounter').value++;
    		document.getElementById('measurement').disabled = true;
    		document.getElementById('measurement').style.backgroundColor = 'rgba(255, 101, 1, .5)';
    		document.getElementById('measurement').style.cursor = 'default';
		}

		function addInput(id){
			var div = document.getElementById('content'+id);
			var tr = document.createElement("tr");
			var tdMeas = document.createElement("td");
			var tdOption = document.createElement("td");
			var tdUnit = document.createElement("td");
			var tdAmount = document.createElement("td");
			tdMeas.className = 'tdInput';
			tdOption.className = 'tdInput';
			tdUnit.className = 'tdInput';
			tdAmount.className = 'tdInput';

			var meas = document.createElement('input');
			meas.setAttribute("class", "check"+id);
			meas.setAttribute("type", "checkbox");
			meas.setAttribute("id",  "meas"+id+"-"+document.getElementById('counter'+id).value);
			meas.setAttribute("name", "meas"+id+"-"+document.getElementById('counter'+id).value);
			meas.setAttribute("onclick", "check('"+id+"', '"+document.getElementById('counter'+id).value+"')");
			if(document.getElementById('type'+id).value == "Dropdown"){
				meas.setAttribute("disabled", "");
			}

			var option = document.createElement('input');
			option.setAttribute("type", "text");
			option.setAttribute("name","input"+id+"-"+document.getElementById('counter'+id).value);
			option.setAttribute("maxlength", "50");
			option.setAttribute("pattern", "[a-zA-Z0-9._%+-].{0,}");
			option.setAttribute("required", "");

			var unit = document.createElement('input');
			unit.setAttribute("type", "text");
			unit.setAttribute("id", "unit"+id+"-"+document.getElementById('counter'+id).value);
			unit.setAttribute("name", "unit"+id+"-"+document.getElementById('counter'+id).value);
			unit.setAttribute("maxlength", "10");
			unit.setAttribute("pattern", "[a-zA-Z0-9._%+-].{0,}");
			unit.setAttribute("required", "");
			unit.setAttribute("disabled", "");

			var amount = document.createElement('input');
			amount.setAttribute("type", "text");
			amount.setAttribute("name","amount"+id+"-"+document.getElementById('counter'+id).value);
			amount.setAttribute("maxlength", "11");
			amount.setAttribute("pattern", "[0-9].{0,}");
			amount.setAttribute("required", "");

			tdMeas.appendChild(meas);
			tdOption.appendChild(option);
			tdUnit.appendChild(unit);
			tdAmount.appendChild(amount);

			tr.appendChild(tdMeas);
			tr.appendChild(tdOption);
			tr.appendChild(tdUnit);
			tr.appendChild(tdAmount);

			div.appendChild(tr);
			document.getElementById('counter'+id).value++;
		}

		function minusInput(id){
			var list = document.getElementById('content' + id);
		    if(document.getElementById('counter'+id).value > 1){
		    	document.getElementById('counter'+id).value--;
		    	list.removeChild(list.lastChild);
		    }
		}

		function validateMyForm(){
			if (document.getElementById('rowCounter').value < 1) {
				alert("There is nothing to create.");
				return false;
			}
			else{
				return true;
			}
		}

		function changeAmount(id){
			var amount = document.getElementById('select'+id).value;
			document.getElementById('amount'+id).value = amount;
		}

		function measurementAmount(id){
			var kg = document.getElementById('kg'+id).value;
			var amountTimes	= document.getElementById('amountTimes'+id).value;
			var answer = kg * amountTimes;
			document.getElementById('measurementAmount'+id).value = answer;
		}

		function deleteThisRow(id){
			document.getElementById('form'+id).remove();
		}

		function deleteMeasurement(id) {
			document.getElementById('form'+id).remove();
			document.getElementById('measurement').disabled = false;
			document.getElementById('measurement').style.backgroundColor = 'rgb(255, 101, 1)';
    		document.getElementById('measurement').style.cursor = 'pointer';
		}

		function createNewForm(){
		    var modal = document.getElementById('viewAddModal');
		    var btn = document.getElementById('addOptions');
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

		function check(divID, counter){
			var check = document.getElementById('meas'+divID+'-'+counter).checked;
			if(check == true){
				document.getElementById('unit'+divID+'-'+counter).disabled = false;
			}
			else if(check == false){
				document.getElementById('unit'+divID+'-'+counter).value = "";
				document.getElementById('unit'+divID+'-'+counter).disabled = true;
			}
		}

		function selectChange(id){
			var select = document.getElementById('type'+id).value;
			if(select == "Dropdown"){
				var cells = document.getElementsByClassName('check'+id);
				for (var i = 0; i < cells.length; i++) {
					cells[i].disabled = true;
				}
			}
			else{
				var cells = document.getElementsByClassName('check'+id);
				for (var i = 0; i < cells.length; i++) {
					cells[i].disabled = false;
				}
			}
		}
	</script>
	</head>
	<body>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['serviceOptionLink'] = "";
			}

			if(isset($_GET['serviceID'])){
				$_SESSION['serviceIDSet'] = $_GET['serviceID'];

			}

			if(isset($_SESSION['serviceIDSet'])){
				$serviceIDSet = "&serviceID=" . $_SESSION['serviceIDSet'];
			}

			$sqlServiceName = "SELECT name FROM services WHERE flag = 1 AND serviceID = (:serviceIDSet)";

			$stmt = $con->prepare($sqlServiceName);
			$stmt->bindParam(':serviceIDSet', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
			$stmt->execute();
			$rowServiceName = $stmt->fetch();

			$serviceIDName = $rowServiceName["name"];
		?>
		<div class = "sideNavigation">
			<?php
				require("admin-sidebar.php");
			?>
		</div>
		<div class = wrapper>
			<div class = "serviceOptionDesc">
				<div class = "serviceOptionsDescContents">
					<table class = "centerTable">
						<col width = "200">
						<col width = "600">
						<col width = "600">
						<tr>
							<td>
								<form method="post">
									<div class = "backBtn">
										<button type = "submit" class="backButton" name = "backButton"> Go Back </button>
									</div>
								</form>
							</td>
							<td>
								<table class = "serviceDescTable">
			    					<col width="125">
									<tr class = "trInputs">
									    <td class = "tdName">Service No</td>
									    <td class = "tdInput"><?php echo $_SESSION['serviceIDSet']; ?></td>
									</tr>
								</table>
							</td>
							<td>
								<table class = "serviceDescTable">
			    					<col width="125">
									<tr class = "trInputs">
									    <td class = "tdName">Service Name</td>
									    <td class = "tdInput"><?php echo $serviceIDName; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php
				if(isset($_POST['backButton'])){
					echo "<script type='text/javascript'>window.location.href='index?route=service';</script>";
				}
			?>
			<div class = "contents">
				<table id = actionTable>
					<col width="650">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<?php
	    											$sqlCheckForm = "SELECT ServiceID FROM form WHERE flag = 1 AND ServiceID = :serviceID";

													$stmt = $con->prepare($sqlCheckForm);
													$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
													$stmt->execute();
													$results = $stmt->fetchAll();
													$rowCount = $stmt->rowCount();

													if ($rowCount > 0) {
														?>
														<div id='addOptions' class = "createForm" onclick="viewEditForm()"><img class = "iconAction" src="Resources/addIcon.png">EDIT FORM</div>
														<?php
													}
													else{
														?>
													 	<div id='addOptions' class = "createForm" onclick="createNewForm()"><img class = "iconAction" src="Resources/addIcon.png">CREATE FORM</div>
													 	<?php
													}
	    										?>
	    									</a>
	  									</li>
									</ul>
								</div>
							</th>
						</tr>
					</thead>
				</table>
				<div class = "viewForm">
					<?php
						$sqlViewForm = "SELECT FormID, Title, Component FROM form WHERE flag = 1 AND ServiceID = :serviceID";
						$stmt = $con->prepare($sqlViewForm);
						$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$rowCount = $stmt->rowCount();
						//echo "<script>alert('".$rowCount."');</script>";
						if($rowCount > 0){
							foreach ($results as $rowViewForm) {
								echo "<div class = 'rowForm'>";
								echo "<table>";
								echo "<col width = 120>";
								echo "<col width = 520>";
								echo "<tbody>";
								echo "<tr>";
								$viewFormID = $rowViewForm['FormID'];
								$viewFormTitle = $rowViewForm['Title'];
								$viewFormComponent = $rowViewForm['Component'];
								echo "<td class = 'tdName'>Title</td><td class = 'tdInput'>".$viewFormTitle."</td>";
								echo "</tr>";
								echo "</tbody>";
								echo "</table>";
								echo "<table>";
								echo "<col width = 425>";
								echo "<col width = 120>";
								echo "<col width = 80>";
								echo "<thead>";
								echo "<tr><th class = 'tdName'>Options</th><th class = 'tdName'>Measurement</th><th class = 'tdName'>Amount</th></thead>";
								echo "<tbody>";
								$sqlViewFormChoices = "SELECT Description, unit, Amount FROM formchoices WHERE FormID = :viewFormID";
								$stmt = $con->prepare($sqlViewFormChoices);
								$stmt->bindParam(':viewFormID', $viewFormID);
								$stmt->execute();
								$results = $stmt->fetchAll();
								if($viewFormComponent == 'Checkbox'){
									$x = 0;
									foreach ($results as $rowViewFormChoices) {
										echo "<tr>";
										$viewFormChoicesDescription = $rowViewFormChoices['Description'];
										$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
										$viewFormChoicesUnit = $rowViewFormChoices['unit'];
										if($viewFormChoicesUnit != ""){
											echo "<td class = 'tdInput'><input type='checkbox' name = ".$viewFormID.$x.">".$viewFormChoicesDescription."</td><td class = 'tdInput measurement'><input type = 'text' />&nbsp".$viewFormChoicesUnit."</td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
										}
										else{
											echo "<td class = 'tdInput'><input type='checkbox' name = ".$viewFormID.$x.">".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' disabled/></td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
										}
										echo "</tr>";
										$x++;
									}
									$x=0;
								}
								elseif($viewFormComponent == 'RadioButton'){
									$x=0;
									foreach ($results as $rowViewFormChoices) {
										echo "<tr>";
										$viewFormChoicesDescription = $rowViewFormChoices['Description'];
										$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
										$viewFormChoicesUnit = $rowViewFormChoices['unit'];
										if($viewFormChoicesUnit != ""){
											echo "<td class = 'tdInput'><input id = '".$viewFormID.$x."' type='radio' name = ".$viewFormID.$x." value = ".$viewFormChoicesAmount." onchange = 'changeAmount();'>&nbsp".$viewFormChoicesDescription."</td><td class = 'tdInput measurement'><input type = 'text'>&nbsp".$viewFormChoicesUnit."</td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
										}
										else{
											echo "<td class = 'tdInput'><input id = '".$viewFormID.$x."' type='radio' name = ".$viewFormID.$x." value = ".$viewFormChoicesAmount." onchange = 'changeAmount();'>&nbsp".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' disabled></td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
										}
										echo "</tr>";
										$x++;
									}
									$x=0;
								}
								elseif($viewFormComponent == 'Dropdown'){
									$x=0;
									echo "<tr>";
									echo "<td class = 'tdInput'>";
									echo "<select id = 'select".$viewFormID."' class = 'dropdownType' onchange = 'changeAmount(".$viewFormID.")'>";
									echo "<option selected disabled> -Select Option- </option>";
									foreach ($results as $rowViewFormChoices) {
										$viewFormChoicesDescription = $rowViewFormChoices['Description'];
										$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
										echo "<option name = ".$viewFormID.$x." value = '".$viewFormChoicesAmount."'>".$viewFormChoicesDescription."</option>";
										$x++;
									}
									echo "</select>";
									echo "</td>";
									echo "<td class = 'tdInput'><input type = 'text' disabled></td><td class = 'tdInput'><input type = 'text' id = 'amount".$viewFormID."' readonly></td>";
									echo "</tr>";
									$x=0;
								}
								elseif($viewFormComponent == 'Textbox'){
									$x=0;
									foreach ($results as $rowViewFormChoices) {
										$viewFormChoicesDescription = $rowViewFormChoices['Description'];
										$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
										echo "<td colspan = 3 class = 'tdInput amount'><input type = 'text' id = 'amountTimes".$viewFormID."' value = '".$viewFormChoicesAmount."' readonly> per ".$viewFormChoicesDescription."</td>";
										$x++;
									}
									echo "<tr>";
									echo "<td class = 'tdInput amount'><input type = 'text' id = 'kg".$viewFormID."' oninput = 'measurementAmount(".$viewFormID.")'></input> ".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' disabled></td><td class = 'tdInput'><input type = 'text' id = 'measurementAmount".$viewFormID."' readonly></td>";
									$x=0;
									echo "</tr>";
								}
								echo "</tbody>";
								echo "</table>";
								echo "</div>";
							}
						}
					?>
				</div>
			</div>
		</div>
		<div id="viewAddModal" class="viewAddModal">
			<div class="viewAddModal-content">
				<span class="viewAddClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Create Form</b></div>
					<div>
						<div>
							<input class = "view" id = "add" type="button" value="ADD" onclick="addRow()"> <input class = "measurementBtn" type="button" value="MEASUREMENT" onclick="addMeasurement()" id = "measurement">
						</div>
						<form method="post" onsubmit="return validateMyForm();" autocomplete="off">
							<input type="hidden" id = "rowCounter" name="rowCounter" readonly>
							<div id="content">
							</div>
							<div class = buttonSubmit>
								<button type="submit" class="addSubmit" name="submit">CREATE</button>
	  						</div>
						</form>
					</div>			
				</div>
			</div>
		</div>
		<?php
			if (isset($_POST['submit'])) {
				$rowCounter = $_POST['rowCounter'];
				for ($i=0; $i < $rowCounter; $i++) {
					$index = $i;
					try{	
						if(isset($_POST['counter'.$index])){
							//insert to form table
							$contentCounter = $_POST['counter'.$index];
							$sqlCreateForm = "INSERT INTO form (ServiceID, Title, Component)values(:serviceID, :title, :component)";
							$stmt = $con->prepare($sqlCreateForm);
							$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
							$stmt->bindParam(':title', $_POST['title'.$index], PDO::PARAM_STR);
							$stmt->bindParam(':component',$_POST['type'.$index], PDO::PARAM_STR);
							$stmt->execute();
							$formLastInsertedID = $con->lastInsertId();
							// echo "Form # : form" . $index . "<br>";
							// echo "Type : " .  . "<br>";
							for ($x=0; $x < $contentCounter; $x++) {
								$sqlCreateFormChoices = "INSERT INTO formchoices (FormID, Description, unit, Amount) VALUES (:formLastInsertedID, :description, :unit, :amount)";
								$stmt = $con->prepare($sqlCreateFormChoices);
								$stmt->bindParam(':formLastInsertedID',$formLastInsertedID, PDO::PARAM_INT);
								
								$stmt->bindParam(':description',$_POST['input'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->bindParam(':amount',$_POST['amount'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->bindParam(':unit',$_POST['unit'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->execute();
							}
							//echo "<br>";
						}
					}
					catch (Exception $e){
					}
				}
				$rowCounter = 0;
				echo "<script>window.location.href = 'index?route=serviceOptions".$serviceIDSet."';</script>";
			}
		?>
		<div id="viewEditModal" class="viewEditModal">
			<div class="viewEditModal-content">
				<span class="viewEditClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Edit Form</b></div>
						<div>
							<div>
								<?php 
									$sqlMeasurement = "SELECT count(FormID) AS count FROM form WHERE flag = 1 AND serviceID = :serviceIDSet AND Component = 'Textbox'";
									$stmt = $con->prepare($sqlMeasurement);
									$stmt->bindParam(':serviceIDSet', $_SESSION['serviceIDSet']);
									$stmt->execute();
									$rowMeasurementCount = $stmt->fetch();
									$measurementCount = $rowMeasurementCount['count'];
								?>
								<input class = "view" id = "editadd" type="button" value="ADD" onclick="editAddRow()"> <input class = "measurementBtn" type="button" value="MEASUREMENT" onclick="editAddMeasurement()" id = "editmeasurement" <?php if($measurementCount > 0){echo "disabled";}?>>
							</div>
							<?php
								$sqlEditFormView = "SELECT FormID, Title, Component FROM form WHERE flag = 1 AND serviceID = :serviceIDSet";
								$stmt = $con->prepare($sqlEditFormView);
								$stmt->bindParam(':serviceIDSet', $_SESSION['serviceIDSet']);
								$stmt->execute();
								$results = $stmt->fetchAll();
								$rowCount = $stmt->rowCount();
								$ctr = 0;
								$true = "False";
								foreach ($results as $checkTextInput) {
									if($checkTextInput['Component'] == "Textbox"){
										$true = "True";
									}
								}
							?>
							<form method="post" onsubmit="return editValidateMyForm();" autocomplete="off">
								<input type="hidden" id = "editrowCounter" name="editrowCounter" value = "<?php echo $rowCount;?>" readonly>
								<div id="editcontent">

							<?php
								foreach ($results as $rowEditFormView){
									$editViewFormID = $rowEditFormView['FormID'];
									$editViewFormTitle = $rowEditFormView['Title'];
									$editViewFormComponent = $rowEditFormView['Component'];

									$sqlEditFormChoicesView = "SELECT Description, Amount, unit FROM formchoices WHERE FormID =:editViewFormID";
									$stmt = $con->prepare($sqlEditFormChoicesView);
									$stmt->bindParam(':editViewFormID', $editViewFormID);
									$stmt->execute();
									$resultsChoices = $stmt->fetchAll();
									$rowCountChoices = $stmt->rowCount();
									$counterChoices = 0;
									//echo "<script>alert('".$rowCount."');</script>";
									?>
									<div class = "rowForm" id = "editform<?php echo $ctr;?>">
										<table>
									        <col width	= 100>
									        <col width = 800>
									        <tbody>
										        <tr>
										        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "editremove<?php echo $ctr; ?>" onclick = "<?php if($editViewFormComponent == "Textbox"){ echo "editDeleteMeasurement(".$ctr.")"; }else{ echo "editDeleteThisRow(".$ctr.")"; } ?>"> &times</span></td>
										        <tr>
									        	<tr>
										        <td class = "tdName">Title : </td>
										        <td class = "tdInput"><input type="text" name = "edittitle<?php echo $ctr;?>" value = "<?php echo $editViewFormTitle; ?>" maxlength="100" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>
										        </tr>
										        <input name="editcounter<?php echo $ctr;?>" id="editcounter<?php echo $ctr;?>" type = "hidden" value = <?php echo $rowCountChoices;?> readonly>
										        <tr>
										        <?php if($editViewFormComponent != "Textbox"){?>
										        <td class = "tdName">Component: </td>
										        <td class = "tdInput">
										        	<select class = "dropdownType" id = "edittype<?php echo $ctr;?>" onchange = "editSelectChange('<?php echo $ctr; ?>')" name = "edittype<?php echo $ctr;?>">
											        	<option v
											        	alue = "Dropdown" <?php if($editViewFormComponent == "Dropdown"){echo "selected";}?>>Dropdown</option>
												        <option value = "Checkbox" <?php if($editViewFormComponent == "Checkbox"){echo "selected";}?>>Checkbox</option>
												        <option value = "RadioButton" <?php if($editViewFormComponent == "RadioButton"){echo "selected";}?>>Radio Button</option>
											        </select>
										        </td>
										        <?php }else{?><input type = "hidden" name = "edittype<?php echo $ctr;?>" value = "Textbox"><?php }?>
										        </tr>
										        <tr>
											        <table>
												        <col width = 425>
												        <col width = 120>
												        <col width = 80>
												        <tbody id = "editcontent<?php echo $ctr; ?>">
												        <tr>
												        	<th class = "tdName">Unit</th>
												        	<th class = "tdName">Options</th>
												        	<th class = "tdName">Metric</th>
												        	<th class = "tdName">Amount</th>
												        </tr>
												        <?php
												        foreach ($resultsChoices as $rowEditFormChoiceView) {
															$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
															$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
															$editViewFormChoicesUnit = $rowEditFormChoiceView['unit'];
												        ?>
												        <tr>
												        	<?php if($editViewFormChoicesUnit != ""){
												        		?>
													        	<td class = "tdInput"><input class = "editcheck<?php echo $ctr; ?>" type="checkbox"  id="editmeas<?php echo $ctr;?>-<?php echo $counterChoices;?>" name="editmeas<?php echo $ctr;?>-<?php echo $counterChoices;?>" onclick = "editcheck('<?php echo $ctr;?>', '<?php echo $counterChoices; ?>')" checked/></td>
												        		<?php
												        	}else{
												        		?>
												        		<td class = "tdInput"><input type="checkbox" class = "editcheck<?php echo $ctr; ?>" id="editmeas<?php echo $ctr;?>-<?php echo $counterChoices;?>" name="editmeas<?php echo $ctr;?>-<?php echo $counterChoices;?>" onclick = "editcheck('<?php echo $ctr;?>', '<?php echo $counterChoices; ?>')" <?php if($editViewFormComponent == "Dropdown"){echo "disabled"; }?>/></td>
												        		<?php
												        	}?>
												        	<td class = "tdInput"><input type="text" name="editinput<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesDescription;?>" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>
												        	<?php if($editViewFormChoicesUnit != ""){
												        		?>
												        		<td class = "tdInput"><input type="text" class="editunit<?php echo $ctr;?>" id="editunit<?php echo $ctr;?>-<?php echo $counterChoices;?>" name="editunit<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesUnit;?>" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>
												        		<?php
												        	}else{
												        		?>	
												        		<td class = "tdInput"><input type="text" class="editunit<?php echo $ctr;?>" id="editunit<?php echo $ctr;?>-<?php echo $counterChoices;?>" name="editunit<?php echo $ctr;?>-<?php echo $counterChoices;?>" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required disabled/></td>
												        		<?php  		
												        	}
												        	?>
												        	<td class = "tdInput"><input type="text" name="editamount<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesAmount;?>" maxlength="11" pattern = "[0-9].{0,}" required/></td>
												        </tr>
												        <?php $counterChoices++;}?>
												        </tbody>
											        </table>
											    <?php if($editViewFormComponent != "Textbox"){?>
										        <input type="button" class = "view" name="editname" value="+" onclick = "editAddInput(<?php echo $ctr;?>);" />
										        <input type="button" class = "delete" name="editname" value="-" onclick = "editMinusInput(<?php echo $ctr;?>);" />
										        <?php }?>
										        </tr>
										    </tbody>
									    </table>
									</div>
									<?php
									$ctr++;
								}
							?>
							</div>
							<div class = buttonSubmit>
								<button type="submit" class="addSubmit" name="editsubmit">UPDATE</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			if (isset($_POST['editsubmit'])) {
				$sqlFormDeac = "UPDATE form
								SET flag = 0
								WHERE serviceID = :serviceIDSet";
				$stmt = $con->prepare($sqlFormDeac);
				$stmt->bindParam(':serviceIDSet', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
				$stmt->execute();

				$rowCounter = $_POST['editrowCounter'];
				for ($i=0; $i < $rowCounter; $i++) {
					$index = $i;
					try{	
						if(isset($_POST['editcounter'.$index])){
							//insert to form table
							$contentCounter = $_POST['editcounter'.$index];

							$sqlCreateForm = "INSERT INTO form (ServiceID, Title, Component)values(:serviceIDSet, :title, :component)";
							$stmt = $con->prepare($sqlCreateForm);
							$stmt->bindParam(':serviceIDSet', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
							$stmt->bindParam(':title', $_POST['edittitle'.$index], PDO::PARAM_STR);
							$stmt->bindParam(':component',$_POST['edittype'.$index], PDO::PARAM_STR);
							$stmt->execute();
							$formLastInsertedID = $con->lastInsertId();
							// echo "Form # : form" . $index . "<br>";
							// echo "Type : " .  . "<br>";
							for ($x=0; $x < $contentCounter; $x++) {
								$sqlCreateFormChoices = "INSERT INTO formchoices (FormID, Description, unit, Amount) VALUES (:formLastInsertedID, :description, :unit, :amount)";
								$stmt = $con->prepare($sqlCreateFormChoices);
								$stmt->bindParam(':formLastInsertedID',$formLastInsertedID, PDO::PARAM_INT);
								$stmt->bindParam(':description',$_POST['editinput'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->bindParam(':unit',$_POST['editunit'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->bindParam(':amount',$_POST['editamount'.$index.'-'.$x], PDO::PARAM_STR);
								$stmt->execute();
							}
							//echo "<br>";
						}
					}
					catch (Exception $e){
					}
				}
				$rowCounter = 0;
				echo "<script>window.location.href = 'index?route=serviceOptions".$serviceIDSet."';</script>";
			}
		?>
	</body>
	<script type="text/javascript">
		var divID = <?php echo $ctr;?>;
		var inputID = 0;
		function editAddRow() {
			var div = document.createElement('div');
			div.className = 'rowForm';
			div.id = 'editform' + divID;

			//content
			var check = 'check'+ divID;
			var name = 'input'+ divID;
			var unit = 'unit'+ divID;
			var amountName = 'amount'+divID;

		    div.innerHTML =
		        '<table>\
		        <col width	= 100>\
		        <col width = 800>\
		        <tbody>\
		        <tr>\
		        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "editremove'+divID+'" onclick = "editDeleteThisRow('+divID+')"> &times</span></td>\
		        <tr>\
		        <tr>\
		        <td class = "tdName">Title : </td>\
		        <td class = "tdInput"><input type="text" name = "edittitle'+divID+'" maxlength="100" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        </tr>\
		        <input name="editcounter'+divID+'" id="editcounter'+divID+'" type = "hidden" value = "1" readonly>\
		        <tr>\
		        <td class = "tdName">Component: </td>\
		        <td class = "tdInput">\
		        	<select class = "dropdownType" id = "edittype'+divID+'" onchange = "editSelectChange(\''+divID+'\')" name = "edittype'+divID+'">\
			        	<option value = "Dropdown">Drodown</option>\
				        <option value = "Checkbox">Checkbox</option>\
				        <option value = "RadioButton">Radio Button</option>\
			        </select>\
		        </td>\
		        </tr>\
		        <tr>\
		        <table>\
		        <col width = 800>\
		        <tbody id = editcontent'+ divID +'>\
		        <tr>\
		        	<th class = "tdName">Unit</th>\
		        	<th class = "tdName">Options</th>\
		        	<th class = "tdName">Metric</th>\
		        	<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input type="checkbox" class = "edit'+check+'" id = "edit'+check+'-0" name="edit'+check+'-0" onclick = "editcheck(\''+divID+'\', \'0\')" required disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="edit'+name+'-0" maxlength="50" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        	<td class = "tdInput"><input type="text" class="edit'+unit+'" id="edit'+unit+'-0" name="edit'+unit+'-0" maxlength="10" pattern = "[a-zA-Z0-9._%+-].{0,}" required disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="edit'+amountName+'-0" maxlength="11" pattern = "[0-9].{0,}" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        <input type="button" class = "view" name="editname" value="+" onclick = "editAddInput('+divID+');" />\
		        <input type="button" class = "delete" name="editname" value="-" onclick = "editMinusInput('+divID+')" />\
		        </tr>\
		        </tbody>\
		        </table>\
		        ';
			document.getElementById('editcontent').appendChild(div);
			divID++;
			document.getElementById('editrowCounter').value++;
		}

		function editAddMeasurement() {
			var div = document.createElement('div');
			div.className = 'rowForm';
			div.id = 'editform' + divID;

			//content
			var name = 'input'+ divID;
			var amountName = 'amount'+divID;

			div.innerHTML =
		        '<table>\
		        <col width	= 100>\
		        <col width = 800>\
		        <tbody>\
		        <tr>\
		        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "editremove'+divID+'" onclick = "editDeleteMeasurement('+divID+')"> &times</span></td>\
		        </tr>\
		        <tr>\
		        <td class = "tdName">Title : </td>\
		        <td class = "tdInput"><input type="text" name = "edittitle'+divID+'" maxlength="100" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        </tr>\
		        <input name="editcounter'+divID+'" id="editcounter'+divID+'" type = "hidden" value = "1" readonly>\
		        <tr>\
		        <input type = "hidden" name = "edittype'+divID+'" value = "Textbox">\
		        </tr>\
		        <tr>\
		        <table>\
		        <col width = 800>\
		        <tbody id = editcontent'+ divID +'>\
		        <tr>\
		        	<th class = "tdName">Metric</th>\
		       		<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input type="text" name="edit'+name+'-0" maxlength="10" pattern = "[a-zA-Z0-9._%+-].{0,}" required/></td>\
		        	<td class = "tdInput"><input type="text" name="edit'+amountName+'-0" maxlength="11" pattern = "[0-9].{0,}" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        </tr>\
		        </tbody>\
		        </table>\
		        ';

			document.getElementById('editcontent').appendChild(div);
			divID++;
			document.getElementById('editrowCounter').value++;
			document.getElementById('editmeasurement').disabled = true;
			document.getElementById('editmeasurement').style.backgroundColor = 'rgba(255, 101, 1, .5)';
			document.getElementById('editmeasurement').style.cursor = 'default';
		}

		function editAddInput(id){
			var div = document.getElementById('editcontent'+id);
			var tr = document.createElement("tr");
			var tdMeas = document.createElement("td");
			var tdOption = document.createElement("td");
			var tdUnit = document.createElement("td");
			var tdAmount = document.createElement("td");
			tdMeas.className = 'tdInput';
			tdOption.className = 'tdInput';
			tdUnit.className = 'tdInput';
			tdAmount.className = 'tdInput';

			var meas = document.createElement('input');
			meas.setAttribute("class", "editcheck"+id);
			meas.setAttribute("type", "checkbox");
			meas.setAttribute("id",  "editmeas"+id+"-"+document.getElementById('editcounter'+id).value);
			meas.setAttribute("name", "editmeas"+id+"-"+document.getElementById('editcounter'+id).value);
			meas.setAttribute("onclick", "editcheck('"+id+"', '"+document.getElementById('editcounter'+id).value+"')");
			if(document.getElementById('edittype'+id).value == "Dropdown"){
				meas.setAttribute("disabled", "");
			}

			var option = document.createElement('input');
			option.setAttribute("type", "text");
			option.setAttribute("name","editinput"+id+"-"+document.getElementById('editcounter'+id).value);
			option.setAttribute("maxlength", "50");
			option.setAttribute("pattern", "[a-zA-Z0-9._%+-].{0,}");
			option.setAttribute("required", "");

			var metric = document.createElement('input');
			metric.setAttribute("type", "text");
			metric.setAttribute("class", "editunit"+id+"-"+document.getElementById('editcounter'+id).value);
			metric.setAttribute("id", "editunit"+id+"-"+document.getElementById('editcounter'+id).value);
			metric.setAttribute("name", "editunit"+id+"-"+document.getElementById('editcounter'+id).value);
			metric.setAttribute("maxlength", "10");
			metric.setAttribute("pattern", "[a-zA-Z0-9._%+-].{0,}");
			metric.setAttribute("required", "");
			metric.setAttribute("disabled", "");

			var amount = document.createElement('input');
			amount.setAttribute("type", "text");
			amount.setAttribute("name","editamount"+id+"-"+document.getElementById('editcounter'+id).value);
			amount.setAttribute("maxlength", "11");
			amount.setAttribute("pattern", "[0-9].{0,}");
			amount.setAttribute("required", "");

			tdMeas.appendChild(meas);
			tdOption.appendChild(option);
			tdUnit.appendChild(metric);
			tdAmount.appendChild(amount);

			tr.appendChild(tdMeas);
			tr.appendChild(tdOption);
			tr.appendChild(tdUnit);
			tr.appendChild(tdAmount);

			div.appendChild(tr);
			document.getElementById('editcounter'+id).value++;
		}

		function editMinusInput(id){
			var list = document.getElementById('editcontent' + id);
		    if(document.getElementById('editcounter'+id).value > 1){
		    	document.getElementById('editcounter'+id).value--;
		    	if(list.lastChild == "[object Text]"){
			    	list.removeChild(list.lastChild);
			    	list.removeChild(list.lastChild);
			    }
			    else{
			    	list.removeChild(list.lastChild);
			    }
		    }
		}

		function editValidateMyForm(){
			if (document.getElementById('editrowCounter').value < 1) {
				alert("There is nothing to create.");
				return false;
			}
			else{
				return true;
			}
		}

		function editChangeAmount(id){
			var amount = document.getElementById('editselect'+id).value;
			document.getElementById('editamount'+id).value = amount;
		}

		function editMeasurementAmount(id){
			var kg = document.getElementById('editkg'+id).value;
			var amountTimes	= document.getElementById('editamountTimes'+id).value;
			var answer = kg * amountTimes;
			document.getElementById('editmeasurementAmount'+id).value = answer;
		}

		function editDeleteThisRow(id){
			document.getElementById('editform'+id).remove();
		}

		function editDeleteMeasurement(id) {
			document.getElementById('editform'+id).remove();
			document.getElementById('editmeasurement').disabled = false;
			document.getElementById('editmeasurement').style.backgroundColor = 'rgb(255, 101, 1)';
			document.getElementById('editmeasurement').style.cursor = 'pointer';
		}

		function editCreateNewForm(){
		    var modal = document.getElementById('editviewAddModal');
		    var btn = document.getElementById('editaddOptions');
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

		function viewEditForm(){
			var serviceID = '<?php if(isset($_SESSION['serviceIDSet'])){echo '&serviceID=' . $_SESSION['serviceIDSet'];}?>'
			location.replace("index?route=serviceOptions"+serviceID);
			localStorage.setItem('viewEditModal',true);
			var top = window.scrollY;
			localStorage.setItem('y',top);
		}

		function editcheck(divID, counter){
			var check = document.getElementById('editmeas'+divID+'-'+counter).checked;
			if(check == true){
				document.getElementById('editunit'+divID+'-'+counter).disabled = false;
			}
			else if(check == false){
				document.getElementById('editunit'+divID+'-'+counter).value = "";
				document.getElementById('editunit'+divID+'-'+counter).disabled = true;
			}
		}

		function editSelectChange(id){
			var select = document.getElementById('edittype'+id).value;
			if(select == "Dropdown"){
				var cells = document.getElementsByClassName('editcheck'+id);
				var unit = document.getElementsByClassName('editunit'+id);
				for (var i = 0; i < cells.length; i++) {
					cells[i].disabled = true;
					cells[i].checked = false;
				}

				for (var i = 0; i < unit.length; i++) {
					unit[i].disabled = true;
					unit[i].value = "";
				}
			}
			else{
				var cells = document.getElementsByClassName('editcheck'+id);
				for (var i = 0; i < cells.length; i++) {
					cells[i].disabled = false;
				}
			}
		}

		window.onload = function(){
			var view = localStorage.getItem('viewEditModal');
			if (view == 'true'){
				document.getElementById('viewEditModal').style.display = "block";
			}
			localStorage.setItem('viewEditModal',false)
		}

		var span = document.getElementsByClassName("viewEditClose")[0];
		span.onclick = function() {
			document.getElementById('viewEditModal').style.display = "none";
		}
	</script>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>