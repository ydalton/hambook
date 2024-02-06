<?php

$logfile = "./log.json";

if(strcmp($_SERVER['REQUEST_METHOD'], "GET")) {
	http_response_code(403);
	die("How did you get here?");
}

$id = $_GET["id"];

if (!$id)
	die("No ID specified.\n");

$log = fopen($logfile, "r") or die("Cannot open log file, is path correct?");
$decoded = json_decode(fread($log, filesize($logfile)), true);
fclose($log);

for($i = 0; $i < count($decoded); $i++) {
	if($decoded[$i]["id"] == $id)
		unset($decoded[$i]);
}

$log = fopen($logfile, "w");
fwrite($log, json_encode($decoded));
fclose($log);

header("Location: /");

