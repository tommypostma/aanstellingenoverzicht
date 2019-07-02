<?php
$servername = "localhost";
$username = "********";
$password = "********";
$dbname = "********";
$conn = mysqli_connect($servername, $username, $password, $dbname);
$result = mysqli_query($conn, "SELECT CONCAT(referees.first_name, IF(referees.ln_prefix NOT LIKE '', ' ', '') , referees.ln_prefix, ' ', referees.last_name) AS official, DATE_FORMAT(activities.date, '%d-%m-%Y') AS date,   IF(DATE_FORMAT(activities.date, '%H:%i') = '01:23', '--', DATE_FORMAT(activities.date, '%H:%i')) AS time, activities.name AS activity, appointments.function, IF(DATE_FORMAT(activities.date, '%H:%i') = '01:23', 'Afgelast', appointments.status) AS status, activities.type FROM appointments INNER JOIN referees ON referees.referee_id = appointments.referee_id INNER JOIN activities ON activities.activity_id = appointments.activity_id INNER JOIN permission ON permission.referee_id = appointments.referee_id WHERE referees.nwf = 1 AND permission.publish = 1 AND activities.date >= CURRENT_DATE AND appointments.function NOT LIKE '%Team%' AND appointments.function NOT LIKE '%coach%' AND appointments.function NOT LIKE '%Doelverdediger%' AND appointments.function NOT LIKE '%trainer%' AND appointments.club = 0 ORDER BY activities.date");
$result_free = mysqli_query($conn, "SELECT CONCAT(referees.first_name, IF(referees.ln_prefix NOT LIKE '', ' ', '') , referees.ln_prefix, ' ', referees.last_name) AS official, 'Zaterdag' AS day, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (6-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y') AS date FROM referees INNER JOIN permission ON permission.referee_id = referees.referee_id WHERE referees.nwf = 1 AND permission.publish = 1 AND referees.sat = 1 AND referees.referee_id NOT IN (SELECT DISTINCT referees.referee_id FROM referees INNER JOIN appointments ON appointments.referee_id = referees.referee_id INNER JOIN activities ON activities.activity_id = appointments.activity_id WHERE referees.nwf = 1 AND DATE_FORMAT(activities.date, '%d-%m-%Y') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (6-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y') AND appointments.function NOT LIKE '%Team%' AND appointments.function NOT LIKE '%coach%' AND appointments.function NOT LIKE '%Doelverdediger%' AND appointments.function NOT LIKE '%trainer%' AND appointments.club = 0 ORDER BY referees.referee_id)
									UNION ALL
									SELECT CONCAT(referees.first_name, IF(referees.ln_prefix NOT LIKE '', ' ', '') , referees.ln_prefix, ' ', referees.last_name) AS official, 'Zondag' AS day,  IF(DATE_FORMAT(NOW(), '%w') > 0, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (7-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y'), DATE_FORMAT(NOW(), '%d-%m-%Y')) AS date FROM referees INNER JOIN permission ON permission.referee_id = referees.referee_id WHERE referees.nwf = 1 AND permission.publish = 1 AND referees.sun = 1 AND referees.referee_id NOT IN (SELECT DISTINCT referees.referee_id FROM referees INNER JOIN appointments ON appointments.referee_id = referees.referee_id INNER JOIN activities ON activities.activity_id = appointments.activity_id WHERE referees.nwf = 1 AND DATE_FORMAT(activities.date, '%d-%m-%Y') = IF(DATE_FORMAT(NOW(), '%w') > 0, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (7-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y'), DATE_FORMAT(NOW(), '%d-%m-%Y')) AND appointments.function NOT LIKE '%Team%' AND appointments.function NOT LIKE '%coach%' AND appointments.function NOT LIKE '%Doelverdediger%' AND appointments.function NOT LIKE '%trainer%' AND appointments.club = 0 ORDER BY referees.referee_id)
									UNION ALL
									SELECT CONCAT(referees.first_name, IF(referees.ln_prefix NOT LIKE '', ' ', '') , referees.ln_prefix, ' ', referees.last_name) AS official, 'Zaterdag' AS day, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (13-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y') AS date FROM referees INNER JOIN permission ON permission.referee_id = referees.referee_id WHERE referees.nwf = 1 AND permission.publish = 1 AND referees.sat = 1 AND referees.referee_id NOT IN (SELECT DISTINCT referees.referee_id FROM referees INNER JOIN appointments ON appointments.referee_id = referees.referee_id INNER JOIN activities ON activities.activity_id = appointments.activity_id WHERE referees.nwf = 1 AND DATE_FORMAT(activities.date, '%d-%m-%Y') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (13-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y') AND appointments.function NOT LIKE '%Team%' AND appointments.function NOT LIKE '%coach%' AND appointments.function NOT LIKE '%Doelverdediger%' AND appointments.function NOT LIKE '%trainer%' AND appointments.club = 0 ORDER BY referees.referee_id)
									UNION ALL
									SELECT CONCAT(referees.first_name, IF(referees.ln_prefix NOT LIKE '', ' ', '') , referees.ln_prefix, ' ', referees.last_name) AS official, 'Zondag' AS day,  IF(DATE_FORMAT(NOW(), '%w') > 0, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (14-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y'), DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 14 DAY), '%d-%m-%Y')) AS date FROM referees INNER JOIN permission ON permission.referee_id = referees.referee_id WHERE referees.nwf = 1 AND permission.publish = 1 AND referees.sun = 1 AND referees.referee_id NOT IN (SELECT DISTINCT referees.referee_id FROM referees INNER JOIN appointments ON appointments.referee_id = referees.referee_id INNER JOIN activities ON activities.activity_id = appointments.activity_id WHERE referees.nwf = 1 AND DATE_FORMAT(activities.date, '%d-%m-%Y') = IF(DATE_FORMAT(NOW(), '%w') > 0, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL (14-DATE_FORMAT(NOW(), '%w')) DAY), '%d-%m-%Y'), DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 14 DAY), '%d-%m-%Y')) AND appointments.function NOT LIKE '%Team%' AND appointments.function NOT LIKE '%coach%' AND appointments.function NOT LIKE '%Doelverdediger%' AND appointments.function NOT LIKE '%trainer%' AND appointments.club = 0 ORDER BY referees.referee_id)");

/*
Compressed scripts
CSS:1.bootstrap.css
	2.dataTables.bootstrap4.min.css
	3.responsive.bootstrap4.min.css 
JS:	1.jquery-3.3.1.js
	2.jquery.dataTables.min.js
	3.dataTables.bootstrap4.min.js
	4.dataTables.responsive.min.js
	5.responsive.bootstrap4.min.js
	6.moment.min.js
	7.datetime-moment.js*/

echo "
<!DOCTYPE html>
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"/scripts/compressed.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"/scripts/buttons.dataTables.min.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"/scripts/buttons.bootstrap4.min.css\">
<style type=\"text/css\" class=\"init\">	
	tr.cancelledreferee {
		text-decoration: line-through;
	}
	tr.cancelled {
		text-decoration: line-through;
		color: #ff8400;
	}
	tr.today {
		color: #007bff;
	}
</style> 
<script type=\"text/javascript\" charset=\"utf8\" src=\"/scripts/compressed.js\"></script>
<script type=\"text/javascript\" charset=\"utf8\" src=\"/scripts/dataTables.buttons.min.js\"></script>
<script type=\"text/javascript\" charset=\"utf8\" src=\"/scripts/buttons.bootstrap4.min.js\"></script>
<script type=\"text/javascript\" class=\"init\">
	$(document).ready( function () {
	$.fn.dataTable.moment('DD-MM-YYYY');
	$.fn.dataTable.Responsive.breakpoints.push({
		name: 'mobile-p',
		width: 600
	})
	$('#table_1').DataTable( {
		\"createdRow\": function(row, data, index) {
			if (data[5] == \"Afgezegd\") {
				$(row).addClass('cancelledreferee');
			};
			if (data[5] == \"Afgelast\") {
				$(row).addClass('cancelled');
			};
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!
			var yyyy = today.getFullYear();
			if (dd < 10) {
			  dd = '0' + dd;
			}
			if (mm < 10) {
			  mm = '0' + mm;
			}
			today = dd + '-' + mm + '-' + yyyy;
			if (data[1] == today) {
				$(row).addClass('today');
			}
		},
		dom: 'lBfrtip',
        buttons: [ {
            text: 'Terug naar website',
            action: function ( e, dt, node, config ) {
				window.location.href = \"http://svnwf.nl\";
            }
        } ],
		stateSave: true,
		responsive: true,
		\"pageLength\": 10,
		\"order\": [[1, 'asc'], [2, 'asc'], [3, 'asc'], [4, 'desc']],
		\"language\": {
			\"url\": \"/scripts/Dutch.json\" 
		}	
		} );
	$('#table_2').DataTable( {
		\"createdRow\": function(row, data, index) {
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!
			var yyyy = today.getFullYear();
			if (dd < 10) {
			  dd = '0' + dd;
			}
			if (mm < 10) {
			  mm = '0' + mm;
			}
			today = dd + '-' + mm + '-' + yyyy;
			if (data[2] == today) {
				$(row).addClass('today');
			}
		},
		stateSave: true,
		responsive: true,
		\"pageLength\": 10,
		\"order\": [[2, 'asc']],
		\"language\": {
			\"url\": \"/scripts/Dutch.json\" 
		}	
		} );	
	} );
</script>
<title>Aanstellingen sv NWF</title>
</head>
<body>
<div class=\"container-fluid\">
<h2><img src=\"images/nwf.jpg\" alt=\"nwf\" style=\"float:right;height:84px;\">Aanstellingoverzicht scheidsrechtersvereniging Noord- en West-Friesland</h2>
<table id=\"table_1\" class=\"table table-striped table-bordered dt-responsive nowrap\" style=\"width:100%\">
	<thead>
		<tr>
			<th class=\"all\">Official</th>
			<th class=\"all\">Datum</th>
			<th class=\"all\">Tijd</th>
			<th class=\"all\">Activiteit</th>
			<th>Rol</th>
			<th>Status</th>
			<th>Type</th>
		</tr>
	</thead>
	<tbody> ";
while($row = mysqli_fetch_assoc($result)) {
echo "
		<tr>
			<td>{$row['official']}</td>
			<td>{$row['date']}</td>
			<td>{$row['time']}</td>
			<td>{$row['activity']}</td>
			<td>{$row['function']}</td>
			<td>{$row['status']}</td>
			<td>{$row['type']}</td>
		</tr>";
};
echo "
	</tbody>
</table>
</div>

<div class=\"container-fluid\">
<h2>Reserve / Geen wedstrijd</h2>
<table id=\"table_2\" class=\"table table-striped table-bordered dt-responsive nowrap\" style=\"width:100%\">
	<thead>
		<tr>
			<th class=\"all\">Official</th>
			<th class=\"all\">Dag</th>
			<th class=\"all\">Datum</th>
		</tr>
	</thead>
	<tbody> ";
while($row = mysqli_fetch_assoc($result_free)) {
echo "
		<tr>
			<td>{$row['official']}</td>
			<td>{$row['day']}</td>
			<td>{$row['date']}</td>
		</tr>";
};
echo "
	</tbody>
</table>
<h1></h1>
</div>
</body>
</html> ";
?>

