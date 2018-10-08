<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="Libraries/chart/js/Chart.min.js"></script>
		<link rel="stylesheet" type="text/css" href="Libraries/chart/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/print.css">
		<script type="text/javascript" src = "Libraries/jquery/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src = "Libraries/jsPDF/dist/jspdf.min.js"></script>
		<script type="text/javascript" src = "Libraries/htmlCanvas/html2canvas.min.js"></script>
		<script type="text/javascript">
			// function genPDF(){
			// 	var canvas = document.getElementById("services-availed");
			// 	var imgData = canvas.toDataURL("image/jpeg", 1.0);
			// 	var pdf = new jsPDF();
			// 	pdf.addImage(imgData, 'JPEG', 0, 0);
			// 	pdf.save("download.pdf");
			// }
			// var doc = new jsPDF();
			// var specialElementHandlers = {
			// 	'#editor': function(element, renderer){
			// 		return true;
			// 	}
			// }

			// $('#print').click(function(){
			// 	doc.fromHTML($('#wrapper').html(), 15, 15, {
			// 		'width': 170,
			// 			'elementHandlers': specialElementHandlers
			// 	});
			// 	doc.save('sample.pdf');
			// 	console.log('hey');
			// })

			function print(){
				html2canvas(document.querySelector("#wrapper")).then(canvas => {
				    document.body.appendChild(canvas)
				});
			}

		</script>
</head>
<body>
	<button id = "print" onclick = "print();">Print</button>
	<div id = "wrapper" class = wrapper>
		<?php
		require("connection.php");
			$sqlInfoCustomerNo = "SELECT count(userID) as customerNo FROM users WHERE type = 1";
			$stmt = $con->prepare($sqlInfoCustomerNo);
			$stmt->execute();
			$rowInfoCustomerNo = $stmt->fetch();
			$infoCustomerNo = $rowInfoCustomerNo["customerNo"];

			$sqlInfoHandymanNo = "SELECT count(userID) as handymanNo FROM users WHERE type = 2";
			$stmt = $con->prepare($sqlInfoHandymanNo);
			$stmt->execute();
			$rowInfoHandymanNo = $stmt->fetch();
    		$infoHandymanNo = $rowInfoHandymanNo["handymanNo"];

    		$sqlInfoBookingNo = "SELECT count(bookingID) as bookingNo FROM booking";
			$stmt = $con->prepare($sqlInfoBookingNo);
			$stmt->execute();
			$rowInfoBookingNo = $stmt->fetch();
			$infoBookingNo = $rowInfoBookingNo["bookingNo"];

			$sqlInfoTransactionNo = "SELECT count(transactionID) as transactionNo FROM transaction";
			$stmt = $con->prepare($sqlInfoTransactionNo);
			$stmt->execute();
			$rowInfoTransactionNo = $stmt->fetch();
			$infoTransactionNo = $rowInfoTransactionNo["transactionNo"];

			$sqlInfoReportsNo = "SELECT count(reportsID) as reportsNo FROM reports";
			$stmt = $con->prepare($sqlInfoReportsNo);
			$stmt->execute();
			$rowInfoReportsNo = $stmt->fetch();
			$infoReportsNo = $rowInfoReportsNo["reportsNo"];

			$sqlBookingThisDay = "SELECT count(bookingID) as bookThisDay FROM booking AS BG WHERE DATE_FORMAT(date, '%Y-%m-%d') = CURDATE()";
			$stmt = $con->prepare($sqlBookingThisDay);
			$stmt->execute();
			$rowBookingThisDay = $stmt->fetch();
			$bookingThisDay = $rowBookingThisDay["bookThisDay"];

			$sqlAveBooking = "SELECT count(bookingID)/DATEDIFF(CURDATE(), MIN(date)) AS aveBook FROM booking AS BG";
			$stmt = $con->prepare($sqlAveBooking);
			$stmt->execute();
			$rowAveBooking = $stmt->fetch();
			$aveBooking = $rowAveBooking["aveBook"];

			$sqlTransactionThisDay = "SELECT count(transactionID) as tranThisDay FROM transaction AS TN WHERE (SELECT DATE_FORMAT(date, '%Y-%m-%d') FROM booking AS BG WHERE BG.bookingID = TN.bookingID) = CURDATE()";
			$stmt = $con->prepare($sqlTransactionThisDay);
			$stmt->execute();
			$rowTransactionThisDay = $stmt->fetch();
			$transactionThisDay = $rowTransactionThisDay["tranThisDay"];

			$sqlAveTransaction = "SELECT count(transactionID)/(SELECT DATEDIFF(CURDATE(), MIN(date)) FROM booking AS BG) AS aveTran FROM transaction AS TN";
			$stmt = $con->prepare($sqlAveTransaction);
			$stmt->execute();
			$rowAveTransaction = $stmt->fetch();
			$aveTransaction = $rowAveTransaction["aveTran"];

			$sqlOpinion = "SELECT (SELECT name FROM services AS SS WHERE SS.serviceID = BG.serviceID) AS serviceName, count(bookingID) AS bookCount, (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) AS bookingWithoutTran FROM booking AS BG GROUP BY serviceID ORDER BY (count(bookingID)-count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID))) DESC, count(bookingID) DESC LIMIT 1";
			$stmt = $con->prepare($sqlOpinion);
			$stmt->execute();
			$rowOpinion = $stmt->fetch();
			$mostAvailed = $rowOpinion["serviceName"];

			$sqlReportsThisDay = "SELECT count(reportsID) as reportThisDay FROM reports WHERE DATE_FORMAT(date, '%Y-%m-%d') = CURDATE()";
			$stmt = $con->prepare($sqlReportsThisDay);
			$stmt->execute();
			$rowReportsThisDay = $stmt->fetch();
			$reportsThisDay = $rowReportsThisDay["reportThisDay"];

			$sqlAveReport = "SELECT count(reportsID)/(SELECT DATEDIFF(CURDATE(), MIN(date)) FROM booking AS BG) AS aveRep FROM reports";
			$stmt = $con->prepare($sqlAveReport);
			$stmt->execute();
			$rowAveReport = $stmt->fetch();
			$aveReport = $rowAveReport["aveRep"];

			function randomColor(){
				$rgbColor = array();
				foreach(array('r', 'g', 'b') as $color){
				    $rgbColor[$color] = mt_rand(0, 255);
				}
				$color = "'rgba(" . implode(",", $rgbColor) . ", .4)',";
				echo $color;
			}
			
			$sqlServiceChart = "SELECT serviceID, name, (SELECT count(bookingID) FROM booking AS BG WHERE BG.serviceID = SS.serviceID) AS countAvailed FROM services AS SS WHERE flag = 1";
			$stmt = $con->prepare($sqlServiceChart);
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

	    	$sqlLastSixMonths = "SELECT DATE_FORMAT(date, '%M') AS date, count(bookingID) AS bookingCount, count((SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID)) AS tranCount, count((SELECT reportsID FROM reports AS RS WHERE RS.transactionID = (SELECT transactionID FROM transaction AS TN WHERE TN.bookingID = BG.bookingID) GROUP BY serviceID)) AS repCount FROM booking AS BG GROUP BY DATE_FORMAT(date, '%M') ORDER BY date DESC";
			$stmt = $con->prepare($sqlLastSixMonths);
			$stmt->execute();
			$results = $stmt->fetchAll();
			$rowCountTransaction = $stmt->rowCount();
			$ctr = 0;
			foreach($results as $rowLastSixMonths){
				$transactionChartTranMonth[$ctr] = $rowLastSixMonths["date"];
				$transactionChartTranCount[$ctr] = $rowLastSixMonths["tranCount"];
				$transactionChartRepCount[$ctr] = $rowLastSixMonths["repCount"];
				$transactionChartBookCount[$ctr] = $rowLastSixMonths["bookingCount"];
				$ctr++;
	    	}

	    	$sqlLastSixMonths = ""
		?>
	<div class = content>
		<div class = "totalCustomer div">
			TOTAL CUSTOMERS<br>
			________________________<br>
			<h1><?php echo $infoCustomerNo;?></h1>
		</div>
		<div class = "totalHandyman div">
			TOTAL HANDYMEN<br>
			________________________<br>
			<h1><?php echo $infoHandymanNo;?></h1>
		</div>
		<div class = "totalBooking div">
			TOTAL BOOKINGS<br>
			________________________<br>
			<h1><?php echo $infoBookingNo;?></h1>
		</div>
		<div class = "totalTransaction div">
			TOTAL TRANSACTIONS<br>
			________________________<br>
			<h1><?php echo $infoTransactionNo;?></h1>
		</div>
		<div class = "totalReports div">
			TOTAL REPORTS<br>
			________________________<br>
			<h1><?php echo $infoReportsNo;?></h1>
		</div>
		<div class = "bookingThisDay div">
			BOOKING THIS DAY<br>
			________________________<br>
			<h1><?php echo $bookingThisDay;?></h1>
		</div>
		<div class = "aveBooking div">
			AVE BOOKING / DAY<br>
			________________________<br>
			<h1><?php echo $aveBooking;?></h1>
		</div>
		<div class = "transactionThisDay div">
			TRANSACTIONS THIS DAY<br>
			________________________<br>
			<h1><?php echo $transactionThisDay;?></h1>
		</div>
		<div class = "aveTransaction div">
			AVE TRANSACTION / DAY<br>
			________________________<br>
			<h1><?php echo $aveTransaction;?></h1>
		</div>
		<div class = "serviceSuggestion div">
			You should hire more<br>
			handyman for<br>
			<h1><?php echo $mostAvailed;?></h1>
		</div>
		<div class = "reportsThisDay div">
			REPORTS THIS DAY<br>
			________________________<br>
			<h1><?php echo $reportsThisDay;?></h1>
		</div>
		<div class = "aveReports div">
			AVE REPORT / DAY<br>
			________________________<br>
			<h1><?php echo $aveReport;?></h1>
		</div>
	</div>
	<div class = "services-availed">
		<canvas id = "services-availed"></canvas>
	</div>
	<script type="text/javascript">
		var myChart = document.getElementById("services-availed").getContext('2d');
		var massPopChart = new Chart(myChart, {
			type: 'pie',
			data: {
				datasets: [{
					label: 'Service',
					data: [
						<?php for ($i=0; $i < $rowCountService; $i++) { 
							echo $serviceChartServiceCountAvailed[$i].", ";
						}?>
					],
					backgroundColor: [
					<?php for ($i=0; $i < $rowCountService; $i++) { 
						echo randomColor();
					}?>
					],
					borderWidth: 1,
					borderColor:'white',
					hoverBorderWidth: 2,
					hoverBorderColor: '#777'
					}],
				labels: [
					<?php for ($i=0; $i < $rowCountService; $i++) { 
						echo "'" . $serviceChartServiceName[$i] . "', ";
					}?>
				]
				},
				options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                	display:false,
			                    beginAtZero:true
			                },
			                gridLines: {
			                	display:false,
			                	drawTicks:false,
			                	offsetGridLines:0
			                }
			            }]
			        },
			        title:{
			        	display:true,
			        	text:'Services Availed',
			        	fontSize:25
			        },
			        legend:{
			        	display:true,
			        	position:'right',
			        	labels:{
			        		fontColor: '#000'
			        	}
			        },
			        layout:{
			        	padding:{
			        		left:0,
			        		right:0,
			        		bottom:0,
			        		top:0
			        	}
			        },
			        tooltips:{
			        	enabled:true
			        }
			    }
			});
	</script>

	<div class = "report-transaction">
		<canvas id="report-transaction"></canvas>
	</div>
	<script>
		var reportChart = document.getElementById("report-transaction").getContext('2d');
		var massPopChart = new Chart(reportChart, {
			type: 'line',
			data: {
				labels: [
					<?php for ($i=0; $i < $rowCountTransaction; $i++) { 
						echo "'" . $transactionChartTranMonth[$i] . "', ";
					}?>
				],
				datasets: [
				{
					label: 'Booking',
					fill: false,
					data: [
						<?php for ($i=0; $i < $rowCountTransaction; $i++) { 
							echo $transactionChartBookCount[$i].", ";
						}?>
					],
					borderWidth: 3,
					borderColor:'rgba(135, 197, 64, .9)',
					backgroundColor:'rgba(135, 197, 64, .9)'
				},{
					label: 'Transaction',
					fill: false,
					data: [
						<?php for ($i=0; $i < $rowCountTransaction; $i++) { 
							echo $transactionChartTranCount[$i].", ";
						}?>
					],
					borderWidth: 3,
					borderColor:'rgba(52, 152, 219, .9)',
					backgroundColor:'rgba(52, 152, 219, .9)'
				},{
					label: 'Reports',
					fill: false,
					data: [
						<?php for ($i=0; $i < $rowCountTransaction; $i++) { 
							echo $transactionChartRepCount[$i].", ";
						}?>
					],
					borderWidth: 3,
					borderColor:'rgba(231, 76, 60, .9)',
					backgroundColor:'rgba(231, 76, 60, .9)'
				}]
			},
			options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        },
		        title:{
		        	display:true,
		        	text:'Transactions : Reports',
		        	fontSize:25
		        },
		        legend:{
		        	display:true,
		        	position:'right',
		        	labels:{
		        		fontColor: '#000'
		        	}
		        },
		        layout:{
		        	padding:{
		        		left:0,
		        		right:0,
		        		bottom:0,
		        		top:0
		        	}
		        },
		        tooltips:{
		        	enabled:true,
		        	mode:'index',
		        	intersect:false
		        }
		    }
		});
	</script>
</div>
</body>
</html>