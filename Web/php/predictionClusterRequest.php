<?php
require_once('database.php');
// require_once('database3.php');

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





if($requestRessource == "PredictionCluster"){
    switch ($requestMethod) {
        case 'GET':

    isset($_GET['age']) ? $age = $_GET['age'] : $age = null;
    isset($_GET['date']) ? $date = $_GET['date'] : $date = null;
    isset($_GET['ville']) ? $ville = strtoupper($_GET['ville']) : $ville = null;
    isset($_GET['latitude']) ? $latitude = $_GET['latitude'] : $latitude = null;
    isset($_GET['longitude']) ? $longitude = $_GET['longitude'] : $longitude = null;
    isset($_GET['athmo']) ? $athmo = $_GET['athmo'] : $athmo = null;
    isset($_GET['lum']) ? $lum = $_GET['lum'] : $lum = null;
    isset($_GET['surf']) ? $surf = $_GET['surf'] : $surf = null;
    isset($_GET['secu']) ? $secu = $_GET['secu'] : $secu = null;

    $data = getInfosEventFiltered($db, $age, $date, $ville, $latitude, $longitude, $athmo, $lum, $surf, $secu);
    //print_r($data);
    
    foreach ($data as $item) {
        $latitude = $item[0]['latitude'];
        $longitude = $item[0]['longitude'];  
    }
    
    //print_r($latitude);
    //print_r($longitude);
    
    // 
    $data = predictOneCluster($latitude, $longitude); 
    
    // doit retourner un tableau avec lat, long et valeur cluster
        
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