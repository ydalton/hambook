<?php

/* Normally this should not be possible */
if(strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
	http_response_code(403);
	die("What are you doing here? How did you get here? You're not supposed to be here!\n");
}

$logfile = "./log.json";

$log = fopen($logfile, "r");

$decoded = json_decode(fread($log, filesize($logfile)), true);

fclose($log);

// Increment the primary key counter
$pk_counter = $decoded[0]["pk_counter"]++;

$to_be_added["operator"] = $_POST["name"];
$to_be_added["callsign"] = $_POST["callsign"];
$to_be_added["frequency"] = $_POST["frequency"];
$to_be_added["time"] = strtotime($_POST["date"] . " " . $_POST["time"]);
// This can be changed later
$to_be_added["mode"] = "SSB";
$to_be_added["country"] = $_POST["country"];
$to_be_added["comment"] = $_POST["comment"];
$to_be_added["id"] = $pk_counter;

array_push($decoded, $to_be_added);

$log = fopen($logfile, "w");
fwrite($log, json_encode($decoded));
fclose($log);

header("Location: /");
