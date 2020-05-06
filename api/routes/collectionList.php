<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://www.ryanwilliamharper.com');
include_once '../config/Database.php';
include_once '../models/CollectionItem.php';

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->connect();
    $CollectionItemObject = new CollectionItem($db);
    $CollectionItemObject->create();
}

if($_SERVER["REQUEST_METHOD"] === "GET") {
    
    $user_id = $_GET["user"];
    $database = new Database();
    $db = $database->connect();
    $CollectionItemObject = new CollectionItem($db);
    $CollectionItemObject->read($user_id);
}

if($_SERVER["REQUEST_METHOD"] === "DELETE") {

    $database = new Database();
    $db = $database->connect();
    $CollectionItemObject = new CollectionItem($db);
    $CollectionItemObject->delete();
}
?>