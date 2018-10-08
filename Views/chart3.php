<?php
	require("connection.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chart</title>
	<script type="text/javascript" src="Libraries/chart/js/Chart.min.js"></script>
	<link rel="stylesheet" type="text/css" href="Libraries/chart/css/bootstrap.min.css">
</head>
<!-- 'rgba(225, 151, 76, 0.6)',
						'rgba(132, 186, 91, 0.6)',
						'rgba(211, 94, 96, 0.6)',
						'rgba(128, 144, 171, 0.6)',
						'rgba(144, 103, 167, 0.6)' -->
<body>
	<div class = "container">
		<canvas id = "myChart">
			
		</canvas>
	</div>
	<?php
		require("connection.php");

		function randomColor(){
			$rgbColor = array();
			foreach(array('r', 'g', 'b') as $color){
			    $rgbColor[$color] = mt_rand(0, 255);
			}
			$color = "'rgba(" . implode(",", $rgbColor) . ", .4)',";
			echo $color;
		}
		
		$sqlServiceChart = "SELECT serviceID, name, (SELECT count(transactionID) FROM transaction AS TN WHERE TN.serviceID = SS.serviceID) AS countAvailed FROM services AS SS WHERE flag = 1";
		$stmt = $con->prepare($sqlServiceChart);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$rowCount = $stmt->rowCount();
		$ctr = 0;
		foreach($results as $rowServiceChart){
			$serviceChartServiceID[$ctr] = $rowServiceChart["serviceID"];
			$serviceChartServiceName[$ctr] = $rowServiceChart["name"];
			$serviceChartServiceCountAvailed[$ctr] = $rowServiceChart["countAvailed"];
			$ctr++;
    	}
	?>
	<script type="text/javascript">
		var myChart = document.getElementById("myChart").getContext('2d');
		var massPopChart = new Chart(myChart, {
			type: 'pie',
			data: {
				datasets: [{
					label: 'Service',
					data: [
						<?php for ($i=0; $i < $rowCount; $i++) { 
							echo $serviceChartServiceCountAvailed[$i].", ";
						}?>
					],
					backgroundColor: [
					<?php for ($i=0; $i < $rowCount; $i++) { 
						echo randomColor();
					}?>
					],
					borderWidth: 1,
					borderColor:'#777',
					hoverBorderWidth: 3,
					hoverBorderColor: 'black'
					}],
				labels: [
					<?php for ($i=0; $i < $rowCount; $i++) { 
						echo "'" . $serviceChartServiceName[$i] . "', ";
					}?>
				]
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
			        		left:50,
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
</body>
</html>