<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="rowForm">
			<?php
				require("connection.php");

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
				<div class = "">
					<input class = "view" id = "add" type="button" value="ADD" onclick="addRow()"> <input class = "measurementBtn" type="button" value="MEASUREMENT" onclick="addMeasurement()" id = "measurement" <?php if($true == "True"){echo "disabled";}?>>
				</div>
				<form method="post" onsubmit="return validateMyForm();" autocomplete="off">
				<input type="hidden" id = "rowCounter" name="rowCounter" readonly>
				<div id="content">
				<input type="hidden" id = "rowCounter" name="rowCounter" value = "<?php echo $rowCount;?>" readonly>
				<div id="content">
				<?php
				if($rowCount > 0){
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
						?>
						<div id = "form<?php echo $ctr;?>">
					        <table>
						        <col width	= 100>
						        <col width = 800>
						        <tbody>
							        <tr>
							        	 <td colspan = 2 class = "removeDivTd"><span class = "removeDiv" id = "remove<?php echo $ctr;?>" onclick = "deleteThisRow(<?php echo $ctr;?>)"> &times</span></td>
							        </tr>
							        <tr>
							        <td class = "tdName">Title : </td>
							        <td class = "tdInput"><input type="text" name = "title<?php echo $ctr;?>" value = "<?php echo $editViewFormTitle;?>" required/></td>
							        </tr>
							        <input name="counter<?php echo $ctr;?>" id="counter<?php echo $ctr;?>" type = "text" value = "<?php echo $rowCountChoices; ?>" readonly>
							        <tr>
							        <input type = "hidden" name = "type<?php echo $ctr;?>" value = "<?php echo $editViewFormComponent; ?>">
							        </tr>
							        <tr>
							        	<table>
									        <col width = 800>
									        <tbody id = "content<?php echo $ctr; ?>">
									        <tr>
									        	<th class = "tdName">Options</th>
									        	<th class = "tdName">Amount</th>
									        </tr>
	    	<?php
	    		if($editViewFormComponent != "Textbox"){
					foreach ($resultsChoices as $rowEditFormChoiceView) {
						$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
						$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
						?>
					        <tr>
					        	<td class = "tdInput"><input type="text" name="input<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesDescription;?>" required/></td>
					        	<td class = "tdInput"><input type="text" name="amount<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesAmount; ?>" required/></td>
					        </tr>
						<?php
						$counterChoices++;
					}
					?>
					</tbody>
			        </table>
			        <input type="button" class = "view" name="name" value="+" onclick = "addInput(<?php echo $ctr;?>);" />
			        <input type="button" class = "delete" name="name" value="-" onclick = "minusInput(<?php echo $ctr;?>)" />
					<?php
				}
				else{
					foreach ($resultsChoices as $rowEditFormChoiceView) {
						$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
						$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
						?>
					        <tr>
					        	<td class = "tdInput"><input type="text" name="input<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesDescription;?>" required/></td>
					        	<td class = "tdInput"><input type="text" name="amount<?php echo $ctr;?>-<?php echo $counterChoices;?>" value = "<?php echo $editViewFormChoicesAmount; ?>" required/></td>
					        </tr>
						<?php
						$counterChoices++;
					}
					?>
					</tbody>
			        </table>
					<?php
				}
	    	?>
						        	</tr>
						        </tbody>
					        </table>
					    </div>
						<?php
						$ctr++;
					}
				}
			?>
			</div>
		</div>
			<div class = buttonSubmit>
				<button type="submit" class="addSubmit" name="submit">CREATE</button>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		var divID = <?php echo $ctr;?>;
		var inputID = 0;
		function addRow() {
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
		        	<select class = "dropdownType" name = "type'+divID+'">\
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
		        	<th class = "tdName">Options</th>\
		        	<th class = "tdName">Amount</th>\
		        </tr>\
		        <tr>\
		        	<td class = "tdInput"><input type="text" name="'+name+'-0" required/></td>\
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
			var tdOption = document.createElement("td");
			var tdAmount = document.createElement("td");
			tdOption.className = 'tdInput';
			tdAmount.className = 'tdInput';

			var option = document.createElement('input');
			option.setAttribute("type", "text");
			option.setAttribute("name","input"+id+"-"+document.getElementById('counter'+id).value);
			option.setAttribute("required", "");
			var amount = document.createElement('input');
			amount.setAttribute("type", "text");
			amount.setAttribute("name","amount"+id+"-"+document.getElementById('counter'+id).value);
			amount.setAttribute("required", "");

			tdOption.appendChild(option);
			tdAmount.appendChild(amount);

			tr.appendChild(tdOption);
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
	</script>
</body>
</html>