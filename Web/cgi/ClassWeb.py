import json
import joblib
import sys
import numpy as np


def high_level_classification(accident_info, classification_method, trained_model):
    # Utilisation du modèle de classification spécifié
    if classification_method == 'SVM':
        model = joblib.load(trained_model)
        # Effectuer la prédiction avec le modèle 1
        
    elif classification_method == 'RF':
        model = joblib.load(trained_model)
        # Effectuer la prédiction avec le modèle 2
    
    elif classification_method == 'MLP':
        model = joblib.load(trained_model)

    # Extraction des valeurs du dictionnaire et conversion en nombres réels
    accident_info_values = [
        accident_info['age'],
        accident_info['latitude'],
        accident_info['longitude'],
        accident_info['descr_athmo_id'],
        accident_info['descr_lum_id'],
        accident_info['descr_etat_surf_id'],
        accident_info['descr_dispo_secu_id']
    ]
    
    # Prédiction de la gravité de l'accident
    gravite = model.predict(np.array([accident_info_values]).reshape(1, -1))[0]
    
    # Conversion du résultat en format JSON
    result = {'gravite': int(gravite)}
    json_result = json.dumps(result)
    
    return json_result


#-----------------------------------------------------MISE EN PLACE DES ARGUMENTS-----------------------------------------------------#
#accident_info au format [{"premiere_cle": valeur, "deuxieme_cle": valeur, ...}"}] avec les memes infos qu'une ligne du csv sans descr_grav
#on prend uniquement les colonnes latitude, longitude, descr_cat_veh, descr_athmo, descr_lum, descr_etat_surf, age
#classification_method au format "SVM", "RF" ou "MLP"
#trained_model au format "models/nom_du_fichier.pkl"
#-------------------------------------------------------------------------------------------------------------------------------------#

accident_info = json.loads(sys.argv[1])[0]

json_result = high_level_classification(accident_info, sys.argv[2], sys.argv[3])
print(json_result)

#récupération de la méthode de classification
classification_method = sys.argv[2]

#booleen pour choisir si on veut exporter le resultat dans un fichier json ou non
export = True

if export == True:
    #export du resultat json_result dans un fichier json
    if classification_method == 'SVM': 
        with open('../json/result_svm_predict.json', 'w') as outfile:
            json.dump(json_result, outfile)
    
    if classification_method == 'RF': 
        with open('../json/result_rf_predict.json', 'w') as outfile:
            json.dump(json_result, outfile)
            
    if classification_method == 'MLP': 
        with open('../json/result_mlp_predict.json', 'w') as outfile:
            json.dump(json_result, outfile)
    
        
    