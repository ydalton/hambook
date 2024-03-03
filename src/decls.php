<?php

declare(strict_types=1);

/* Do not access this file directly. */
if(basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    die("Forbidden");
}

$logfile_name = "../storage/log.db";
$callsign = 'ON8EI';
