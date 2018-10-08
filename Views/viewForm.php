<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table, tbody, th, tr, td, div {
			border-style: solid;
			border-color: black;
			border-width: 1px;
		}
	</style>
	<script type="text/javascript">
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
	</script>
</head>

<body>
		<?php
			require("connection.php");
			$sqlViewForm = "SELECT FormID, Title, Component FROM form WHERE flag = 1 AND ServiceID = 1";
			$stmt = $con->prepare($sqlViewForm);
			$stmt->execute();
			$results = $stmt->fetchAll();
			foreach ($results as $rowViewForm) {
				echo "<div>";
				echo "<table>";
				echo "<tr>";
				$viewFormID = $rowViewForm['FormID'];
				$viewFormTitle = $rowViewForm['Title'];
				$viewFormComponent = $rowViewForm['Component'];
				echo "<td>Title</td><td>".$viewFormTitle."</td>";
				echo "</tr>";
				echo "</table>";
				echo "<table>";
				echo "<thead>";
				echo "<tr><th>Options</th><th>Amount</th></thead>";
				echo "<tbody>";
				$sqlViewFormChoices = "SELECT Description, Amount FROM formchoices WHERE BlockFormID = :viewFormID";
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
						echo "<td><input type='checkbox' name = ".$viewFormID.$x.">".$viewFormChoicesDescription."</td><td>".$viewFormChoicesAmount."</td>";
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
						echo "<td><input type='radio' name = ".$viewFormID.$x." value = ".$viewFormChoicesAmount." onchange = 'changeAmount();'>&nbsp".$viewFormChoicesDescription."</td><td>".$viewFormChoicesAmount."</td>";
						echo "</tr>";
						$x++;
					}
					$x=0;
				}
				elseif($viewFormComponent == 'Dropdown'){
					$x=0;
					echo "<tr>";
					echo "<td>";
					echo "<select id = 'select".$viewFormID."' onchange = 'changeAmount(".$viewFormID.")'>";
					echo "<option selected disabled> -Select Option- </option>";
					foreach ($results as $rowViewFormChoices) {
						$viewFormChoicesDescription = $rowViewFormChoices['Description'];
						$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
						echo "<option name = ".$viewFormID.$x." value = '".$viewFormChoicesAmount."'>".$viewFormChoicesDescription."</option>";
						$x++;
					}
					echo "</select>";
					echo "</td>";
					echo "<td><input type = 'text' id = 'amount".$viewFormID."' readonly></td>";
					echo "</tr>";
					$x=0;
				}
				elseif($viewFormComponent == 'Textbox'){
					$x=0;
					foreach ($results as $rowViewFormChoices) {
						$viewFormChoicesDescription = $rowViewFormChoices['Description'];
						$viewFormChoicesAmount = $rowViewFormChoices['Amount'];
						echo "<td colspan = 2><input type = 'textbox' id = 'amountTimes".$viewFormID."' value = '".$viewFormChoicesAmount."' readonly> per ".$viewFormChoicesDescription."</td>";
						$x++;
					}
					echo "<tr>";
					echo "<td><input type = 'textbox' id = 'kg".$viewFormID."' oninput = 'measurementAmount(".$viewFormID.")'></input> Kg</td><td><input type = 'textbox' id = 'measurementAmount".$viewFormID."'></td>";
					$x=0;
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
				echo "</div>";
			}
		?>
</body>
</html>