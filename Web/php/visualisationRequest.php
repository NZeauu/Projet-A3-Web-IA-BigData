<?php

require_once('database.php');

//enable all warnings and errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = dbConnect();

//check the request
$requestMethod = $_SERVER["REQUEST_METHOD"];
$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode("/", $request);
$requestRessource = array_shift($request);


if($requestRessource == "data"){
    switch ($requestMethod) {
        case 'GET': 
            $data = getInfosAllEvent($db); 
            break;
    }
}
if($requestRessource == "search"){
    switch ($requestMethod) {
        case 'GET':

            isset($_GET['age']) ? $age = $_GET['age'] : $age = null;
            isset($_GET['date']) ? $date = $_GET['date'] : $date = null;
            isset($_GET['ville']) ? $ville = $_GET['ville'] : $ville = null;
            isset($_GET['latitude']) ? $latitude = $_GET['latitude'] : $latitude = null;
            isset($_GET['longitude']) ? $longitude = $_GET['longitude'] : $longitude = null;
            isset($_GET['athmo']) ? $athmo = $_GET['athmo'] : $athmo = null;
            isset($_GET['lum']) ? $lum = $_GET['lum'] : $lum = null;
            isset($_GET['surf']) ? $surf = $_GET['surf'] : $surf = null;
            isset($_GET['secu']) ? $secu = $_GET['secu'] : $secu = null;

            $data = getInfosEventFiltered($db, $age, $date, $ville, $latitude, $longitude, $athmo, $lum, $surf, $secu);

            break;
    }
}

if($requestRessource == "filters"){
    switch ($requestMethod) {
        case 'GET':
            // Créé plusieurs tableaux pour les différents filtres et les remplis avec les données de la base de données via les fonctions et combine les tableaux dans un tableau final pour l'envoyer au client via $data
            $data = array();
            $data['descr_athmo'] = getValDescrAthmo($db);
            $data['descr_lum'] = getValDescrLum($db);
            $data['descr_surf'] = getValDescrEtatSurf($db);
            $data['dispo_secu'] = getValDescrDispoSecu($db);
            
            break;
        
    }
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