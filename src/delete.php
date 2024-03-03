<?php

require_once('./decls.php');

if(strcmp($_SERVER['REQUEST_METHOD'], "GET")) {
	http_response_code(403);
	die("How did you get here?");
}

$id = $_GET["id"];

if (!$id)
	die("No ID specified.\n");

$pdo = "";
try {
    $pdo = new PDO("sqlite:$logfile_name");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage() . $e->errorInfo);
}

try {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e) {
    die("Can't delete entry: " . $e->getMessage());
}

header("Location: /");
