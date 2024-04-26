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

var queryString = location.search.substring(1);
var infos = queryString.split("=")
var id_acc = infos[1];


let colNames = {
    1: 'Indemne',
    2: 'Tué', 
    3: 'Blessé hospitalisé',
    4: 'Blessé léger'
}


ajaxRequest('GET', `../php/predictionHautNiveauRequest.php/svmMlpRf?id_acc=${id_acc}`, function(result){
    document.getElementById('mlp').innerHTML = colNames[result[0]['gravite']]
    document.getElementById('svm').innerHTML = colNames[result[1]['gravite']]
    document.getElementById('rf').innerHTML = colNames[result[2]['gravite']]
})


ajaxRequest('GET', `../php/predictionHautNiveauRequest.php/knn?id_acc=${id_acc}`, function(result){
    document.getElementById('knn').innerHTML = colNames[result[0]['gravite']]
})





