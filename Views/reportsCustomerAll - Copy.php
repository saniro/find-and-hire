<?php
	$var = "This";
	$p="<html>\n
			<head>
				<title>Reports</title>\n
			</head>\n
			<body>\n
				<center><br>\n
				".$var." is the html file written by a php page.\n\n
				How do you like it?<br>
				Here is a link: <a HREF='index.html'>home</a>.\n
				</center>
			</body>\n
		</html>";
	$a = fopen("Views/print.html", 'w');
	fwrite($a, $p);
	fclose($a);
	chmod("Views/print.html", 0644);

	require 'Libraries/dompdf/autoload.inc.php';
	//Reference the Dompdf namespace
	use Dompdf\Dompdf;
	//Instatiate dompdf class
	$dompdf = new Dompdf();
	//get file
	$html = file_get_contents("Views/print/index.html");
	$dompdf->loadHtml($html);
	//Setup paper size
	$dompdf->setPaper('letter', 'portrait');
	//Render the html as pdf
	$dompdf->render();
	//output the genereated  PDF
	$dompdf->stream("Report", array("Attachment" => false));
	//unlink("print.html");
	exit(0);

?>