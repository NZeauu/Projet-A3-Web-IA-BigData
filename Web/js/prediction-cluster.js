
// -----------------------------------------------------------------------------------------------------------
// -------------------------------------------- AFFICHAGE DATA -----------------------------------------------
// -----------------------------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------------------------
// ------------------------------------------- VALEURS FILTRES -----------------------------------------------
// -----------------------------------------------------------------------------------------------------------



function ajaxRequest(type, url, callback, data = null) {

    let xhr;

    console.log("ajaxRequest");
    // Create XML HTTP request.
    xhr = new XMLHttpRequest();
    if (type == 'GET' && data != null) {
        console.log("GET request with data");
        url += '?' + data;
    }


    xhr.open(type, url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Add the onload function.
    xhr.onload = () => {

        switch (xhr.status) {
            case 200:
            case 201:
                console.log(xhr.responseText);
                callback(JSON.parse(xhr.responseText));
                break;
            default:
                httpErrors(xhr.status);
        }
    };

    // Send XML HTTP request.
    xhr.send(data);

}

function GenerationLoad(data) {
    var mapData = [{
        type: 'scattermapbox',
        lat: [],
        lon: [],
        mode: 'markers',
        marker: {
            size: 10,
            color: 'rgb(255, 0, 0)',
            opacity: 0.7
        },
        text: []
    }];

    var layout = {
        autosize: true,
        hovermode: 'closest',
        mapbox: {
            style: 'open-street-map',
            bearing: 0,
            center: {
                lat: 0,
                lon: 0
            },
            pitch: 0,
            zoom: 0
        },
        showlegend: false
    };
    // console.log(data);
    console.log(data.latitude)

    mapData[0].lat = data['latitude'];
    mapData[0].lon = data['longitude'];

    Plotly.newPlot('map', mapData, layout);

}


function GenerationRefresh(data) {
    console.log(data);
  
    // Tableau de couleurs pour les clusters
    var colors = ['rgb(255, 0, 0)', 'rgb(0, 255, 0)', 'rgb(0, 0, 255)', 'rgb(255, 255, 0)', 'rgb(255, 0, 255)'];
  
    var mapData = [];
    
    // Créer un objet pour chaque cluster
    data.forEach(function(cluster, index) {
      var clusterData = {
        type: 'scattermapbox',
        lat: [],
        lon: [],
        mode: 'markers',
        marker: {
          size: 10,
          color: colors[index % colors.length],  // Attribution de la couleur en fonction de l'index du cluster
          opacity: 0.7
        },
        text: []
      };
  
      // Ajouter les points de données du cluster à l'objet clusterData
      cluster.forEach(function(element) {
        var latitude = element.latitude;
        var longitude = element.longitude;
  
        clusterData.lat.push(latitude);
        clusterData.lon.push(longitude);
      });
  
      // Ajouter l'objet clusterData au tableau mapData
      mapData.push(clusterData);
    });
  
    var layout = {
      autosize: true,
      hovermode: 'closest',
      mapbox: {
        style: 'open-street-map',
        bearing: 0,
        center: {
          lat: 0,
          lon: 0
        },
        pitch: 0,
        zoom: 0
      },
      showlegend: false
    };
  
    Plotly.newPlot('map', mapData, layout);
  }
  
  

function updateFilters(data) {
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

    for (let i = 0; i < data['descr_athmo'].length; i++) {
        let option = document.createElement('option')
        option.value = data['descr_athmo'][i]
        option.innerHTML = data['descr_athmo'][i]
        weatherFilter.appendChild(option)
    }

    for (let i = 0; i < data['descr_lum'].length; i++) {
        let option = document.createElement('option')
        option.value = data['descr_lum'][i]
        option.innerHTML = data['descr_lum'][i]
        lumFilter.appendChild(option)
    }

    for (let i = 0; i < data['descr_surf'].length; i++) {
        let option = document.createElement('option')
        option.value = data['descr_surf'][i]
        option.innerHTML = data['descr_surf'][i]
        roadFilter.appendChild(option)
    }

    for (let i = 0; i < data['dispo_secu'].length; i++) {
        let option = document.createElement('option')
        option.value = data['dispo_secu'][i]
        option.innerHTML = data['dispo_secu'][i]
        secuFilter.appendChild(option)
    }

}


let urlFilters = '../php/visualisationRequest.php/filters'
ajaxRequest('GET', urlFilters, updateFilters)




// -----------------------------------------------------------------------------------------------------------
// ------------------------------------------- PLOTLY MAP -----------------------------------------------
// -----------------------------------------------------------------------------------------------------------


// -------------------------------------------------DISPLAY AU CHARGEMENT DE LA PAGE -------------------------------
// Fonction pour créer et afficher la carte par défaut
function afficherCarteParDefaut() {
    var url = '../php/visualisationMapRequest.php/VisualisationMap';
    var type = 'GET';
    ajaxRequest(type, url, GenerationLoad);
}

window.addEventListener('load', afficherCarteParDefaut);


// -------------------------------------------MAP AFTER FILTER --------------------------------------------
document.getElementById('buttonValid').addEventListener('click', function () {
    console.log("BUTTON EVENT ");
    var url = '../php/visualisationMapRequest.php/VisualisationMapSubmit';
    var type = 'GET';


    lumBool = false
    ageBool = false
    meteoBool = false
    routeBool = false
    securiteBool = false
    villeBool = false
    dateBool = false
    latitudeBool = false
    longBool = false


    let luminosite = document.getElementById('luminosite')
    let age = document.getElementById('age')
    let meteo = document.getElementById('conditionAtmospherique')
    let route = document.getElementById('etatRoute')
    let securite = document.getElementById('securite')

    // let ville = document.getElementById('ville').value;
    // ville = ville.toUpperCase();

    let ville = document.getElementById('ville')

    let date = document.getElementById('date')
    // let day = document.getElementById('day')
    // let month = document.getElementById('month')
    // let date = document.getElementById('day').value + ' ' + document.getElementById('month').value;
    let latitude = document.getElementById('latitude')
    let longitude = document.getElementById('longitude')


    if (luminosite.value != 'noLumFilter') {
        lumBool = true
    }
    if (age.value !== '') {
        ageBool = true
    }
    if (latitude.value !== '') {
        latitudeBool = true
    }
    if (longitude.value !== '') {
        longBool = true
    }
    if (meteo.value != 'noWeatherFilter') {
        meteoBool = true
    }
    if (route.value != 'noRoadFilter') {
        routeBool = true
    }
    if (securite.value != 'noSecuFilter') {
        securiteBool = true
    }
    if (ville.value !== '') {
        villeBool = true
    }
    if (date.value !== "") {
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
     urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?lum=' + lumDictInv[luminosite.value]
     started = true
 }
 if(ageBool){
     if(started){
         urlDataSearch += '&age=' + age.value
     }
     else{
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?age=' + age.value
         started = true
     }
 }
 if(latitudeBool){
     if(started){
         urlDataSearch += '&latitude=' + latitude.value
     }
     else{
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?latitude=' + latitude.value
         started = true
     }
 }
 if(longBool){
     if(started){
         urlDataSearch += '&longitude=' + longitude.value
     }
     else{
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?longitude=' + longitude.value
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
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?athmo=' + meteoDictInv[meteo.value]
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
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?surf=' + routeDictInv[route.value]
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
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?secu=' + securiteDictInv[securite.value]
         started = true
     }
 }

 if(villeBool){
     if(started){
         urlDataSearch += '&ville=' + ville.value

     }
     else{
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?ville=' + ville.value
         started = true
     }
 }

 if(dateBool){
     if(started){
         urlDataSearch += '&date=' + date.value
     }
     else{
         urlDataSearch = '../php/visualisationMapRequest.php/VisualisationMapSubmit?date=' + date.value
         started = true
     }
 }

 console.log(urlDataSearch)

 if(urlDataSearch == ''){
     urlDataSearch = '../php/visualisationMapRequest.php/data'
 }
 else{
     ajaxRequest('GET', urlDataSearch, GenerationRefresh)
    //  console.log(data); ASYNCHRONE
     }

})


let buttonReset = document.getElementById('buttonReset')

buttonReset.addEventListener('click', function(){

    //reset filtres textes
    let ageFilter = document.getElementById('age')
    let cityFilter = document.getElementById('ville')
    let latFilter = document.getElementById('latitude')
    let longFilter = document.getElementById('longitude')
    let dateFilter = document.getElementById('date')
    var weatherFilter = document.getElementById('conditionAtmospherique')
    var lumFilter = document.getElementById('luminosite')
    var roadFilter = document.getElementById('etatRoute')
    var secuFilter = document.getElementById('securite')

    //reset des filtres
    weatherFilter.innerHTML = ''
    lumFilter.innerHTML = ''
    roadFilter.innerHTML = ''
    secuFilter.innerHTML = ''
    ageFilter.value = ''
    cityFilter.value = ''
    latFilter.value = ''
    longFilter.value = ''
    dateFilter.value = ''

let urlDataReset = '../php/visualisationMapRequest.php/VisualisationMap'
ajaxRequest('GET', urlDataReset, GenerationLoad)

})



let buttonCluster = document.getElementById('buttonCluster')

buttonCluster.addEventListener('click', function(){

    //reset filtres textes
    let ageFilter = document.getElementById('age')
    let cityFilter = document.getElementById('ville')
    let latFilter = document.getElementById('latitude')
    let longFilter = document.getElementById('longitude')
    let dateFilter = document.getElementById('date')
    var weatherFilter = document.getElementById('conditionAtmospherique')
    var lumFilter = document.getElementById('luminosite')
    var roadFilter = document.getElementById('etatRoute')
    var secuFilter = document.getElementById('securite')

let urlDataReset = '../php/predictionClusterRequest.php/PredictionCluster'
ajaxRequest('GET', urlDataReset, GenerationRefresh)

})