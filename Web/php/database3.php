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

// Fonction ajoutant un accident à la base de données
//ajouterAccident($db, $age, $ville, $latitude, $longitude, $date,$heure, $conditionAtmospherique, $luminosite, $etatRoute, $securite){
//
//  // Vérifiez si tous les champs sont remplis
//  if (
//    empty($age) ||
//    empty($ville) ||
//    empty($latitude) ||
//    empty($longitude) ||
//    empty($date) ||
//    empty($heure) ||
//    empty($conditionAtmospherique) ||
//    empty($luminosite) ||
//    empty($etatRoute) ||
//    empty($securite)
//  ) {
//    // Affichez un message d'erreur ou effectuez des actions appropriées
//    console.log('Veuillez remplir tous les champs du formulaire.');
//    return; // Arrêtez l'exécution de la fonction si un champ est vide
//  }
//
//  // Préparation de la requête SQL d'insertion avec des paramètres liés
//  $request = "INSERT INTO accident (age, date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id) VALUES (:age, :date_heure, :ville, :latitude, :longitude, :descr_athmo_id, :descr_lum_id, :descr_etat_surf_id, :descr_dispo_secu_id)";
//  $stmt = $db->prepare($request);
//
//  // Liaison des paramètres à la requête préparée
//  $stmt->bindParam(':age', $age);
//  $stmt->bindParam(':date_heure', $date_heure);
//  $stmt->bindParam(':ville', $ville);
//  $stmt->bindParam(':latitude', $latitude);
//  $stmt->bindParam(':longitude', $longitude);
//  $stmt->bindParam(':descr_athmo_id', $conditionAtmospherique);
//  $stmt->bindParam(':descr_lum_id', $luminosite);
//  $stmt->bindParam(':descr_etat_surf_id', $etatRoute);
//  $stmt->bindParam(':descr_dispo_secu_id', $securite);
//
//  // Exécuter la requête préparée
//  if ($stmt->execute()) {
//    echo "Accident ajouté avec succès !";
//  } else {
//    echo "Erreur lors de l'ajout de l'accident.";
//  }
//
//  // Récupération de l'ID de l'accident ajouté ?
//}



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


// Recherche de tous les accidents en retournant un tableau contenant les attributs suivant :
// [age, date_heure, ville, latitude, longitude, descr_athmo, descr_lum, descr_etat_surf, descr_dispo_secu] 
function getInfosAllEvent($db){
    // Requête SQL pour récupérer tous les accidents
    $requete = "SELECT id_acc, age, date_heure, ville, latitude, longitude, descr_athmo.descr AS descr_athmo, descr_lum.descr AS descr_lum, descr_etat_surf.descr AS descr_etat_surf, descr_dispo_secu.descr AS descr_dispo_secu
                FROM accident
                INNER JOIN descr_athmo ON accident.descr_athmo_id = descr_athmo.id
                INNER JOIN descr_lum ON accident.descr_lum_id = descr_lum.id
                INNER JOIN descr_etat_surf ON accident.descr_etat_surf_id = descr_etat_surf.id
                INNER JOIN descr_dispo_secu ON accident.descr_dispo_secu_id = descr_dispo_secu.id
                ORDER BY id_acc ASC
                LIMIT 6000";

    $resultat = $db->query($requete);

    if ($resultat) {
        return $resultat->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return array(); // Retourner un tableau vide en cas d'erreur ou de résultats vides
    }
}



// Fonction récupérant uniquement les informations d'un seul accidetns à partir de son ID
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


// fonction de filtrage pour la visualisation
// IDee faire une recherche autour de la zone d'une ville ou d'une latitude ou longitude
function searchEvent($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id){
  // setting generic value
  $genericVal = '*';

  // getting the table with all events
  $eventArr = getInfosAllEvent($db);
  print_r($eventArr);

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


// Recherche de tous les accidents en retournant un CSV contenant les attributs suivant :
// [age, date_heure, ville, latitude, longitude, descr_athmo, descr_lum, descr_etat_surf, descr_dispo_secu] 
// Le modèle de prédiction a besoin des attributs suivants : ['age', 'latitude', 'longitude','descr_athmo', 'descr_lum','descr_etat_surf' ,'descr_dispo_secu']
function getInfosAllEventCSV($db, $filename){
    
  // Chemin complet du répertoire data
  $dataDirectory = __DIR__ . '/../data/';

  // Vérifier si le répertoire data existe, sinon le créer
  if (!file_exists($dataDirectory)) {
      mkdir($dataDirectory, 0777, true);
  }

  // Chemin complet du fichier CSV
  $filePath = $dataDirectory . $filename;

  // Requête SQL pour récupérer tous les accidents
  $requete = "SELECT id_acc, age, date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id
              FROM accident
              ORDER BY id_acc ASC
              LIMIT 10";

  $resultat = $db->query($requete);

  if ($resultat) {
      // Générer le fichier CSV
      $file = fopen($filePath, 'w');

      // Écrire les en-têtes des colonnes
      $headers = array_keys($resultat->fetch(PDO::FETCH_ASSOC));
      fputcsv($file, $headers);

      // Réinitialiser le pointeur du résultat
      $resultat->execute();

      // Écrire les lignes de données
      while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
          fputcsv($file, $row);
      }

      fclose($file);

      return true;
  } else {
      return false; // Retourner false en cas d'erreur ou de résultats vides
  }
}


// ------------------------------------ PREDICTION CLUSTER RELATED ------------------------------------


// fonction prenant le csv acc_selection et qui le nettoie afin de pouvoir l'utiliser pour la prédiction en générant un autre csv
function createCSVFromSelectedColumns($inputFile, $outputFile, $selectedColumns) {
  
  // Chemin complet du répertoire data
  $dataDirectory = __DIR__ . '/../data/';
  
  // Chemin complet du fichier CSV
  $inputFile = $dataDirectory . $inputFile;
  $outputFile = $dataDirectory . $outputFile;
  
  $inputHandle = fopen($inputFile, 'r');
  $outputHandle = fopen($outputFile, 'w');

  // Vérification si l'ouverture des fichiers a réussi
  if (!$inputHandle || !$outputHandle) {
      fclose($inputHandle);
      fclose($outputHandle);
      return false;
  }

  $header = fgetcsv($inputHandle);
  $selectedIndexes = array();

  // Recherche des index des colonnes sélectionnées
  foreach ($selectedColumns as $column) {
      $index = array_search($column, $header);
      if ($index !== false) {
          $selectedIndexes[] = $index;
      }
  }

  // Écriture de l'en-tête dans le fichier de sortie
  fputcsv($outputHandle, $selectedColumns);

  // Parcours des lignes du fichier d'entrée et écriture des colonnes sélectionnées dans le fichier de sortie
  while (($row = fgetcsv($inputHandle)) !== false) {
      $selectedRow = array();
      foreach ($selectedIndexes as $index) {
          $selectedRow[] = $row[$index];
      }
      fputcsv($outputHandle, $selectedRow);
  }

  // Fermeture des fichiers
  fclose($inputHandle);
  fclose($outputHandle);

  return true;
}


function getArrayLatLongIDFromCSV($csvFile){
  // Ouvrir le fichier CSV en lecture
  $fileHandle = fopen($csvFile, 'r');

  // Lire la première ligne contenant les en-têtes (non utilisée ici)
  $headers = fgetcsv($fileHandle);

  // Tableau pour stocker les latitudes et longitudes
  $coordinates = [];

  // Parcourir les lignes restantes du fichier
  while (($row = fgetcsv($fileHandle)) !== false) {
      $id_acc = $row[0]; // Indice 0 pour la latitude
      $latitude = $row[4]; // Indice 4 pour la latitude
      $longitude = $row[5]; // Indice 5 pour la longitude
      
      // Stocker les valeurs dans le tableau
      $coordinates[] = ['id_acc' => $id_acc, 'latitude' => $latitude, 'longitude' => $longitude];
  }

  // Fermer le fichier
  fclose($fileHandle);
  
  return $coordinates;  
}
  

// Fonction prédisant les clusters des accidents passés en paramètre dans le fichier CSV
// retourne un CSV avec l'ID de l'accident et le numéro du cluster prédit [num_centroid, id_acc]
function predictCluster($csvFile){
  
  // Génration du tableau contenant [id_acc, latitude, longitude] de chaque accident
  $csvFile = "../data/acc_selection.csv";
  $latLongData = getArrayLatLongIDFromCSV($csvFile);

  // Définition du chemin de json contenant les informations des centroïdes
  $centroids_path = '../json/centroids.json';

  // Création d'un Json avec les prédiction de cluster pour chaque accident => format de sortie [num_centroid, id_acc]
  foreach ($latLongData as $row) {
      $id_acc = $row['id_acc'];
      $latitude = $row['latitude'];
      $longitude = $row['longitude'];
            
      // Commande Python à exécuter
      $command = "python ../cgi/AppNonSupWeb.py $latitude $longitude \"$centroids_path\"";
      
      // Exécution de la commande
      exec($command, $output);
      
      // Décoder le JSON retourné
      //$jsonResult = json_decode($output, true);
      $jsonResult = json_decode($output[0], true);
      
      // Ajouter l'ID de l'accident au résultat
      $jsonResult['id_acc'] = $id_acc;
      
      // Ajouter le résultat au tableau final
      $clusterResult[] = $jsonResult; // => sortie [['num_cluster'], ['id_acc']]
  }

  print_r($clusterResult);

  // Convertir le tableau final en JSON
  $jsonData = json_encode($clusterResult);

  // Écrire le JSON dans un fichier
  $file = '../json/cluster_predict.json';
  file_put_contents($file, $jsonData);
}


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
  

// ------------------------------------ PREDICTION GRAVITEE RELATED ------------------------------------


// Fonction récupérant uniquement les informations d'un seul accident à partir de son ID 
// et générant un csv sous le format suivant avec ces colonnes :
// ['age', 'latitude', 'longitude','descr_athmo', 'descr_lum', 'descr_etat_surf' ,'descr_dispo_secu']
//[{"premiere_cle": valeur, "deuxieme_cle": valeur, ...}"}]
function getInfosEventJSONForPredict($db, $id_acc){
  // Requête SQL pour récupérer les informations de l'accident correspondant à id_acc
  $requete = "SELECT age, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id
              FROM accident
              WHERE id_acc = :id_acc";

  $stmt = $db->prepare($requete);
  $stmt->bindParam(':id_acc', $id_acc, PDO::PARAM_INT);
  $stmt->execute();

  $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($resultat) {
    // Convertir le tableau en JSON
    //$jsonData = json_encode($resultat); // sortir une chaine de caractère de la forme : 
    $jsonData = json_encode($resultat, JSON_NUMERIC_CHECK); // retire les guillements autour des valeurs

    // Retourner la chaîne JSON
    return $jsonData;
  } else {
    return false; // Retourner false en cas d'erreur ou de résultat vide
  }
}
  
  
// Fonction faisant la prédiction MLP, SVM et RF à partir de l'ID d'un accident
// Elle retourne et génère un tableau de tableau (json) de la forme suivante pour chaque accidnet : [0] => {"gravite": 1}
function predictionGraviteHautNiveau($db, $id_acc){
  
  // Génération d'un JSON adapté pour les modèles de prédiction comportant les informations de l'accident passé
  $jsonData = getInfosEventJSONForPredict($db, $id_acc);
  
  if ($jsonData != false) {
      //echo $jsonData; // Afficher la chaîne JSON
  } else {
      echo "Erreur lors de la récupération des informations de l'accident.";
  }
  
  // Génération commande script prédiction SVM
  $commandSVM = "python ../cgi/ClassWeb.py '$jsonData' 'SVM' '../models/svm_model.pkl'";
  $commandRF = "python ../cgi/ClassWeb.py '$jsonData' 'RF' '../models/rf_model.pkl'";
  $commandMLP = "python ../cgi/ClassWeb.py '$jsonData' 'MLP' '../models/mlp_model.pkl'";

  // Exécution des commandes
  exec($commandSVM, $outputSVM);
  exec($commandRF, $outputRF);
  exec($commandMLP, $outputMLP);
  
  
  // Décode JSON pour les tableaux de sortie
  $outputMLP = json_decode($outputMLP[0], true);
  $outputSVM = json_decode($outputSVM[0], true);
  $outputRF = json_decode($outputRF[0], true);
  
  // Ajouter le champ "methode" à chaque tableau de sortie
  $outputMLP[0]["methode"] = "MLP";
  $outputSVM[0]["methode"] = "SVM";
  $outputRF[0]["methode"] = "RF";
  
  // Retourner un tableau contenant les tableaux de sortie
  return array($outputMLP, $outputSVM, $outputRF); // [0] => Array => MLP, [1] => Array => SVM, [2] => Array => RF
}


// Fonction faisant la prédiction KNN à partir de l'ID d'un accident
// Elle retourne et génère un tableau de tableau (json) de la forme suivante pour chaque accidnet : [0] => {"gravite": 1}
function predictionGraviteKNN($db, $id_acc){
  
  // Génération d'un JSON adapté pour les modèles de prédiction comportant les informations de l'accident passé
  $jsonData = getInfosEventJSONForPredict($db, $id_acc);
    
  if ($jsonData != false) {
      //echo $jsonData; // Afficher la chaîne JSON
  } else {
      echo "Erreur lors de la récupération des informations de l'accident.";
  }

  // Génération commande script prédiction SVM
  $commandKNN = "python ../cgi/AppSupWeb.py '$jsonData' '../data/stat_acc_V3_cleared.csv'";

  // Exécution des commandes
  exec($commandKNN, $outputKNN);
  
  // Décode JSON pour le tableau de sortie
  $outputKNN = json_decode($outputKNN[0], true);
  
  // Retourner un tableau contenant les tableaux de sortie
  return array($outputKNN); 
}





?>
