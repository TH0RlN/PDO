<?php
const NAME = "recetas";
const HOST = "localhost";
const USER = "yo";
const PASS = "1234";

$db['conect'] = "mysql:dbname=" . NAME . ";host=" . HOST;
$db['user'] = USER;
$db['passw'] = PASS;

$f = fopen("db.json", "r");

$dbs = filesize("db.json") > 0 ? json_decode(fread($f, filesize("db.json")), true) : array();
fclose($f);

$f = fopen("db.json", "w");

array_push($dbs, $db);

var_dump($dbs);

fwrite($f, json_encode($dbs));
fclose($f);