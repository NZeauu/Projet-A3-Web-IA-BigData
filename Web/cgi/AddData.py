#creation d'un script python qui récupère les données du csv et créé des fichier sql pour les insérer dans la base de données
#On créé la table "accident" avec à l'intérieur l'id de l'accident auto increment, l'age, la date/heure en timestamp, la ville varchar, la latitude et longitude en floats
#une autre table "descr_athmo" avec l'identifiant de la description et la description
#une autre table "descr_lum" avec l'identifiant de la description et la description
#une autre table "descr_etat_surf" avec l'identifiant de la description et la description
#une autre table "descr_dispo_secu" avec l'identifiant de la description et la description

import pandas as pd
import os
import mysql.connector


conn = mysql.connector.connect(host="127.0.0.1", user="etu740", password="mkdxuhnf", database="etu740")
c = conn.cursor()

#ouverture du fichier csv
df = pd.read_csv('../data/stat_acc_V3_cleared.csv', sep=';')


#drop tables a chaque lancement de script
c.execute("DROP TABLE IF EXISTS accident;")
c.execute("DROP TABLE IF EXISTS descr_athmo;")
c.execute("DROP TABLE IF EXISTS descr_lum;")
c.execute("DROP TABLE IF EXISTS descr_etat_surf;")
c.execute("DROP TABLE IF EXISTS descr_dispo_secu;")



#création de la table descr_athmo
c.execute("CREATE TABLE IF NOT EXISTS descr_athmo (id INTEGER PRIMARY KEY, descr VARCHAR(50));")

#création de la table descr_lum
c.execute("CREATE TABLE IF NOT EXISTS descr_lum (id INTEGER PRIMARY KEY, descr VARCHAR(50));")

#création de la table descr_etat_surf
c.execute("CREATE TABLE IF NOT EXISTS descr_etat_surf (id INTEGER PRIMARY KEY, descr VARCHAR(50));")

#création de la table descr_dispo_secu
c.execute("CREATE TABLE IF NOT EXISTS descr_dispo_secu (id INTEGER PRIMARY KEY, descr VARCHAR(50));")

# Création de la table accident avec réinitialisation de l'auto-incrément à 0
c.execute("CREATE TABLE IF NOT EXISTS accident (id_acc INTEGER PRIMARY KEY AUTO_INCREMENT, age INTEGER, date_heure TIMESTAMP, ville VARCHAR(50), latitude FLOAT, longitude FLOAT, descr_athmo_id INTEGER, descr_lum_id INTEGER, descr_etat_surf_id INTEGER, descr_dispo_secu_id INTEGER, FOREIGN KEY (descr_athmo_id) REFERENCES descr_athmo(id), FOREIGN KEY (descr_lum_id) REFERENCES descr_lum(id), FOREIGN KEY (descr_etat_surf_id) REFERENCES descr_etat_surf(id), FOREIGN KEY (descr_dispo_secu_id) REFERENCES descr_dispo_secu(id)) AUTO_INCREMENT = 1;")


#création de dict pour les descriptions pour avoir une meilleure ergonomie sur les formulaires du projet

descr_athmo = {
    1: "Normale",
    2: "Pluie légère",
    3: "Pluie forte",
    4: "Neige - grêle",
    5: "Brouillard - fumée",
    6: "Vent fort - tempête",
    7: "Temps éblouissant",
    8: "Temps couvert",
    9: "Autre"
}


descr_lum = {
    1: "Plein jour",
    2: "Crépuscule ou aube",
    3: "Nuit sans éclairage public",
    4: "Nuit avec éclairage public non allumé",
    5: "Nuit avec éclairage public allumé"
}

descr_etat_surf = {
    1: "Normale",
    2: "Mouillée",
    3: "Flaques",
    4: "Inondée",
    5: "Enneigée",
    6: "Boue",
    7: "Verglacée",
    8: "Corps gras - huile",
    9: "Autre"
}


descr_dispo_secu = {
    1: "Utilisation d une ceinture de sécurité",
    2: "Utilisation d un casque",
    3: "Présence d une ceinture de sécurité - Utilisation non déterminable",
    4: "Présence de ceinture de sécurité non utilisée",
    5: "Autre - Non déterminable",
    6: "Présence d un équipement réfléchissant non utilisé",
    7: "Présence d un casque non utilisé",
    8: "Utilisation d un dispositif enfant",
    9: "Présence d un casque - Utilisation non déterminable",
    10: "Présence dispositif enfant - Utilisation non déterminable",
    11: "Autre - Utilisé",
    12: "Utilisation d un équipement réfléchissant",
    13: "Autre - Non utilisé",
    14: "Présence équipement réfléchissant - Utilisation non déterminable",
    15: "Présence d un dispositif enfant non utilisé"
}

#insertion des données dans les tables à partir du csv et des dict

for i in range(len(df)):
    # Insertion dans la table descr_athmo
    c.execute("INSERT IGNORE INTO descr_athmo (id, descr) VALUES (%s, %s)", (int(df['descr_athmo'][i]), descr_athmo[df['descr_athmo'][i]]))

    # Insertion dans la table descr_lum
    c.execute("INSERT IGNORE INTO descr_lum (id, descr) VALUES (%s, %s)", (int(df['descr_lum'][i]), descr_lum[df['descr_lum'][i]]))

    # Insertion dans la table descr_etat_surf
    c.execute("INSERT IGNORE INTO descr_etat_surf (id, descr) VALUES (%s, %s)", (int(df['descr_etat_surf'][i]), descr_etat_surf[df['descr_etat_surf'][i]]))

    # Insertion dans la table descr_dispo_secu
    c.execute("INSERT IGNORE INTO descr_dispo_secu (id, descr) VALUES (%s, %s)", (int(df['descr_dispo_secu'][i]), descr_dispo_secu[df['descr_dispo_secu'][i]]))


#insertion des valeurs dans la table "accident" avec les clés étrangères correspondantes
for i in range(len(df)):
    # Récupérer les clés étrangères à partir des dictionnaires
    descr_athmo_id = int(df['descr_athmo'][i])
    descr_lum_id = int(df['descr_lum'][i])
    descr_etat_surf_id = int(df['descr_etat_surf'][i])
    descr_dispo_secu_id = int(df['descr_dispo_secu'][i])

    # Insérer les valeurs dans la table "accident" avec les clés étrangères
    c.execute("INSERT INTO accident (age, date_heure, ville, latitude, longitude, descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)", (int(df['age'][i]), df['date'][i], str(df['ville'][i]), float(df['latitude'][i]), float(df['longitude'][i]), descr_athmo_id, descr_lum_id, descr_etat_surf_id, descr_dispo_secu_id))



#Valider les changements
conn.commit()

#fermeture de la connexion
conn.close()