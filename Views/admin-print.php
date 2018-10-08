<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-profilePictureStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-profilePictureModalStyles.css">
	</head>
	<body>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<div class = "sideNavigation">
			<?php
				require("admin-sidebar.php");
				$sqlGetDate = "SELECT DATE_FORMAT(min(date), '%Y-%m-%d') AS minDate FROM booking";

				$stmt = $con->prepare($sqlGetDate);
				//$stmt->bindParam(':handymanID', $_GET['handymanID'], PDO::PARAM_INT);
				$stmt->execute();
				$rowGetDate = $stmt->fetch();

				$getDate = $rowGetDate["minDate"];
			?>
		</div>
		<div class = wrapper>
			<div class = "contents">
				<div class= "title"> Change Profile Picture </div>
				<table id = actionTable>
					<col width="650">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<form method="post">
										<ul class = "nothing">
		  									<li class="dropdownSort">
		    									<a href="javascript:void(0)" class="dropbtnSort">
													<div id='addOptions' class = "print" >
														<img class = "iconProf" src="Resources/printer.png">PRINT
													</div>
		    									</a>
		  									</li>
		  									<li class="dropdownSort">
		    									<a href="javascript:void(0)" class="dropbtnSort">
													<div id='addOptions' class = "dateInput">
														Starting Date<input type="date" name="" value = "<?php echo $getDate;?>">
													</div>

		    									</a>
		  									</li>
		  									<li class="dropdownSort">
		    									<a href="javascript:void(0)" class="dropbtnSort">
													<div id='addOptions' class = "dateInput">
														End Date<input type="date" name="" value = "<?php echo date("Y-m-d"); ?>">
													</div>
		    									</a>
		  									</li>
										</ul>
									</form>
								</div>
							</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</body>
	<?php
		$p="hey";
		require 'Libraries/dompdf/autoload.inc.php';
		//Reference the Dompdf namespace
		use Dompdf\Dompdf;
		//Instatiate dompdf class
		$dompdf = new Dompdf();
		//get file
		$dompdf->loadHtml($p);
		//Setup paper size
		$dompdf->setPaper('letter', 'portrait');
		//Render the html as pdf
		$dompdf->render();
		//output the genereated  PDF
		$dompdf->stream("Report", array("Attachment" => false));
		//unlink("print.html");
		exit(0);
	?>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>