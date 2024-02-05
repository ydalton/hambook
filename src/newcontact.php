<?php

if(strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
	http_response_code(403);
	die("What are you doing here? How did you get here? You're not supposed to be here!\n");
}

var_dump($_POST);

// header("Location: /");
