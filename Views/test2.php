
<?php
	require("connection.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
</head>
<body>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="requirementtypeID" value="<?php echo $modalRequirementsID;?>">
		<tr class = "tableContentYesNo">
			<td><input type='file' onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" name="myfile" /></td>
			<td><img id="blah" src="#" alt="your image" /></td>
			<script type="text/javascript">
			</script>
	    </tr>
	    <tr class = "tableContentYesNo">
	    	<td colspan="2"><button name="insert">Insert</button></td>
	    </tr>
	</form>
	<?php
		$_POST["requirementtypeID"] = 1;
		$modalVerifyHandymanID = 1;
		$dateToday = 1;

		if(isset($_POST["insert"])){

			$dateToday = date('Y-m-d h:i:sa');
			$tmpName  = $_FILES['myfile']['tmp_name'];  
			$name = $_FILES['myfile']['name'];
			$type = $_FILES['myfile']['type'];
			$image = addslashes(file_get_contents($_FILES['myfile']['tmp_name']));
	   		$sqlAddRequirements = "INSERT INTO requirements (requirementTypeID, userID, file, submissionDate, expirationDate)values(:requirementtypeID, :modalVerifyHandymanID, '$image', :dateToday, :dateToday)";
			$stmt = $con->prepare($sqlAddRequirements);
			$stmt->bindParam(':requirementtypeID', $_POST["requirementtypeID"], PDO::PARAM_STR);
			$stmt->bindParam(':modalVerifyHandymanID', $modalVerifyHandymanID, PDO::PARAM_STR);
			$stmt->bindParam(':dateToday', $dateToday, PDO::PARAM_STR);
			//$stmt->bindParam(':file', $file, PDO::PARAM_LOB);
			$stmt->execute();
			echo "<script>alert('hey".$image."');</script>";
			echo "success";
		}
	?>
	<script type="text/javascript">
		function readURL(input) {
		        if (input.files && input.files[0]) {
		            var reader = new FileReader();

		            reader.onload = function (e) {
		                $('#blah')
		                    .attr('src', e.target.result)
		                    .width(150)
		                    .height(200);
		            };

		            reader.readAsDataURL(input.files[0]);
		        }
		    }
	</script>
</body>
</html>
