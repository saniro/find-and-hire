<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class = "viewForm">
	<?php
		require("connection.php");
		$sqlViewForm = "SELECT FormID, Title, Component FROM form WHERE flag = 1 AND ServiceID = 7";
		$stmt = $con->prepare($sqlViewForm);
		//$stmt->bindParam(':serviceID', $_SESSION['serviceIDSet'], PDO::PARAM_INT);
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
				echo "<col width = 520>";
				echo "<col width = 120>";
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
							echo "<td class = 'tdInput'><input type='checkbox' name = ".$viewFormID.$x.">".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' />&nbsp".$viewFormChoicesUnit."</td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
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
							echo "<td class = 'tdInput'><input id = '".$viewFormID.$x."' type='radio' name = ".$viewFormID.$x." value = ".$viewFormChoicesAmount." onchange = 'changeAmount();'>&nbsp".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text'>&nbsp".$viewFormChoicesUnit."</td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
						}
						else{
							echo "<td class = 'tdInput'><input id = '".$viewFormID.$x."' type='radio' name = ".$viewFormID.$x." value = ".$viewFormChoicesAmount." onchange = 'changeAmount();'>&nbsp".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' disabled>&nbsp".$viewFormChoicesUnit."</td><td class = 'tdInput'>".$viewFormChoicesAmount."</td>";
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
					echo "<td class = 'tdInput'><input type = 'text' id = 'amount".$viewFormID."' readonly></td>";
					echo "</tr>";
					$x=0;
				}
				elseif($viewFormComponent == 'Textbox'){
					$x=0;
					foreach ($results as $rowViewFormChoices) {
						$viewFormChoicesDescription = $rowViewFormChoices['Description'];
						$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
						echo "<td colspan = 2 class = 'tdInput amount'><input type = 'text' id = 'amountTimes".$viewFormID."' value = '".$viewFormChoicesAmount."' readonly> per ".$viewFormChoicesDescription."</td>";
						$x++;
					}
					echo "<tr>";
					echo "<td class = 'tdInput amount'><input type = 'text' id = 'kg".$viewFormID."' oninput = 'measurementAmount(".$viewFormID.")'></input> ".$viewFormChoicesDescription."</td><td class = 'tdInput'><input type = 'text' id = 'measurementAmount".$viewFormID."' readonly></td>";
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
</body>
</html>