<?php
	//require("connection.php");

	require 'Libraries/dompdf/autoload.inc.php';
	//Reference the Dompdf namespace
	use Dompdf\Dompdf;
	//Instatiate dompdf class
	$dompdf = new Dompdf();
	//get file
	$html = file_get_contents("Views/print/Find and Hire.html");
	$dompdf->loadHtml($html);
	//Setup paper size
	$dompdf->setPaper('A4', 'portrait');
	//Render the html as pdf
	$dompdf->render();
	//output the genereated  PDF
	$dompdf->stream("Report", array("Attachment" => 0));
	//unlink("print.html");
	exit(0);
?>