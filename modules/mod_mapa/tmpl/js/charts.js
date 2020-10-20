var canvas = null;
var pieProps = new Object();


/*
 * complementos necesario para poder mostrar un char en un mapa 
 * http://blog.crondesign.com/2012/05/simple-javascript-pie-chart-using-html5.html
 * 
 */

/**
 * @description muesta un pie chart como marker en el mapa.
 * @param {node} nodo del cual se mostrara el pie chart
 * @returns {true} si todo se ha cumplido correctamente
 * 
 * 
 */
function showPieChart(node) {
    var myLatlng = new google.maps.LatLng(-25.363882, 131.044922);
    if (node.data && !node.data.grafico) {
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            icon: pieChart(21, [70, 25, 15], ['Carbs', 'Protein', 'Fat'], ['CCCCCC', 'FFBF43', 'FF850D']),
            title: "Hello World!"
        });
        node.data.grafico = marker;
    }
}

/**
 * @description Quita del mapa el chart pie de ese proyecto
 * @param {type} node
 * @returns {undefined}
 */
function hidePieChart(node) {
    if (node.data && node.data.grafico) {
        node.data.grafico.setMap(null);
        node.data.grafico = null;
    }
}


function pieChart(radius, percentages, n, colors) {
    var canvas = document.createElement("canvas");
    var a = pieProps.a = canvas.getContext("2d");
    pieProps.colors = colors
    pieProps.i = 0
    canvas.width = 3.5 * radius;
    canvas.height = 2.5 * radius;
    x = y = canvas.height / 2;
    a.font = "bold 12px Arial";
    var u = 0;
    var v = 0;
    for (i = 0; i < percentages.length; i++) {
        v += percentages[i];
        W(i)(x, y, radius, u, v);
        u = v;
        a.fillText(n[i], x + radius + 10, y - radius / 2 + i * 18);
    }

    return canvas.toDataURL();
}

function W(x, y, r, u, v) {
    var a = pieProps.a
    r ? a.beginPath() | a.fill(a.moveTo(x, y) | a.arc(x, y, r, (u || 0) / 50 * Math.PI, (v || 7) / 50 * Math.PI, 0) | a.lineTo(x, y))
            : a.fillStyle = '#' + pieProps.colors[pieProps.i++];
    return W;
}