
// -----------------------------------------------------------------------------------------------------------
// -------------------------------------------- AFFICHAGE DATA -----------------------------------------------
// -----------------------------------------------------------------------------------------------------------

lastValue = 1000 //valeur par défaut du nombre de valeurs à afficher

//Cette fonction permet l'affichage du loading le temps de la requête
//Elle est uniquement propre à la recherche des données d'accident

function ajaxRequestTab(type, url, callback, data = null){

    document.addEventListener('DOMContentLoaded', function() {
        var loadingElement = document.getElementById('loading');

        loadingElement.style.display = 'flex'; // Utiliser le flexbox pour centrer les éléments
        loadingElement.style.flexDirection = 'column'; // Placer les éléments en colonne
        loadingElement.style.alignItems = 'center'; // Centrer les éléments horizontalement


        var loadingImage = document.createElement('img');
        loadingImage.src = '../img/gif_loading.gif';

        var loadingText = document.createElement('h1');
        loadingText.innerHTML = "Chargement en cours...";

       
        loadingElement.appendChild(loadingImage);
        loadingElement.appendChild(loadingText);
        


        let xhr;

        console.log("ajaxRequest");
        // Create XML HTTP request.
        xhr = new XMLHttpRequest();
        if (type == 'GET' && data != null) {
            console.log("GET request with data");
            url += '?' + data;
        }
        
        // if (type == 'POST' && data != null){
        //     url += ' ' + data;
        // }

        console.log(url)
        xhr.open(type, url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Add the onload function.
        xhr.onload = () =>
        {
            switch (xhr.status)
            {
            case 200:
            case 201:
                // console.log(xhr.responseText);
                callback(JSON.parse(xhr.responseText));
                break;
            default:
                httpErrors(xhr.status);
            }
        };

        // Send XML HTTP request.
        xhr.send(data);

    });
}

events = []

function createTab(data){

    events = data

    // console.log(data)

    let tabDiv = document.getElementById('tab')

    tabDiv.innerHTML = ''

    let tab = document.createElement('table')

    let tabHead = document.createElement('thead')
    let tabHeadRow = document.createElement('tr')

    //dictionnaire des noms de colonnes
    let colNames = {
        1: 'Prédiction gravité',
        2: 'ID Accident', 
        3: 'Age',
        4: 'Date',
        5: 'Ville',
        6: 'Latitude',
        7: 'Longitude',
        8: 'Description Météo',
        9: 'Description Luminosité',
        10: 'Description Route',
        11: 'Description Sécurité'
    }


    //Creation de 10 cellules pour le header de la forme tabHeadRowCellX
    for(let i = 0; i < 11; i++){
        let tabHeadRowCell = document.createElement('th')
        tabHeadRowCell.innerHTML = colNames[i+1]
        tabHeadRow.appendChild(tabHeadRowCell)
    }

    tabHead.appendChild(tabHeadRow)
    tab.appendChild(tabHead)

    let tabBody = document.createElement('tbody')

    //Creation des lignes du tableau
    // console.log(data.length)

    //dict des noms de colonnes
    let dataColNames = {
        1: 'id_acc',
        2: 'age',
        3: 'date_heure',
        4: 'ville',
        5: 'latitude',
        6: 'longitude',
        7: 'descr_athmo',
        8: 'descr_lum',
        9: 'descr_etat_surf',
        10: 'descr_dispo_secu'
    }


    for(let i = 0; i <= lastValue; i++){
        let tabBodyRow = document.createElement('tr')

        let selectRowCell = document.createElement('td')
        let radioSelect = document.createElement('input')
        radioSelect.setAttribute('type', 'radio')
        radioSelect.id = 'selectEvent'
        radioSelect.name = data[i]['id_acc']
        radioSelect.setAttribute('onclick', 'redirect(this)')
        selectRowCell.appendChild(radioSelect)
        tabBodyRow.appendChild(selectRowCell)

        //Creation des cellules de la ligne
        for(let j = 0; j < 10; j++){
            let tabBodyRowCell = document.createElement('td')
            // console.log(data[i][dataColNames[j+1]])
            
            tabBodyRowCell.innerHTML = data[i][dataColNames[j+1]]
            
            
            tabBodyRow.appendChild(tabBodyRowCell)
        }

        tabBody.appendChild(tabBodyRow)
    }

    tab.appendChild(tabBody)

    tabDiv.appendChild(tab)

    var loadingElement = document.getElementById('loading');
    loadingElement.style.display = 'none';
    loadingElement.innerHTML = ''
}

let maxSelect = document.getElementById('selectNombreValeurs')

maxSelect.addEventListener('change', function(){

    var loadingElement = document.getElementById('loading');

    loadingElement.innerHTML = ''

    loadingElement.style.display = 'flex'; // Utiliser le flexbox pour centrer les éléments
    loadingElement.style.flexDirection = 'column'; // Placer les éléments en colonne
    loadingElement.style.alignItems = 'center'; // Centrer les éléments horizontalement


    var loadingImage = document.createElement('img');
    loadingImage.src = '../img/gif_loading.gif';

    var loadingText = document.createElement('h1');
    loadingText.innerHTML = "Chargement en cours...";

       
    loadingElement.appendChild(loadingImage);
    loadingElement.appendChild(loadingText);

    
    if(maxSelect.value != 'All'){
        lastValue = maxSelect.value
    }
    else{
        lastValue = events.length
    } 

    createTab(events)
})




let urlData = '../php/visualisationRequest.php/data'
ajaxRequestTab('GET', urlData, createTab)


let buttonValid = document.getElementById('buttonValid')

buttonValid.addEventListener('click', function(){


    let tabDiv = document.getElementById('tab')

    //suppression du tableau précédent s'il existe déjà 
    tabDiv.innerHTML = ''

    var loadingElement = document.getElementById('loading');

    loadingElement.style.display = 'flex'; // Utiliser le flexbox pour centrer les éléments
    loadingElement.style.flexDirection = 'column'; // Placer les éléments en colonne
    loadingElement.style.alignItems = 'center'; // Centrer les éléments horizontalement 
    
    var loadingImage = document.createElement('img');
    loadingImage.src = '../img/gif_loading.gif';

    var loadingText = document.createElement('h1');
    loadingText.innerHTML = "Chargement en cours...";

   
    loadingElement.appendChild(loadingImage);
    loadingElement.appendChild(loadingText);

    lumBool = false
    ageBool = false
    meteoBool = false
    routeBool = false
    securiteBool = false
    villeBool = false
    dateBool = false
    latitudeBool = false
    longBool = false

    //si les filtres changent d'option on relance une requete
    let luminosite = document.getElementById('luminosite')
    let age = document.getElementById('ageConducteur')
    let meteo = document.getElementById('conditionAtmospherique')
    let route = document.getElementById('etatRoute')
    let securite = document.getElementById('securite')
    let ville = document.getElementById('ville')
    let date = document.getElementById('date')
    let latitude = document.getElementById('latitude')
    let longitude = document.getElementById('longitude')

    if(luminosite.value != 'noLumFilter'){
        lumBool = true
    }
    if(age.value !== ''){
        ageBool = true
    }
    if(latitude.value !== ''){
        latitudeBool = true
    }
    if(longitude.value !== ''){
        longBool = true
    }
    if(meteo.value != 'noWeatherFilter'){
        meteoBool = true
    }
    if(route.value != 'noRoadFilter'){
        routeBool = true
    }
    if(securite.value != 'noSecuFilter'){
        securiteBool = true
    }
    if(ville.value !== ''){
        villeBool = true
    }
    if(date.value !== ""){
        dateBool = true
    }

    //bool pour savoir si une requete est deja demarree
    let started = false

    urlDataSearch = ''

    //creation de la requete
    if(lumBool){
        lumDictInv = {
            'Plein jour': 1,
            'Crépuscule ou aube': 2,
            'Nuit sans éclairage public': 3,
            'Nuit avec éclairage public non allumé': 4,
            'Nuit avec éclairage public allumé': 5
        }
        console.log(lumDictInv[luminosite.value])
        urlDataSearch = '../php/visualisationRequest.php/search?lum=' + lumDictInv[luminosite.value]
        started = true
    }
    if(ageBool){
        if(started){
            urlDataSearch += '&age=' + age.value
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?age=' + age.value
            started = true
        }
    }
    if(latitudeBool){
        if(started){
            urlDataSearch += '&latitude=' + latitude.value
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?latitude=' + latitude.value
            started = true
        }
    }
    if(longBool){
        if(started){
            urlDataSearch += '&longitude=' + longitude.value
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?longitude=' + longitude.value
            started = true
        }
    }
    if(meteoBool){

        meteoDictInv = {
            'Normale': 1,
            'Pluie légère': 2,
            'Pluie forte': 3,
            'Neige - grêle': 4,
            'Brouillard - fumée': 5,
            'Vent fort - tempête': 6,
            'Temps éblouissant': 7,
            'Temps couvert': 8,
            'Autre': 9
        }

        if(started){
            urlDataSearch += '&athmo=' + meteoDictInv[meteo.value]
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?athmo=' + meteoDictInv[meteo.value]
            started = true
        }
    }

    if(routeBool){

        routeDictInv = {
            'Normale': 1,
            'Mouillée': 2,
            'Inondée': 3,
            'Enneigée': 4,
            'Boueuse': 5,
            'Verglacée': 6,
            'Autre': 7
        }

        if(started){
            urlDataSearch += '&surf=' + routeDictInv[route.value]
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?surf=' + routeDictInv[route.value]
            started = true
        }
    }

    if(securiteBool){

        securiteDictInv = {
            'Utilisation d une ceinture de sécurité': 1,
            'Utilisation d un casque': 2,
            'Présence d une ceinture de sécurité - Utilisation': 3,
            'Présence de ceinture de sécurité non utilisée': 4,
            'Autre - Non déterminable': 5,
            'Présence d un équipement réfléchissant non utilisé': 6,
            'Présence d un casque non utilisé': 7,
            'Utilisation d un dispositif enfant': 8,
            'Présence d un casque - Utilisation non déterminabl': 9,
            'Présence dispositif enfant - Utilisation non déter': 10,
            'Autre - Utilisé': 11,
            'Utilisation d un équipement réfléchissant': 12,
            'Autre - Non utilisé': 13,
            'Présence équipement réfléchissant - Utilisation no': 14,
            'Présence d un dispositif enfant non utilisé': 15
        }

        if(started){
            urlDataSearch += '&secu=' + securiteDictInv[securite.value]
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?secu=' + securiteDictInv[securite.value]
            started = true
        }
    }

    if(villeBool){
        if(started){
            urlDataSearch += '&ville=' + ville.value.toUpperCase()

        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?ville=' + ville.value.toUpperCase()
            started = true
        }
    }

    if(dateBool){
        if(started){
            urlDataSearch += '&date=' + date.value
        }
        else{
            urlDataSearch = '../php/visualisationRequest.php/search?date=' + date.value
            started = true
        }
    }

    console.log(urlDataSearch)

    if(urlDataSearch == ''){
        urlDataSearch = '../php/visualisationRequest.php/data'
    }
    else{
        ajaxRequest('GET', urlDataSearch, createTabFromIds)
    }   

})

let buttonReset = document.getElementById('buttonReset')

buttonReset.addEventListener('click', function(){

    //reset filtres textes
    let ageFilter = document.getElementById('ageConducteur')
    let cityFilter = document.getElementById('ville')
    let latFilter = document.getElementById('latitude')
    let longFilter = document.getElementById('longitude')
    let dateFilter = document.getElementById('date')

    ageFilter.value = ''
    cityFilter.value = ''
    latFilter.value = ''
    longFilter.value = ''
    dateFilter.value = ''


    let tabDiv = document.getElementById('tab')

    //suppression du tableau précédent s'il existe déjà 
    tabDiv.innerHTML = ''

    var loadingElement = document.getElementById('loading');

    loadingElement.style.display = 'flex'; // Utiliser le flexbox pour centrer les éléments
    loadingElement.style.flexDirection = 'column'; // Placer les éléments en colonne
    loadingElement.style.alignItems = 'center'; // Centrer les éléments horizontalement 
    
    var loadingImage = document.createElement('img');
    loadingImage.src = '../img/gif_loading.gif';

    var loadingText = document.createElement('h1');
    loadingText.innerHTML = "Chargement en cours...";

   
    loadingElement.appendChild(loadingImage);
    loadingElement.appendChild(loadingText);

    //remise a zero des filtres
    let urlFiltersReset = '../php/visualisationRequest.php/filters'
    ajaxRequest('GET', urlFilters, updateFilters)


    let urlData = '../php/visualisationRequest.php/data'
    ajaxRequest('GET', urlData, createTab)
})

newdata = []

function createTabFromIds(data){

    newdata = []

    //reduction de data à un tableau a 2 dimensions
    for(let i = 0; i < data.length; i++){
        for(let j = 0; j < data[i].length; j++){
            newdata.push(data[i][j])
        }
    }

    //export du tableau newdata en json dans le dossier json
    const jsonData = JSON.stringify(newdata, null, 2);
    // console.log(newdata)

    //suppression du tableau précédent s'il existe déjà 
    let tabDiv = document.getElementById('tab')
    tabDiv.innerHTML = ''

    let tab = document.createElement('table')

    let tabHead = document.createElement('thead')

    let tabHeadRow = document.createElement('tr')

    //dictionnaire des noms de colonnes

    let colNames = {
        1: 'Selection',
        2: 'ID Accident',
        3: 'Age',
        4: 'Date',
        5: 'Ville',
        6: 'Latitude',
        7: 'Longitude',
        8: 'Description Météo',
        9: 'Description Luminosité',
        10: 'Description Route',
        11: 'Description Sécurité'
    }


    //Creation de 10 cellules pour le header de la forme tabHeadRowCellX
    for(let i = 0; i < 11; i++){
        let tabHeadRowCell = document.createElement('th')
        tabHeadRowCell.innerHTML = colNames[i+1]
        tabHeadRow.appendChild(tabHeadRowCell)
    }

    tabHead.appendChild(tabHeadRow)
    tab.appendChild(tabHead)

    let tabBody = document.createElement('tbody')

    //Creation des lignes du tableau
    // console.log(data.length)

    //dict des noms de colonnes
    let dataColNames = {
        1: 'id_acc',
        2: 'age',
        3: 'date_heure',
        4: 'ville',
        5: 'latitude',
        6: 'longitude',
        7: 'descr_athmo',
        8: 'descr_lum',
        9: 'descr_etat_surf',
        10: 'descr_dispo_secu'
    }


    //for each row of data

    for(let i = 0; i < newdata.length; i++){
        let tabBodyRow = document.createElement('tr')

        let selectRowCell = document.createElement('td')
        let radioSelect = document.createElement('input')
        radioSelect.setAttribute('type', 'radio')
        radioSelect.id = 'selectEvent'
        radioSelect.name = newdata[i]['id_acc']
        radioSelect.setAttribute('onclick', 'redirect(this)')
        selectRowCell.appendChild(radioSelect)
        tabBodyRow.appendChild(selectRowCell)

        //Creation des cellules de la ligne
        for(let j = 1; j < 11; j++){
            let tabBodyRowCell = document.createElement('td')

            tabBodyRowCell.innerHTML = newdata[i][dataColNames[j]]

            tabBodyRow.appendChild(tabBodyRowCell)


        }

        tabBody.appendChild(tabBodyRow)
    }

    tab.appendChild(tabBody)

    tabDiv.appendChild(tab)

    var loadingElement = document.getElementById('loading');
    loadingElement.style.display = 'none';
    loadingElement.innerHTML = ''
}


// -----------------------------------------------------------------------------------------------------------
// ------------------------------------------- VALEURS FILTRES -----------------------------------------------
// -----------------------------------------------------------------------------------------------------------



function ajaxRequest(type, url, callback, data = null){

        let xhr;

        

        console.log("ajaxRequest");
        // Create XML HTTP request.
        xhr = new XMLHttpRequest();
        if (type == 'GET' && data != null) {
            console.log("GET request with data");
            url += '?' + data;
        }
        // if (type == 'POST' && data != null){
        //     url += ' ' + data;
        // }


        xhr.open(type, url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Add the onload function.
        xhr.onload = () =>
        {
            switch (xhr.status)
            {
            case 200:
            case 201:
                // console.log(xhr.responseText);
                callback(JSON.parse(xhr.responseText));
                break;
            default:
                httpErrors(xhr.status);
            }
        };

        // Send XML HTTP request.
        xhr.send(data);

}


function updateFilters(data){
    var weatherFilter = document.getElementById('conditionAtmospherique')

    var lumFilter = document.getElementById('luminosite')

    var roadFilter = document.getElementById('etatRoute')

    var secuFilter = document.getElementById('securite')

    //reset des filtres
    weatherFilter.innerHTML = ''
    lumFilter.innerHTML = ''
    roadFilter.innerHTML = ''
    secuFilter.innerHTML = ''

    // console.log(data)

    //remplissage des filtres
    let noWeatherFilter = document.createElement('option')
    noWeatherFilter.innerHTML = "- - - -"
    noWeatherFilter.value = "noWeatherFilter"
    weatherFilter.appendChild(noWeatherFilter)

    let noLumFilter = document.createElement('option')
    noLumFilter.innerHTML = "- - - -"
    noLumFilter.value = "noLumFilter"
    lumFilter.appendChild(noLumFilter)

    let noRoadFilter = document.createElement('option')
    noRoadFilter.innerHTML = "- - - -"
    noRoadFilter.value = "noRoadFilter"
    roadFilter.appendChild(noRoadFilter)

    let noSecuFilter = document.createElement('option')
    noSecuFilter.innerHTML = "- - - -"
    noSecuFilter.value = "noSecuFilter"
    secuFilter.appendChild(noSecuFilter)

    for(let i = 0; i < data['descr_athmo'].length; i++){
        let option = document.createElement('option')
        option.value = data['descr_athmo'][i]
        option.innerHTML = data['descr_athmo'][i]
        weatherFilter.appendChild(option)
    }

    for(let i = 0; i < data['descr_lum'].length; i++){
        let option = document.createElement('option')
        option.value = data['descr_lum'][i]
        option.innerHTML = data['descr_lum'][i]
        lumFilter.appendChild(option)
    }

    for(let i = 0; i < data['descr_surf'].length; i++){
        let option = document.createElement('option')
        option.value = data['descr_surf'][i]
        option.innerHTML = data['descr_surf'][i]
        roadFilter.appendChild(option)
    }

    for(let i = 0; i < data['dispo_secu'].length; i++){
        let option = document.createElement('option')
        option.value = data['dispo_secu'][i]
        option.innerHTML = data['dispo_secu'][i]
        secuFilter.appendChild(option)
    }      

}


let urlFilters = '../php/visualisationRequest.php/filters'
ajaxRequest('GET', urlFilters, updateFilters)