<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
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
	echo "Measurement = ".$true."<br>";
	if($rowCount > 0){
		echo "rowCounter = ".$rowCount."<br>";
		foreach ($results as $rowEditFormView){
			$editViewFormID = $rowEditFormView['FormID'];
			$editViewFormTitle = $rowEditFormView['Title'];
			$editViewFormComponent = $rowEditFormView['Component'];
			echo "Form Name = form".$ctr."<br>";
			echo "Title Name = title".$ctr." :: Title = ".$editViewFormTitle."<br>Component Name = type".$ctr." :: Component = ".$editViewFormComponent."<br>";
			$sqlEditFormChoicesView = "SELECT Description, Amount FROM formchoices WHERE FormID =:editViewFormID";
			$stmt = $con->prepare($sqlEditFormChoicesView);
			$stmt->bindParam(':editViewFormID', $editViewFormID);
			$stmt->execute();
			$resultsChoices = $stmt->fetchAll();
			$rowCountChoices = $stmt->rowCount();
			$counterChoices = 0;
			echo "Counter Name = counter".$ctr."::counter = ". $rowCountChoices . "<br>";
			if($editViewFormComponent != "Textbox"){
				foreach ($resultsChoices as $rowEditFormChoiceView) {
					$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
					$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
					echo "choice ID = input".$ctr."-".$counterChoices." == choice = ".$editViewFormChoicesDescription."::amount ID = amount".$ctr."-".$counterChoices." == Amount = ".$editViewFormChoicesAmount."<br>";
					$counterChoices++;
				}
				echo "+ADD";
			}
			else{
				foreach ($resultsChoices as $rowEditFormChoiceView) {
					$editViewFormChoicesDescription = $rowEditFormChoiceView['Description'];
					$editViewFormChoicesAmount = $rowEditFormChoiceView['Amount'];
					echo "choice ID = input".$ctr."-".$counterChoices." == choice = ".$editViewFormChoicesDescription."::amount ID = amount".$ctr."-".$counterChoices." == Amount = ".$editViewFormChoicesAmount."<br>";
					$counterChoices++;
				}
			}
			$ctr++;
			echo "<br>";
		}
	}
?>
</body>
</html>