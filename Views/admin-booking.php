<?php
	require("sessionStart.php");
	if(isset($_SESSION['adminUserID'])){
?>
<html>
	<head>
		<link rel="shortcut icon" href="Resources/sample-logo.png" />
		<title>Find and Hire</title>
		<link rel="stylesheet" type="text/css" href="Styles/wholeStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-bookingStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/iconActionStyles.css">
		<link rel="stylesheet" type="text/css" href="Styles/admin-bookingModalStyles.css">
	</head>
	<body>
		<?php
			if(!isset($_GET['sort'])){
				$_SESSION['linkBooking'] = "";
			}
		?>
		<div class = webTitlePage>
			<?php
				require("admin-title.php");
			?>
		</div>
		<div>
			<div class = "sideNavigation">
				<?php
					require("admin-sidebar.php");
				?>
			</div>
			<div class = wrapper>
				<div class= "title"> BOOKING </div>
				<table id = actionTable>
					<col width = "550">
					<thead class = "actions">	
						<tr>
							<th class = "addButton" colspan="1">
								<div class = linkSort>
									<ul class = "nothing">
	  									<li id = "dropdownSortID" class="dropdownSort">
	    									<a href="javascript:void(0)" class="dropbtnSort">
	    										<div class = "sortBtn"><img class = "iconAction" src="Resources/sort-by-attributes.png">SORT</div>
	    									</a>
	    									<div class="dropdown-content">
	    										<?php
	    											$optionSearch = "";
	    											$search = "";
	    											if(isset($_GET['optionSearch'])){
	    												$optionSearch = "&optionSearch=" . $_GET['optionSearch'];
	    											}
	    											if(isset($_GET['search'])){
	    												$search = "&search=" . $_GET['search'];
	    											}
	    										?>
	    										<a href="index?route=booking&sort=bookingID<?php echo $optionSearch . $search; ?>">ID</a>
	      										<a href="index?route=booking&sort=customerName<?php echo $optionSearch . $search; ?>">Customer Name</a>
	      										<a href="index?route=booking&sort=serviceType<?php echo $optionSearch . $search; ?>">Service Type</a>
	      										<a href="index?route=booking&sort=date<?php echo $optionSearch . $search; ?>">Date</a>
	    									</div>
	  									</li>
	  									
									</ul>
								</div>
							</th>
							<th class = "searchCol" colspan="2">
								<form method = "get">
								<div class = "searchClass">
										<a href = "index?route=booking"><img class = "resetAction" src="Resources/reset.png"></a>
										<select class = "optionSearch" name = "optionSearch">
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'bookingID'){
														echo " selected ";
													}
												}
											?>value="bookingID">ID</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'customerName'){
														echo " selected ";
													}
												}
											?>
											value="customerName">Customer Name</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'serviceType'){
														echo " selected ";
													}
												}
											?>
											value="serviceType">Service Type</option>
											<option
											<?php
												if(isset($_GET['optionSearch'])){
													if($_GET['optionSearch'] == 'date'){
														echo " selected ";
													}
												}
											?>
											value="date">Date</option>
										</select>&nbsp<input name = "search" class = "searchInput" type = "search" placeholder="Search here..." pattern="[^'\x22]+" title="Must not contain quotations."><input type = "hidden" name = "route" value="booking">&nbsp<button name="searchButton" class = "searchBtn">SEARCH</button>
								</div>
							</form>
							</th>
						</tr>
					</thead>
				</table>
				<table id = userTable>
					<col width = 50>
					<col width = 440>
					<col width = 240>
					<col width = 240>
					<thead>
						<tr>
							<th> ID </th>
							<th> Customer Name </th>
							<th> Service Type </th>
							<th> Date </th>
							<th> Action </th>
						</tr>
					</thead>
					<tbody>
					<?php
							$sqlBooking = "SELECT bookingID, (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID) AS customerName, (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) AS serviceName, DATE_FORMAT(date,'%b %d, %Y %r') AS date FROM booking AS BG";
							if(isset($_GET['optionSearch'])){
								if($_GET['optionSearch'] == 'bookingID'){
									$optionSearchVar = "bookingID";
								}
								elseif($_GET['optionSearch'] == 'customerName'){
									$optionSearchVar = "(SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID)";
								}
								elseif($_GET['optionSearch'] == 'serviceType'){
									$optionSearchVar = "(SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID)";
								}
								elseif($_GET['optionSearch'] == 'date'){
									$optionSearchVar = "(DATE_FORMAT(date,'%b %d, %Y %r'))";
								}

		    					$sqlBooking .= " WHERE " . $optionSearchVar . " LIKE '%".$_GET['search']."%'";
							}
							if(isset($_GET['sort'])){
								if ($_GET['sort'] == 'bookingID'){
		    						$sqlBooking .= " ORDER BY bookingID";
		    						$_SESSION['linkBooking'] = "bookingID";
								}
								elseif ($_GET['sort'] == 'customerName')
								{
								    $sqlBooking .= " ORDER BY (SELECT concat(lastName, ', ', firstName, ' ', middleName) AS name FROM users AS US WHERE US.userID = BG.customerID), bookingID";
								    $_SESSION['linkBooking'] = "customerName";
								}
								elseif ($_GET['sort'] == 'serviceType')
								{
								    $sqlBooking .= " ORDER BY (SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID), bookingID";
								    $_SESSION['linkBooking'] = "serviceType";
								}
								elseif ($_GET['sort'] == 'date')
								{
								    $sqlBooking .= " ORDER BY date DESC";
								    $_SESSION['linkBooking'] = "date";
								}
							}

							$stmt = $con->prepare($sqlBooking);
							$stmt->execute();
							$results = $stmt->fetchAll();
							$rowCount = $stmt->rowCount();

							foreach($results as $rowBooking){
								$bookingID = $rowBooking["bookingID"];
			    				$bookingCustomerName = $rowBooking["customerName"];
			    				$bookingServiceName = $rowBooking["serviceName"];
			    				$bookingDate = $rowBooking["date"];
			    				?>
			    				<tr class = "tableContent">
						        	<td><?php echo $bookingID; ?></td>
						        	<td><?php echo $bookingCustomerName; ?></td>
						        	<td><?php echo $bookingServiceName; ?></td>
						        	<td><?php echo $bookingDate; ?></td>

						        	<td><button id='viewBtn' class = 'view' onclick='viewModal()'>View</button></td>
						        </tr>
							<?php
					    		}
					    		if($rowCount == 0){
									echo "<td colspan = 5> No results. </td>";
								}
							?>	
					</tbody>
				</table>
			</div>
		</div>
		<div id="viewModal" class="viewModal">
			<div class="viewModal-content">
				<span class="viewClose">&times;</span>
				<div class = "details">
    				<div class = "titleDetails"><b>View Full Details</b></div>
    				<?php
    					if(isset($_GET['bookingID'])){
    						$bookingIDVar = $_GET['bookingID'];
    						$sqlBookingView = "SELECT bookingID, 
    						customerID, 
    						(SELECT profilepicture FROM users AS US WHERE US.userID = BG.customerID) AS profilepicture, 
    						(SELECT concat(lastName, ', ', firstName, ' ', middleName) FROM users AS US WHERE US.userID = BG.customerID) AS customerName, 
    						(SELECT email FROM users AS US WHERE US.userID = BG.customerID) AS customerEmail, 
    						(SELECT contact FROM users AS US WHERE US.userID = BG.customerID) AS customerContactNo, 
    						(SELECT SS.name from services AS SS WHERE BG.serviceID = SS.serviceID) AS serviceName, 
							groupChoicesID, 
    						DATE_FORMAT(date,'%b %d, %Y %r') AS date, 
    						amount, 
    						remarks 
    						FROM booking AS BG WHERE bookingID = (:bookingIDVar)";

    						$stmt = $con->prepare($sqlBookingView);
    						$stmt->bindParam(':bookingIDVar', $bookingIDVar, PDO::PARAM_INT);
							$stmt->execute();
							$rowBookingView = $stmt->fetch();

			    			// output data of each row
		    				$bookingIDView = $rowBookingView["bookingID"];
		    				
		    				$bookingCustomerIDView = $rowBookingView["customerID"];

		    				if(empty($rowBookingView["profilepicture"])){
		    					$bookingProfilePictureView = 'Resources/userIcon.png';
		    				}
		    				else{
		    					$bookingProfilePictureView = $rowBookingView["profilepicture"];
		    				}
		    				$bookingCustomerNameView = $rowBookingView["customerName"];
		    				$bookingCustomerEmailView = $rowBookingView["customerEmail"];
		    				$bookingCustomerContactView = $rowBookingView["customerContactNo"];
		    				$bookingServiceNameView = $rowBookingView["serviceName"];
		    				$bookingGroupChoicesID = $rowBookingView["groupChoicesID"];
		    				$bookingDateView = $rowBookingView["date"];
		    				$bookingAmountView = $rowBookingView["amount"];
		    				$bookingRemarksView = $rowBookingView["remarks"];
						}
					?>
    				<div class = "wholeModal">
    					<table class = "tableInputs">
	    					<col width="170">
							<tr class = "trInputs">
							    <td class = "tdName">Booking ID</td>
							    <td class = "tdInput"><?php echo $bookingIDView;?></td>
							</tr>
						</table>
					    <div class = "customerWholeInfo">
					    	<div id = number class = customerDetails>
								<b>Customer Information </b>
							</div>
					    	<div class = "profilePicContent">
    							<img class = "profilePic" src="<?php echo $bookingProfilePictureView;?>">
    						</div>
    						<table class = "tableInputs">
		    					<col width="130">
								<tr class = "trInputs">
								    <td class = "tdName">User ID</td>
								    <td class = "tdInput"><?php echo $bookingCustomerIDView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Name</td>
								    <td class = "tdInput"><?php echo $bookingCustomerNameView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Email Address</td>
								    <td class = "tdInput"><?php echo $bookingCustomerEmailView;?></td>
								</tr>
								<tr class = "trInputs">
								    <td class = "tdName">Contact No</td>
								    <td class = "tdInput"><?php echo $bookingCustomerContactView;?></td>
								</tr>
							</table>
					    </div>
					</div>
					<div class = "bookingInfo">
						<table class = "tableInputs">
	    					<col width="130">
							<tr class = "trInputs">
							    <td class = "tdName">Service Type</td>
							    <td class = "tdInput"><?php echo $bookingServiceNameView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Description</td>
							    <td class = "tdInput">
							   	<?php 
						    		$sqlSelectedTitle = "SELECT (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS titleID, (SELECT Title FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) AS title FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = :bookingIDView) GROUP BY (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID))";

									$stmt = $con->prepare($sqlSelectedTitle);
									$stmt->bindParam(':bookingIDView', $bookingIDView, PDO::PARAM_INT);
									$stmt->execute();
									$results = $stmt->fetchAll();
									$rowCount = $stmt->rowCount();

									foreach($results as $rowSelectedTitle){
										echo "<div class = 'rowForm'>";
										echo "<table><col width = 100><col width = 550><tbody><tr>";
										$selectedTitleID = $rowSelectedTitle["titleID"];
										$selectedTitle = $rowSelectedTitle["title"];
										echo "<td class = 'tdName'>Title</td><td class = 'tdInput'>" .$selectedTitle . "</td>";
										echo "</tr>";
										echo "</tbody></table>";
										$sqlSelectedChoices = "SELECT (SELECT Description FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS description, (SELECT Amount FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID) AS amount FROM selected AS SD WHERE groupChoicesID = (SELECT groupChoicesID FROM booking AS BG WHERE BG.bookingID = :bookingIDView) AND (SELECT FormID FROM form AS FM WHERE FM.FormID = (SELECT FormID FROM formchoices AS FS WHERE FS.FormChoicesID = SD.choicesID)) = :selectedTitleID";

										$stmt = $con->prepare($sqlSelectedChoices);
										$stmt->bindParam(':bookingIDView', $bookingIDView, PDO::PARAM_INT);
										$stmt->bindParam(':selectedTitleID', $selectedTitleID, PDO::PARAM_INT);
										$stmt->execute();
										$results = $stmt->fetchAll();
										$rowCount = $stmt->rowCount();
										echo "<table><col width = 550><thead><tr><th class = 'tdName'>Options</th><th class = 'tdName'>Amount</th></tr></thead><tbody>";
										foreach($results as $rowSelectedChoices){
											$selectedChoicesDescription = $rowSelectedChoices["description"];
											$selectedChoicesAmount = $rowSelectedChoices["amount"];

											echo "<tr><td class = 'tdInput'>".$selectedChoicesDescription . "</td>";
											echo "<td class = 'tdInput'>".$selectedChoicesAmount . "</td></tr>";
										}
										echo "</tbody></table>";
										echo "</div>";	
							    	}
							    ?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Date and Time</td>
							    <td class = "tdInput"><?php echo $bookingDateView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Amount</td>
							    <td class = "tdInput"><?php echo $bookingAmountView;?></td>
							</tr>
							<tr class = "trInputs">
							    <td class = "tdName">Remarks</td>
							    <td class = "tdInput"><?php echo $bookingRemarksView;?></td>
							</tr>
						</table>
					</div>
				</div>
  			</div>
		</div>

		<script type="text/javascript">
			function viewModal(){
				var table = document.getElementById('userTable');
				if(table!=null){
					for(var x = 1;x<table.rows.length;x++){
						table.rows[x].onclick = function(){
							var bookingID = this.cells[0].innerHTML;
							var linkSort = '<?php if(isset($_SESSION['linkBooking'])){echo '&sort=' . $_SESSION['linkBooking'];}?>'
							var search = '<?php if(isset($_GET['search'])){echo '&search=' . $_GET['search'];}?>'
							var optionSearch = '<?php if(isset($_GET['optionSearch'])){echo '&optionSearch=' . $_GET['optionSearch'];}?>'
							var url = "index?route=booking&bookingID="+bookingID+linkSort+search+optionSearch
							location.replace(url);
							localStorage.setItem('viewModal',true);
							var top = window.scrollY;
							localStorage.setItem('y',top);	
						}						
					}
				}
			}

			window.onload = function(){
				var x = localStorage.getItem('viewModal');
				if (x == 'true'){
					document.getElementById('viewModal').style.display = "block";
				}
				localStorage.setItem('viewModal',false)
			}

			var span = document.getElementsByClassName("viewClose")[0];
			span.onclick = function() {
				document.getElementById('viewModal').style.display = "none";
			}

			window.onclick = function(event) {
		        if (event.target == document.getElementById('viewModal')) {
		            document.getElementById('viewModal').style.display = "none";
		        }
		    }
		</script>
	</body>
</html>
<?php
	}
	else{
		require("serviceError.php");
	}
?>