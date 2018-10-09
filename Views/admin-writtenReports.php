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
		<link rel="stylesheet" type="text/css" href="Styles/admin-writtenReportsStyles.css">
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
						//===== Count Booking Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year";
						}
						else{
							$sqlCountBooking = "SELECT count(bookingID) AS bookingCount FROM booking AS BG";
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
						$stmt->execute();
						$rowCountBooking = $stmt->fetch();
						$countBooking = $rowCountBooking["bookingCount"];
						//===== Count Booking End =====
						//=====================================================================
						//===== Average Booking Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlAveBooking = "SELECT count(bookingID)/1 AS aveBook FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlAveBooking = "SELECT count(bookingID)/DAY(LAST_DAY(date)) AS aveBook FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlAveBooking = "SELECT count(bookingID)/DAYOFYEAR(LAST_DAY(DATE_ADD(date, INTERVAL 12-MONTH(date) MONTH))) AS aveBook FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year";
						}
						else{
							$sqlAveBooking = "SELECT count(bookingID)/DATEDIFF(CURDATE(), MIN(date)) AS aveBook FROM booking AS BG";
						}
						$stmt = $con->prepare($sqlAveBooking);
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
						$rowAveBooking = $stmt->fetch();
						$aveBooking = $rowAveBooking["aveBook"];
						//===== Average Booking End =====
						//=====================================================================
						//===== Count Transaction Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) AS countTransaction FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) AS countTransaction FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountTransaction = "SELECT count(transactionID) AS countTransaction FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year";
						}
						else{
							$sqlCountTransaction = "SELECT count(transactionID) as countTransaction FROM transaction AS TN";
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
						$stmt->execute();
						$rowCountTransaction = $stmt->fetch();
						$countTransaction = $rowCountTransaction["countTransaction"];
						//===== Count Transaction End =====
						//=====================================================================
						//===== Average Transaction Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlAveTransaction = "SELECT count(transactionID)/1 AS aveTran FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlAveTransaction = "SELECT count(transactionID)/DAY(LAST_DAY((SELECT date FROM booking AS BG WHERE BG.bookingID = TN.bookingID))) AS aveTran FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlAveTransaction = "SELECT count(transactionID)/DAYOFYEAR(LAST_DAY(DATE_ADD((SELECT date FROM booking AS BG WHERE BG.bookingID = TN.bookingID), INTERVAL 12-MONTH((SELECT date FROM booking AS BG WHERE BG.bookingID = TN.bookingID)) MONTH))) AS aveTran FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = :year";
						}
						else{
							$sqlAveTransaction = "SELECT count(transactionID)/(SELECT DATEDIFF(CURDATE(), MIN(date)) FROM booking AS BG) AS aveTran FROM transaction AS TN";
						}
						$stmt = $con->prepare($sqlAveTransaction);
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
						$rowAveTransaction = $stmt->fetch();
						$aveTransaction = $rowAveTransaction["aveTran"];
						//===== Average Transaction End =====
						//=====================================================================
						//===== Opinion Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlOpinion = "SELECT (SELECT name FROM services AS SS WHERE SS.serviceID = BG.serviceID) AS serviceName, count(bookingID) AS bookCount, (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) AS bookingWithoutTran FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day GROUP BY serviceID ORDER BY (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) DESC, count(bookingID) DESC LIMIT 1";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlOpinion = "SELECT (SELECT name FROM services AS SS WHERE SS.serviceID = BG.serviceID) AS serviceName, count(bookingID) AS bookCount, (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) AS bookingWithoutTran FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month GROUP BY serviceID ORDER BY (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) DESC, count(bookingID) DESC LIMIT 1";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlOpinion = "SELECT (SELECT name FROM services AS SS WHERE SS.serviceID = BG.serviceID) AS serviceName, count(bookingID) AS bookCount, (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) AS bookingWithoutTran FROM booking AS BG WHERE DATE_FORMAT(date, '%Y') = :year GROUP BY serviceID ORDER BY (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) DESC, count(bookingID) DESC LIMIT 1";
						}
						else{
							$sqlOpinion = "SELECT (SELECT name FROM services AS SS WHERE SS.serviceID = BG.serviceID) AS serviceName, count(bookingID) AS bookCount, (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) AS bookingWithoutTran FROM booking AS BG GROUP BY serviceID ORDER BY (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) DESC, count(bookingID) DESC LIMIT 1";
						}
						$stmt = $con->prepare($sqlOpinion);
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
						$rowOpinion = $stmt->fetch();
						$mostAvailed = $rowOpinion["serviceName"];
						//===== Opinion End =====
						//=====================================================================
						//===== Count Report Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year";
						}
						else{
							$sqlCountReports = "SELECT count(reportsID) AS countReport FROM reports AS RS";
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
						$stmt->execute();
						$rowCountReports = $stmt->fetch();
						$countReports = $rowCountReports["countReport"];
						//===== Count Report End =====
						//=====================================================================
						//===== Average Report Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlAveReport = "SELECT count(reportsID)/1 AS aveRep FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlAveReport = "SELECT count(reportsID)/DAY(LAST_DAY((SELECT date FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)))) AS aveRep FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlAveReport = "SELECT count(reportsID)/DAYOFYEAR(LAST_DAY(DATE_ADD((SELECT date FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)), INTERVAL 12-MONTH((SELECT date FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID))) MONTH))) AS aveRep FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking AS BG WHERE BG.bookingID = (SELECT transactionID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year";
						}
						else{
							$sqlAveReport = "SELECT count(reportsID)/(SELECT DATEDIFF(CURDATE(), MIN(date)) FROM booking AS BG) AS aveRep FROM reports";
						}
						$stmt = $con->prepare($sqlAveReport);
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
						$rowAveReport = $stmt->fetch();
						$aveReport = $rowAveReport["aveRep"];
						//===== Average Report End =====
						function randomColor(){
							$rgbColor = array();
							foreach(array('r', 'g', 'b') as $color){
							    $rgbColor[$color] = mt_rand(0, 255);
							}
							$color = "'rgba(" . implode(",", $rgbColor) . ", .4)',";
							echo $color;
						}
						//===== Service Chart Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlServiceChart = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) <> 0";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlServiceChart = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) <> 0";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlServiceChart = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) <> 0";
						}
						else{
							$sqlServiceChart = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) <> 0";
						}
						$stmt = $con->prepare($sqlServiceChart);
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
						$rowCountService = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowServiceChart){
							$serviceChartServiceID[$ctr] = $rowServiceChart["serviceID"];
							$serviceChartServiceName[$ctr] = $rowServiceChart["name"];
							$serviceChartServiceCountAvailed[$ctr] = $rowServiceChart["countAvailed"];
							$ctr++;
				    	}
				    	//===== Service Chart End =====
				    	//=====================================================================
				    	//===== Service Transaction Start =====
						if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlServiceTransaction = "SELECT serviceID, name, (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) AS countAvailed FROM services AS SS WHERE (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) <> 0";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlServiceTransaction = "SELECT serviceID, name, (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) AS countAvailed FROM services AS SS WHERE (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) <> 0";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlServiceTransaction = "SELECT serviceID, name, (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) AS countAvailed FROM services AS SS WHERE (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year)";
						}
						else{
							$sqlServiceTransaction = "SELECT serviceID, name, (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID) AS countAvailed FROM services AS SS WHERE (SELECT count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS transactionID FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND BG.serviceID = SS.serviceID)";
						}
						$stmt = $con->prepare($sqlServiceTransaction);
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
						$rowCountServiceTransaction = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowServiceTransaction){
							$serviceTransactionServiceID[$ctr] = $rowServiceTransaction["serviceID"];
							$serviceTransactionServiceName[$ctr] = $rowServiceTransaction["name"];
							$serviceTransactionServiceCountAvailed[$ctr] = $rowServiceTransaction["countAvailed"];
							$ctr++;
				    	}
				    	//===== Service Chart End =====
				    	//=====================================================================
				    	//===== Top 5 Service Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
							$sqlTopServices = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) <> 0 ORDER BY (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopServices = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) <> 0 ORDER BY (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopServices = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) <> 0 ORDER BY (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID AND BG.serviceID = SS.serviceID AND DATE_FORMAT(date, '%Y') = :year) DESC LIMIT 5";
						}
						else{
							$sqlTopServices = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) AS countAvailed FROM services AS SS WHERE (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) <> 0 ORDER BY (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) DESC LIMIT 5";
						}
						$stmt = $con->prepare($sqlTopServices);
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
						$countTopServices = $stmt->rowCount();
						$ctr = 0;
						foreach($results as $rowTopServices){
							$serviceTopServicesID[$ctr] = $rowTopServices["serviceID"];
							$serviceTopServicesName[$ctr] = $rowTopServices["name"];
							$serviceTopServicesCountAvailed[$ctr] = $rowTopServices["countAvailed"];
							$ctr++;
				    	}
				    	//===== Top 5 Service End =====
				    	//=====================================================================
				    	//===== Top 5 Customer Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
				    		$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month AND DATE_FORMAT(date, '%d') = :day GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year AND DATE_FORMAT(date, '%M') = :month GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL AND DATE_FORMAT(date, '%Y') = :year GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
						}
						else{
							$sqlTopCustomers = "SELECT customerID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS name, count(bookingID) countTransacted FROM booking AS BG WHERE (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) IS NOT NULL GROUP BY customerID ORDER BY count(bookingID) DESC LIMIT 5";
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
				    	//===== Top 5 Accused Start =====
				    	if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
				    		$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month AND (SELECT DATE_FORMAT(date, '%d') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :day GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year AND (SELECT DATE_FORMAT(date, '%M') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :month GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else if(isset($_GET['year']) && ($yearTrue)){
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS WHERE (SELECT DATE_FORMAT(date, '%Y') FROM booking WHERE bookingID = (SELECT bookingID FROM transaction AS TN WHERE TN.transactionID = RS.transactionID)) = :year GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
						}
						else{
							$sqlTopAccused = "SELECT count(reportsID) AS countReport, reportedID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users WHERE userID = reportedID) AS accused FROM reports AS RS GROUP BY reportedID ORDER BY count(reportsID) DESC LIMIT 5";
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
									echo 'In ' . $_GET['month'] . " " . $_GET['day'] . ", " . $_GET['year'] . ', ';
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo 'In ' . $_GET['month'] . " " . $_GET['year'] . ', ';
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo 'In <b>' . $_GET['year'] . '</b>, ';
								}
								else{
									echo 'As of <b>' . date('F j, Y') . '</b>, ';
								}
							?>
							the total bookings are <b><?php echo $countBooking;?></b>. In those <b><?php echo $countBooking;?></b> bookings
							<?php
								for ($i=0; $i < $rowCountService; $i++) { 
									echo ', <b>' . $serviceChartServiceName[$i] . '</b> is <b>' . $serviceChartServiceCountAvailed[$i] . '</b>';
								}
							?>. The average booking per 
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "day";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "month";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "year";
								}
								else{
									echo "day";
								}
							?> is <b><?php echo $aveBooking;?></b>. In those <b><?php echo $countBooking;?></b> bookings, <b><?php echo $countTransaction;?></b> do have transactions or accepted. In those <b><?php echo $countTransaction;?></b> transactions<?php
								for ($i=0; $i < $rowCountServiceTransaction; $i++) { 
									echo ', <b>' . $serviceTransactionServiceName[$i] . '</b> is <b>' . $serviceTransactionServiceCountAvailed[$i] . '</b>';
								}
							?>. The average transaction per 
							<?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "day";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "month";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "year";
								}
								else{
									echo "day";
								}
							?> is <b><?php echo $aveTransaction;?></b>. In those <b><?php echo $countTransaction;?></b> transactions, <b><?php echo $countReports; ?></b> is the total of complaints. The average complaints per <?php
								if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue) && isset($_GET['day']) && ($dayTrue)){
									echo "day";
								}
								else if(isset($_GET['year']) && ($yearTrue) && isset($_GET['month']) && ($monthTrue)){
									echo "month";
								}
								else if(isset($_GET['year']) && ($yearTrue)){
									echo "year";
								}
								else{
									echo "day";
								}
							?> is <b><?php echo $aveReport;?></b>. The booking, transaction, and report are the following: BOOKING is <b><?php echo $countBooking;?></b>. TRANSACTION is <?php echo '<b>'.$countTransaction . "</b>"; ?>. REPORT is <?php echo '<b>'.$countReports . '</b>'; ?>.<br><br>
							<b>You should hire more handyman for <?php echo $mostAvailed;?>.</b>
							<br><br>
							The <b>TOP <?php echo $countTopServices; ?> SERVICES
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
									<th>Name</th>
									<th>Count</th>
								</tr>
								<?php
									if($countTopServices > 0){
										for ($i=0; $i < $countTopServices; $i++) { 
											echo "<tr>";
											echo "<td>" . $serviceTopServicesID[$i] . "</td>";
											echo "<td>" . $serviceTopServicesName[$i] . "</td>";
											echo "<td>" . $serviceTopServicesCountAvailed[$i] . "</td>";
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
									<th>Service Name</th>
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
				if(year == 'none'){
					window.location.href = 'index?route=writtenReports';
				}
				else{
					window.location.href = 'index?route=writtenReports&year='+year;
				}

			}

			function selectMonth(){
				var year = document.getElementById('changeYear').value;
				var month = document.getElementById('changeMonth').value;
				if(month == 'none'){
					window.location.href = 'index?route=writtenReports&year='+year;
				}
				else{
					window.location.href = 'index?route=writtenReports&year='+year+'&month='+month;
				}
			}

			function selectDay(){
				var year = document.getElementById('changeYear').value;
				var month = document.getElementById('changeMonth').value;
				var day = document.getElementById('changeDay').value;
				if(day == 'none'){
					window.location.href = 'index?route=writtenReports&year='+year+'&month='+month;
				}
				else{
					window.location.href = 'index?route=writtenReports&year='+year+'&month='+month+'&day='+day;
				}
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

