<?php

require_once('./decls.php');

/* Normally this should not be possible */
if(strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
	http_response_code(403);
	die("What are you doing here? How did you get here? You're not supposed to be here!\n");
}

$pdo = "";
try {
    $pdo = new PDO("sqlite:$logfile_name");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$stmt = $pdo->prepare("
    INSERT INTO contacts
    (callsign, frequency, mode, time, country, operator, comment)
    VALUES (:callsign, :frequency, :mode, :time, :country, :operator, :comment)
");

$stmt->bindParam(':callsign', $_POST["callsign"], PDO::PARAM_STR);
$stmt->bindParam(':frequency', $_POST["frequency"], PDO::PARAM_STR);
$stmt->bindValue(':mode', 'SSB', PDO::PARAM_STR);
$stmt->bindValue(':time', strtotime($_POST["date"] . " " . $_POST["time"]), PDO::PARAM_INT);
$stmt->bindParam(':country', $_POST["country"], PDO::PARAM_STR);
$stmt->bindParam(':operator', $_POST["name"], PDO::PARAM_STR);
$stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);

// Execute the prepared statement
try {
    $stmt->execute();
} catch (PDOException $e) {
    die('Insertion failed: ' . $e->getMessage());
}

/* Redirect */
header("Location: /");
