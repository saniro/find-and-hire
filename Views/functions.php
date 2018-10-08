<?php
	$loginErrors = array();

	function filter($data){
		$data = addslashes($data);
		//$data = mysqli_real_escape_string("", $data);
		$data = str_replace("\\\"", "", $data);
		$data = str_replace("\'", "", $data);
		return $data;
	}

	function emptyCheck($data, &$array, $name, $req){
		if(empty($data)&&$req=='true'){
			array_push($array,  $name." is required.");
			#echo "<script>alert('" . $name . " is required.');</script>";
		}
		else{
			return filter($data);
		}
	}
?>