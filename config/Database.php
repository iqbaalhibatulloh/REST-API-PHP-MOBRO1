<?php

$host = "localhost";
$dbname = "id22310307_kaspri";
$username = "id22310307_iqbaal";
$password = "Kaspriiqbaal1#";

$mysqli = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;