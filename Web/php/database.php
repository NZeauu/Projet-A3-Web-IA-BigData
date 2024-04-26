<?php
//Author: Prenom NOM
//Login : etuXXX
//Groupe: ISEN X GROUPE Y
//Annee:

include_once("constants.php");
 
function dbConnect(){
    try
    {
      $db = new PDO('mysql:host='.DB_SERVER.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $exception)
    {
      error_log('Connection error: '.$exception->getMessage());
      return false;
    }
    return $db;
}

$db = dbConnect();


function ajouterAccident($db, $age, $ville, $latitude, $longitude, $date, $heure, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id) {
  // Préparation des requêtes SQL pour récupérer les IDs des différentes descriptions
  $descr_athmo_query = "SELECT id FROM descr_athmo WHERE descr = :descr_athmo";
  $descr_lum_query = "SELECT id FROM descr_lum WHERE descr = :descr_lum_id";
  $descr_etat_surf_query = "SELECT id FROM descr_etat_surf WHERE descr = :descr_etat_surf_id";
  $descr_dispo_secu_query = "SELECT id FROM descr_dispo_secu WHERE descr = :descr_dispo_secu_id";

  // Exécution des requêtes SQL pour récupérer les IDs
  $descr_athmo_stmt = $db->prepare($descr_athmo_query);
  $descr_athmo_stmt->bindValue(':descr_athmo', $descr_athmo_id, PDO::PARAM_STR);
  $descr_athmo_stmt->execute();
  $descr_athmo_id = $descr_athmo_stmt->fetchColumn();

  $descr_lum_stmt = $db->prepare($descr_lum_query);
  $descr_lum_stmt->bindValue(':descr_lum_id', $descr_lum_id, PDO::PARAM_STR);
  $descr_lum_stmt->execute();
  $descr_lum_id = $descr_lum_stmt->fetchColumn();

  $descr_etat_surf_stmt = $db->prepare($descr_etat_surf_query);
  $descr_etat_surf_stmt->bindValue(':descr_etat_surf_id', $descr_etat_surf_id, PDO::PARAM_STR);
  $descr_etat_surf_stmt->execute();
  $descr_etat_surf_id = $descr_etat_surf_stmt->fetchColumn();

  $descr_dispo_secu_stmt = $db->prepare($descr_dispo_secu_query);
  $descr_dispo_secu_stmt->bindValue(':descr_dispo_secu_id', $descr_dispo_secu_id, PDO::PARAM_STR);
  $descr_dispo_secu_stmt->execute();
  $descr_dispo_secu_id = $descr_dispo_secu_stmt->fetchColumn();

  // Préparation de la requête SQL d'insertion avec les IDs récupérés
  $request = "INSERT INTO accident (age, date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id) VALUES (:age, :date_heure, :ville, :latitude, :longitude, :descr_athmo_id, :descr_lum_id, :descr_etat_surf_id, :descr_dispo_secu_id)";
  $stmt = $db->prepare($request);

  // Liaison des valeurs directes à la requête préparée
  $stmt->bindValue(':age', $age, PDO::PARAM_INT);
  $stmt->bindValue(':ville', $ville, PDO::PARAM_STR);
  $stmt->bindValue(':date_heure', $date . ' ' . $heure, PDO::PARAM_STR);
  $stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
  $stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
  $stmt->bindValue(':descr_athmo_id', $descr_athmo_id, PDO::PARAM_INT);
  $stmt->bindValue(':descr_lum_id', $descr_lum_id, PDO::PARAM_INT);
  $stmt->bindValue(':descr_etat_surf_id', $descr_etat_surf_id, PDO::PARAM_INT);
  $stmt->bindValue(':descr_dispo_secu_id', $descr_dispo_secu_id, PDO::PARAM_INT);

  $stmt->execute();
  $response = array();
  if ($stmt->rowCount() == 0){
    $response['message'] = "Accident non ajouté";
  }
  return $response;
}

// ajouterAccident($db, 25, "Paris", 69,69, "2023-06-26", "15:30:00", "Normale", "Plein jour", "Normale", "Autre - Non utilisé");
 

// Fonction ajoutant un accident à la base de données
// function ajouterAccident($db, $age, $ville, $latitude, $longitude, $date, $heure, $descr_athmo_id, $descr_lum_id, $descr_etat_surf, $descr_dispo_secu) {
  // if (
  //   empty($age) ||
  //   empty($ville) ||
  //   empty($latitude) ||
  //   empty($longitude) ||
  //   empty($date) ||
  //   empty($heure) ||
  //   empty($descr_athmo_id) ||
  //   empty($descr_lum_id) ||
  //   empty($descr_etat_surf) ||
  //   empty($descr_dispo_secu)
  // ) {
  //   // Affichez un message d'erreur ou effectuez des actions appropriées
  //   echo 'Veuillez remplir tous les champs du formulaire.';
  //   return; // Arrêtez l'exécution de la fonction si un champ est vide
  // }

  // Combinaison de la date et de l'heure
  // $date_heure = $date . ' ' . $heure;

  // // Obtenir les IDs correspondants pour luminosite, etatRoute et securite
  // $descr_athmo_id = getValDescrAthmoID($db, $descr_athmo_id);
  // $descr_lum_id = getValDescrLumID($db, $descr_lum_id);
  // $descr_etat_surf = getValDescrEtatSurfID($db, $descr_etat_surf);
  // $descr_dispo_secu = getValDescrDispoSecuID($db, $descr_dispo_secu);

  // Préparation de la requête SQL d'insertion avec des paramètres liés
//   $request = "INSERT INTO accident (age, date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id) VALUES (:age, :date_heure, :ville, :latitude, :longitude, :descr_athmo_id, :descr_lum_id, :descr_etat_surf_id, :descr_dispo_secu_id)";
//   $stmt = $db->prepare($request);
  
// // Affichage de la requête préparée
// // echo '<script>console.log("Requête préparée : ' . $request . '");</script>';

// //   // Affichage de la requête préparée
// //   echo "Requête préparée : " . $stmt->queryString;

//   // Liaison des paramètres à la requête préparée
//   $stmt->bindParam(':age', $age);
//   $stmt->bindParam(':date_heure', $date . ' ' . $heure);
//   $stmt->bindParam(':ville', $ville);
//   $stmt->bindParam(':latitude', $latitude);
//   $stmt->bindParam(':longitude', $longitude);
//   $stmt->bindParam(':descr_athmo_id', $descr_athmo_id);
//   $stmt->bindParam(':descr_lum_id', $descr_lum_id);
//   $stmt->bindParam(':descr_etat_surf_id', $descr_etat_surf);
//   $stmt->bindParam(':descr_dispo_secu_id', $descr_dispo_secu);

//   $stmt->execute();
//   }
//   if ($stmt->execute()) {
//     echo "Accident ajouté avec succès !";
//   } else {
//     echo "Erreur lors de l'ajout de l'accident.";
//   }
// }


function getLatitude($db) {
  // Requête de récupération des valeurs de latitude
//   $request = "SELECT latitude FROM accident WHERE descr_athmo_id = 5";
  $request = "SELECT latitude FROM accident";
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
    $valeurs[] = $row['latitude'];
  }
  
  return $valeurs;
}

function getLongitude($db) {
  // Requête de récupération des valeurs de latitude
  $request = "SELECT longitude FROM accident";
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
    $valeurs[] = $row['longitude'];
  }
  
  return $valeurs;
}


function getValDescrAthmo($db){
  
  // requête de récupération des valeurs de description d'athmosphère
  $request = "SELECT descr FROM descr_athmo";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['descr'];
  }
  
  return $valeurs;
}


function getValDescrAthmoID($db){
  
  // requête de récupération des valeurs de description d'athmosphère
  $request = "SELECT id FROM descr_athmo";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['id'];
  }
  
  return $valeurs;
}



// fonction récupérant la liste des valeurs de "descr_lum"
function getValDescrLum($db){
  
  // requête de récupération des valeurs de descr_lum
  $request = "SELECT descr FROM descr_lum";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['descr'];
  }
  
  return $valeurs; 
}

function getValDescrLumID($db){
  
  // requête de récupération des valeurs de descr_lum
  $request = "SELECT id FROM descr_lum";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['id'];
  }
  
  return $valeurs; 
}

// fonction récupérant la liste des valeurs de "descr_etat_surf"
function getValDescrEtatSurf($db){
  
  // requête de récupération des valeurs de descr_etat_surf
  $request = "SELECT descr FROM descr_etat_surf";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['descr'];
  }
  
  return $valeurs; 
}

function getValDescrEtatSurfID($db){
  
  // requête de récupération des valeurs de descr_etat_surf
  $request = "SELECT id FROM descr_etat_surf";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['id'];
  }
  
  return $valeurs; 
}

// fonction récupérant la liste des valeurs de "descr_dispo_secu"
function getValDescrDispoSecu($db){
  
  // requête de récupération des valeurs de descr_dispo_secu
  $request = "SELECT descr FROM descr_dispo_secu";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['descr'];
  }

  return $valeurs; 
}

// fonction récupérant la liste des valeurs de "descr_dispo_secu"
function getValDescrDispoSecuID($db){
  
  // requête de récupération des valeurs de descr_dispo_secu
  $request = "SELECT id FROM descr_dispo_secu";
  // récupération des valeurs
  $resultat = $db->query($request);
  $valeurs = array();
  while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
      $valeurs[] = $row['id'];
  }
  
  return $valeurs; 
}

// Recherche de tous les accidents en retournant un tableau contenant les attributs suivant :
// [age, date_heure, ville, latitude, longitude, descr_athmo, descr_lum, descr_etat_surf, descr_dispo_secu] 
// UTILISEE POUR AVOIR TOUS LES EVENTS DE LA VISUALISATION
function getInfosAllEvent($db){
  // Requête SQL pour récupérer tous les accidents
  $requete = "SELECT id_acc, age, date_heure, ville, latitude, longitude, descr_athmo.descr AS descr_athmo, descr_lum.descr AS descr_lum, descr_etat_surf.descr AS descr_etat_surf, descr_dispo_secu.descr AS descr_dispo_secu
              FROM accident
              INNER JOIN descr_athmo ON accident.descr_athmo_id = descr_athmo.id
              INNER JOIN descr_lum ON accident.descr_lum_id = descr_lum.id
              INNER JOIN descr_etat_surf ON accident.descr_etat_surf_id = descr_etat_surf.id
              INNER JOIN descr_dispo_secu ON accident.descr_dispo_secu_id = descr_dispo_secu.id
              ORDER BY id_acc DESC
              LIMIT 20000";

  $resultat = $db->query($requete);

  if ($resultat) {
      return $resultat->fetchAll(PDO::FETCH_ASSOC);
  } else {
      return array(); // Retourner un tableau vide en cas d'erreur ou de résultats vides
  }
}


//fonction destinée aux filtres
function getInfosAllEventV2($db){
  // Requête SQL pour récupérer tous les accidents
  $requete = "SELECT id_acc, age, DATE(date_heure) as date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id
              FROM accident
              ORDER BY id_acc DESC
              LIMIT 20000";

  $resultat = $db->query($requete);

  if ($resultat) {
      return $resultat->fetchAll(PDO::FETCH_ASSOC);
  } else {
      return array(); // Retourner un tableau vide en cas d'erreur ou de résultats vides
  }
}


// Fonction récupérant uniquement les informations d'un suel accidetns à partir de son ID
function getInfosEvent($db, $id_acc){
    // Requête SQL pour récupérer les informations des accidents correspondant à id_acc
    $requete = "SELECT id_acc, age, date_heure, ville, latitude, longitude, descr_athmo.descr AS descr_athmo, descr_lum.descr AS descr_lum, descr_etat_surf.descr AS descr_etat_surf, descr_dispo_secu.descr AS descr_dispo_secu
                FROM accident
                INNER JOIN descr_athmo ON accident.descr_athmo_id = descr_athmo.id
                INNER JOIN descr_lum ON accident.descr_lum_id = descr_lum.id
                INNER JOIN descr_etat_surf ON accident.descr_etat_surf_id = descr_etat_surf.id
                INNER JOIN descr_dispo_secu ON accident.descr_dispo_secu_id = descr_dispo_secu.id
                WHERE id_acc = :id_acc";

    $stmt = $db->prepare($requete);
    $stmt->bindParam(':id_acc', $id_acc, PDO::PARAM_INT);
    $stmt->execute();

    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultat) {
        return $resultat;
    } else {
        return array(); // Retourner un tableau vide en cas d'erreur ou de résultats vides
    }
}


function bindStringToIDForAthmo($db, $descr_athmo_id) {
  $descr_athmo_query = "SELECT id FROM descr_athmo WHERE descr = :descr_athmo";
  $descr_athmo_stmt = $db->prepare($descr_athmo_query);
  $descr_athmo_stmt->bindValue(':descr_athmo', $descr_athmo_id, PDO::PARAM_STR);
  $descr_athmo_stmt->execute();
  $descr_athmo_id = $descr_athmo_stmt->fetchColumn();

  return $descr_athmo_id;
}

function bindStringToIDForLum($db, $descr_lum_id) {
  $descr_lum_query = "SELECT id FROM descr_lum WHERE descr = :descr_lum_id";
  $descr_lum_stmt = $db->prepare($descr_lum_query);
  $descr_lum_stmt->bindValue(':descr_lum_id', $descr_lum_id, PDO::PARAM_STR);
  $descr_lum_stmt->execute();
  $descr_lum_id = $descr_lum_stmt->fetchColumn();

  return $descr_lum_id;
}


function bindStringToIDForEtatSurf($db, $descr_etat_surf_id) {
  $descr_etat_surf_query = "SELECT id FROM descr_etat_surf WHERE descr = :descr_etat_surf_id";
  $descr_etat_surf_stmt = $db->prepare($descr_etat_surf_query);
  $descr_etat_surf_stmt->bindValue(':descr_etat_surf_id', $descr_etat_surf_id, PDO::PARAM_STR);
  $descr_etat_surf_stmt->execute();
  $descr_etat_surf_id = $descr_etat_surf_stmt->fetchColumn();

  return $descr_etat_surf_id;
}

function bindStringToIDForDispoSecu($db, $descr_dispo_secu_id) {
  $descr_dispo_secu_query = "SELECT id FROM descr_dispo_secu WHERE descr = :descr_dispo_secu_id";
  $descr_dispo_secu_stmt = $db->prepare($descr_dispo_secu_query);
  $descr_dispo_secu_stmt->bindValue(':descr_dispo_secu_id', $descr_dispo_secu_id, PDO::PARAM_STR);
  $descr_dispo_secu_stmt->execute();
  $descr_dispo_secu_id = $descr_dispo_secu_stmt->fetchColumn();

  return $descr_dispo_secu_id;
}






// fonction de filtrage pour la visualisation
// IDee faire une recherche autour de la zone d'une ville ou d'une latitude ou longitude
function searchEvent($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id){
    
  // setting generic value
  $genericVal = '*';

  // getting the table with all events
  $eventArr = getInfosAllEventV2($db);

  // modifying the array with all events filtered (if the search field is empty, it puts a generic value for this column)
  foreach ($eventArr as $i => $val){

    if (empty($age)){
        $eventArr[$i]['age'] = $genericVal;
    }
    if (empty($date_heure)){
        $eventArr[$i]['date_heure'] = $genericVal;
    }
    if (empty($ville)){
        $eventArr[$i]['ville'] = $genericVal;
    }
    if (empty($latitude)){
      $eventArr[$i]['latitude'] = $genericVal;
    }
    if (empty($longitude)){
        $eventArr[$i]['longitude'] = $genericVal;
    }
    if (empty($descr_athmo_id)){
      $eventArr[$i]['descr_athmo_id'] = $genericVal;
    }
    if (empty($descr_lum_id)){
      $eventArr[$i]['descr_lum_id'] = $genericVal;
    }
    if (empty($descr_etat_surf_id)){
      $eventArr[$i]['descr_etat_surf_id'] = $genericVal;
    }
    if (empty($descr_dispo_secu_id)){
      $eventArr[$i]['descr_dispo_secu_id'] = $genericVal;
    }
  }

  // setting the non searched fields value to the value '*'
  if (empty($age)){
    $age = $genericVal;
  }
  if (empty($date_heure)){
    $date_heure = $genericVal;
  }
  if (empty($ville)){
    $ville = $genericVal;
  }
  if (empty($latitude)){
    $latitude = $genericVal;
  }
  if (empty($longitude)){
    $longitude = $genericVal;
  }
  if (empty($descr_athmo_id)){
    $descr_athmo_id = $genericVal;
  }
  if (empty($descr_lum_id)){
    $descr_lum_id = $genericVal;
  }
  if (empty($descr_etat_surf_id)){
    $descr_etat_surf_id = $genericVal;
  }
  if (empty($descr_dispo_secu_id)){
    $descr_dispo_secu_id = $genericVal;
  }

  // filling the searchedAcc with the IDs of the matches searched
  $searchedAcc = [];
  $id_acc = 0;
  foreach ($eventArr as $val){
    
      if (isset($val['id_acc']) && $val['age']==$age && $val['date_heure']==$date_heure && $val['ville']==$ville && $val['latitude']==$latitude && $val['longitude']==$longitude && $val['descr_athmo_id']==$descr_athmo_id && $val['descr_lum_id']==$descr_lum_id && $val['descr_etat_surf_id']==$descr_etat_surf_id && $val['descr_dispo_secu_id']==$descr_dispo_secu_id){ // gerer periodes
          array_push($searchedAcc, $val['id_acc']);
      }
  }
  //print_r($searchedEventIdArr);
  return $searchedAcc;

}

function getInfosEventFiltered($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id){

  $eventArr = searchEvent($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id);

  //obtention des informations des accidents filtrés à partir de leur ID grâce à la fonction getInfosEvent et le tableau $eventArr
  $eventArrFiltered = [];

  foreach ($eventArr as $val){
    array_push($eventArrFiltered, getInfosEvent($db, $val));
  }

  return $eventArrFiltered;
}


// $age = 58;
// $date = "2009-02-18";
// $heure = "17:45:00";
// $ville = "SOUVIGNE";
// $latitude = 45.9667;
// $longitude = 0.066667;
// $descr_athmo_id = 1;
// $descr_lum_id = 1;
// $descr_etat_surf_id = 1;
// $descr_dispo_secu_id = 1;
// getInfosEventFiltered($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id);







function predictOneCluster($latitude, $longitude) {
  // Définition du chemin du fichier contenant les informations des centroïdes
  $centroidsPath = '../json/centroids.json';

  // Commande Python à exécuter pour prédire le cluster
  $command = "python ../cgi/AppNonSupWeb.py $latitude $longitude \"$centroidsPath\"";

  // Exécution de la commande
  exec($command, $output);

  // Décoder le JSON retourné
  $jsonResult = json_decode($output[0], true);
  
  // Récupérer la valeur du cluster prédict
  // $clusterValue = $jsonResult['cluster'];

  // Retourner la valeur du cluster
  // return $clusterValue;
  return $jsonResult;
}
  













// function getInfosEventFiltered($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id) {
//   $eventArr = searchEvent($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id);

//   // Affichage pour le débogage
//   // echo "Event IDs after filtering: ";
//   print_r($eventArr);

//   $eventArrFiltered = [];

//   foreach ($eventArr as $val) {
//       $eventDetails = getInfosEvent($db, $val);

//       // Affichage pour le débogage
//       // echo "Event details for ID $val: ";
//       print_r($eventDetails);

//       if (!empty($eventDetails)) {
//           array_push($eventArrFiltered, $eventDetails);
//       }
//   }

//   // Affichage pour le débogage
//   // echo "Filtered event array: ";
//   print_r($eventArrFiltered);

//   return $eventArrFiltered;
// }
























?>
