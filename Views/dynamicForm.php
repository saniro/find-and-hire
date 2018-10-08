<?php require("connection.php");?>
<!DOCTYPE html>
<html>
<head>
	<title> Dynamic Form </title>
	<style type="text/css">
		.row{
			width: 500px;
			border-color: black;
			border-style: solid;
			border-width: 1px;
			margin-bottom: 10px;
		}
	</style>
	<script type="text/javascript">
		var divID = 0;
		var inputID = 0;
		function addRow() {
    		var div = document.createElement('div');
    		div.className = 'row';
    		div.id = 'form' + divID;

    		//content
    		var name = 'input'+ divID;
    		var amountName = 'amount'+divID;

		    div.innerHTML =
		        'Title : <input type="text" name = "title'+divID+'" required/><span id = "remove'+divID+'" onclick = "deleteThisRow('+divID+')"> &times</span><br>\
		        <input name="counter'+divID+'" id="counter'+divID+'" type = "hidden" value = "1" readonly>\
		        <br>Component: <select name = "type'+divID+'">\
		        <option value = "Dropdown">Drodown</option>\
		        <option value = "Checkbox">Checkbox</option>\
		        <option value = "RadioButton">Radio Button</option>\
		        </select>\
		        <table>\
		        <tbody id = content'+ divID +'>\
		        <tr>\
		        	<td>Options : </td>\
		        	<td><input type="text" name="'+name+'-0" required/></td>\
		        	<td>Amount: </td>\
		        	<td><input type="text" name="'+amountName+'-0" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        <input type="button" name="name" value="Add" onclick = "addInput('+divID+');" />\
		        <input type="button" name="name" value="Minus" onclick = "minusInput('+divID+')" />\
		        ';
    		document.getElementById('content').appendChild(div);
    		divID++;
    		document.getElementById('rowCounter').value++;
		}

		function removeRow() {
			var list = document.getElementById('content');
			if(document.getElementById('rowCounter').value > 0){
	    		if (list.hasChildNodes()) {
			        list.removeChild(list.childNodes[divID]);
			        document.getElementById('rowCounter').value--;
	    			divID--;
			    }
			}
		}

		function deleteMeasurement(id) {
			document.getElementById('form'+id).remove();
			document.getElementById('measurement').disabled = false;
		}

		function addInput(id){
			var div = document.getElementById('content'+id);
			var tr = document.createElement("tr");
			var tdOptionText = document.createElement("td");
			var tdOption = document.createElement("td");
			var tdAmountText = document.createElement("td");
			var tdAmount = document.createElement("td");

			var optionText = document.createTextNode(" Option: ");
			var option = document.createElement('input');
			option.setAttribute("type", "text");
			option.setAttribute("name","input"+id+"-"+document.getElementById('counter'+id).value);
			option.setAttribute("required", "");
			var amountText = document.createTextNode(" Amount: ");
			var amount = document.createElement('input');
			amount.setAttribute("type", "text");
			amount.setAttribute("name","amount"+id+"-"+document.getElementById('counter'+id).value);
			amount.setAttribute("required", "");

			tdOptionText.appendChild(optionText);
			tdOption.appendChild(option);
			tdAmountText.appendChild(amountText);
			tdAmount.appendChild(amount);

			tr.appendChild(tdOptionText);
			tr.appendChild(tdOption);
			tr.appendChild(tdAmountText);
			tr.appendChild(tdAmount);

			div.appendChild(tr);
			document.getElementById('counter'+id).value++;
			// document.getElementById('content'+id).innerHTML += '<input type="text" name="input'+id+'-'+document.getElementById('counter'+id).value+'" /> Amount: <input type = "text" name= "amount'+id+'-'+document.getElementById('counter'+id).value+'" />';
			// 
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

		function deleteThisRow(id){
			document.getElementById('form'+id).remove();
		}

		function addMeasurement() {
    		var div = document.createElement('div');
    		div.className = 'row';
    		div.id = 'form' + divID;

    		//content
    		var name = 'input'+ divID;
    		var amountName = 'amount'+divID;

		    div.innerHTML =
		        'Title : <input type="text" name = "title'+divID+'" required/><span id = "remove'+divID+'" onclick = "deleteMeasurement('+divID+')"> &times</span><br>\
		        <input name="counter'+divID+'" id="counter'+divID+'" type = "hidden" value = "1" readonly>\
		        <input type = "hidden" name = "type'+divID+'" value = "Textbox">\
		        <table>\
		        <tbody id = content'+ divID +'>\
		        <tr>\
		        	<td>Metric : </td>\
		        	<td><input type="text" name="'+name+'-0" required/></td>\
		        	<td>Amount: </td>\
		        	<td><input type="text" name="'+amountName+'-0" required/></td>\
		        </tr>\
		        </tbody>\
		        </table>\
		        ';
    		document.getElementById('content').appendChild(div);
    		divID++;
    		document.getElementById('rowCounter').value++;
    		document.getElementById('measurement').disabled = true;
		}
	</script>
</head>
<body>
	<input id = "add" type="button" value="ADD" onclick="addRow()">
	<input type="button" value="REMOVE" onclick="removeRow()">
	<input type="button" value="MEASUREMENT" onclick="addMeasurement()" id = "measurement">
	<form method="post" onsubmit="return validateMyForm();">
		<input type="hidden" id = "rowCounter" name="rowCounter" readonly>
		<div id="content">
		</div>
		<button type="submit" name="submit">submit</button>
	</form>
</body>
</html>
<?php
	if (isset($_POST['submit'])) {
		$rowCounter = $_POST['rowCounter'];

		for ($i=0; $i < $rowCounter; $i++) {
			$index = $i;
			$contentCounter = $_POST['counter'.$index];
			//insert to form table
			if(($_POST['counter'.$index])!=""){
				$sqlCreateForm = "INSERT INTO form (ServiceID, Title, Component)values(1, :title, :component)";
				$stmt = $con->prepare($sqlCreateForm);
				//$stmt->bindParam(':serviceID', '1', PDO::PARAM_INT);
				$stmt->bindParam(':title', $_POST['title'.$index], PDO::PARAM_STR);
				$stmt->bindParam(':component',$_POST['type'.$index], PDO::PARAM_STR);
				$stmt->execute();
				$formLastInsertedID = $con->lastInsertId();
				// echo "Form # : form" . $index . "<br>";
				// echo "Type : " .  . "<br>";
				for ($x=0; $x < $contentCounter; $x++) {
					$sqlCreateFormChoices = "INSERT INTO formchoices (BlockFormID, Description, Amount) VALUES (:formLastInsertedID, :description, :amount)";
					$stmt = $con->prepare($sqlCreateFormChoices);
					$stmt->bindParam(':formLastInsertedID',$formLastInsertedID, PDO::PARAM_INT);
					
					$stmt->bindParam(':description',$_POST['input'.$index.'-'.$x], PDO::PARAM_STR);
					$stmt->bindParam(':amount',$_POST['amount'.$index.'-'.$x], PDO::PARAM_STR);
					$stmt->execute();
				}
				//echo "<br>";
			}
		}
		$rowCounter = 0;
		echo "<script>window.location.href = 'index.php?route=df';</script>";
	}
?>