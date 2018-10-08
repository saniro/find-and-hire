
<!-- <?php
	// require("connection.php");
?>
<form method="post" name = "form" enctype="multipart/form-data" autocomplete="off">
	<input type="file" name="fileupload" />
	<button name = "submit">Upload</button>
</form>
<?php
	// $_POST["requirementtypeID"] = 1;
	// $modalVerifyHandymanID = 1;
	// $dateToday = 1;

	// if(isset($_POST["submit"])){
	// 	if($_FILES['fileupload']['name']!=""){
	// 		$image = addslashes(file_get_contents($_FILES['fileupload']['tmp_name']));
	// 		$sqlAddRequirements = "INSERT INTO requirements (requirementTypeID, userID, file, submissionDate, expirationDate)values(:requirementtypeID, :modalVerifyHandymanID, '$image', :dateToday, :dateToday)";
	// 		$stmt = $con->prepare($sqlAddRequirements);
	// 		$stmt->bindParam(':requirementtypeID', $_POST["requirementtypeID"], PDO::PARAM_STR);
	// 		$stmt->bindParam(':modalVerifyHandymanID', $modalVerifyHandymanID, PDO::PARAM_STR);
	// 		$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
	// 		//$stmt->bindParam(':file', $file, PDO::PARAM_LOB);
	// 		$stmt->execute();
	// 		echo "success";
	// 	}
	// 	else{
	// 		echo "empty";
	// 	}
	// }
?> -->

<!-- query for selected -->
<!-- SELECT selectedID, choicesID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT BlockFormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS Title, (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS Amount, groupChoicesID FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM transaction WHERE transactionID = 1) -->

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		require("connection.php");
		$sqlSelectedTitle = "SELECT (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT BlockFormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS titleID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT BlockFormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS title FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM transaction WHERE transactionID = 1) GROUP BY (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT BlockFormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID))";

		$stmt = $con->prepare($sqlSelectedTitle);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$rowCount = $stmt->rowCount();

		foreach($results as $rowSelectedTitle){
			$selectedTitleID = $rowSelectedTitle["titleID"];
			$selectedTitle = $rowSelectedTitle["title"];
			echo $selectedTitleID . " - " .$selectedTitle . "<br>";

			$sqlSelectedChoices = "SELECT (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS amount FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM transaction WHERE transactionID = 1) AND (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT BlockFormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) = :selectedTitleID";

				$stmt = $con->prepare($sqlSelectedChoices);
				$stmt->bindParam(':selectedTitleID', $selectedTitleID, PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll();
				$rowCount = $stmt->rowCount();

				foreach($results as $rowSelectedChoices){
					$selectedChoicesDescription = $rowSelectedChoices["description"];
					$selectedChoicesAmount = $rowSelectedChoices["amount"];

					echo $selectedChoicesDescription . "</br>";
					echo $selectedChoicesAmount . "</br>";
				}
    	}
	?>
</body>
</html>