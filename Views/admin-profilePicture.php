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
		<script type="text/javascript" src = "Libraries/jquery/jquery.min.js"></script>
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
									<ul class = "nothing">
	  									<li class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
												<div id='addOptions' class = "changeProfile" onclick="changeProfile()">
													<img class = "iconProf" src="Resources/changePicture.png">CHANGE PROFILE
												</div>
	    									</a>
	  									</li>
									</ul>
								</div>
							</th>
						</tr>
					</thead>
				</table>
				<div class = "viewPictureDiv">
					<center>
					<img class = "viewPicture" src="<?php echo $profilePic;?>"/>
					</center>
				</div>
			</div>
		</div>
		<div id="viewAddModal" class="viewAddModal">
			<div class="viewAddModal-content">
				<span class="viewAddClose">&times;</span>
				<div class = "details">
					<div class = "titleDetails"><b>Change Profile</b></div>
					<div>
						<form method="post" enctype="multipart/form-data">
							<input type="hidden" id = "rowCounter" name="rowCounter" readonly>
							<div id="content">
							<input type='file' class = "inputfile" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" name="profile" />
							<center><img id="blah" class = "profile-preview" src="Resources/noimage.png" alt="your image" /></center>
							</div>
							<div class = buttonSubmit>
								<button type="submit" class="addSubmit" name="submit">UPDATE</button>
	  						</div>
						</form>
					</div>			
				</div>
			</div>
		</div>
		<?php
			if (isset($_POST['submit'])) {
				if($_FILES['profile']['name']!=""){
					$filetype = $_FILES['profile']['type'];
					if ($filetype == 'image/jpeg' or $filetype == 'image/png'){
						$target_dir = "../Images/";
						$file = $_FILES['profile']['name'];
						$path = pathinfo($file);
						$filename = $path['filename'];
						$temp = explode(".", $_FILES['profile']['name']);
						$newfilename = round(microtime(true)) . '.' . end($temp);

						$ext = $path['extension'];
						$temp_name = $_FILES['profile']['tmp_name'];
						$path_filename_ext = $target_dir.$newfilename;
						$path_filename_for_database = "../Images/".$newfilename;
						move_uploaded_file($temp_name,$path_filename_ext);
						echo "<script>alert('".$path_filename_for_database."');</script>";


					}
				}
				$sqlUpdatePic = "UPDATE users
								SET profilepicture = :picture
								WHERE userID = :adminUserID";
				$stmt = $con->prepare($sqlUpdatePic);
				$stmt->bindParam(':adminUserID', $_SESSION['adminUserID'], PDO::PARAM_INT);
				$stmt->bindParam(':picture', $path_filename_for_database, PDO::PARAM_STR);
				$stmt->execute();

				echo "<script>window.location.href = 'index?route=picture';</script>";
			}
		?>
	</body>
	<script type="text/javascript">
		function readURL(input) {
	        if (input.files && input.files[0]) {
	            var reader = new FileReader();

	            reader.onload = function (e) {
	                $('#blah')
	                    .attr('src', e.target.result)
	                    .width(512)
	                    .height(512);
	            };

	            reader.readAsDataURL(input.files[0]);
	        }
	    }
		function changeProfile(){
			location.replace("index?route=picture");
			localStorage.setItem('viewAddModal',true);
			var top = window.scrollY;
			localStorage.setItem('y',top);
		}

		window.onload = function(){
			var view = localStorage.getItem('viewAddModal');
			if (view == 'true'){
				document.getElementById('viewAddModal').style.display = "block";
			}
			localStorage.setItem('viewAddModal',false)
		}

		var span = document.getElementsByClassName("viewAddClose")[0];
		span.onclick = function() {
			document.getElementById('viewAddModal').style.display = "none";
		}

		window.onclick = function(event) {
	        if (event.target == document.getElementById('viewAddModal')) {
	            document.getElementById('viewAddModal').style.display = "none";
	        }
	    }
	</script>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>