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
	<script type="text/javascript">
		var myChart = document.getElementById("myChart").getContext('2d');
		var massPopChart = new Chart(myChart, {
			type: 'bar',
			data: {
				labels: ['Quezon City', 'Manila', 'Caloocan City', 'Budta', 'Davao'],
				datasets: [{
					label: 'Current Population',
					data: [2761720, 1600000, 1500000, 1273715, 1212504],
					backgroundColor: 'rgba(114, 147, 203, 0.6)',
					borderWidth: 1,
					borderColor:'#777',
					hoverBorderWidth: 3,
					hoverBorderColor: 'black'
					},{
						label: 'Previous Population',
						data: [2700000, 1500000, 1300000, 1100000, 1100000],
						backgroundColor: 'rgba(225, 151, 76, 0.6)',
						borderWidth: 1,
						borderColor:'#777',
						hoverBorderWidth: 3,
						hoverBorderColor: 'black'
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
			        	text:'Largest Cities In Philippines',
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