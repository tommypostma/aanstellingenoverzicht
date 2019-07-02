<?php
//MYSQL 
$servername = "localhost";
$username = "********";
$password = "********";
$dbname = "********";

//Get run properties
$action = file_get_contents('php://input');
$run_token = explode("=", explode("&", $action)[0])[1];
$status = explode("=", explode("&", $action)[1])[1];
$data_ready = explode("=", explode("&", $action)[4])[1];
$pages = explode("&start_url", explode("loop&pages=", $action)[1])[0];
$referee_updated = explode("%5C%22%2C%5C%22", explode("%5C%22%2C%5C%22federation_number%5C%22%3A%5C%22", $action)[1])[0];

//Get data (in JSON)
$params = http_build_query(array(
  "api_key" => "********"
));

$result = file_get_contents(
	'https://www.parsehub.com/api/v2/runs/'.$run_token.'/data?'.$params,
	false,
	stream_context_create(array(
		'http' => array(
			'method' => 'GET'
		)
	))
);
$result = gzdecode($result);
$match_details = json_decode($result, true)["match_details"];

if($status == "complete" AND count($match_details) > 0) {
	//Delete run on parsehub server
	$result_delete = file_get_contents(
		'https://www.parsehub.com/api/v2/runs/'.$run_token.'?'.$params,
		false,
		stream_context_create(array(
			'http' => array(
				'method' => 'DELETE'
			)
		))
	);

	//Update databases
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	//REFEREES
	$update_time = mysqli_query($conn, "UPDATE referees SET update_time = current_timestamp WHERE federation_number = '".$referee_updated."'");
	
	//Update remaining tables
	//Get referee_id from federation number 
	$referee_id_result = mysqli_query($conn, "SELECT referee_id FROM referees WHERE federation_number = '".$referee_updated."'");
	$referee_id = mysqli_fetch_row($referee_id_result)[0];
	//Delete planned appointments for this referee
	$delete_appointments = mysqli_query($conn, "DELETE FROM appointments WHERE referee_id = '".$referee_id."' AND (status = 'Afgezegd' OR status = 'Gepland' OR status = 'Geplaatst' OR status = 'Aangemeld' OR status = 'Toegelaten')");
			
	foreach($match_details as $match) {
		//ACTIVITIES
		//Set lower limit as date is sometimes not recorded
        $activity_date = date("Y-m-d H:i:s", "1970-01-01 00:00:00");
		//Replace lower limit when a date has been recorded
		//Time equals "--" when match has been cancelled
        if(strlen($match["match_date"]) > 1 && $match["match_time"] !== "--") {
			$activity_date = date("Y-m-d H:i:s", strtotime($match["match_date"]." ".$match["match_time"]));
        } else if(strlen($match["match_date"]) > 1 && $match["match_time"] == "--") {
			$activity_date = date("Y-m-d H:i:s", strtotime($match["match_date"]." 01:23")); //code for cancelled
		}	
		//Replace signs in activity name which do not work well with mysql
		$activity_name = str_replace("'", "\'", $match["match"]);
		//Add activity to database (when not already existing => name + datetime)
		$add_activity = mysqli_query($conn, "INSERT INTO activities (activity_id, date, name, type) VALUES ('', '".$activity_date."', '".$activity_name."', '".$match["match_type"]."')");
		//APPOINTMENTS
		//Get resulting activity_id
		$activity_id_result = mysqli_query($conn, "SELECT activity_id FROM activities WHERE name = '".$activity_name."' AND date = '".$activity_date."'");
		$activity_id = mysqli_fetch_row($activity_id_result)[0];
		//Club appointments
		$club = 0;
		if(strpos($match["referee_function"], "club") !== false) {
			$club = 1;
		}	
	
		//Add appointment to database
		$add_appointment = mysqli_query($conn, "INSERT INTO appointments (appointment_id, activity_id, referee_id, function, club, status) VALUES ('', '".$activity_id."', '".$referee_id."', '".$match["referee_function"]."', '".$club."', '".$match["match_status"]."')");
	}
	$conn->close();
	//when to litte pages have been traversed in OP, rerun script
} else if ($status == "complete" AND count($match_details) == 0) { 
	//Delete run on parsehub server
	$result_delete = file_get_contents(
		'https://www.parsehub.com/api/v2/runs/'.$run_token.'?'.$params,
		false,
		stream_context_create(array(
			'http' => array(
				'method' => 'DELETE'
			)
		))
	);
	
    //Run script again
	include "run.php";
}	
?>