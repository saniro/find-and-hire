<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php require("connection.php");?>
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
		        <td class = "tdInput"><input type="text" name = "title'+divID+'" required/></td>\
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
		        <col width = 800>\
		        <tbody id = content'+ divID +'>\
		        <tr>\
		        	<th class = "tdName">Measurement</th>\
		        	<th class = "tdName">Options</th>\
		        	<th class = "tdName">Unit</th>\
		        	<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input class = "check'+divID+'" type="checkbox" id = "'+meas+'-0" name="'+meas+'-0" onclick = "check(\''+divID+'\', \'0\')" disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="'+name+'-0" required/></td>\
		        	<td class = "tdInput"><input type="unit" id = "'+unit+'-0" name="'+unit+'-0" required disabled/></td>\
		        	<td class = "tdInput"><input type="text" name="'+amountName+'-0" required/></td>\
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
		        <td class = "tdInput"><input type="text" name = "title'+divID+'" required/></td>\
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
		        	<td class = "tdInput"><input type="text" name="'+name+'-0" required/></td>\
		        	<td class = "tdInput"><input type="text" name="'+amountName+'-0" required/></td>\
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
			option.setAttribute("required", "");

			var unit = document.createElement('input');
			unit.setAttribute("type", "text");
			unit.setAttribute("id", "unit"+id+"-"+document.getElementById('counter'+id).value);
			unit.setAttribute("name", "unit"+id+"-"+document.getElementById('counter'+id).value);
			unit.setAttribute("required", "");
			unit.setAttribute("disabled", "");

			var amount = document.createElement('input');
			amount.setAttribute("type", "text");
			amount.setAttribute("name","amount"+id+"-"+document.getElementById('counter'+id).value);
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
	<?php
		if (isset($_POST['submit'])) {
			$rowCounter = $_POST['rowCounter'];
			for ($i=0; $i < $rowCounter; $i++) {
				$index = $i;
				try{	
					if(isset($_POST['counter'.$index])){
						//insert to form table
						$contentCounter = $_POST['counter'.$index];
						$sqlCreateForm = "INSERT INTO form (ServiceID, Title, Component)values(1, :title, :component)";
						$stmt = $con->prepare($sqlCreateForm);
						//$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
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
			//echo "<script>window.location.href = 'index?route=serviceOptions".$serviceIDSet."';</script>";
		}
	?>
</body>
</html>