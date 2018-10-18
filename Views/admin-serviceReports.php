<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<script type="text/javascript" src="Libraries/chart/js/Chart.min.js"></script>
		<link rel="stylesheet" type="text/css" href="Libraries/chart/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-serviceReportsStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<script type="text/javascript" src = "Libraries/jsPDF/dist/jspdf.min.js"></script>
		<script type="text/javascript" src = "Libraries/htmlCanvas/html2canvas.js"></script>

		<script type="text/javascript">
			function genPDF(){
				html2canvas(document.getElementById("printReport")).then(function(canvas) {
				    var doc = new jsPDF("p", "mm", "legal");
				    var width = doc.internal.pageSize.width;    
					var height = doc.internal.pageSize.height;

					doc.addImage(canvas, 'JPEG', 0, 0, width, height);
					doc.output('save','Find & Hire Reports.pdf');
				});
			}
		</script>
	</head>
	<body>
		<div class = webTitlePage>
		<?php
			require("admin-title.php");
		?>
		</div>
		<div>
			<div class = "sideNavigation">
				<?php
					require("admin-sidebar.php");
				?>
			</div>
			<div class = wrapper>
					<?php
					require("connection.php");
						$yearTrue = false;
						$monthTrue = false;
						$dayTrue = false;
						$sqlReportYear = "SELECT DATE_FORMAT(date, '%Y') AS year FROM booking GROUP BY DATE_FORMAT(date, '%Y') ORDER BY DATE_FORMAT(date, '%Y')";
						$stmt = $con->prepare($sqlReportYear);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$ctrYear = 0;
						foreach ($results as $rowReportYear) {
							$reportYear[$ctrYear] = $rowReportYear['year'];	
							if(isset($_GET['year'])){
								if($_GET['year'] == $reportYear[$ctrYear]){
									$yearTrue = true;
								}
							}
							$ctrYear++;
						}

						if(isset($_GET['year']) && ($yearTrue)){
							$sqlReportMonth = "SELECT DATE_FORMAT(date, '%M') AS month FROM booking WHERE DATE_FORMAT(date, '%Y') = :year GROUP BY DATE_FORMAT(date, '%c') ORDER BY DATE_FORMAT(date, '%Y %c')";
							$stmt = $con->prepare($sqlReportMonth);
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$ctrMonth = 0;
							foreach ($results as $rowReportMonth) {
								$reportMonth[$ctrMonth] = $rowReportMonth['month'];
								if(isset($_GET['month'])){
									if($_GET['month'] == $reportMonth[$ctrMonth]){
										$monthTrue = true;
									}
								}
								$ctrMonth++;
							}
						}

						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlReportDay = "SELECT DATE_FORMAT(date, '%d') AS day FROM booking WHERE (DATE_FORMAT(date, '%Y') = :year) AND (DATE_FORMAT(date, '%M') = :month) ORDER BY DATE_FORMAT(date, '%Y %c')";
							$stmt = $con->prepare($sqlReportDay);
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$ctrDay = 0;
							foreach ($results as $rowReportDay) {
								$reportDay[$ctrDay] = $rowReportDay['day'];
								if(isset($_GET['day'])){
									if($_GET['day'] == $reportDay[$ctrDay]){
										$dayTrue = true;
									}
								}
								$ctrDay++;
							}
						}

						$sqlService = "SELECT serviceID, name FROM services";
						$stmt = $con->prepare($sqlService);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$ctrService = 0;
						foreach ($results as $rowService) {
							$reportServiceID[$ctrService] = $rowService['serviceID'];
							$reportService[$ctrService] = $rowService['name'];
							// if(isset($_GET['day'])){
							// 	if($_GET['day'] == $reportDay[$ctrDay]){
							// 		$dayTrue = true;
							// 	}
							// }
							$ctrService++;
						}

						//===== GET SERVICE =====
						$sqlService = "SELECT name FROM services WHERE serviceID = :serviceID";
						$stmt = $con->prepare($sqlService);
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$results = $stmt->fetch();
						$serviceName = $results['name'];
						//===== GET SERVICE END =====

						//===== Count Booking Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day AND serviceID = :serviceID AND serviceID = :serviceID";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND serviceID = :serviceID";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND serviceID = :serviceID";
						}
						else{
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE serviceID = :serviceID";
						}
						$stmt = $con->prepare($sqlCountBooking);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowCountBooking = $stmt->fetch();
						$countBooking = $rowCountBooking["bookingCount"];
						//===== Count Booking End =====
						//=====================================================================
						//===== Percentage of Booking ====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlPercentBooking = "SELECT round((SELECT count(bookingID) FROM booking AS BG2 WHERE serviceID = :serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day)/count(bookingID)*100, 2) AS percentBooking FROM booking AS BG1 WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlPercentBooking = "SELECT round((SELECT count(bookingID) FROM booking AS BG2 WHERE serviceID = :serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month)/count(bookingID)*100, 2) AS percentBooking FROM booking AS BG1 WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlPercentBooking = "SELECT round((SELECT count(bookingID) FROM booking AS BG2 WHERE serviceID = :serviceID AND DATE_FORMAT(date, '%Y') = :year)/count(bookingID)*100, 2) AS percentBooking FROM booking AS BG1 WHERE DATE_FORMAT(date, '%Y') = :year";
						}
						else{
							$sqlPercentBooking = "SELECT round((SELECT count(bookingID) FROM booking AS BG2 WHERE serviceID = :serviceID)/count(bookingID)*100, 2) AS percentBooking FROM booking AS BG1";
						}
						$stmt = $con->prepare($sqlPercentBooking);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowPercentBooking = $stmt->fetch();
						$percentBooking = $rowPercentBooking["percentBooking"];
						//===== End of percentage of booking =====
						//===== Count Transaction Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) as countTransaction FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) as countTransaction FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) as countTransaction FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year";
						}
						else{
							$sqlCountTransaction = "SELECT count(transactionID) as countTransaction FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID";
						}
						$stmt = $con->prepare($sqlCountTransaction);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowCountTransaction = $stmt->fetch();
						$countTransaction = $rowCountTransaction["countTransaction"];
						//===== End of Count transaction =====
						// ====================================================================
						//===== percentage of transaction =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlPercentTransaction = "SELECT round((SELECT count(transactionID) FROM transaction AS TN2 WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN2.bookingID) = 1 AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :day)/count(transactionID)*100, 2) AS percentTransaction FROM transaction AS TN1 WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlPercentTransaction = "SELECT round((SELECT count(transactionID) FROM transaction AS TN2 WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN2.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :month)/count(transactionID)*100, 2) AS percentTransaction  FROM transaction AS TN1 WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlPercentTransaction = "SELECT round((SELECT count(transactionID) FROM transaction AS TN2 WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN2.bookingID) = 1 AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year)/count(transactionID)*100, 2) AS percentTransaction FROM transaction AS TN1 WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN1.bookingID) = :year";
						}
						else{
							$sqlPercentTransaction = "SELECT round((SELECT count(transactionID) FROM transaction AS TN2 WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN2.bookingID) = :serviceID)/count(transactionID)*100, 2) AS percentTransaction  FROM transaction AS TN1";
						}
						$stmt = $con->prepare($sqlPercentTransaction);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowPercentTransaction = $stmt->fetch();
						$percentTransaction = $rowPercentTransaction["percentTransaction"];
						if($percentTransaction == NULL){
							$percentTransaction = '0.00';
						}
						//===== End of percentage of transaction =====
						//=====================================================================
						//===== Count Report Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year";
						}
						else{
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID";
						}
						$stmt = $con->prepare($sqlCountReports);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowCountReports = $stmt->fetch();
						$countReports = $rowCountReports["countReport"];
						//===== Count Report End =====
						//=====================================================================
						//===== Percentage Report Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlPercentageReport = "SELECT round((SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day)/count(reportsID)*100, 2) AS percentageReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlPercentageReport = "SELECT round((SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month)/count(reportsID)*100, 2) AS percentageReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlPercentageReport = "SELECT round((SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year)/count(reportsID)*100, 2) AS percentageReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year";
						}
						else{
							$sqlPercentageReport = "SELECT round((SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID)/count(reportsID)*100, 2) AS percentageReport FROM reports";
						}
						$stmt = $con->prepare($sqlPercentageReport);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$rowPercentageReport = $stmt->fetch();
						$percentageReport = $rowPercentageReport["percentageReport"];
						if($percentageReport == NULL){
							$percentageReport = '0.00';
						}
						//===== Percentage Report End =====
						//=====================================================================
						//===== Top 5 Customer Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
				    		$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE serviceID = 1 AND (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE serviceID = 1 AND (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE serviceID = 1 AND (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else{
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE serviceID = 1 AND (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						$stmt = $con->prepare($sqlTopCustomers);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->execute();
						$results = $stmt->fetchAll();
						$countTopCustomers = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowTopCustomers){
							$serviceTopCustomersID[$ctr] = $rowTopCustomers["customerID"];
							$serviceTopCustomersName[$ctr] = $rowTopCustomers["name"];
							$serviceTopCustomersCountAvailed[$ctr] = $rowTopCustomers["countTransacted"];
							$ctr++;
				    	}
				    	//===== Top 5 Customer End =====
				    	//=====================================================================
				    	//===== Top 5 Handyman Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
				    		$sqlTopHandyman = "SELECT count(handymanID) AS countTransacted, handymanID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = handymanID) AS name FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :day GROUP BY handymanID ORDER BY count(handymanID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopHandyman = "SELECT count(handymanID) AS countTransacted, handymanID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = handymanID) AS name FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month GROUP BY handymanID ORDER BY count(handymanID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopHandyman = "SELECT count(handymanID) AS countTransacted, handymanID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = handymanID) AS name FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year GROUP BY handymanID ORDER BY count(handymanID) DESC LIMIT 5";
						}
						else{
							$sqlTopHandyman = "SELECT count(handymanID) AS countTransacted, handymanID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = handymanID) AS name FROM transaction AS TN WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :serviceID GROUP BY handymanID ORDER BY count(handymanID) DESC LIMIT 5";
						}
						$stmt = $con->prepare($sqlTopHandyman);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$countTopHandyman = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowTopHandyman){
							$serviceTopHandymanID[$ctr] = $rowTopHandyman["handymanID"];
							$serviceTopHandymanName[$ctr] = $rowTopHandyman["name"];
							$serviceTopHandymanCountAvailed[$ctr] = $rowTopHandyman["countTransacted"];
							$ctr++;
				    	}
				    	//===== Top 5 Handyman End =====
				    	//=====================================================================
				    	//===== Top 5 Accused Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
				    		$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID AND (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else{
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT serviceID FROM booking AS BG WHERE BG.bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :serviceID GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						$stmt = $con->prepare($sqlTopAccused);
						if(isset($_GET['year']) && ($yearTrue)){
							$stmt->bindParam(':year', $_GET['year'], PDO::PARAM_INT);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$stmt->bindParam(':month', $_GET['month'], PDO::PARAM_STR);
						}
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$stmt->bindParam(':day', $_GET['day'], PDO::PARAM_STR);
						}
						$stmt->bindParam(':serviceID', $_GET['service'], PDO::PARAM_INT);
						$stmt->execute();
						$results = $stmt->fetchAll();
						$countTopAccused = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowTopAccused){
							$topAccusedID[$ctr] = $rowTopAccused["reportedID"];
							$topAccusedName[$ctr] = $rowTopAccused["accused"];
							$topAccusedCountReports[$ctr] = $rowTopAccused["countReport"];
							$ctr++;
				    	}
				    	//===== End Top 5 Accused =====

					?>
				<div class = content>
					<div class = "header">
						<form method="post" class = "dayMonthYear">
							<button type = "button" class = "view" onclick = "genPDF()">PRINT</button>
							<span class = "select">YEAR : </span>
							<select name = "changeYear" id = "changeYear" class = "optionSearch" onchange = "selectYear()">
								<option value = "none" selected>-SELECT YEAR-</option>
								<?php
									for ($i=0; $i < $ctrYear; $i++) { 
										echo '<option value = "'.$reportYear[$i].'"';
										if(isset($_GET['year'])){
											if($reportYear[$i] == $_GET['year']){
												echo ' selected ';
											}
										}
										echo '>'. $reportYear[$i] .'</option>';
									}
								?>
							</select>
							<span class = "select">MONTH : </span>
							<select name = "changeMonth" id = "changeMonth" class = "optionSearch"<?php if(isset($_GET['year']) && ($yearTrue)){ echo "onchange='selectMonth()'"; }else{ echo "disabled "; }?>>
								<option value = "none">-SELECT MONTH-</option>
								<?php
									for ($i=0; $i < $ctrMonth; $i++) { 
										echo '<option value = "'.$reportMonth[$i].'"';
										if(isset($_GET['month'])){
											if($reportMonth[$i] == $_GET['month']){
												echo ' selected ';
											}
										}
										echo '>'. $reportMonth[$i] .'</option>';
									}
								?>
							</select>
							<span class = "select">DAY : </span>
							<select name = "changeDay" id = "changeDay" class = "optionSearch" <?php if(isset($_GET['year']) && isset($_GET['month']) && ($yearTrue) && ($monthTrue)){ echo "onchange='selectDay()'"; }else{ echo "disabled"; }?>>
								<option value = "none">-SELECT DAY-</option>
								<?php
									for ($i=0; $i < $ctrDay; $i++) { 
										echo '<option value = "'.$reportDay[$i].'"';
										if(isset($_GET['day'])){
											if($reportDay[$i] == $_GET['day']){
												echo ' selected ';
											}
										}
										echo '>'. $reportDay[$i] .'</option>';
									}
								?>
							</select>
							<br><br>
							<span class = "select">SERVICE : </span>
							<select name = "changeService" id = "changeService" class = "optionSearch" onchange="selectService()">
								<?php
									for ($i=0; $i < $ctrService; $i++) { 
										echo '<option value = "'.$reportServiceID[$i].'"';
										if(isset($_GET['service'])){
											if($reportServiceID[$i] == $_GET['service']){
												echo ' selected ';
											}
										}
										echo '>'. $reportService[$i] .'</option>';
									}
								?>
							</select>
						</form>
					</div>
					<div id = "printReport">
						<div id class = "headerLogo">
							<center>
								<table class = "logoTable">
									<tr>
										<td rowspan="4" class = "logoHTd"><img id = "logo" src="Resources/sample-logo.png"></td>
										<td class = "logoTd">Find & Hire</td>
									</tr>
									<tr>
										<td class = "logoTd">Brgy. 599, Zone 59, Sta. Mesa, Manila</td>
									</tr>
									<tr>
										<td class = "logoTd">09505574502</td>
									</tr>
									<tr>
										<td class = "logoTd">findandhirehandyman@gmail.com</td>
									</tr>
								</table>
							</center>
						</div>
						<div class = "content_report content_position">
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo 'In <b>' . $_GET['month'] . " " . $_GET['day'] . ", " . $_GET['year'] . '</b>, ';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo 'In <b>' . $_GET['month'] . " " . $_GET['year'] . '</b>, ';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo 'In <b>' . $_GET['year'] . '</b>, ';
								}
								else{
									echo 'As of <b>' . date('F j, Y') . '</b>, ';
								}
							?>the <b><?php echo $serviceName; ?></b> has a total booking of <b><?php echo $countBooking; ?></b>. The percentage that a customer booked <b><?php echo $serviceName; ?></b><?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo ' this day ';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo ' this month ';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo ' this year ';
								}
							?> is <b><?php echo $percentBooking; ?>%</b>. The total transaction for <b><?php echo $serviceName; ?></b><?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo ' this day ';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo ' this month ';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo ' this year ';
								}
							?> is <b><?php echo $countTransaction; ?></b> which is <b><?php echo $percentTransaction; ?>%</b> of all transaction</b><?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo ' during this day';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo ' during this month';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo ' during this year';
								}
							?>. The total complaints for <b><?php echo $serviceName; ?></b> is <b><?php echo $countReports; ?></b> which is <b><?php echo $percentageReport; ?>%</b> of all complaints<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo ' during this day';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo ' during this month';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo ' during this year';
								}
							?>.
							<br><br>
							The <b>TOP <?php echo $countTopCustomers; ?> CUSTOMERS
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "THIS DAY";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "THIS MONTH";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "THIS YEAR";
								}
							?></b> are the following:
							<table>
								<col width="50">
								<col width="600">
								<tr>
									<th>ID</th>
									<th>Customer Name</th>
									<th>Count</th>
								</tr>
								<?php
									if($countTopCustomers > 0){
										for ($i=0; $i < $countTopCustomers; $i++) { 
											echo "<tr>";
											echo "<td>" . $serviceTopCustomersID[$i] . "</td>";
											echo "<td>" . $serviceTopCustomersName[$i] . "</td>";
											echo "<td>" . $serviceTopCustomersCountAvailed[$i] . "</td>";
											echo "</tr>";
										}
									}
									else{
										echo "<tr>";
										echo "<td colspan = '3'> No results. </td>";
										echo "</tr>";
									}
								?>
							</table><br>
							The <b>TOP <?php echo $countTopHandyman; ?> HANDYMAN
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "THIS DAY";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "THIS MONTH";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "THIS YEAR";
								}
							?></b> are the following:
							<table>
								<col width="50">
								<col width="600">
								<tr>
									<th>ID</th>
									<th>Handyman Name</th>
									<th>Count</th>
								</tr>
								<?php
									if($countTopHandyman > 0){
										for ($i=0; $i < $countTopHandyman; $i++) { 
											echo "<tr>";
											echo "<td>" . $serviceTopHandymanID[$i] . "</td>";
											echo "<td>" . $serviceTopHandymanName[$i] . "</td>";
											echo "<td>" . $serviceTopHandymanCountAvailed[$i] . "</td>";
											echo "</tr>";
										}
									}
									else{
										echo "<tr>";
										echo "<td colspan = '3'> No results. </td>";
										echo "</tr>";
									}
								?>
							</table><br>
							The <b>TOP <?php echo $countTopAccused; ?> ACCUSED
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "THIS DAY";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "THIS MONTH";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "THIS YEAR";
								}
							?></b> are the following:
							<table>
								<col width="50">
								<col width="600">
								<tr>
									<th>ID</th>
									<th>Accused</th>
									<th>Count</th>
								</tr>
								<?php
									if($countTopAccused > 0){
										for ($i=0; $i < $countTopAccused; $i++) { 
											echo "<tr>";
											echo "<td>" . $topAccusedID[$i] . "</td>";
											echo "<td>" . $topAccusedName[$i] . "</td>";
											echo "<td>" . $topAccusedCountReports[$i] . "</td>";
											echo "</tr>";
										}
									}
									else{
										echo "<tr>";
										echo "<td colspan = '3'> No results. </td>";
										echo "</tr>";
									}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function selectYear(){
				var year = document.getElementById('changeYear').value;
				var service = document.getElementById('changeService').value;
				if(year == 'none'){
					window.location.href = 'index?route=serviceReports&service='+service;
				}
				else{
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year;
				}

			}

			function selectMonth(){
				var year = document.getElementById('changeYear').value;
				var month = document.getElementById('changeMonth').value;
				var service = document.getElementById('changeService').value;
				if(month == 'none'){
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year;
				}
				else{
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year+'&month='+month;
				}
			}

			function selectDay(){
				var year = document.getElementById('changeYear').value;
				var month = document.getElementById('changeMonth').value;
				var day = document.getElementById('changeDay').value;
				var service = document.getElementById('changeService').value;
				if(day == 'none'){
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year+'&month='+month;
				}
				else{
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year+'&month='+month+'&day='+day;
				}
			}

			function selectDay(){
				var year = document.getElementById('changeYear').value;
				var month = document.getElementById('changeMonth').value;
				var day = document.getElementById('changeDay').value;
				var service = document.getElementById('changeService').value;
				if(day == 'none'){
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year+'&month='+month;
				}
				else{
					window.location.href = 'index?route=serviceReports&service='+service+'&year='+year+'&month='+month+'&day='+day;
				}
			}

			function selectService(){
				var service = document.getElementById('changeService').value;
				window.location.href = 'index?route=serviceReports&service='+service;
			}
		</script>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>

