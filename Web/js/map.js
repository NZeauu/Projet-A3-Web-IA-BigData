// include("constants.php");
// setlocale(LC_TIME, "fr_FR.utf8");
// $time = time();
// session_start();

// // Inclure le fichier avec les fonctions
// include("../php/provdatabase.php");

// // Connexion à la base de données
// $db = dbConnect();


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

// Requête AJAX prend latitute et longitude et affiche les points a cet endroit
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
  if (xhr.readyState === 4) {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      mapData[0].lat = response.latitude;
      mapData[0].lon = response.longitude;
      // Create plotly map
      Plotly.newPlot('map', mapData, layout);
    } else {
      console.error('Erreur de requête AJAX: ' + xhr.status);
    }
  }
};
xhr.open('GET', 'provdatabase.php', true);
xhr.send();

