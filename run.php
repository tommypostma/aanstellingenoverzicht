<?php
$servername = "localhost";
$username = "********";
$password = "********";
$dbname = "********";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Get data from database
// LIMIT should be 1 on new version, 15 on old
$fetch = mysqli_query($conn, "SELECT last_name, federation_number FROM referees ORDER BY update_time ASC LIMIT 1"); 
$conn->close();
// Make array
$starting_value = array();
while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
    $row_array['name'] = $row['last_name'];
    $row_array['federation_number'] = $row['federation_number'];
    $row_array['un'] = "********";
	$row_array['pw'] = "********";

    array_push($starting_value,$row_array);
}


// Convert to json and send request
$project_token = "tGhLuvdDhnwy";
$params = array(
  "api_key" => "********",
  "start_value_override" => json_encode(array('referees' => $starting_value))
);

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
    'content' => http_build_query($params)
  )
);

$context = stream_context_create($options);
$result = file_get_contents('https://www.parsehub.com/api/v2/projects/'.$project_token.'/run', false, $context);
echo($result);
?>