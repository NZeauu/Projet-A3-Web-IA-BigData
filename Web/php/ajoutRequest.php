<?php


// $test = 1;
// if ($test > 0){
//   echo "TESTING MODE ACTIVATED ON api.php -> NOT ANY REQUEST WILL WORK.<br><br><br>";
//   $requestMethod = $_SERVER['REQUEST_METHOD'];
//   $request = substr($_SERVER['PATH_INFO'], 1);
//   var_dump($requestMethod);
//   echo "\n";
//   var_dump($request);
//   echo "\n";
//   // $request = explode('/', $request);
//   // $requestRessource = array_shift($request);

//   ini_set('display_errors', 1);
//   error_reporting(E_ALL);

//   echo "GET : ";
//   var_dump($_GET);
//   echo "POST : ";
//   var_dump($_POST);
//   echo "PUT : ";
//   var_dump($_PUT);
//   echo "DELETE : ";
//   var_dump($_DELETE);
//   echo "<br><br><br>";
 
// }






require_once('database.php');

//enable all warnings and errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
dbConnect();
$db = dbConnect();
//check the request
$requestMethod = $_SERVER["REQUEST_METHOD"];
if (isset($_SERVER['PATH_INFO'])) {
    $request = substr($_SERVER['PATH_INFO'], 1);
} else {
    error_log("Erreur : clé PATH_INFO non définie.");
    // Gérer l'absence de la clé PATH_INFO
}

$request = explode("/", $request);
$requestRessource = array_shift($request);


if($requestRessource == "data"){
    switch ($requestMethod) {
        case 'GET':
            $data = getInfosAllEventV2($db);
                
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
 // AJOUT 
//  $requestRessource = $_POST['requestRessource'];

if($requestMethod == "POST"){
    if ($requestRessource == "ajout") {
    // echo " JE SUIS DANS REQUESTRESSOURCE = AJOUT sur le fichier AjoutRequest ";
            
            // Récupération des données du formulaire
            $age = $_POST['age'];
            $ville = $_POST['ville'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $date = $_POST['day'];
            $heure = $_POST['month'];
            $descr_athmo_id = $_POST['conditionAtmospherique'];
            $descr_lum_id = $_POST['luminosite'];
            $descr_etat_surf_id = $_POST['etatRoute'];
            $descr_dispo_secu_id = $_POST['securite'];

            // Appel de la fonction ajouterAccident
            // $db, $age, $ville, $latitude, $longitude, $date, $heure, $descr_athmo_i, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id
            $result = ajouterAccident($db, $age, $ville, $latitude, $longitude, $date, $heure, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id);
            $json = json_encode($result);
            echo $json;
        }
}























// if ($_SERVER['REQUEST_URI'] == '/php/ajoutRequest.php') {
//     // echo "JE SUIS DANS REQUEST URI = ajoutRequest.php";

//     switch ($requestMethod) {
//         case 'POST':
//             $response = array();
//             $response['message'] = "JE SUIS LA DANS REQUEST URI = ajoutRequest.php";
//             echo json_encode($response);

//             // Récupération des données du formulaire
//             $age = $_POST['age'];
//             $ville = $_POST['ville'];
//             $latitude = $_POST['latitude'];
//             $longitude = $_POST['longitude'];
//             $date = $_POST['day'];
//             $heure = $_POST['month'];

//             // Récupération des IDs pour luminosite, etatRoute et securite
//             // $descr_athmo_id = getValDescrAthmoID($db, 'descr_athmo_id', $descr_athmo_id);
//             // $descr_lum_id = getValDescrLumID($db, 'descr_lum_id', $descr_lum_id);
//             // $descr_etat_surf = getValDescrEtatSurfID($db, 'descr_etat_surf', $descr_etat_surf);
//             // $descr_dispo_secu = getValDescrDispoSecuID($db, 'descr_dispo_secu', $descr_dispo_secu);

//             $descr_athmo_id = $_POST['conditionAtmospherique'];
//             $descr_lum_id = $_POST['luminosite'];
//             $descr_etat_surf = $_POST['etatRoute'];
//             $descr_dispo_secu = $_POST['securite'];

//             // Appel de la fonction ajouterAccident
//             echo "JE VAIS AJOUTER UN ACCIDENT DANS REQUEST URI = ajoutRequest.php";
//             ajouterAccident($db, $age, $ville, $latitude, $longitude, $date, $heure, $descr_athmo_id, $descr_lum_id, $descr_etat_surf, $descr_dispo_secu);
//             break;
//     }
// }

  

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