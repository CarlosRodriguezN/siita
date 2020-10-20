var mapContratos = null;
jQuery(document).ready(function() {
    var LatLngc = new google.maps.LatLng(-1.66872587, -78.65233094);
    var myOptions = {
        center: LatLngc,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    jQuery("#ubicacionGeografircaTab").click(function() {
        mapContratos = new google.maps.Map(document.getElementById("mapaContratos"), myOptions);
    });

    jQuery(".verGrafico").live("click", function() {
        var grafReg = this.parentNode.parentNode.id;
        drawHideGrafic(grafReg);
    });

    jQuery(".verCoordenada").live("click", function() {
        var grafReg = this.parentNode.parentNode.id;
        drawHideCoordenada(grafReg);
    });

});



/*
 * FUNCIONES PARA GRAFIACAR
 */







/*
 * @name drawHidePoint
 * @param idCoordenada Identificador de las coordenadas dentro de el array "lstCoordenadas"
 */
function drawHidePoint(idCoordenada) {
    for (var i = 0; i < lstCoordenadas.length; i++) {
        if (lstCoordenadas[i].idCoordenada == idCoordenada) {
            var glPoint = getGLatlng(parseFloat(lstCoordenadas[i].lat), parseFloat(lstCoordenadas[i].lng));
            if (glPoint) {// si se puedo crear el objeto de google lat,long
                if (lstCoordenadas[i].glPointMarket == null) {//  draw
                    //  Creo el Marcador
                    var pointMarker = new google.maps.Marker({
                        position: glPoint,
                        map: mapContratos
                    });
                    //  Agrego el Objeto de Google al array 
                    lstCoordenadas[i].glPointMarket = pointMarker;
                    //  Cambio el texto del ancla
                    jQuery("#grafCoor-" + idCoordenada).html("Ocultar")
                } else {//    hide
                    //  Oculto el marcador del Array
                    lstCoordenadas[i].glPointMarket.setMap(null);
                    //  Pongo en nulo el objeto
                    lstCoordenadas[i].glPointMarket = null;
                    //  Cambio el texto del ancla
                    jQuery("#grafCoor-" + idCoordenada).html("Ver")
                }
            }
            else {
                jAlert("No se puede ubicar el punto.", "SIITA - ECORAE");
            }
        }
    }
}

/*
 *@name getGLatlng
 *@param lat Latitud de una coordenada.
 *@param lng Longitud de una coordenada.
 *@return  Coordenada lat,lng de google.
 *@type gloogle.point
 */
function getGLatlng(lat, lng) {
    //aqui validar el rango
    var glPoint = new google.maps.LatLng(lat, lng);
    return glPoint;
}

/*
 * 
 */
function drawHideGrafic(grafReg) {
    //  Recuperamos solo las coordenadas del ese gráfico
    var grafico = getGraficoByReg(grafReg);
    //  Mandamos a dibujar esas coordenadas
    drawHideGooleGrafic(grafico);
}

/*
 * 
 */
function drawHideGooleGrafic(grafico) {
    switch (grafico.idTipoGrafico) {
        case "1"://   Punto
            drawHideGraficPoint(grafico);
            break;
        case "2"://   Linea(Polilinea)
            drawHideGraficPoliLine(grafico);
            break;
        case "3"://   Area(Poligono)
            drawHideGraficPoligone(grafico);
            break;
        case "4"://   Circulo
            drawHideGraficCircle(grafico)
            break;
    }
}

/*
 * 
 */
function drawHideGraficPoint(grafico, idGrafico) {
    if (grafico.lstCoordenadas.length > 0) {
        var glPoint = getGLatlng(parseFloat(grafico.lstCoordenadas[0].lat), parseFloat(grafico.lstCoordenadas[0].lng));
        //  Creo el Marker
        var pointMarker = new google.maps.Marker({
            position: glPoint,
        });
        setGoogleGraficoToGrafico(grafico, pointMarker);
    }
    else {

    }
}

/*
 * 
 */
function drawHideGraficPoliLine(grafico) {
    if (grafico.lstCoordenadas.length > 0) {
        var points = getArrayGooglePoints(grafico.lstCoordenadas);
        var glPoliline = new google.maps.Polyline({
            path: points,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 2,
        });
        setGoogleGraficoToGrafico(grafico, glPoliline);
    }
}
/*
 * 
 */
function drawHideGraficPoligone(grafico) {
    if (grafico.lstCoordenadas.length > 0) {
        var points = getArrayGooglePoints(grafico.lstCoordenadas);
        points.push(points[0]);//el ultimo es el primero para cerrar el poligono
        var glPoligone = new google.maps.Polygon({
            paths: points,
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
        });
        setGoogleGraficoToGrafico(grafico, glPoligone);
    }
}
/*
 * 
 */
function drawHideGraficCircle(grafico) {
    if (grafico.lstCoordenadas.length > 0) {
        var centro = getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng);
        var borde = getGLatlng(grafico.lstCoordenadas[1].lat, grafico.lstCoordenadas[1].lng);
        var radio = getRadioRevese(centro, borde);
        var populationOptions = {
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
            center: getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng),
            radius: radio
        };
        //todo Es necesario hacer un bound

        mapContratos.setCenter(getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng));
        var glCircle = new google.maps.Circle(populationOptions);
        setGoogleGraficoToGrafico(grafico, glCircle);
    }

}

/*
 * 
 */
function getArrayGooglePoints(coordenadas) {
    var points = new Array();
    for (var j = 0; j < coordenadas.length; j++) {
        var point = getGLatlng(parseFloat(coordenadas[j].lat), parseFloat(coordenadas[j].lng));
        points.push(point);
    }
    return points;
}



function setGoogleGraficoToGrafico(grafico, gGrafico) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == grafico.regGrafico) {
            if (contratos.lstGraficos[j].gGrafico != null) {
                contratos.lstGraficos[j].gGrafico.setMap(null);
                contratos.lstGraficos[j].gGrafico = null;
                jQuery("#g-" + grafico.regGrafico).html("Ver");
            } else
            {
                contratos.lstGraficos[j].gGrafico = gGrafico;
                gGrafico.setMap(mapContratos);
                jQuery("#g-" + grafico.regGrafico).html("Ocultar");
            }
        }

    }
}

function getGraficoByReg(grafReg) {
    var grafico = null;
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == grafReg) {
            grafico = contratos.lstGraficos[j];
        }
    }
    return grafico;
}


function drawHideCoordenada(coordenadaReg) {
    var grafico = null;
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            for (var k = 0; k < contratos.lstGraficos[j].lstCoordenadas.length; k++) {
                if (contratos.lstGraficos[j].lstCoordenadas[k].regCoordenada = coordenadaReg) {
                    if (!contratos.lstGraficos[j].lstCoordenadas[k].gMarker) {
                        var glPoint = getGLatlng(parseFloat(contratos.lstGraficos[j].lstCoordenadas[k].lat), parseFloat(contratos.lstGraficos[j].lstCoordenadas[k].lng));
                        //  Creo el Marker
                        var pointMarker = new google.maps.Marker({
                            position: glPoint,
                        });
                        contratos.lstGraficos[j].lstCoordenadas[k].gMarker = pointMarker;
                        pointMarker.setMap(mapContratos);
                    } else {
                        contratos.lstGraficos[j].lstCoordenadas[k].gMarker.setMap(null);
                        contratos.lstGraficos[j].lstCoordenadas[k].gMarker = false;
                    }
                }
            }
        }
    }
    return grafico;
}
