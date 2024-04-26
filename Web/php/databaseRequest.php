
<?php 

//Author: Prenom NOM
//Login : etuXXX
//Groupe: ISEN X GROUPE Y
//Annee:

include('database3.php');

//session_start();

setlocale(LC_TIME, "fr_FR.utf8");
$time = time();


/**
 * Connexion à la BDD
 * @return PDO
 */


try {
    // Connexion à la base de donnée => OK
    $db = dbConnect();

    // Exemple d'utilisation de la fonction ajouterAccident ) => OK
    //ajouterAccident($db, 25, "2023-06-26 15:30:00", "Paris", 48.8566, 2.3522, 1, 2, 3, 4);

    // exemple d'utilisation de la fonction getDescrAthmoValues
    //$valeursDescrAthmo = getValDescrAthmo($db);
    //echo "valeursDescrAthmo <br>";
    //print_r($valeursDescrAthmo);

    // exemple d'utilisation de la fonction getValDescrLum
    //$valeursDescrLum = getValDescrLum($db);
    //echo "valeursDescrAthmo <br>";
    //print_r($valeursDescrAthmo);

    // exemple d'utilisation de la fonction getValDescrEtatSurf
    //$valeursDescrEtatSurf = getValDescrEtatSurf($db);
    //echo "valeursDescrAthmo <br>";
    //print_r($valeursDescrAthmo);

    // exemple d'utilisation de la fonction getValDescrDispoSecu
    //$valeursDescrDispoSecu = getValDescrDispoSecu($db);
    //echo "valeursDescrAthmo <br>";
    //print_r($valeursDescrAthmo);

    // exemple d'utilisation de la fonction searchEvent
    $age = 18;
    $date_heure = "";
    $ville = "";
    $latitude = "";
    $longitude = "";
    $descr_athmo_id = "";
    $descr_lum_id = "";
    $descr_etat_surf_id = "";
    $descr_dispo_secu_id = "";
    
    // $searchedArr = searchEvent($db, $age, $date_heure, $ville, $latitude, $longitude, $descr_athmo_id, $descr_lum_id, $descr_etat_surf_id, $descr_dispo_secu_id);
    // echo "searchedArr :  <br>";
    // print_r($searchedArr);
    
    // foreach ($searchedArr as $val){
    //     print_r(getInfosEvent($db, $val));
    // }
    
    // $eventArr = getInfosAllEvent($db);
    // echo "eventArr :  <br>";
    // print_r($eventArr);
    
    
    // ------------------------------ TEST PREDICTION CLUSTER ------------------------------
        
    
    //$inputFile = 'acc_selection.csv';
    //$outputFile = 'acc_selection_cleared.csv';
    //$selectedColumns = ['age', 'latitude', 'longitude', 'descr_athmo_id', 'descr_lum_id', 'descr_etat_surf_id', 'descr_dispo_secu_id'];    
    //$result = createCSVFromSelectedColumns($inputFile, $outputFile, $selectedColumns);
    
    /*
    // Génération du CSV avec tout les accidents
    $filename = '../data/acc_selection.csv';
    $resultCSV = getInfosAllEventCSV($db, $filename);
    
    // Chemin du CSV avec les accidents à clusteriser
    $csvFile = "../data/acc_selection.csv";
    */
    
    $latitude = 47.1234;
    $longitude = -1.5678;
        
    $cluster = predictOneCluster($latitude, $longitude);
    echo "Cluster prédit : $cluster";

    
    // Prédiction des cluster
    //predictCluster($csvFile);
    
    
    // ------------------------------ TEST PREDICTION GRAVITE HAUT NIVEAU------------------------------
    
    
    // notre modèle de prédiciton utilise les paramètres  :
    // ['age', 'latitude', 'longitude','descr_athmo', 'descr_lum', 'descr_etat_surf' ,'descr_dispo_secu']
    
    // Récupération de l'ID de l'accident à prédire grâce à la requête AJAX
    $id_acc = 1;
    
    // Chemin du fichier JSON de sortie
    //$jsonFile = '../json/acc_for_predict.json'; 

    // Prédiction de haut niveau (SVM, RF et MLP)
    //$resPredictHautNiveau = predictionGraviteHautNiveau($db, $id_acc);
    //print_r($resPredictHautNiveau);
    
    /*
    // Exemple d'accès aux valeurs de "gravite" et "methode" de MLP
    $graviteMLP = $resPredictHautNiveau[0]['gravite'];
    $methode = $resPredictHautNiveau[0][0]['methode'];
    
    $jsonString = file_get_contents('../json/result_mlp_predict.json');
    $data = json_decode($jsonString, true);
        
    if (isset($data['gravite'])) { // ne fonctionne jamais car format du json incorrect 
        $gravite = $data['gravite'];
        echo "La valeur de gravité est : " . $gravite;
    } else {
        echo "Erreur : impossible de récupérer la valeur de gravité.";
    }*/
    
    // ------------------------------ TEST PREDICTION GRAVITE KNN ------------------------------
    
    
    // Récupération de l'ID de l'accident à prédire grâce à la requête AJAX
    $id_acc = 1;

    //$resPredictKNN = predictionGraviteKNN($db, $id_acc);
    //print_r($resPredictKNN);
    
    //echo $outputKNN;
    /*
    Array
    (
    [0] => Array
        (
            [gravite] => 3
        )

    )
    */
    
    // ------------------------------ END TEST ------------------------------
    
    
    // Fermeture de la connexion
    $db = null;
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}




?>