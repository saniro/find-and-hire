<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
	require("connection.php");
?>

<div>
	<div>
		<input class = "view" id = "editadd" type="button" value="ADD" onclick="editAddRow()"> <input class = "measurementBtn" type="button" value="MEASUREMENT" onclick="editAddMeasurement()" id = "editmeasurement" disabled = false>
	</div>
	<?php
		$sqlEditFormView = "SELECT FormID, Title, Component FROM form WHERE flag = 1 AND serviceID = 5";
		$stmt = $con->prepare($sqlEditFormView);
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

			$sqlEditFormChoicesView = "SELECT Description, Amount FROM formchoices WHERE FormID =:editViewFormID";
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
				        <td class = "tdInput"><input type="text" name = "edittitle<?php echo $ctr;?>" value = "<?php echo $editViewFormTitle; ?>" required/></td>
				        </tr>
				        <input name="editcounter<?php echo $ctr;?>" id="editcounter<?php echo $ctr;?>" type = "text" value = <?php echo $rowCountChoices;?> readonly>
				        <tr>
				        <?php if($editViewFormComponent != "Textbox"){?>
				        <td class = "tdName">Component: </td>
				        <td class = "tdInput">
				        	<select class = "dropdownType" name = "edittype<?php echo $ctr;?>">
					        	<option value = "Dropdown" <?php if($editViewFormComponent == "Dropdown"){echo "selected";}?>>Drodown</option>
						        <option value = "Checkbox" <?php if($editViewFormComponent == "Checkbox"){echo "selected";}?>>Checkbox</option>
						        <option value = "RadioButton" <?php if($editViewFormComponent == "RadioButton"){echo "selected";}?>>Radio Button</option>
					        </select>
				        </td>
				        <?php }else{?><input type = "hidden" name = "edittype<?php echo $ctr;?>" value = "Textbox"><?php }?>
				        </tr>
				        <tr>
					        <table>
						        <col width = 800>
						        <tbody id = "editcontent<?php echo $ctr; ?>">
						        <tr>
						        	<th class = "tdName">Options</th>
						        	<th class = "tdName">Amount</th>
						        </tr>
						        <?php
						        foreach ($resultsChoices as $rowEditFormChoiceView) {
									$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
									$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
						        ?>
						        <tr>
						        	<td class = "tdInput"><input type="text" name="editinput<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesDescription;?>" required/></td>
						        	<td class = "tdInput"><input type="text" name="editamount<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesAmount;?>" required/></td>
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
	<?php
		if (isset($_POST['editsubmit'])) {
			$sqlFormDeac = "UPDATE form
							SET flag = 0
							WHERE serviceID = 5";
			$stmt = $con->prepare($sqlFormDeac);
			//$stmt->bindParam(':modalYesNoUserID', $modalYesNoUserID, PDO::PARAM_INT);
			$stmt->execute();

			$rowCounter = $_POST['editrowCounter'];
			for ($i=0; $i < $rowCounter; $i++) {
				$index = $i;
				try{	
					if(isset($_POST['editcounter'.$index])){
						//insert to form table
						$contentCounter = $_POST['editcounter'.$index];

						$sqlCreateForm = "INSERT INTO form (ServiceID, Title, Component)values(5, :title, :component)";
						$stmt = $con->prepare($sqlCreateForm);
						//$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
						$stmt->bindParam(':title', $_POST['edittitle'.$index], PDO::PARAM_STR);
						$stmt->bindParam(':component',$_POST['edittype'.$index], PDO::PARAM_STR);
						$stmt->execute();
						$formLastInsertedID = $con->lastInsertId();
						// echo "Form # : form" . $index . "<br>";
						// echo "Type : " .  . "<br>";
						for ($x=0; $x < $contentCounter; $x++) {
							$sqlCreateFormChoices = "INSERT INTO formchoices (FormID, Description, Amount) VALUES (:formLastInsertedID, :description, :amount)";
							$stmt = $con->prepare($sqlCreateFormChoices);
							$stmt->bindParam(':formLastInsertedID',$formLastInsertedID, PDO::PARAM_INT);
							
							$stmt->bindParam(':description',$_POST['editinput'.$index.'-'.$x], PDO::PARAM_STR);
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
			//echo "<script>window.location.href = 'index?route=serviceOptions".$serviceIDSet."';</script>";
		}
	?>
</div>
<script type="text/javascript">
	var divID = <?php echo $ctr;?>;
	var inputID = 0;
	function editAddRow() {
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
	        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "editremove'+divID+'" onclick = "editDeleteThisRow('+divID+')"> &times</span></td>\
	        <tr>\
	        <tr>\
	        <td class = "tdName">Title : </td>\
	        <td class = "tdInput"><input type="text" name = "edittitle'+divID+'" required/></td>\
	        </tr>\
	        <input name="editcounter'+divID+'" id="editcounter'+divID+'" type = "hidden" value = "1" readonly>\
	        <tr>\
	        <td class = "tdName">Component: </td>\
	        <td class = "tdInput">\
	        	<select class = "dropdownType" name = "edittype'+divID+'">\
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
	        	<th class = "tdName">Options</th>\
	        	<th class = "tdName">Amount</th>\
	        </tr>\
	        <tr>\
	        	<td class = "tdInput"><input type="text" name="edit'+name+'-0" required/></td>\
	        	<td class = "tdInput"><input type="text" name="edit'+amountName+'-0" required/></td>\
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
	        <td class = "tdInput"><input type="text" name = "edittitle'+divID+'" required/></td>\
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
	        	<td class = "tdInput"><input type="text" name="edit'+name+'-0" required/></td>\
	        	<td class = "tdInput"><input type="text" name="edit'+amountName+'-0" required/></td>\
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
		var tdOption = document.createElement("td");
		var tdAmount = document.createElement("td");
		tdOption.className = 'tdInput';
		tdAmount.className = 'tdInput';

		var option = document.createElement('input');
		option.setAttribute("type", "text");
		option.setAttribute("name","editinput"+id+"-"+document.getElementById('editcounter'+id).value);
		option.setAttribute("required", "");
		var amount = document.createElement('input');
		amount.setAttribute("type", "text");
		amount.setAttribute("name","editamount"+id+"-"+document.getElementById('editcounter'+id).value);
		amount.setAttribute("required", "");

		tdOption.appendChild(option);
		tdAmount.appendChild(amount);

		tr.appendChild(tdOption);
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
		document.getElementById('editmeasurement').removeAttribute(disabled);
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
</script>
</body>
</html>