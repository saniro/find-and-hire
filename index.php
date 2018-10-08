<?php
	require("Views/functions.php");
	if(!isset($_GET['route'])){
		$_GET['route'] = "";
	}
	#mysqli prepared statements or pdo

	switch (filter($_GET['route'])) {
		case '':
			require("Views/admin-login.php");
			break;
		case 'login':
			require("Views/admin-login.php");
			break;
		case 'dashboard':
			require("Views/admin-dashboard.php");
			break;
		case 'customerAccounts':
			require("Views/admin-customerAccounts.php");
			break;
		case 'request':
			require("Views/admin-request.php");
			break;
		case 'customerViewTransactions':
			require("Views/admin-customerViewTransactions.php");
			break;
		case 'customerViewReports':
			require("Views/admin-customerViewReports.php");
			break;
		case 'handymanAccounts':
			require("Views/admin-handymanAccounts.php");
			break;
		case 'handymanViewReports':
			require("Views/admin-handymanViewReports.php");
			break;
		case 'handymanViewTransactions':
			require("Views/admin-handymanViewTransactions.php");
			break;
		case 'booking':
			require("Views/admin-booking.php");
			break;
		case 'transactions':
			require("Views/admin-transactions.php");
			break;
		case 'notifications':
			require("Views/admin-notifications.php");
			break;
		case 'requirementTypes':
			require("Views/admin-requirementTypes.php");
			break;
		case 'questions':
			require("Views/admin-questions.php");
			break;
		case 'service':
			require("Views/admin-service.php");
			break;
		case 'serviceOptions':
			require("Views/admin-serviceOptions.php");
			break;
		case 'violations':
			require("Views/admin-violations.php");
			break;
		case 'reportFromCustomer':
			require("Views/admin-reportFromCustomer.php");
			break;
		case 'reportFromHandyman':
			require("Views/admin-reportFromHandyman.php");
			break;
		case 'complaints':
			require("Views/admin-complaints.php");
			break;
		case 'pending':
			require("Views/admin-topupPending.php");
			break;
		case 'history':
			require("Views/admin-topupHistory.php");
			break;
		case 'paymentHisto':
			require("Views/admin-paymentHistories.php");
			break;
		case 'penalty':
			require("Views/admin-penalty.php");
			break;
		case 'picture':
			require("Views/admin-profilePicture.php");
			break;
		case 'profile':
			require("Views/admin-profile.php");
			break;
		case 'changePassword':
			require("Views/admin-changePassword.php");
			break;
		case 'changeAddress':
			require("Views/admin-changeAddress.php");
			break;
		case 'print':
			require("Views/admin-print.php");
			break;
		case 'generateReports':
			require("Views/admin-generateReports.php");
			break;
		case 'writtenReports':
			require("Views/admin-writtenReports.php");
			break;
		case 'sidebar':
			require("Views/admin-sidebar.php");
			break;

		// case 'example':
		// 	require("Views/tabExample.php");
		// 	break;
		// case 'pdf':
		// 	require("Views/reportsCustomerAll.php");
		// 	break;
		// case 'test':
		// 	require("Views/test.php");
		// 	break;
		// case 'test2':
		// 	require("Views/test2.php");
		// 	break;
		// case 'test3':
		// 	require("Views/test3.php");
		// 	break;
		// case 'df':
		// 	require("Views/dynamicForm3.php");
		// 	break;
		// case 'charts':
		// 	require("Views/charts.php");
		// 	break;
		// case 'tables':
		// 	require("Views/datatables.php");
		// 	break;
		// case 'charts2':
		// 	require("Views/chart2.php");
		// 	break;
		// case 'charts3':
		// 	require("Views/chart3.php");
		// 	break;
		// case 'color':
		// 	require("Views/color.php");
		// 	break;
		// case 'editForm':
		// 	require("Views/editForm3.php");
		// 	break;
		// case 'actionPDF':
		// 	require("Views/actionPDF.php");
		// 	break;
		// case 'vf':
		// 	require("Views/viewForm2.php");
		// 	break;
		// case 'ef':
		// 	require("Views/editForm4.php");
		// 	break;
		// case 'email':
		// 	require("Views/sendEmail.php");
		// 	break;
		// case 'pt':
		// 	require("Views/print.php");
		// 	break;
		default:
			echo '<br>'.$_GET['route'];
			break;
	}
?>