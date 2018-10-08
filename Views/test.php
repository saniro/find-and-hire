<!-- <?php
	// require("connection.php");
	// 	$dateToday = date("Y-m-d");
	// 	$sqlReqCheckExpiration = "SELECT requirementID, expirationDate FROM requirements WHERE submitted = 1";

	// 	$stmt = $con->prepare($sqlReqCheckExpiration);
	// 	$stmt->execute();
	// 	$results = $stmt->fetchAll();
	// 	$rowCount = $stmt->rowCount();

	// 	echo "<script>alert('" . $dateToday . "');</script>";
	// 	foreach ($results as $rowReqCheckExpiration) {
	// 		$requirementID = $rowReqCheckExpiration['requirementID'];
	// 		$expirationDate = $rowReqCheckExpiration['expirationDate'];
	// 		if($expirationDate > $dateToday){
	// 			echo $expirationDate . "is greater than " . $dateToday . "<br>";
	// 		}
	// 		else{
	// 			$sqlUpdateRequirements = "	UPDATE requirements
	// 										SET submitted = 0
	// 										WHERE requirementID = (:requirementID)";
	// 			$stmt = $con->prepare($sqlUpdateRequirements);
	// 			$stmt->bindParam(':requirementID', $requirementID, PDO::PARAM_INT);
	// 			$stmt->execute();

	// 		}
	// 	}
?> -->
<!-- <!DOCTYPE html>
<html>
<head>
	<title>hey</title>
</head>
<body>

</body>
</html> -->

<!-- <?php
	// require("connection.php");
	// $userID = 1;
	// $sql = "SELECT file FROM requirements WHERE userID = (:userID)";
	// $stmt = $con->prepare($sql);
	// $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
	// $stmt->execute();
	// $rowYesNo = $stmt->fetch();

	// print $rowYesNo['file'];
	
?> -->

<?php

    //DB details
    // $dbHost     = 'localhost';
    // $dbUsername = 'root';
    // $dbPassword = '';
    // $dbName     = 'handyman';
    
    // //Create connection and select DB
    // $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    
    // //Check connection
    // if($db->connect_error){
    //    die("Connection failed: " . $db->connect_error);
    // }
    
    // $userID = 1;
    // //Get image data from database
    // $result = $db->query("SELECT file FROM requirements WHERE userID = {$userID}");
    // echo "<script>alert('hey');</script>";
    // if($result->num_rows > 0){
    //     $imgData = $result->fetch_assoc();
        
    //     //Render image
    //     echo "<img src='" . $imgData['file'] . "'>";
    //     //header('Content-type: image/png'); 
    //     //echo $imgData['file']; 
    // }else{
    //     echo 'Image not found...';
    // }

?>
<?php
	// $id = 1;
 //  // do some validation here to ensure id is safe

 //  $link = mysqli_connect("localhost", "root", "");
 //  mysql_select_db("handyman");
 //  $sql = "SELECT file FROM requirements WHERE id=$id";
 //  $result = mysql_query("$sql");
 //  $row = mysql_fetch_assoc($result);
 //  mysql_close($link);

 //  header("Content-type: image/png");
 //  echo $row['file'];
?>

<?php
	// require("connection.php");
	// $userID = 1;
	// $sql = "SELECT file, mime FROM requirements WHERE userID = (:userID)";
	// $stmt = $con->prepare($sql);
	// $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
	// $stmt->execute();
	// $stmt->bindColumn(1, $lob, PDO::PARAM_LOB);
	// $stmt->bindColumn(2, $mime, PDO::PARAM_STR);
	// $rowYesNo = $stmt->fetch(PDO::FETCH_BOUND);

	// header("Content-type: $mime");
 //  	echo $lob;
?>

<!-- <?php
	// $date = date("Y-m-d h:i:s A");
	// echo $date;
?> -->

<?php
$to = "jairo.sanchez943.js@gmail.com";
$sub = "Php Mail";
$msg = "Test Message From PHP";

mail($to, $sub, $msg, "From: jairo@gmail.com");
?>