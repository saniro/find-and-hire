<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src = "Libraries/jsPDF/dist/jspdf.min.js"></script>
	<script type="text/javascript" src = "Libraries/htmlCanvas/html2canvas.js"></script>
	<script type="text/javascript">
		function genPDF(){
			html2canvas(document.body, {
				onrendered: function (canvas){
					var img = canvas.toDataURL("image/url");
					var doc = new jsPDF();
					doc.addImage(img, 'JPEG', 20, 20);
					doc.save('test.pdf');
				}
			})
			.then((canvas) => console.log(canvas));
		}
	</script>
</head>
<body>
	<a href = "javascript:genPDF()">print</a>
	<p>hello</p>
</body>
</html>