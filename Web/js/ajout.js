

function ajaxRequest(type, url, callback, data = null){

    let xhr;

    console.log("ajaxRequest");
    // Create XML HTTP request.
    xhr = new XMLHttpRequest();
    if (type == 'GET' && data != null) {
        console.log("GET request with data");
        url += '?' + data;
    }

    /*
    if (type == 'POST' && data != null) {
        url += ' ' + data;
        console.log("POST request with data");

    }
    */
    
  

    // if (type == 'POST' && data != null) {
    //     console.log("POST request with data");
    //     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
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
var weatherFilter = document.getElementById('conditionAtmospherique');

var lumFilter = document.getElementById('luminosite');

var roadFilter = document.getElementById('etatRoute');

var secuFilter = document.getElementById('securite');

// console.log(data)

//remplissage des filtres

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


function response(infos){
  console.log(infos);
  // Traitez la réponse ici
}

var button = document.getElementById('submitBtn');
button.addEventListener('click', function() {
// Effectuer la requête Ajax ici en appelant la fonction ajaxRequest
console.log("BUTTON EVENT ");
var url = '../php/ajoutRequest.php/ajout';
var type = 'POST';

//var requestRessource = 'ajout'; // La valeur de requestRessource
var age = document.getElementById('age').value;
var ville = document.getElementById('ville').value.toUpperCase();
var latitude = document.getElementById('latitude').value;
var longitude = document.getElementById('longitude').value;
var day = document.getElementById('day').value;
var month = document.getElementById('month').value;
var conditionAtmospherique = document.getElementById('conditionAtmospherique').value;
var luminosite = document.getElementById('luminosite').value;
var etatRoute = document.getElementById('etatRoute').value;
var securite = document.getElementById('securite').value;

var data = 'age=' + age +
           '&ville=' + ville +
           '&latitude=' + latitude +
           '&longitude=' + longitude +
           '&day=' + day +
           '&month=' + month +
           '&conditionAtmospherique=' + conditionAtmospherique +
           '&luminosite=' + luminosite +
           '&etatRoute=' + etatRoute +
           '&securite=' + securite;
          console.log(data);

ajaxRequest(type, url, response, data);
});







// $(document).ready(function() {
//     var submitBtn = $('#submitBtn');
//     var ageInput = $('#age');
//     var villeInput = $('#ville');
//     var latitudeInput = $('#latitude');
//     var longitudeInput = $('#longitude');
//     var dayInput = $('#day');
//     var monthInput = $('#month');
//     var conditionAtmospheriqueInput = $('#conditionAtmospherique');
//     var luminositeInput = $('#luminosite');
//     var etatRouteInput = $('#etatRoute');
//     var securiteInput = $('#securite');
  
//     submitBtn.on('click', function() {
//       var url = '../php/ajoutRequest.php';
//       var type = 'POST';
  
//       var age = ageInput.val();
//       var ville = villeInput.val();
//       var latitude = latitudeInput.val();
//       var longitude = longitudeInput.val();
//       var day = dayInput.val();
//       var month = monthInput.val();
//       var conditionAtmospherique = conditionAtmospheriqueInput.val();
//       var luminosite = luminositeInput.val();
//       var etatRoute = etatRouteInput.val();
//       var securite = securiteInput.val();
  
//       var data = {
//         age: age,
//         ville: ville,
//         latitude: latitude,
//         longitude: longitude,
//         day: day,
//         month: month,
//         conditionAtmospherique: conditionAtmospherique,
//         luminosite: luminosite,
//         etatRoute: etatRoute,
//         securite: securite
//       };
  
//       $.ajax({
//         type: type,
//         url: url,
//         data: data,
//         success: function(response) {
//           console.log(response);
//           // Traitez la réponse ici
//         },
//         error: function(xhr, status, error) {
//           console.error(error);
//         }
//       });
//     });
//   });
  


// function buttonAjout(){

// // Sélectionnez le bouton de soumission
// const submitBtn = document.getElementById('submitBtn');
// submitBtn.addEventListener('click', function(event) {
//   event.preventDefault(); 
  
// });
// }

let urlFilters = '../php/ajoutRequest.php/filters'
// let urlAjout = '../php/ajoutRequest.php/ajout'

ajaxRequest('GET', urlFilters, updateFilters)
// ajaxRequest('POST', urlAjout, buttonAjout)