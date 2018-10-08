<?php
	function insertNotification($notifMessageAdd, $notifMessageType, $optionSearch, $search){

		$dateToday = date('Y-m-d');

		$sqlNotifAddCheck = "SELECT notifTypeID, flag FROM notificationtype WHERE message = (:notifMessageAdd) AND type = (:notifMessageType)";
		$stmt = $con->prepare($sqlNotifAddCheck);
		$stmt->bindParam(':notifMessageAdd', $notifMessageAdd, PDO::PARAM_STR);
		$stmt->bindParam(':notifMessageType', $notifMessageType, PDO::PARAM_STR);
		$stmt->execute();
		$rowNotifAddCheck = $stmt->fetch();
		$rowCount = $stmt->rowCount();

		$notificationNotifIDFound = $rowNotifAddCheck["notifTypeID"];
		$notificationFlagFound = $rowNotifAddCheck["flag"];
		if(($rowCount >= 1)&&($notificationFlagFound == 0)){
			$sqlNotificationFoundUpdate = "UPDATE notificationtype
										SET flag = 1
										WHERE notifTypeID = :notificationNotifIDFound";

			$stmt = $con->prepare($sqlNotificationFoundUpdate);
			$stmt->bindParam(':notificationNotifIDFound', $notificationNotifIDFound, PDO::PARAM_INT);
			$stmt->execute();
			echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
		}
		elseif(($rowCount > 0) && ($notificationFlagFound == 1)){
			echo "<script>
				alert('There is already existing notification.');
				// Get the modal
			    var modal = document.getElementById('viewAddModal');

			    // Get the button that opens the modal
			    var btn = document.getElementById('addNotif');

			    // Get the <span> element that closes the modal
			    var span = document.getElementsByClassName('viewAddClose')[0];

			    // When the user clicks the button, open the modal 
			    viewAddModal.style.display = 'block';
			    // When the user clicks on <span> (x), close the modal
			    span.onclick = function() {
			        viewAddModal.style.display = 'none';
			        window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';
			    }

			    // When the user clicks anywhere outside of the modal, close it
			    window.onclick = function(event) {
			        if (event.target == modal) {
			            viewAddModal.style.display = 'none';
			            window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';
			        }
			    }
			</script>";
		}
		elseif($rowCount == 0){
			$sqlAddNotif = "INSERT INTO notificationtype (type, message, dateModified, flag)values(:notifMessageType, :notifMessageAdd, NOW(), 1)";

			$stmt = $con->prepare($sqlAddNotif);
			$stmt->bindParam(':notifMessageType', $notifMessageType, PDO::PARAM_INT);
			$stmt->bindParam(':notifMessageAdd', $notifMessageAdd, PDO::PARAM_STR);
			$stmt->execute();

			echo "<script type='text/javascript'>alert('Submitted successfully.');window.location.href='index?route=notifications&sort=".$_SESSION['notificationLink'].$optionSearch.$search."';</script>";
		}
	}
?>