<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
	function getRandomColor() {
        var letters = 'BCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * letters.length)];
        }
        return color;
    }
	function setRandomColor() {
		<?php //echo "<script>getRandomColor();</script>";?>
		//alert(getRandomColor());
	  //$("#colorpad").css("background-color", getRandomColor());
	}
</script>

<div id="colorpad" style="width:300px;height:300px;background-color:#000">

</div>
<button onclick="setRandomColor()">Random Color</button> -->

<!-- <!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
	// function random_color_part() {
 //   		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	// }

	// function random_color() {
	//     return random_color_part() . random_color_part() . random_color_part();
	// }
	// echo random_color();
?>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		function randomColor(){
			$rgbColor = array();
			foreach(array('r', 'g', 'b') as $color){
			    $rgbColor[$color] = mt_rand(0, 255);
			}
			$color = "'rgba(" . implode(",", $rgbColor) . ", .4)',";
			echo $color;
		}

		randomColor();

	?>
	<div style = "background-color: rgb(<?= implode(",", $rgbColor); ?>);">
		Random Color!
	</div>
</body>
</html>