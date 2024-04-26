<?php

require_once('database3.php');

//enable all warnings and errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = dbConnect();

//check the request
$requestMethod = $_SERVER["REQUEST_METHOD"];
$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode("/", $request);
$requestRessource = array_shift($request);



if ($requestRessource == "svmMlpRf"){
    $data = predictionGraviteHautNiveau($db, $_GET['id_acc']);
}


if ($requestRessource == "knn"){
    $data = predictionGraviteKNN($db, $_GET['id_acc']);
}



header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if($requestMethod == 'POST'){
    header('HTTP/1.1 200 Created');
}
else{
    header('HTTP/1.1 200 OK');
}
echo json_encode($data);