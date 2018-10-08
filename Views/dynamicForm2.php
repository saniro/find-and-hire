<?php
	session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.division {
			border-style: solid;
			border-width: 1px;
			margin-bottom: 10px;
			padding: 10px;
		}
	</style>
</head>
<body>
	<form method="post">
		<button name = "addDiv">Add</button>
		<button name = "removeDiv">Remove</button>
		<input type="hidden" name="route" value="df">
		<?php
			if(!isset($_SESSION['divCounter'])){
				$divCounter = 0;
			}
			else{
				$divCounter = $_SESSION['divCounter'];
			}
			for ($divX=0; $divX < $divCounter; $divX++) { 
				${'contentCounter' . $divX} = 1;
				echo ${'contentCounter' . $divX};
				?>
				<div class = "division">
					Title: <input type="text" name="name"><br>
					Type: <select>
						<option value="radio">
							RadioButton
						</option>
						<option value="checkbox">
							Checkbox
						</option>
						<option value="dropdown">
							Dropdown
						</option>
					</select><br>
					Options:<br>
					<?php


						if(!isset($_SESSION['contentCounterAdd' . $divX])){
							${'contentCounter' . $divX} = 1;
						}
						else{
							${'contentCounter' . $divX} = $_SESSION['contentCounterAdd' . $divX];
						}

						for (${'contentX' . $divX} = 0; ${'contentX' . $divX} < ${'contentCounter' . $divX}; ${'contentX' . $divX}++) { 
						?>
							<input type="text" name=""><br>
						<?php
					} ?>
					<br>
					<button name = "addComponent<?php echo $divX?>" id = "<?php echo $divX;?>">Add</button><button name = "removeComponent<?php echo $divX?>" id = "<?php echo $divX;?>">Remove</button> 
				</div>
				<?php
			}
		?>
	</form>
</body>
</html>

<?php
	if (isset($_POST["addDiv"])) {
		$divCounterAdd = $divCounter + 1;
		$_SESSION['divCounter'] = $divCounterAdd;
		echo "<script>window.location.href = 'index.php?route=df';</script>";
	}
	if (isset($_POST["removeDiv"])) {
		if($divCounter == 0){

		}
		else{
			$divRemoveNo = $divCounter - 1;
			$_SESSION['contentCounterAdd' . $divRemoveNo] = 1;
			$divCounterAdd = $divCounter - 1;
			$_SESSION['divCounter'] = $divCounterAdd;
			echo "<script>window.location.href = 'index.php?route=df';</script>";

		}
	}
	for ($post=0; $post < $divCounter; $post++) { 
		if (isset($_POST["addComponent".$post])) {
			${'contentCounterAdd' . $post} = ${'contentCounter' . $post} + 1;
			$_SESSION['contentCounterAdd' . $post] = ${'contentCounterAdd' . $post};
			echo "<script>window.location.href = 'index.php?route=df';</script>";
		}

		if (isset($_POST["removeComponent".$post])) {
			if(${'contentCounter' . $post} == 1){

			}
			else{
				${'contentCounterAdd' . $post} = ${'contentCounter' . $post} - 1;
				$_SESSION['contentCounterAdd' . $post] = ${'contentCounterAdd' . $post};
				echo "<script>window.location.href = 'index.php?route=df';</script>";
			}
		}
	}
?>
