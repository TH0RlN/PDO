<?php
const NAME = "recetas";
const HOST = "localhost";
const USER = "root";
const PASS = "";

$db['conect'] = "mysql:dbname=" . NAME . ";host=" . HOST;
$db['user'] = USER;
$db['passw'] = PASS;

$f = fopen("db.json", "w");

fwrite($f, json_encode($db));
fclose($f);